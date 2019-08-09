<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use App\Models\ServiceAchievement;
use App\Models\ServiceTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ComputeServiceAchievement extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'compute:service_achievement';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '统计客服业绩';

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
	 *
	 * @return mixed
	 */
	public function handle()
	{
		//客服工作时长 [service_id=>work_duration]
		$serviceWorks = ServiceTime::select(DB::raw('SUM(timestampdiff(second,on_time,off_time)) as work_duration,service_id'))
			->where('status', 2)->groupBy('service_id')
			->pluck('work_duration', 'service_id');
		// SELECT service_id ,count(*) FROM chat_messages WHERE u_type = 1 AND status = 1  AND id in
		// (SELECT max(id) FROM chat_messages GROUP BY player_id) GROUP BY service_id;
		// 玩家最后一条记录ids
		$ids = ChatMessage::select(DB::raw('max(id) AS max_id , player_id'))->groupBy('player_id')->pluck('max_id');
		// 客服未处理数量
		$serviceNotHandleNums = ChatMessage::select(DB::raw('count(*) as not_handle_num, service_id'))
			->whereIn('id', $ids)->where('u_type', 1)->where('status', 1)
			->groupBy('service_id')
			->pluck('not_handle_num', 'service_id');

		// SELECT service_id,count(*) FROM chat_messages WHERE u_type = 1 AND status = 1 GROUP BY service_id;
		// 客服分配问题数 （玩家发送给客服的所有信息数量）
		$serviceAllotNums = ChatMessage::select(DB::raw('count(*) as allot_num, service_id'))
			->where('u_type', 1)->where('status', 1)->groupBy('service_id')
			->pluck('allot_num', 'service_id');
        //  客服最后一条回复记录时间
		$serviceLastReplayTimes = ChatMessage::select(DB::raw('max(created_at) AS last_replay_time , service_id'))
			->where('u_type', 2)->where('status', 1)->groupBy('service_id')
			->pluck('last_replay_time', 'service_id');
		foreach ($serviceWorks as $serviceId => $workDuration) {
			ServiceAchievement::updateOrCreate(['service_id' => $serviceId],
				['allot_num'        => array_get($serviceAllotNums, $serviceId, 0),
				 'not_handle_num'   => array_get($serviceNotHandleNums, $serviceId, 0),
				 'work_duration'    => $workDuration,
				 'last_replay_time' => array_get($serviceLastReplayTimes, $serviceId),
				]);
		}
	}
}
