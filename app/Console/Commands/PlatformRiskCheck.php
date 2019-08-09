<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Forward\Common;
use App\Models\RiskJob;
use App\Models\RiskManagement;
use App\Models\RiskRecord;
use App\Proto\C2S_AGGetList;
use App\Proto\S2C_AGGetListRespond;
use App\Proto\tyAgentKeyPair;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PlatformRiskCheck extends Command
{
	use  Common;
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'risk_check:platform';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '平台风险控制检查';

	private $token = 'zhangsanfengaishangzhangwujisile';

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
		// 最近p1分钟内，注册人数超过p2
		$this->handelPlatformRisk1();
		// 最近p1分钟内， 玩家反馈问题超过p2
		$this->handelPlatformRisk2();
		// 当前 玩家反馈问题超过  N 条未处理
//		$this->handelPlatformRisk3();
		// 当前， 在线人数突破  N
		$this->handelPlatformRisk4();
		// 未审核订单超过  N 笔
		$this->handelPlatformRisk5();
	}

	/**
	 * 10 最近p1分钟内，注册人数超过p2
	 */
	private function handelPlatformRisk1()
	{
		$RM = RiskManagement::where('id', 10)->where('status', 1)->first();
		if ($RM) {
			$riskJob = RiskJob::where('risk_management_id', $RM->id)->first();
			if (!$riskJob) {
				RiskJob::create([
					'risk_management_id' => $RM->id,
					'last_time'          => Carbon::now(),
				]);
			}
			else {
				// 上次执行时刻
				$lastTime = Carbon::parse($riskJob->last_time);
				// 上次执行时刻与现在时间差值 单位分钟
				$diff = $lastTime->diffInMinutes(null, false);
				// 差值 >= 任务执行间隔 执行任务
				if ($diff >= $RM->rate_minute) {
					// \Log::info('======执行任务10=====');
					$queryMessage = new C2S_AGGetList();
					$queryMessage->setSzToken($this->token);
					$arrWhere  = [];
					$startTime = Carbon::now()->subMinute(intval($RM->p1))->toDateTimeString(); // $RM->p1 分钟前的时间点
					$now       = Carbon::now();  // 当前时间点
					$endTime   = $now->toDateTimeString();

					// 更新本次执行任务的时间
					$riskJob->last_time = $now;
					$riskJob->save();

					$where = new tyAgentKeyPair();
					$where->setSzKey('RegDate')->setNType(2)->setSzValue($startTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('RegDate')->setNType(4)->setSzValue($endTime);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetUserList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$total = array_get($data, 'nTotal');

						if ($total !== null && ($total > $RM->p2)) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，注册人数超过' . $RM->p2 . '人',
								'case_user' => ' ',
							]);
						}
					} catch (\Exception $e) {

						\Log::info($e);
					}
				}

			}
		}
	}

	/**
	 * 11 最近p1分钟内， 玩家反馈问题超过p2
	 */
	private function handelPlatformRisk2()
	{
		$RM = RiskManagement::where('id', 11)->where('status', 1)->first();
		if ($RM) {
			$riskJob = RiskJob::where('risk_management_id', $RM->id)->first();
			if (!$riskJob) {
				RiskJob::create([
					'risk_management_id' => $RM->id,
					'last_time'          => Carbon::now(),
				]);
			}
			else {
				// 上次执行时刻
				$lastTime = Carbon::parse($riskJob->last_time);
				// 上次执行时刻与现在时间差值 单位分钟
				$diff = $lastTime->diffInMinutes(null, false);
				// 差值 >= 任务执行间隔 执行任务
				if ($diff >= $RM->rate_minute) {
					// \Log::info('======执行任务11=====');
					$queryMessage = new C2S_AGGetList();
					$queryMessage->setSzToken($this->token);
					$arrWhere  = [];
					$startTime = Carbon::now()->subMinute(intval($RM->p1))->toDateTimeString(); // $RM->p1 分钟前的时间点
					$now       = Carbon::now();  // 当前时间点
					$endTime   = $now->toDateTimeString();

					//更新本次执行任务的时间
					$riskJob->last_time = $now;
					$riskJob->save();

					$where = new tyAgentKeyPair();
					$where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetServiceList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$total = array_get($data, 'nTotal');

						if ($total !== null && $total > $RM->p2) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，玩家反馈问题超过' . $RM->p2 . '个',
								'case_user' => ' ',
							]);
						}
					} catch (\Exception $e) {

						\Log::info($e);
					}
				}

			}
		}
	}

	/**
	 * 12 当前 玩家反馈问题超过  N 条未处理
	 */
	private function handelPlatformRisk3()
	{
		$RM = RiskManagement::where('id', 12)->where('status', 1)->first();
		if ($RM) {
			$riskJob = RiskJob::where('risk_management_id', $RM->id)->first();
			if (!$riskJob) {
				RiskJob::create([
					'risk_management_id' => $RM->id,
					'last_time'          => Carbon::now(),
				]);
			}
			else {
				// 上次执行时刻
				$lastTime = Carbon::parse($riskJob->last_time);
				// 上次执行时刻与现在时间差值 单位分钟
				$diff = $lastTime->diffInMinutes(null, false);
				// 差值 >= 任务执行间隔 执行任务
				if ($diff >= $RM->rate_minute) {
					// \Log::info('======执行任务12=====');
					$queryMessage = new C2S_AGGetList();
					$queryMessage->setSzToken($this->token);
					$arrWhere = [];
					$now      = Carbon::now();  // 当前时间点

					//更新本次执行任务的时间
					$riskJob->last_time = $now;
					$riskJob->save();

					$where = new tyAgentKeyPair();
					$where->setSzKey('Answer')->setNType(0)->setSzValue('');
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetServiceList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$total = array_get($data, 'nTotal');

						if ($total !== null && $total > $RM->p1) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '当前 玩家反馈问题超过' . $RM->p1 . '条未处理',
								'case_user' => ' ',
							]);
						}
					} catch (\Exception $e) {

						\Log::info($e);
					}
				}

			}
		}
	}

	/**
	 * 13 当前， 在线人数突破  N
	 */
	private function handelPlatformRisk4()
	{
		$RM = RiskManagement::where('id', 13)->where('status', 1)->first();
		if ($RM) {
			$riskJob = RiskJob::where('risk_management_id', $RM->id)->first();
			if (!$riskJob) {
				RiskJob::create([
					'risk_management_id' => $RM->id,
					'last_time'          => Carbon::now(),
				]);
			}
			else {
				// 上次执行时刻
				$lastTime = Carbon::parse($riskJob->last_time);
				// 上次执行时刻与现在时间差值 单位分钟
				$diff = $lastTime->diffInMinutes(null, false);
				// 差值 >= 任务执行间隔 执行任务
				if ($diff >= $RM->rate_minute) {
					// \Log::info('======执行任务13=====');
					$queryMessage = new C2S_AGGetList();
					$queryMessage->setSzToken($this->token);

					$now = Carbon::now();  // 当前时间点
					//更新本次执行任务的时间
					$riskJob->last_time = $now;
					$riskJob->save();

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetOLUserList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$total = array_get($data, 'nTotal');
						if ($total !== null && $total >= $RM->p1) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '当前， 在线人数突破 ' . $RM->p1 . '人',
								'case_user' => ' ',
							]);
						}
					} catch (\Exception $e) {

						\Log::info($e);
					}
				}

			}
		}
	}

	/**
	 * 未审核订单超过  N 笔
	 */
	private function handelPlatformRisk5()
	{
		$RM = RiskManagement::where('id', 14)->where('status', 1)->first();
		if ($RM) {
			$riskJob = RiskJob::where('risk_management_id', $RM->id)->first();
			if (!$riskJob) {
				RiskJob::create([
					'risk_management_id' => $RM->id,
					'last_time'          => Carbon::now(),
				]);
			}
			else {
				// 上次执行时刻
				$lastTime = Carbon::parse($riskJob->last_time);
				// 上次执行时刻与现在时间差值 单位分钟
				$diff = $lastTime->diffInMinutes(null, false);
				// 差值 >= 任务执行间隔 执行任务
				if ($diff >= $RM->rate_minute) {
					// \Log::info('======执行任务14=====');
					$queryMessage = new C2S_AGGetList();
					$queryMessage->setSzToken($this->token);
					$arrWhere = [];

					$now = Carbon::now();  // 当前时间点


					//更新本次执行任务的时间
					$riskJob->last_time = $now;
					$riskJob->save();

					$where = new tyAgentKeyPair();
					$where->setSzKey('Status')->setNType(0)->setSzValue(0);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetExchangeList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$total = array_get($data, 'nTotal');

						if ($total !== null && $total > $RM->p1) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '未审核订单超过' . $RM->p1 . '笔',
								'case_user' => ' ',
							]);
						}
					} catch (\Exception $e) {

						\Log::info($e);
					}
				}

			}
		}
	}
}
