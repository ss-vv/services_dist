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

class PayRiskCheck extends Command
{
	use  Common;
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'risk_check:pay';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '支付风险控制检查';

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
		//最近p分钟内，订单成功率低于N（成功的订单/总订单数量）
		$this->handelPayRisk1();

		//最近p1分钟内，总 充值 金额达到N（查询结果集最后一行有个累计）
		$this->handelPayRisk2();

		//最近p1分钟内，总 提现 金额达到N（查询结果集最后一行有个累计）
		$this->handelPayRisk3();

		//  最近p1分钟内，提交未支付订单超过p2笔
		$this->handelPayRisk4();
	}

	/**
	 * 15 最近p1分钟内，订单成功率低于N（成功的订单/总订单数量）
	 */
	private function handelPayRisk1()
	{
		$RM = RiskManagement::where('id', 15)->where('status', 1)->first();
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
					$queryMessage1 = new C2S_AGGetList();
					$queryMessage1->setSzToken($this->token);
					$arrWhere1 = [];

					$queryMessage2 = new C2S_AGGetList();
					$queryMessage2->setSzToken($this->token);
					$arrWhere2 = [];

					$startTime = Carbon::now()->subMinute(intval($RM->p1))->toDateTimeString(); // $RM->p1 分钟前的时间点
					$now       = Carbon::now();  // 当前时间点
					$endTime   = $now->toDateTimeString();

					//更新本次执行任务的时间
					$riskJob->last_time = $now;
					$riskJob->save();

					$where = new tyAgentKeyPair();
					$where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
					$arrWhere1[] = $where;
					$arrWhere2[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
					$arrWhere1[] = $where;
					$arrWhere2[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('Status')->setNType(0)->setSzValue(1);
					$arrWhere1[] = $where;

					$queryMessage1->setArrWheres($arrWhere1);
					$queryMessage2->setArrWheres($arrWhere2);

					$proto1 = $queryMessage1->serializeToString();
					$proto2 = $queryMessage2->serializeToString();
					// 请求外部接口
					try {
						$data1 = $this->outerRespond($proto1, config('outsideurls.AGGetChargeList'), S2C_AGGetListRespond::class);
						$data2 = $this->outerRespond($proto2, config('outsideurls.AGGetChargeList'), S2C_AGGetListRespond::class);
						// \Log::info($data1);
						// \Log::info($data2);
						$successTotal = array_get($data1, 'nTotal');
						$total        = array_get($data2, 'nTotal');
						if ($successTotal && $total) {
							if (($successTotal / $total * 100) < $RM->p2) {
								RiskRecord::create([
									'status'    => 0,
									'content'   => '最近' . $RM->p1 . '分钟内，订单成功率低于' . $RM->p2 . '%',
									'case_user' => ' ',
								]);
							}
						}
					} catch (\Exception $e) {
						\Log::info($e);
					}
				}
			}
		}
	}

	/**
	 * 16 最近p1分钟内，总充值金额达到N（查询结果集最后一行有个累计）
	 */
	private function handelPayRisk2()
	{
		$RM = RiskManagement::where('id', 16)->where('status', 1)->first();
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
					// \Log::info('======执行任务16=====');
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
					$where->setSzKey('Status')->setNType(0)->setSzValue(1);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetChargeList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$total  = array_get($data, 'nTotal');
						$amount = array_get($data, 'arrDatas.' . $total . '.arrString.2');
						if ($amount !== null && $amount >= $RM->p2) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，总充值金额达' . $RM->p2 . '元',
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
	 *17  最近p1分钟内，总提现金额达到N（查询结果集最后一行有个累计）
	 */
	private function handelPayRisk3()
	{
		$RM = RiskManagement::where('id', 17)->where('status', 1)->first();
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
					// \Log::info('======执行任务17=====');
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
					$where->setSzKey('LogTime')->setNType(2)->setSzValue($startTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('LogTime')->setNType(4)->setSzValue($endTime);
					$arrWhere[] = $where;

					$where = new tyAgentKeyPair();
					$where->setSzKey('Status')->setNType(0)->setSzValue(1);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetExchangeList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$total  = array_get($data, 'nTotal');
						$amount = array_get($data, 'arrDatas.' . $total . '.arrString.2');
						if ($amount !== null && $amount >= $RM->p2) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，总提现金额达到' . $RM->p2 . '元',
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
	 *18  最近p1分钟内，提交未支付订单超过p2笔
	 */
	private function handelPayRisk4()
	{
		$RM = RiskManagement::where('id', 18)->where('status', 1)->first();
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
					// \Log::info('======执行任务18=====');
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
					$where->setSzKey('Status')->setNType(0)->setSzValue(0);
					$arrWhere[] = $where;

					$queryMessage->setArrWheres($arrWhere);

					$proto = $queryMessage->serializeToString();
					// 请求外部接口
					try {
						$data = $this->outerRespond($proto, config('outsideurls.AGGetChargeList'), S2C_AGGetListRespond::class);
						// \Log::info($data);
						$total = array_get($data, 'nTotal');

						if ($total !== null && $total > $RM->p2) {
							RiskRecord::create([
								'status'    => 0,
								'content'   => '最近' . $RM->p1 . '分钟内，提交未支付订单超过' . $RM->p2 . '笔',
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
