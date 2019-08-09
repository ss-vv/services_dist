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

class UserRiskCheck extends Command
{
	use  Common;
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'risk_check:user';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '用户风险控制检查';

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
		// 最近p1分钟内，赢取金币超过p2元
		$this->handelUserRisk1();

		// 最近p1分钟内，单用户提现订单超过p2笔
		$this->handelUserRisk2();

		// 最近p1分钟内，单用户提现金额超过p2元
		$this->handelUserRisk3();

		// 最近p1分钟内注册，并提现的用户
		$this->handelUserRisk4();

	}


	/**
	 *  1 最近p1分钟内，赢取金币超过p2元
	 */
	private function handelUserRisk1()
	{
		$RM = RiskManagement::where('id', 1)->where('status', 1)->first();
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
					// \Log::info('======执行任务1=====');
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
					$where->setSzKey('总输赢')->setNType(2)->setSzValue($RM->p2);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetUserWinTotal'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$UserId   = array_get($data, 'arrDatas.0.arrString.0');
						$NickName = array_get($data, 'arrDatas.0.arrString.1');
						if ($UserId !== null && $NickName !== null) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，赢取金币超过' . $RM->p2 . '元',
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
	 * 2 最近p1分钟内，单用户提现订单超过p2笔
	 */
	private function handelUserRisk2()
	{
		$RM = RiskManagement::where('id', 2)->where('status', 1)->first();
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
					$where->setSzKey('总提现笔数')->setNType(2)->setSzValue($RM->p2);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetUserWinTotal'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$UserId   = array_get($data, 'arrDatas.0.arrString.0');
						$NickName = array_get($data, 'arrDatas.0.arrString.1');
						if ($UserId !== null && $NickName !== null) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，单用户提现订单超过' . $RM->p2 . '笔',
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
	 * 3 最近p1分钟内，单用户提现金额超过p2元
	 */
	private function handelUserRisk3()
	{
		$RM = RiskManagement::where('id', 3)->where('status', 1)->first();
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
					// \Log::info('======执行任务3=====');
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
					$where->setSzKey('总提现金额')->setNType(2)->setSzValue($RM->p2);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetUserWinTotal'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$UserId   = array_get($data, 'arrDatas.0.arrString.0');
						$NickName = array_get($data, 'arrDatas.0.arrString.1');
						if ($UserId !== null && $NickName !== null) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，单用户提现金额超过' . $RM->p2 . '元',
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
	 * 4 最近p1分钟内注册，并提现的用户
	 */
	private function handelUserRisk4()
	{
		$RM = RiskManagement::where('id', 4)->where('status', 1)->first();
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
					// \Log::info('======执行任务4=====');
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
					$where->setSzKey('RegDate')->setNType(2)->setSzValue($startTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('RegDate')->setNType(4)->setSzValue($endTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('总提现')->setNType(2)->setSzValue(0);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetUserList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$UserId   = array_get($data, 'arrDatas.0.arrString.0');
						$NickName = array_get($data, 'arrDatas.0.arrString.1');
						if ($UserId !== null && $NickName !== null) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内注册，并提现的用户',
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
