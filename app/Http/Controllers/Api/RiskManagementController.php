<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RiskManagementResource;
use App\Models\RiskManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class RiskManagementController extends ApiController
{

	protected $riskManagement;

	public function __construct(RiskManagement $riskManagement)
	{
		parent::__construct();
		$this->riskManagement = $riskManagement;
	}

	/**
	 * 查询所有风控配置
	 */
	public function index()
	{
		$riskManagements= $this->riskManagement->get();
		return $this->apiResponse->collection($riskManagements, RiskManagementResource::class);
	}

	/**
	 * 修改风控配置
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$inputs = $request->all();
		$validator = Validator::make($inputs, [
			'riskManage.*.id' => 'required|numeric|min:1',
			'riskManage.*.status' => 'required|in:0,1',
			'riskManage.*.rate_minute' => 'required|numeric|min:1',
			'riskManage.*.p1' => 'required|numeric|min:1',
			'riskManage.*.p2' => 'required|numeric|min:0',
		],[
			'required'=>':attribute 必填',
			'numeric'=>':attribute 必须是数字',
			'min'=>':attribute 最少为:min',
			'in'=>':attribute 必须为 :values 其中之一'
		],[
			'riskManage.*.id'=>'配置id',
			'riskManage.*.rate_minute'=>'运行时间',
			'riskManage.*.status'=>'选中状态参数',
			'riskManage.*.p1'=>'分钟参数',
			'riskManage.*.p2'=>'配置参数',
		]);

		if ($validator->fails()) {
			$errorMessages = $validator->errors()->first();
			$this->apiResponse->error($errorMessages,400);
		}
		DB::beginTransaction();
		try {
			$riskManages = array_get($inputs,'riskManage');
			foreach ($riskManages as $riskManage){
				$this->riskManagement
					->where('id',$riskManage['id'])
					->update([
						'status'=>$riskManage['status'],
						'rate_minute'=>$riskManage['rate_minute'],
						'p1'=>$riskManage['p1'],
						'p2'=>$riskManage['p2'],
					]);
			}
			DB::commit();
			return $this->apiResponse->created();
		} catch (\Exception $e) {
			DB::rollback();
			\Log::INFO($e->getMessage());
			$this->apiResponse->errorInternal($e->getMessage());
		}


	}

}