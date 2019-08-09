<?php

namespace App\Http\Controllers\Api\Forward;

use App\Http\Controllers\Api\ApiController;
use App\Models\BindSecret;
use App\Proto\C2S_AGDeleteSetting;
use App\Proto\C2S_AGGetList;
use App\Proto\C2S_AGInsertSetting;
use App\Proto\C2S_AGUpdateSetting;
use App\Proto\S2C_AGGetListRespond;
use App\Proto\S2C_AGOperateRespond;
use App\Proto\tyAgentKeyPair;
use App\Support\PHPGangsta_GoogleAuthenticator;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class AgentController extends ApiController
{
	use  Common;
	protected $token;
	protected $queryMessage;
	protected $insertSetting;
	protected $updateSetting;
	protected $deleteSetting;
	protected $commonArr;

	public function __construct(C2S_AGGetList $C2S_AGGetList, C2S_AGInsertSetting $C2S_AGInsertSetting, C2S_AGUpdateSetting $C2S_AGUpdateSetting
	,C2S_AGDeleteSetting $C2S_AGDeleteSetting)
	{
		parent::__construct();
		$this->queryMessage  = $C2S_AGGetList;
		$this->insertSetting = $C2S_AGInsertSetting;
		$this->updateSetting = $C2S_AGUpdateSetting;
		$this->deleteSetting = $C2S_AGDeleteSetting;
		$this->token         = request()->header('Authorization');
		$this->queryMessage->setSzToken($this->token);
		$this->insertSetting->setSzToken($this->token);
		$this->updateSetting->setSzToken($this->token);
		$this->deleteSetting->setSzToken($this->token);
		if (null !== request('order_by')) {
			$this->queryMessage->setSzOrderBy(request('order_by'));
		}
        $RegisterPlatform = request()->cookie('RegisterPlatform');
		$this->commonArr = [];

        if ($RegisterPlatform) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RegisterPlatform')->setNType(0)->setSzValue($RegisterPlatform);
            $this->commonArr[] = $where;
        }
    }

	/**
	 * 代理推广首页
	 * @return \Illuminate\Http\Response
	 */
	public function promoStatics()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetPromoStatics'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理管理
	 * @return \Illuminate\Http\Response
	 */
	public function agentList()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;

		if (null !== request('AgencyID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgencyID')->setNType(0)->setSzValue(request('AgencyID'));
			$arrWhere[] = $where;
		}
		if (null !== request('AgentLv')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgentLv')->setNType(0)->setSzValue(request('AgentLv'));
			$arrWhere[] = $where;
		}
		if (null !== request('WXID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('WXID')->setNType(0)->setSzValue(request('WXID'));
			$arrWhere[] = $where;
		}
		if (null !== request('AccountName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AccountName')->setNType(0)->setSzValue(request('AccountName'));
			$arrWhere[] = $where;
		}
		if (null !== request('Status')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('Status')->setNType(0)->setSzValue(request('Status'));
			$arrWhere[] = $where;
		}
		if (null !== request('Mobile')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('Mobile')->setNType(0)->setSzValue(request('Mobile'));
			$arrWhere[] = $where;
		}
		if (null !== request('ParentID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('ParentID')->setNType(0)->setSzValue(request('ParentID'));
			$arrWhere[] = $where;
		}
		if (null !== request('ParentTree')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('ParentTree')->setNType(5)->setSzValue(request('ParentTree'));
			$arrWhere[] = $where;
		}
		if (null !== request('minMoney')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('CurMoney')->setNType(2)->setSzValue(request('minMoney'));
			$arrWhere[] = $where;
		}
		if (null !== request('maxMoney')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('CurMoney')->setNType(4)->setSzValue(request('maxMoney'));
			$arrWhere[] = $where;
		}
		if ($startTime = request('start_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('CreateTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where;
		}
		if ($endTime = request('end_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('CreateTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetAgentList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理推广统计
	 * @return \Illuminate\Http\Response
	 */
	public function promoList()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('AgencyID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgencyID')->setNType(0)->setSzValue(request('AgencyID'));
			$arrWhere[] = $where;
		}
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetPromoList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理提现订单
	 * @return \Illuminate\Http\Response
	 */
	public function settleRecord()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));

		$arrWhere = $this->commonArr;
		if (null !== request('OrderID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('OrderID')->setNType(0)->setSzValue(request('OrderID'));
			$arrWhere[] = $where;
		}
		if (null !== request('AgencyID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgencyID')->setNType(0)->setSzValue(request('AgencyID'));
			$arrWhere[] = $where;
		}
		if (null !== request('Status')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('Status')->setNType(0)->setSzValue(request('Status'));
			$arrWhere[] = $where;
		}
		if (null !== request('WithdrawChannel')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('WithdrawChannel')->setNType(0)->setSzValue(request('WithdrawChannel'));
			$arrWhere[] = $where;
		}

		if ($startTime = request('start_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('FinishTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where;
		}
		if ($endTime = request('end_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('FinishTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetSettleRecord'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理推广二维码
	 * @return \Illuminate\Http\Response
	 */
	public function promoURL()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetPromoURL'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理推广余额
	 * @return \Illuminate\Http\Response
	 */
	public function account()
	{
	    $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetAccount'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 *    代理转账记录
	 * @return \Illuminate\Http\Response
	 */
	public function transferRecord()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;

		if (null !== request('AgencyID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgencyID')->setNType(0)->setSzValue(request('AgencyID'));
			$arrWhere[] = $where;
		}
        if (null !== request('userInId')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('转到玩家')->setNType(0)->setSzValue(request('userInId'));
            $arrWhere[] = $where;
        }
		if (null !== request('minMoney')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('转出金额')->setNType(2)->setSzValue(request('minMoney'));
			$arrWhere[] = $where;
		}
		if (null !== request('maxMoney')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('转出金额')->setNType(4)->setSzValue(request('maxMoney'));
			$arrWhere[] = $where;
		}
		if ($startTime = request('start_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where;
		}
		if ($endTime = request('end_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetTransferRecord'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理商人绑定信息
	 * @return \Illuminate\Http\Response
	 */
	public function merchantSetting()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('AgencyID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgencyID')->setNType(0)->setSzValue(request('AgencyID'));
			$arrWhere[] = $where;
		}
		if (null !== request('nickname')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('nickname')->setNType(0)->setSzValue(request('nickname'));
			$arrWhere[] = $where;
		}
		if (null !== request('ntype')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('ntype')->setNType(0)->setSzValue(request('ntype'));
			$arrWhere[] = $where;
		}

		if (null !== request('state')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('state')->setNType(0)->setSzValue(request('state'));
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetMerchantSetting'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理系统公告
	 * @return \Illuminate\Http\Response
	 */
	public function agentMessage()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetAgentMessage'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理上分记录
	 * @return \Illuminate\Http\Response
	 */
	public function buyRecord()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;

		if ($agencyID = request('AgencyID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgencyID')->setNType(0)->setSzValue($agencyID);
			$arrWhere[] = $where;
		}
		if ($userID = request('UserID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('UserID')->setNType(0)->setSzValue($userID);
			$arrWhere[] = $where;
		}
		if (null !== request('minMoney')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('进分金额')->setNType(2)->setSzValue(request('minMoney'));
			$arrWhere[] = $where;
		}
		if (null !== request('maxMoney')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('进分金额')->setNType(4)->setSzValue(request('maxMoney'));
			$arrWhere[] = $where;
		}

		if ($startTime = request('start_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where;
		}
		if ($endTime = request('end_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetBuyRecord'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理收入统计
	 * @return \Illuminate\Http\Response
	 */
	public function incomeList()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if ($agencyID = request('AgencyID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgencyID')->setNType(0)->setSzValue($agencyID);
			$arrWhere[] = $where;
		}
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetIncomeList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 玩家收入明细
	 * @return \Illuminate\Http\Response
	 */
	public function wjIncomeDetail()
	{
        $this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if ($startTime = request('start_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where;
		}
		if ($endTime = request('end_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where;
		}
        if ($UserID = request('UserID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserID')->setNType(0)->setSzValue($UserID);
            $arrWhere[] = $where;
        }
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGWJIncomeDetail'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
		    \Log::info($e);
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理收入明细
	 * @return \Illuminate\Http\Response
	 */
	public function incomeDetailList()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if ($agencyID = request('ToID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('ToID')->setNType(0)->setSzValue($agencyID);
			$arrWhere[] = $where;
		}
		if ($userID = request('UserID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('UserID')->setNType(0)->setSzValue($userID);
			$arrWhere[] = $where;
		}
		if ($startTime = request('start_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where;
		}
		if ($endTime = request('end_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetIncomeDetailList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理转账
	 * @return \Illuminate\Http\Response
	 */
	public function insertTransfer()
	{

		$inputs          = request()->only(['money', 'UserID']);
		$inputs['money'] = $inputs['money'] * env('TRANSFER_PROP', 1);
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGInsertTransfer'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理提现审核
	 * @return \Illuminate\Http\Response
	 */
	public function updateSettlement()
	{
		$inputs = request()->only(['OrderID', 'Status']);
		$this->setUpdateWhere(['OrderID' => $inputs['OrderID']])
			->setUpdateValues([
				'Status' => $inputs['Status'],
			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateSettlement'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理申请提现
	 * @return \Illuminate\Http\Response
	 */
	public function insertSettlement()
	{
        $inputs = request()->only(['money', 'type','oneCode']);
	    $userName = request()->cookie('user_name');
        $userBindSecret = BindSecret::where('user_name', $userName)->pluck('secret')->first();
        $oneCode = array_get($inputs,'oneCode');
        // 用户已经绑定过google验证器秘钥
        if ($userBindSecret) {
            $ga          = new PHPGangsta_GoogleAuthenticator();
            $checkResult = $ga->verifyCode($userBindSecret, $oneCode, 0);
           if(!$checkResult){
               $this->apiResponse->error('google验证码有误！', 400);
           }
        }else{
            $this->apiResponse->error('参数有误！', 500);
        }
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();

		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGInsertSettlement'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 *    代理增加商人绑定,依次传入，1类型（eg:qq,wx），2微信号和qq号（eg:wxagent，15687963541）
	 * @return \Illuminate\Http\Response
	 */
	public function insertMerchantSetting()
	{
		$inputs = request()->only(['ntype', 'name', 'nickname']);
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGInsertMerchantSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理修改商人绑定
	 * @return \Illuminate\Http\Response
	 */
	public function updateMerchantSetting()
	{
		$inputs = request()->only(['pkid', 'ntype', 'name', 'nickname', 'state', 'BelondAG']);
		$update = [
			'ntype'    => $inputs['ntype'],
			'name'     => $inputs['name'],
			'nickname' => $inputs['nickname'],
			'state'    => $inputs['state'],
		];
		$nLv    = Redis::get($this->token . '_nlv');
		if (($nLv !== null) && ($nLv >= 0) && ($nLv <= 1) && (null !== array_get($inputs, 'BelondAG'))) {
			$update['BelondAG'] = $inputs['BelondAG'];
		}
		$this->setUpdateWhere(['pkid' => $inputs['pkid']])
			->setUpdateValues($update);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateMerchantSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理删除商人绑定
	 * @return \Illuminate\Http\Response
	 */
	public function deleteMerchantSetting()
	{

		$inputs = request()->only(['pkid', 'ntype', 'name', 'nickname', 'state']);
		$where  = new tyAgentKeyPair();
		$where->setSzKey('pkid')->setSzValue($inputs['pkid']);
		$this->updateSetting->setArrWheres([$where]);
		$proto = $this->updateSetting->serializeToString();

		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGDeleteMerchantSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 *    增加代理公告依次传入，1标题，2内容，3开始时间，4结束时间，5排序（排序数字越大越靠前，同样排序的最新的靠前显示)
	 * @return \Illuminate\Http\Response
	 */
	public function insertAgentMessage()
	{
		$inputs = request()->only(['MsgTitle', 'MsgContent', 'StartTime', 'EndTime', 'OrderNum']);
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGInsertAgentMessage'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 修改代理公告
	 * @return \Illuminate\Http\Response
	 */
	public function updateAgentMessage()
	{
		$inputs = request()->only(['pkid', 'MsgTitle', 'MsgContent', 'StartTime', 'EndTime', 'OrderNum']);
		$this->setUpdateWhere(['pkid' => $inputs['pkid']])
			->setUpdateValues([
				'MsgTitle'   => $inputs['MsgTitle'],
				'MsgContent' => $inputs['MsgContent'],
				'StartTime'  => $inputs['StartTime'],
				'EndTime'    => $inputs['EndTime'],
			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateAgentMessage'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 删除代理公告
	 * @return \Illuminate\Http\Response
	 */
	public function deleteAgentMessage()
	{
		$inputs = request()->only(['pkid']);
		$where  = new tyAgentKeyPair();
		$where->setSzKey('pkid')->setNType(0)->setSzValue($inputs['pkid']);

		$this->updateSetting->setArrWheres([$where]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGDeleteAgentMessage'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 增加代理:依次传入1.账号，2.昵称，3.密码，4.返点比例
	 * @return \Illuminate\Http\Response
	 */
	public function insertAgentAccount()
	{
		$inputs = request()->only(['AccountName', 'NickName', 'AcPwd', 'RebatePercent']);
		if(null!==request('animal')){
            $inputs = request()->only(['AccountName', 'NickName', 'AcPwd', 'RebatePercent','animal']);
        }
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGInsertAgentAccount'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 修改代理账号信息
	 * @return \Illuminate\Http\Response
	 */
	public function updateAgentAccount()
	{
		$inputs             = request()->only(['AgencyID', 'NickName', 'RebatePercent', 'AcPwd', 'Status', 'szCode', 'resetType']);
		$wheres['AgencyID'] = $inputs['AgencyID'];
		$resetKeys          = [
			'AliPay' => true,
			'WXID'   => true,
		];
		$updates            = [];

		// 重置某些参数
		if (array_get($inputs, 'resetType')) {
			if (array_get($resetKeys, $inputs['resetType'])) {
				$updates[$inputs['resetType']] = '';
			}
			else {
				$this->apiResponse->error('重置参数有误!');
			}
		}
		else {
			if (null !== array_get($inputs, 'NickName')) {
				$updates['NickName'] = $inputs['NickName'];
			}

			if (null !== array_get($inputs, 'AcPwd')) {
				$updates['AcPwd'] = $inputs['AcPwd'];
			}
			if (null !== array_get($inputs, 'RebatePercent')) {
				$updates['RebatePercent'] = $inputs['RebatePercent'];
			}
			if (null !== array_get($inputs, 'Status')) {
				$updates['Status'] = $inputs['Status'];
			}
		}

		$this->setUpdateWhere($wheres)
			->setUpdateValues($updates);
		if (null !== array_get($inputs, 'szCode')) {
			$this->updateSetting->setSzCode($inputs['szCode']);
		}

		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data          = $this->outerRespond($proto, config('outsideurls.AGUpdateAgentAccount'), S2C_AGOperateRespond::class);
			$data['input'] = $updates;
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 更改结算账户 结算密码
	 * @return \Illuminate\Http\Response
	 */
	public function updateAgentAccountSettle()
	{
		$inputs             = request()->only(['AliPay', 'UnionCard', 'RealName', 'newSettlePwd', 'oldSettlePwd', 'szCode',
			'Mobile', 'IDCard', 'BankProvince', 'BankCity', 'BankName',
		]);
		$wheres['AgencyID'] = Redis::get($this->token . ':agent_id:');
		if (null !== array_get($inputs, 'oldSettlePwd')) {
			$wheres['SettlePwd'] = $inputs['oldSettlePwd'];
		}
		$updates = [];

		if (null !== array_get($inputs, 'AliPay')) {
			$updates['AliPay'] = $inputs['AliPay'];
		}
		if (null !== array_get($inputs, 'UnionCard')) {
			$updates['UnionCard'] = $inputs['UnionCard'];
		}
		if (null !== array_get($inputs, 'RealName')) {
			$updates['RealName'] = $inputs['RealName'];
		}
		if (null !== array_get($inputs, 'newSettlePwd')) {
			$updates['SettlePwd'] = $inputs['newSettlePwd'];
		}
		if (null !== array_get($inputs, 'Mobile')) {
			$updates['Mobile'] = $inputs['Mobile'];
		}
		if (null !== array_get($inputs, 'IDCard')) {
			$updates['IDCard'] = $inputs['IDCard'];
		}
		if (null !== array_get($inputs, 'BankProvince')) {
			$updates['BankProvince'] = $inputs['BankProvince'];
		}
		if (null !== array_get($inputs, 'BankCity')) {
			$updates['BankCity'] = $inputs['BankCity'];
		}
		if (null !== array_get($inputs, 'BankName')) {
			$updates['BankName'] = $inputs['BankName'];
		}
		$this->setUpdateWhere($wheres)
			->setUpdateValues($updates);
		if (null !== array_get($inputs, 'szCode')) {
			$this->updateSetting->setSzCode($inputs['szCode']);
		}

		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data          = $this->outerRespond($proto, config('outsideurls.AGUpdateAgentAccount'), S2C_AGOperateRespond::class);
			$data['input'] = $updates;
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 删除代理账号
	 * @return \Illuminate\Http\Response
	 */
	public function deleteAgentAccount()
	{
		$inputs = request()->only(['AccountName']);
		$where  = new tyAgentKeyPair();
		$where->setSzKey('AccountName')->setSzValue($inputs['AccountName']);
		// $updates = [];
		// $update = new tyAgentKeyPair();
		// $update->setSzKey('NickName')->setSzValue($inputs['NickName']);
		// $updates[] = $update;
		// $update = new tyAgentKeyPair();
		// $update->setSzKey('RebatePercent')->setSzValue($inputs['RebatePercent']);
		// $updates[] = $update;
		$this->updateSetting->setArrWheres([$where]);
		$proto = $this->updateSetting->serializeToString();

		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGDeleteAgentAccount'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	private function setUpdateWhere($inputs)
	{
		$wheres = [];
		foreach ($inputs as $key => $value) {
			$where = new tyAgentKeyPair();
			$where->setSzKey($key)->setSzValue($value);
			$wheres[] = $where;
		}
		$this->updateSetting->setArrWheres($wheres);
		return $this;
	}

	private function setUpdateValues($inputs)
	{
		$updates = [];
		foreach ($inputs as $key => $value) {
			$update = new tyAgentKeyPair();
			$update->setSzKey($key)->setSzValue($value);
			$updates[] = $update;
		}
		$this->updateSetting->setArrUpdates($updates);
		return $this;
	}

	/**
	 * api日志接口
	 * @return \Illuminate\Http\Response
	 */
	public function apiLogList()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));

		$arrWhere = $this->commonArr;
		if (null !== request('UserId')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('UserId')->setNType(5)->setSzValue(request('UserId'));
			$arrWhere[] = $where;
		}
		if (null !== request('LogUse')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('LogUse')->setNType(5)->setSzValue(request('LogUse'));
			$arrWhere[] = $where;
		}

		if ($startTime = request('start_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where;
		}
		if ($endTime = request('end_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetApiLogList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {

			$this->apiResponse->error($e->getMessage(), 500);
		}
	}


	/**
	 * 用户道具查询
	 * @return \Illuminate\Http\Response
	 */
	public function agGetMyItem()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('ItemName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('ItemName')->setNType(5)->setSzValue(request('ItemName'));
			$arrWhere[] = $where;
		}
		if (null !== request('userid')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('userid')->setNType(0)->setSzValue(request('userid'));
			$arrWhere[] = $where;
		}
		if ($startTime = request('start_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('Time')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where;
		}
		if ($endTime = request('end_time')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('Time')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetMyItem'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}
	/**
	 *
	 *  GM增加道具接口
	 * @return \Illuminate\Http\Response
	 */
	public function agUpdateMyItem()
	{
		$inputs           = request()->only(['userid', 'ItemId', 'Count','reason']);
		$wheres = [];
		$wheres['userid'] = array_get($inputs,'userid');

		$updates = [];

		if (null !== array_get($inputs, 'ItemId')) {
			$updates['ItemId'] = $inputs['ItemId'];
		}
		if (null !== array_get($inputs, 'Count')) {
			$updates['Count'] = $inputs['Count'];
		}

		$this->setUpdateWhere($wheres)
			->setUpdateValues($updates);
		$this->updateSetting->setSzMsg($inputs['reason']);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data          = $this->outerRespond($proto, config('outsideurls.AGUpdateMyItem'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			\Log::info($e);
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}
	/**
	 * 用户道具修改日志
	 * @return \Illuminate\Http\Response
	 */
	public function agGetItemRecordList()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('ItemName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('ItemName')->setNType(5)->setSzValue(request('ItemName'));
			$arrWhere[] = $where;
		}
		if (null !== request('userid')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('userid')->setNType(0)->setSzValue(request('userid'));
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetItemRecordList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 商城列表
	 * @return \Illuminate\Http\Response
	 */
	public function agGetShopList()
	{

		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('ItemName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('ItemName')->setNType(5)->setSzValue(request('ItemName'));
			$arrWhere[] = $where;
		}

		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetShopList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 商城修改
	 * @return \Illuminate\Http\Response
	 */
	public function agUpdateShopList()
	{
		$inputs           = request()->only(['ItemId', 'NormalPrice', 'VipPrice' , 'CurPrice','ItemName']);
		$wheres['ItemId'] = $inputs['ItemId'];

		$updates = [];

		if (null !== array_get($inputs, 'NormalPrice')) {
			$updates['NormalPrice'] = $inputs['NormalPrice'];
		}
		if (null !== array_get($inputs, 'VipPrice')) {
			$updates['VipPrice'] = $inputs['VipPrice'];
		}
		if (null !== array_get($inputs, 'CurPrice')) {
			$updates['CurPrice'] = $inputs['CurPrice'];
		}
		if (null !== array_get($inputs, 'ItemName')) {
			$updates['ItemName'] = $inputs['ItemName'];
		}

		$this->setUpdateWhere($wheres)
			->setUpdateValues($updates);

		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data          = $this->outerRespond($proto, config('outsideurls.AGUpdateShopList'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			\Log::info($e);
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 商城增加
	 * @return \Illuminate\Http\Response
	 */
	public function agInsertShopList()
	{
		$inputs = request()->only(['itemid', 'NormalPrice', 'VIPPrice', 'CurPrice','Order']);
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();
		// 请求外部接口
		try {
			$data          = $this->outerRespond($proto, config('outsideurls.AGInsertShopList'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			\Log::info($e);
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 商城删除
	 * @return \Illuminate\Http\Response
	 */
	public function agDeleteShopList()
	{
		$inputs           = request()->only(['ItemId']);
		$where  = new tyAgentKeyPair();
		$where->setSzKey('ItemId')->setSzValue($inputs['ItemId']);
		$this->deleteSetting->setArrWheres([$where]);
		$proto = $this->deleteSetting->serializeToString();
		// 请求外部接口
		try {
			$data          = $this->outerRespond($proto, config('outsideurls.AGDeleteShopList'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			\Log::info($e);
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	public function agGetGameCustomSetting()
	{

		// $this->queryMessage->setMinIndex(request('minIndex', 0));
		// $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
		if (null !== request('RoomName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RoomName')->setSzValue(request('RoomName'));
			$arrWhere[] = $where;
		}

		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();

		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetGameCustomSetting'), S2C_AGGetListRespond::class);

			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	public function agGetGameInfo()
	{

		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('ItemName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('ItemName')->setNType(5)->setSzValue(request('ItemName'));
			$arrWhere[] = $where;
		}

		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetGameInfo'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理创建约战房间，依次传入：房间名，房间号，局数，底分，玩法（0表示常规玩法）,货币类型（0表示游戏币，1表示积分）,房费类型（0表示玩家免房费，1表示玩家自负房费）
	 * @return \Illuminate\Http\Response
	 */
	public function agInsertGame()
	{
		$inputs = request()->only(['RoomName', 'RoomNumber', 'innings', 'footScore','playType','currencyType','freeType']);
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();

		// 请求外部接口
		try {
			$data          = $this->outerRespond($proto, config('outsideurls.AGInsertGame'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			\Log::info($e);
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 代理删除约战房间， where key：RoomName和RoomNumber
	 * @return \Illuminate\Http\Response
	 */
	public function agDeleteGame()
	{
		$inputs           = request()->only(['RoomName','RoomNumber']);
		$wheres = [];

		$where  = new tyAgentKeyPair();
		$where->setSzKey('RoomName')->setSzValue($inputs['RoomName']);
		$wheres[] = $where;

		$where  = new tyAgentKeyPair();
		$where->setSzKey('RoomNumber')->setSzValue($inputs['RoomNumber']);
		$wheres[] = $where;

		$this->deleteSetting->setArrWheres($wheres);
		$proto = $this->deleteSetting->serializeToString();
		// 请求外部接口
		try {
			$data          = $this->outerRespond($proto, config('outsideurls.AGDeleteGame'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			\Log::info($e);
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

    /**
     *  获取推广统计
     * @return \Illuminate\Http\Response
     */
    public function wjPromoList()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if ($agencyID = request('AgencyID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('AgencyID')->setNType(0)->setSzValue($agencyID);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();

        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGWJGetPromoList'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    /**
     * 返回推广链接
     * @return \Illuminate\Http\Response
     */
    public function wjPromoUrl()
    {
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }

        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGWJGetPromoURL'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    /**
     * 请求玩家列表
     * @return \Illuminate\Http\Response
     */
    public function wjUserList()
    {

        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RegDate')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RegDate')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if ($userId = request('UserID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserID')->setNType(0)->setSzValue($userId);
            $arrWhere[] = $where;
        }
        if ($parentId = request('ParentId')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('ParentId')->setNType(0)->setSzValue($parentId);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGWJGetUserList'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //获取推广设置
    public function agGetPromoSetting()
    {
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetPromoSetting'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //更新推广设置
    public function agUpdatePromoSetting()
    {
        $inputs           = request()->only(['h5url','shorturl','tuigurl','wxurl','shorturl0','shorturl1','shorturl2',
            'shorturl3','shorturl4','shorturl5','shorturl6','shorturl7','shorturl8','shorturl9']);
        $updates = [];

        if (null !== array_get($inputs, 'h5url')) {
            $updates['h5url'] = $inputs['h5url'];
        }
        if (null !== array_get($inputs, 'wxurl')) {
            $updates['wxurl'] = $inputs['wxurl'];
        }
        if (null !== array_get($inputs, 'tuigurl')) {
            $updates['tuigurl'] = $inputs['tuigurl'];
        }
        if (null !== array_get($inputs, 'shorturl')) {
            $updates['shorturl'] = $inputs['shorturl'];
        }
        if (null !== array_get($inputs, 'shorturl0')) {
            $updates['shorturl0'] = $inputs['shorturl0'];
        }
        if (null !== array_get($inputs, 'shorturl0')) {
            $updates['shorturl1'] = $inputs['shorturl1'];
        }
        if (null !== array_get($inputs, 'shorturl2')) {
            $updates['shorturl2'] = $inputs['shorturl2'];
        }
        if (null !== array_get($inputs, 'shorturl3')) {
            $updates['shorturl3'] = $inputs['shorturl3'];
        }
        if (null !== array_get($inputs, 'shorturl4')) {
            $updates['shorturl4'] = $inputs['shorturl4'];
        }
        if (null !== array_get($inputs, 'shorturl5')) {
            $updates['shorturl5'] = $inputs['shorturl5'];
        }
        if (null !== array_get($inputs, 'shorturl6')) {
            $updates['shorturl6'] = $inputs['shorturl6'];
        }
        if (null !== array_get($inputs, 'shorturl7')) {
            $updates['shorturl7'] = $inputs['shorturl7'];
        }
        if (null !== array_get($inputs, 'shorturl8')) {
            $updates['shorturl8'] = $inputs['shorturl8'];
        }
        if (null !== array_get($inputs, 'shorturl9')) {
            $updates['shorturl9'] = $inputs['shorturl9'];
        }
        $this->setUpdateValues($updates);

        $proto = $this->updateSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGUpdatePromoSetting'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //游戏输赢统计
    public function agGetGameWinTotal()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('EndTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('EndTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (null !== request('UserID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserId')->setNType(0)->setSzValue(request('UserID'));
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetGameWinTotal'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //获取奖励配置
    public function agGetAwardSetting()
    {
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetAwardSetting'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //更新奖励配置
    public function agUpdateAwardSetting()
    {
        $inputs           = request()->only(['AwardLimit','AwardRate']);
        $updates = [];

        if (null !== array_get($inputs, 'AwardLimit')) {
            $updates['AwardLimit'] = $inputs['AwardLimit'];
        }
        if (null !== array_get($inputs, 'AwardRate')) {
            $updates['AwardRate'] = $inputs['AwardRate'];
        }

        $this->setUpdateValues($updates);

        $proto = $this->updateSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGUpdateAwardSetting'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    /**
     * 领取收益
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userAgencyAward(){
        $name = request('name');
        $client = new Client();
        $url = env('OUT_BASE_URL','http://65.52.175.24:30000').'/GetUserAgencyAward?AccountName='.$name;
        $response = $client->request('GET', $url);
        $result   = (string) $response->getBody();
        $str      = substr($result, strpos($result, '=') + 1);
        $resp     = new S2C_AGOperateRespond();
        $resp->mergeFromString($str);
        $data = json_decode($resp->serializeToJsonString(),true);
        return $this->apiResponse->json($data);
    }

    //玩家曲线
    public function agGoldDetail()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 1000));
        $arrWhere = $this->commonArr;
        if (null !== request('UserId')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
            $arrWhere[] = $where;
        }else{
            $this->apiResponse->error('用户id参数有误', 400);
        }
        if (null !== request('RoomName')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RoomName')->setNType(0)->setSzValue(request('RoomName'));
            $arrWhere[] = $where;
        }
        if (null !== request('game_type')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('游戏')->setNType(0)->setSzValue(request('game_type'));
            $arrWhere[] = $where;
        }
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('EndTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('EndTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }

        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGoldDetail'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //游戏币来源
    public function agGoldSource()
    {
        $arrWhere = $this->commonArr;
        if (null !== request('UserId')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
            $arrWhere[] = $where;
        }else{
            $this->apiResponse->error('用户id参数有误', 400);
        }

        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }

        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGoldSource'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //获取红包配置
    public function agGetRedPacketSetting()
    {
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetRedPacketSetting'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //更新红包配置
    public function agUpdateRedPacketSetting()
    {
        $inputs           = request()->only(["StartTime", "EndTime", "Amount", "DisPlayAmount", "PlayerNum"]);
        $updates = [];

        if (null !== array_get($inputs, 'StartTime')) {
            $updates['StartTime'] = $inputs['StartTime'];
        }
        if (null !== array_get($inputs, 'EndTime')) {
            $updates['EndTime'] = $inputs['EndTime'];
        }
        if (null !== array_get($inputs, 'Amount')) {
            $updates['Amount'] = $inputs['Amount'];
        }
        if (null !== array_get($inputs, 'DisPlayAmount')) {
            $updates['DisPlayAmount'] = $inputs['DisPlayAmount'];
        }
        if (null !== array_get($inputs, 'PlayerNum')) {
            $updates['PlayerNum'] = $inputs['PlayerNum'];
        }

        $this->setUpdateValues($updates);

        $proto = $this->updateSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGUpdateRedPacketSetting'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    /**
     * 发红包，依次传入，红包昵称，显示金额，实际金额, 红包个数，指定账号1，指定账号2...指定账号N
     * @return \Illuminate\Http\Response
     */
    public function agInsertRedPacket()
    {
//        $inputs = request()->only(['redName', 'redMoney', 'redNum']);
        $inserts=[];
        $inserts[] = request('redName');
        $inserts[] = request('showMoney') * env('TRANSFER_PROP',1);
        $inserts[] = request('realMoney') * env('TRANSFER_PROP',1);
        $inserts[] = request('redNum');

        if(request('accounts')!==null){
           $accountsArr = explode(",", request('accounts'));
           foreach ($accountsArr as $value){
              if($value){
                  $inserts[] = $value;
              }
           }
        }

        $this->insertSetting->setArrValues($inserts);
        $proto = $this->insertSetting->serializeToString();

        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGInsertRedPacket'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    /**
     * 发用户邮件，依次传入，标题，内容，金额（0表示没奖励），指定账号1，指定账号2...指定账号N
     * @return \Illuminate\Http\Response
     */
    public function agInsertUserMessage()
    {
        $inputs = request()->only(['title', 'content']);
        $inserts = array_values($inputs);
        $inserts[] = request('money') * env('TRANSFER_PROP',1);

        if(request('accounts')!==null){
            $accountsArr = explode(",", request('accounts'));
            foreach ($accountsArr as $value){
                if($value){
                    $inserts[] = $value;
                }
            }
        }
        $this->insertSetting->setArrValues($inserts);
        $proto = $this->insertSetting->serializeToString();

        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGInsertUserMessage'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //查询排行奖励
    public function agGetRanklistSetting()
    {
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetRanklistSetting'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //修改排行奖励
    public function agUpdateRanklistSetting()
    {
        $inputs           = request()->only("pkId", "RankName", "RankNum", "RewardNum");
        $updates = [];

        if (null !== array_get($inputs, 'RankName')) {
            $updates['RankName'] = $inputs['RankName'];
        }
        if (null !== array_get($inputs, 'RankNum')) {
            $updates['RankNum'] = $inputs['RankNum'];
        }
        if (null !== array_get($inputs, 'RewardNum')) {
            $updates['RewardNum'] = $inputs['RewardNum'];
        }
        $this->setUpdateWhere(['pkId' => $inputs['pkId']])
            ->setUpdateValues($updates);

        $proto = $this->updateSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGUpdateRanklistSetting'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //查询红包领取情况
    public function agGetRedPacketList()
    {
		$this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (null !== request('UserId')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
            $arrWhere[] = $where;
        }

        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetRedPacketList'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //查询系统红包领取情况
    public function agGetSysRedPacket()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (null !== request('UserId')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
            $arrWhere[] = $where;
        }

        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('CreateTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('CreateTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetSysRedPacket'), S2C_AGGetListRespond::class);

            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //查询排行玩家
    public function agGetRankUserlist()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (null !== request('UserId')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserID')->setNType(0)->setSzValue(request('UserId'));
            $arrWhere[] = $where;
        }
        if (null !== request('RankName')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RankName')->setNType(0)->setSzValue(request('RankName'));
            $arrWhere[] = $where;
        }

        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetRankUserlist'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //查询过滤
    public function agGetFilter()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));

        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetFilter'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //新增过滤字
    public function agInsertFilter()
    {
        $inputs = request()->only(['words']);
        $inserts = array_values($inputs);
        $this->insertSetting->setArrValues($inserts);
        $proto = $this->insertSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGInsertFilter'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //修改过滤字
    public function agUpdateFilter()
    {
        $inputs           = request()->only("oldName", "FilterName");
        $updates = [];

        if (null !== array_get($inputs, 'FilterName')) {
            $updates['FilterName'] = $inputs['FilterName'];
        }

        $this->setUpdateWhere(['FilterName' => $inputs['oldName']])
            ->setUpdateValues($updates);

        $proto = $this->updateSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGUpdateFilter'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //查询忽略帐号
    public function agGetLimitUser()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (null !== request('UserID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserID')->setNType(0)->setSzValue(request('UserID'));
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetLimitUser'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //新增忽略帐号
    public function agInsertLimitUser()
    {
        $inputs = request()->only(['words']);
        $inserts = array_values($inputs);
        $this->insertSetting->setArrValues($inserts);
        $proto = $this->insertSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGInsertLimitUser'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //修改忽略帐号
    public function agUpdateLimitUser()
    {
        $inputs           = request()->only("UserID", "Status");
        $updates = [];

        if (null !== array_get($inputs, 'Status')) {
            $updates['Status'] = $inputs['Status'];
        }

        $this->setUpdateWhere(['UserID' => $inputs['UserID']])
            ->setUpdateValues($updates);

        $proto = $this->updateSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGUpdateLimitUser'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //下分记录查询
    public function agGetAgentTransferRecord()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (null !== request('AgencyID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('AgencyID')->setNType(0)->setSzValue(request('AgencyID'));
            $arrWhere[] = $where;
        }
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetAgentTransferRecord'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //收分记录查询
    public function agGetPlayerTransferRecord()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (null !== request('AgencyID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('AgencyID')->setNType(0)->setSzValue(request('AgencyID'));
            $arrWhere[] = $where;
        }
        if (null !== request('UserID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('FromUserID')->setNType(0)->setSzValue(request('UserID'));
            $arrWhere[] = $where;
        }
        if (null !== request('Status')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('Status')->setNType(0)->setSzValue(request('Status'));
            $arrWhere[] = $where;
        }
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetPlayerTransferRecord'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //收分记录审核
    public function agUpdatePlayerTransferRecord()
    {
        $inputs           = request()->only("pkid", "Status", "remark");
        $updates = [];

        if (null !== array_get($inputs, 'Status')) {
            $updates['Status'] = $inputs['Status'];
        }
        if (null !== array_get($inputs, 'remark')) {
            $updates['AdminMsg'] = $inputs['remark'];
        }

        $this->setUpdateWhere(['pkid' => $inputs['pkid']])
            ->setUpdateValues($updates);

        $proto = $this->updateSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGUpdatePlayerTransferRecord'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //查询分包管理
    public function agGetAgentPackages()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (null !== request('AgencyID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('AgencyID')->setNType(0)->setSzValue(request('AgencyID'));
            $arrWhere[] = $where;
        }  if (null !== request('PackageName')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('PackageName')->setNType(5)->setSzValue(request('PackageName'));
            $arrWhere[] = $where;
        }

        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetAgentPackages'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //修改分包管理
    public function agUpdateAgentPackages()
    {
        $inputs           = request()->only("PackageName", "AgencyID", "PromoURL");
        $updates = [];

        if (null !== array_get($inputs, 'AgencyID')) {
            $updates['AgencyID'] = $inputs['AgencyID'];
        }
        if (null !== array_get($inputs, 'PromoURL')) {
            $updates['PromoURL'] = $inputs['PromoURL'];
        }


        $this->setUpdateWhere(['PackageName' => $inputs['PackageName']])
            ->setUpdateValues($updates);

        $proto = $this->updateSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.AGUpdateAgentPackages'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //全民推广统计查询
    public function agGetWJStatic()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;

        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetWJStatic'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }


    //玩家游戏轨迹
    public function agGetPlayerFootmark()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;

        if (null !== request('UserId')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
            $arrWhere[] = $where;
        }


        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetPlayerFootmark'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //新手分析查询
    public function agGetNewPlayerList()
    {
//        $this->queryMessage->setMinIndex(request('minIndex', 0));
//        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (null !== request('agentId')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('agentId')->setNType(0)->setSzValue(request('agentId'));
            $arrWhere[] = $where;
        }
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RegDate')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RegDate')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.AGGetNewPlayerList'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //订单查询
    public function apiGetRecordList()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;

        if (null !== request('OrderID')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('OrderID')->setNType(0)->setSzValue(request('OrderID'));
            $arrWhere[] = $where;
        }
        if (null !== request('WithdrawType')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('WithdrawType')->setNType(0)->setSzValue(request('WithdrawType'));
            $arrWhere[] = $where;
        }
        if (null !== request('MerchantName')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('MerchantName')->setNType(5)->setSzValue(request('MerchantName'));
            $arrWhere[] = $where;
        }
        if (null !== request('RealName')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('RealName')->setNType(0)->setSzValue(request('RealName'));
            $arrWhere[] = $where;
        }

        if (null !== request('Status')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('Status')->setNType(0)->setSzValue(request('Status'));
            $arrWhere[] = $where;
        }
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('CreateTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('CreateTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.APIGetRecordList'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //API账号查询
    public function apiGetAccount()
    {
        $this->queryMessage->setMinIndex(request('minIndex', 0));
        $this->queryMessage->setMaxIndex(request('maxIndex', 30));
        $arrWhere = $this->commonArr;
        if (null !== request('AccountName')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('AccountName')->setNType(5)->setSzValue(request('AccountName'));
            $arrWhere[] = $where;
        }
        if (null !== request('NickName')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('NickName')->setNType(0)->setSzValue(request('NickName'));
            $arrWhere[] = $where;
        }
        if (null !== request('Status')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('Status')->setNType(0)->setSzValue(request('Status'));
            $arrWhere[] = $where;
        }
        if ($startTime = request('start_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('CreateTime')->setNType(2)->setSzValue($startTime);
            $arrWhere[] = $where;
        }
        if ($endTime = request('end_time')) {
            $where = new tyAgentKeyPair();
            $where->setSzKey('CreateTime')->setNType(4)->setSzValue($endTime);
            $arrWhere[] = $where;
        }
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
        $proto = $this->queryMessage->serializeToString();
        // 请求外部接口
        try {
            $data = $this->outerRespond($proto, config('outsideurls.APIGetAccount'), S2C_AGGetListRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
    //新增api帐号
    public function apiInsertAccount()
    {
        $inputs = request()->only(['AccountName','NickName','password','Token']);
        $inserts = array_values($inputs);
        $this->insertSetting->setArrValues($inserts);
        $proto = $this->insertSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.APIInsertAccount'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }

    //修改api帐号
    public function apiUpdateAccount()
    {
        $inputs           = request()->only(['AccountName','NickName','Status','Token','IPPermission']);
        $updates = [];

        if (null !== array_get($inputs, 'Status')) {
            $updates['Status'] = $inputs['Status'];
        }
        if (null !== array_get($inputs, 'NickName')) {
            $updates['NickName'] = $inputs['NickName'];
        }
        if (null !== array_get($inputs, 'Token')) {
            $updates['Token'] = $inputs['Token'];
        }
        if (null !== array_get($inputs, 'IPPermission')) {
            $updates['IPPermission'] = $inputs['IPPermission'];
        }

        $this->setUpdateWhere(['AccountName' => $inputs['AccountName']])
             ->setUpdateValues($updates);

        $proto = $this->updateSetting->serializeToString();
        // 请求外部接口
        try {
            $data          = $this->outerRespond($proto, config('outsideurls.APIUpdateAccount'), S2C_AGOperateRespond::class);
            return $this->apiResponse->json($data);
        } catch (\Exception $e) {
            \Log::info($e);
            $this->apiResponse->error($e->getMessage(), 500);
        }
    }
}