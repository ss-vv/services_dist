<?php

namespace App\Console\Commands;

use App\Models\PunchCard;
use App\Models\ServiceTime;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class AutoPunchCardOff extends Command
{
	/**
	 * The name and signature of the console command.
	 * 检查下班自动打卡
	 * @var string
	 */
	protected $signature = 'punchCard:off';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '客服关闭窗口或者点击下班打卡后检查redis中的serviceId，执行下班打卡操作';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * 检查今天打客服打卡记录 对redis中不存在的客服id 进行自动下班打卡
	 * @return mixed
	 */
	public function handle()
	{
		$startTime  = Carbon::today()->toDateTimeString();
		$now        = Carbon::now()->toDateTimeString();
		$serviceIds = ServiceTime::where('status', 1)
			->where('on_time', '>=', $startTime)
			->where('on_time', '<=', $now)
			->pluck('id', 'service_id');

		if (count($serviceIds)) {
			$punchInserts   = [];
			$serviceTimeIds = [];
			foreach ($serviceIds as $serviceId => $serviceTimeId) {
				$uid = Redis::get('ws:service_id:' . $serviceId);
				//如果不存在 说明已经下班
				if (!$uid) {
					$punchInserts[]   = [
						'service_time_id' => $serviceTimeId,
						'service_id'      => $serviceId,
						'status'          => 2,
						'type'            => 1,
						'punch_time'      => $now,
						'created_at'      => $now,
						'updated_at'      => $now,
					];
					$serviceTimeIds[] = $serviceTimeId;
				}
			}
			if (count($punchInserts)) {
				DB::beginTransaction();
				try {
					PunchCard::insert($punchInserts);
					ServiceTime::whereIn('id', $serviceTimeIds)
						->update([
							'status'   => 2,
							'off_time' => $now,
						]);
					DB::commit();
				} catch (\Exception $e) {
					DB::rollback();
					\Log::INFO($e->getMessage());
				}
			}


		}
	}
}
