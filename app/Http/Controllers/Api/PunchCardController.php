<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\PunchCardResource;
use App\Http\Resources\ServiceTimeResource;
use App\Models\PunchCard;
use App\Models\ServiceTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class PunchCardController extends ApiController
{
	protected $punchCard;
	protected $serviceTime;
	protected $serviceId; // 存放当前调用接口的客服id


	public function __construct(PunchCard $punchCard, ServiceTime $serviceTime)
	{
		parent::__construct();
		$this->punchCard   = $punchCard;
		$this->serviceTime = $serviceTime;
		$token             = request()->header('Authorization');
		$serviceId         = Redis::get($token . '_service_id');
		if (!$serviceId) {
			$this->apiResponse->error('登录超时!', 401);
		}
		else {
			$this->serviceId = $serviceId;
		}

	}

	/**
	 * 查询打卡日志
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function punchCards(Request $request)
	{
		$inputs = $request->all();
		$query  = $this->punchCard->newQuery();
		if (null !== array_get($inputs, 'service_id')) {
			$query->where('service_id', $inputs['service_id']);
		}
		if (null !== array_get($inputs, 'status')) {
			$query->where('status', $inputs['status']);
		}
		if (null !== array_get($inputs, 'start_time')) {
			$query->where('created_at', '>=', $inputs['start_time']);
		}
		if (null !== array_get($inputs, 'end_time')) {
			$query->where('created_at', '<=', $inputs['end_time']);
		}
		$punchCards = $query->paginate();
		return $this->apiResponse->paginator($punchCards, PunchCardResource::class);
	}

	/**
	 * 查询客服某段时间内最后一次打卡记录
	 * @param Request $request
	 * @return mixed
	 */
	public function lastOnePunchCard(Request $request)
	{
		// $inputs = $request->all();
		$query = $this->punchCard->newQuery();
		// if (null !== array_get($inputs, 'service_id')) {
		// 	$query->where('service_id', $inputs['service_id']);
		// }
		$query->where('service_id', $this->serviceId);
		$todayStart = Carbon::today()->toDateTimeString();
		$todayEnd   = Carbon::tomorrow()->toDateTimeString();
		$query->where('punch_time', '>=', $todayStart);
		$query->where('punch_time', '<', $todayEnd);
		// if (null !== array_get($inputs, 'start_time')) {
		// 	$query->where('created_at', '>=', $inputs['start_time']);
		// }
		// if (null !== array_get($inputs, 'end_time')) {
		// 	$query->where('created_at', '<=', $inputs['end_time']);
		// }
		$punchCard = $query->latest()->first();
		return $this->apiResponse->item($punchCard, PunchCardResource::class);
	}

	/**
	 * 客服打卡
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$inputs = $request->all();
		$now    = Carbon::now();
		$status = array_get($inputs, 'status');
		// $serviceId  = array_get($inputs, 'service_id');
		$serviceId  = $this->serviceId;
		$todayStart = Carbon::today()->toDateTimeString();
		$todayEnd   = Carbon::tomorrow()->toDateTimeString();
		$lastOne    = $this->punchCard->where('service_id', $serviceId)->where('punch_time', '>=', $todayStart)->where('punch_time', '<', $todayEnd)->latest()->first();
		$lastStatus = array_get($lastOne, 'status');
		// 如果 (最后一条记录存在 并且记录状态和添加的状态一致)  或者 (记录不存在 但是添加状态却是下班打卡)
		if (($lastStatus && $lastStatus == $status) || (!$lastStatus && $status == 2)) {
			// 请求有误
			$this->apiResponse->errorForbidden();
		}
		DB::beginTransaction();
		try {
			// 上次记录是上班
			if ($lastStatus == 1) {
				// 创建下班记录
				$this->punchCard->fill([
					'service_time_id' => array_get($lastOne, 'service_time_id'),
					'service_id'      => $serviceId,
					'status'          => $status,
					'type'            => array_get($inputs, 'type'),
					'punch_time'      => $now,
				])->save();
				// 更新 service_manages中 记录的 下班时间
				$this->serviceTime->where('id', array_get($lastOne, 'service_time_id'))
					->update([
						'status'   => $status,
						'off_time' => $now,
					]);


			}
			else {
				// 上次记录是下班 或者 上次记录不存在
				// 添加一条 service_manages 下班时间为空
				$this->serviceTime->fill([
					'service_id' => $serviceId,
					'status'     => $status,
					'on_time'    => $now,
				])->save();

				// 创建上班记录
				$this->punchCard->fill([
					'service_time_id' => $this->serviceTime->id,
					'service_id'      => $serviceId,
					'status'          => $status,
					'type'            => array_get($inputs, 'type'),
					'punch_time'      => $now,
				])->save();
				// 上班时调用一下 客服分配
				Artisan::call('distribute:service');
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			\Log::INFO($e->getMessage());
			$this->apiResponse->errorInternal();
		}
		return $this->apiResponse->created();
	}

	/**
	 * 负责人管理
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function serviceTime(Request $request)
	{
		$inputs     = $request->all();
		$status     = array_get($inputs, 'status');
		$query      = $this->serviceTime->newQuery();
		$todayStart = Carbon::today()->toDateTimeString();
		$todayEnd   = Carbon::tomorrow()->toDateTimeString();
		$query->where('on_time', '>=', $todayStart)->where('on_time', '<', $todayEnd);
		if (null !== $status) {
			$query->where('status', $status);
		}

		$seviceTimes = $query->paginate();
		return $this->apiResponse->paginator($seviceTimes, ServiceTimeResource::class);
	}


}