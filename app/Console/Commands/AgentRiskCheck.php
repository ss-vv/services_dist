<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Forward\Common;
use App\Models\RiskJob;
use App\Models\RiskManagement;
use App\Models\RiskRecord;
use App\Proto\C2S_AGGetList;
use App\Proto\S2C_AGGetListRespond;
use App\Proto\tyAgentKeyPair;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AgentRiskCheck extends Command
{
	use  Common;
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'risk_check:agent';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '代理风险控制检查';

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
		// 最近p1分钟内，转出金币超过超过p2元
		$this->handelAgentRisk1();
		// 最近p1分钟内，转入金币超过超过p2元
		$this->handelAgentRisk2();
		// 最近p1分钟内注册，收入小于p2元
		$this->handelAgentRisk3();
		// 最近p1分钟内注册，收入大于p2元
		$this->handelAgentRisk4();
		// 最近p1分钟内，收入大于p2元
		$this->handelAgentRisk5();
	}

	/**
	 * 5 最近p1分钟内，转出金币超过超过p2元
	 */
	private function handelAgentRisk1()
	{
		$RM = RiskManagement::where('id', 5)->where('status', 1)->first();
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
					// \Log::info('======执行任务5=====');
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

					$where = new tyAgentKeyPair();
					$where->setSzKey('总转出')->setNType(2)->setSzValue($RM->p2);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetIncomeList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$UserId   = array_get($data, 'arrDatas.0.arrString.0');
						$NickName = array_get($data, 'arrDatas.0.arrString.1');
						if ($UserId !== null && $NickName !== null) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，转出金币超过' . $RM->p2 . '元',
								'case_user' => $UserId . ' ' . $NickName,
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
	 * 6 最近p1分钟内，转入金币超过超过p2元
	 */
	private function handelAgentRisk2()
	{
		$RM = RiskManagement::where('id', 6)->where('status', 1)->first();
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
					// \Log::info('======执行任务6=====');
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

					$where = new tyAgentKeyPair();
					$where->setSzKey('总转入')->setNType(2)->setSzValue($RM->p2);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetIncomeList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$UserId   = array_get($data, 'arrDatas.0.arrString.0');
						$NickName = array_get($data, 'arrDatas.0.arrString.1');
						if ($UserId !== null && $NickName !== null) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，转入金币超过' . $RM->p2 . '元',
								'case_user' => $UserId . ' ' . $NickName,
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
	 * 7 最近p1分钟内注册，收入小于p2元
	 */
	private function handelAgentRisk3()
	{
		$RM = RiskManagement::where('id', 7)->where('status', 1)->first();
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
					// \Log::info('======执行任务7=====');
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
					$where->setSzKey('CreateTime')->setNType(2)->setSzValue($startTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('CreateTime')->setNType(4)->setSzValue($endTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('累计收益')->setNType(4)->setSzValue($RM->p2);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetAgentList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$UserId   = array_get($data, 'arrDatas.0.arrString.0');
						$NickName = array_get($data, 'arrDatas.0.arrString.1');
						if ($UserId !== null && $NickName !== null) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内注册，收入小于' . $RM->p2 . '元',
								'case_user' => $UserId . ' ' . $NickName,
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
	 * 8 最近p1分钟内注册，收入大于p2元
	 */
	private function handelAgentRisk4()
	{
		$RM = RiskManagement::where('id', 8)->where('status', 1)->first();
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
					// \Log::info('======执行任务8=====');
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
					$where->setSzKey('CreateTime')->setNType(2)->setSzValue($startTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('CreateTime')->setNType(4)->setSzValue($endTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('累计收益')->setNType(2)->setSzValue($RM->p2);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetAgentList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$UserId   = array_get($data, 'arrDatas.0.arrString.0');
						$NickName = array_get($data, 'arrDatas.0.arrString.1');
						if ($UserId !== null && $NickName !== null) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内注册，收入大于' . $RM->p2 . '元',
								'case_user' => $UserId . ' ' . $NickName,
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
	 * 9  最近p1分钟内，收入大于p2元
	 */
	private function handelAgentRisk5()
	{
		$RM = RiskManagement::where('id', 9)->where('status', 1)->first();
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
					// \Log::info('======执行任务9=====');
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

					$where = new tyAgentKeyPair();
					$where->setSzKey('累计收益')->setNType(2)->setSzValue($RM->p2);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetIncomeList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$UserId   = array_get($data, 'arrDatas.0.arrString.0');
						$NickName = array_get($data, 'arrDatas.0.arrString.1');
						if ($UserId !== null && $NickName !== null) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，收入大于' . $RM->p2 . '元',
								'case_user' => $UserId . ' ' . $NickName,
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
