<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RiskRecordResource;
use App\Models\RiskRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class RiskRecordController extends ApiController
{

	protected $riskRecord;

	public function __construct(RiskRecord $riskRecord)
	{
		parent::__construct();
		$this->riskRecord = $riskRecord;
	}

	/**
	 * 查询所有风控记录
	 */
	public function index()
	{
		$perPage     = request('per_page', 10);
		$riskRecords = $this->riskRecord
			->when(request('start_time') !== null, function($query) {
				$query->where('created_at', '>=', request('start_time'));
			})->when(request('end_time') !== null, function($query) {
				$query->where('created_at', '<=', request('end_time'));
			})->when(request('status') !== null, function($query) {
				$query->where('status', '=', request('status'));
			})->paginate($perPage);
		return $this->apiResponse->paginator($riskRecords, RiskRecordResource::class);
	}

	/**
	 * 查询是否存在未读的风控记录
	 */
	public function noReadRiskRecord()
	{
		$counts = $this->riskRecord->where('status', '=', 0)->count();
		return $this->apiResponse->json(compact('counts'));
	}

	/**
	 * 修改风控记录的已读状态
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$inputs    = $request->all();
		$validator = Validator::make($inputs, [
			'ids'          => 'required|array',
			'name'      => 'required|string|max:200',
		], [
			'required' => ':attribute 必填',
			'array'  => ':attribute 必须是数组',
		], [
			'ids'          => 'ids',
			'name' => '参数',
		]);

		if ($validator->fails()) {
			$errorMessages = $validator->errors()->first();
			$this->apiResponse->error($errorMessages, 400);
		}
		DB::beginTransaction();
		try {
			$ids = array_get($inputs, 'ids');
				$this->riskRecord
					->whereIn('id', $ids)
					->update([
						'status'=> 1,
						'admin_user'=> array_get($inputs, 'name'),
					]);
			DB::commit();
			return $this->apiResponse->created();
		} catch (\Exception $e) {
			DB::rollback();
			\Log::INFO($e->getMessage());
			$this->apiResponse->errorInternal($e->getMessage());
		}
	}

    /**
     * 修改全部未读的为已读
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updates(Request $request)
    {
        $inputs    = $request->all();
        $validator = Validator::make($inputs, [
            'name'      => 'required|string|max:200',
        ], [
            'required' => ':attribute 必填',
        ], [
            'name' => '参数',
        ]);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->first();
            $this->apiResponse->error($errorMessages, 400);
        }
        DB::beginTransaction();
        try {
            $this->riskRecord
                ->where('status', 0)
                ->update([
                    'status'=> 1,
                    'admin_user'=> array_get($inputs, 'name'),
                ]);
            DB::commit();
            return $this->apiResponse->created();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::INFO($e->getMessage());
            $this->apiResponse->errorInternal($e->getMessage());
        }
    }

}