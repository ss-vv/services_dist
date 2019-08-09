<?php

namespace App\Http\Controllers\Api\Forward;


use App\Http\Controllers\Api\ApiController;
use App\Proto\C2S_AGGetList;
use App\Proto\C2S_AGInsertSetting;
use App\Proto\C2S_AGUpdateSetting;
use App\Proto\S2C_AGGetListRespond;
use App\Proto\S2C_AGOperateRespond;
use App\Proto\tyAgentKeyPair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class OperateController extends ApiController
{
	use  Common;
	protected $token;
	protected $queryMessage;
	protected $insertSetting;
	protected $updateSetting;
	protected $commonArr;

	public function __construct(C2S_AGGetList $C2S_AGGetList, C2S_AGInsertSetting $C2S_AGInsertSetting, C2S_AGUpdateSetting $C2S_AGUpdateSetting)
	{
		parent::__construct();
		$this->queryMessage  = $C2S_AGGetList;
		$this->insertSetting = $C2S_AGInsertSetting;
		$this->updateSetting = $C2S_AGUpdateSetting;
		$this->token         = request()->header('Authorization');
		$this->queryMessage->setSzToken($this->token);
		$this->insertSetting->setSzToken($this->token);
		$this->updateSetting->setSzToken($this->token);
		if(null!==request('order_by')){
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
	 *  运营首页
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function home()
	{
	    $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetPlatformStatics'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 *  用户查询
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function users()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('UserID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('UserID')->setNType(0)->setSzValue(request('UserID'));
			$arrWhere[] = $where;
		}
		if (null !== request('NickName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('NickName')->setNType(0)->setSzValue(request('NickName'));
			$arrWhere[] = $where;
		}
		if (null !== request('RealName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RealName')->setNType(0)->setSzValue(request('RealName'));
			$arrWhere[] = $where;
		}
		if (null !== request('Email')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('Email')->setNType(0)->setSzValue(request('Email'));
			$arrWhere[] = $where;
		}
		if (null !== request('RegisterPlatform')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RegisterPlatform')->setNType(0)->setSzValue(request('RegisterPlatform'));
			$arrWhere[] = $where;
		}
		if (null !== request('Mobile')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('Mobile')->setNType(0)->setSzValue(request('Mobile'));
			$arrWhere[] = $where;
		}
        if (null !== request('RegisterIP')) {
            $where1 = new tyAgentKeyPair();
            $where1->setSzKey('RegisterIP')->setNType(0)->setSzValue(request('RegisterIP'));
            $arrWhere[] = $where1;
        }
        if (null !== request('LastIP')) {
            $where1 = new tyAgentKeyPair();
            $where1->setSzKey('LastIP')->setNType(0)->setSzValue(request('LastIP'));
            $arrWhere[] = $where1;
        }
		if ($device = request('RegisterDevice')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RegisterDevice')->setNType(0)->setSzValue($device);
			$arrWhere[] = $where;
		}
		if ($AgentID = request('AgentID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgentID')->setNType(0)->setSzValue($AgentID);
			$arrWhere[] = $where;
		}
		if ($XG = request('XG')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('XG')->setNType(0)->setSzValue($XG);
			$arrWhere[] = $where;
		}

		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('RegDate')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RegDate')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetUserList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 *  金币日志
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function goldLogs()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('userid')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('userid')->setNType(0)->setSzValue(request('userid'));
			$arrWhere[] = $where1;
		}
		if (null !== request('logtype')) {
			$where2 = new tyAgentKeyPair();
			$where2->setSzKey('logtype')->setNType(0)->setSzValue(request('logtype'));
			$arrWhere[] = $where2;
		}
		if (null !== request('LogUse')) {
			$where2 = new tyAgentKeyPair();
			$where2->setSzKey('LogUse')->setNType(0)->setSzValue(request('LogUse'));
			$arrWhere[] = $where2;
		}
		if (null !== request('Uuid')) {
			$where2 = new tyAgentKeyPair();
			$where2->setSzKey('Uuid')->setNType(0)->setSzValue(request('Uuid'));
			$arrWhere[] = $where2;
		}
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();

		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetGoldList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 *  游戏查询
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function gameLogs()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));

		$arrWhere = $this->commonArr;
		if (null !== request('UserId')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
			$arrWhere[] = $where1;
		}
		if (null !== request('NickName')) {
			$where2 = new tyAgentKeyPair();
			$where2->setSzKey('NickName')->setNType(0)->setSzValue(request('NickName'));
			$arrWhere[] = $where2;
		}
		if (null !== request('GameID')) {
			$where2 = new tyAgentKeyPair();
			$where2->setSzKey('GameID')->setNType(0)->setSzValue(request('GameID'));
			$arrWhere[] = $where2;
		}
		if ($gametype = request('game_type')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('游戏')->setNType(0)->setSzValue($gametype);
			$arrWhere[] = $where3;
		}
		if ($roomName = request('RoomName')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RoomName')->setNType(5)->setSzValue($roomName);
			$arrWhere[] = $where4;
		}
		if ($startTime = request('start_time')) {
			$where5 = new tyAgentKeyPair();
			$where5->setSzKey('begintime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where5;
		}
		if ($endTime = request('end_time')) {
			$where6 = new tyAgentKeyPair();
			$where6->setSzKey('begintime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where6;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetGameList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 *  银行金币调整记录 (名字和金币调整错对了)
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function goldAdjustment()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('userid')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('userid')->setNType(0)->setSzValue( request('userid'));
			$arrWhere[] = $where1;
		}
		if (null !== request('LogUse')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('LogUse')->setNType(5)->setSzValue(request('LogUse'));
			$arrWhere[] = $where1;
		}
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetGoldRecordList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}


	/**
	 *  金币调整记录
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function bankGoldAdjustment()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if ($userid = request('userid')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('userid')->setNType(0)->setSzValue($userid);
			$arrWhere[] = $where1;
		}
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetBankGoldRecordList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}
	/**
	 *  账号封禁记录
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function accountBlocking()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if ($userid = request('userid')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('userid')->setNType(0)->setSzValue($userid);
			$arrWhere[] = $where1;
		}
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetBanRecordList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 *  游戏公告
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function gameMessage()
	{

		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('StartTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('StartTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetMessageList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 *  修改游戏公告
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateGameMessage()
	{
		$inputs = request()->only(['pkid', 'MsgType', 'MsgTitle', 'MsgContent', 'StartTime', 'EndTime']);
		$this->setUpdateWhere(['pkid' => $inputs['pkid']])
			->setUpdateValues([
				'MsgType'    => $inputs['MsgType'],
				'MsgTitle'   => $inputs['MsgTitle'],
				'MsgContent' => $inputs['MsgContent'],
				'StartTime'  => $inputs['StartTime'],
				'EndTime'    => $inputs['EndTime'],

			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateMessageList'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 *  游戏税收
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function gameTax()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('UserId')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
			$arrWhere[] = $where1;
		}
		if (null !== request('NickName')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('NickName')->setNType(0)->setSzValue(request('NickName'));
			$arrWhere[] = $where1;
		}
		if (null !== request('RegisterPlatform')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('RegisterPlatform')->setNType(0)->setSzValue(request('RegisterPlatform'));
			$arrWhere[] = $where1;
		}
		if (null !== request('game_type')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('游戏')->setNType(5)->setSzValue(request('game_type'));
			$arrWhere[] = $where3;
		}
		if (null !== request('RoomName')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RoomName')->setNType(5)->setSzValue(request('RoomName'));
			$arrWhere[] = $where4;
		}
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('begintime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('begintime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetTaxList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 *  在线用户
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function usersOnline()
	{

		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));

		$arrWhere = $this->commonArr;
		if (null !== request('UserId')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
			$arrWhere[] = $where1;
		}
		if (null !== request('NickName')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('NickName')->setNType(0)->setSzValue(request('NickName'));
			$arrWhere[] = $where1;
		}
		if (null !== request('RegisterPlatform')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('RegisterPlatform')->setNType(0)->setSzValue(request('RegisterPlatform'));
			$arrWhere[] = $where1;
		}

		if (null !== request('game_type')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('GameName')->setNType(5)->setSzValue(request('game_type'));
			$arrWhere[] = $where3;
		}
		if (null !== request('RoomName')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RoomName')->setNType(5)->setSzValue(request('RoomName'));
			$arrWhere[] = $where4;
		}

		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetOLUserList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 *  举报记录
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function reportLogs()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));

		$arrWhere = $this->commonArr;
		if ($userid = request('UserId')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('UserId')->setNType(0)->setSzValue($userid);
			$arrWhere[] = $where1;
		}

		if ($agentId = request('AgentId')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('AgentId')->setNType(0)->setSzValue($agentId);
			$arrWhere[] = $where3;
		}

		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('UpdateTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('UpdateTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}

		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetReportList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 *  提现订单
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function exchangeOrders()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));

		$arrWhere = $this->commonArr;
		if (null !== request('UserID')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('UserID')->setNType(0)->setSzValue(request('UserID'));
			$arrWhere[] = $where1;
		}
		if (null !== request('Status')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('Status')->setNType(0)->setSzValue(request('Status'));
			$arrWhere[] = $where1;
		}
		if (null !== request('WithdrawChannel')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('WithdrawChannel')->setNType(0)->setSzValue(request('WithdrawChannel'));
			$arrWhere[] = $where1;
		}
		if (null !== request('OrderID')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('OrderID')->setNType(0)->setSzValue(request('OrderID'));
			$arrWhere[] = $where1;
		}

		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('LogTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('LogTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}

		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetExchangeList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}


	/**
	 * 提现设置 查询
	 * @return \Illuminate\Http\Response
	 */
	public function exchangeSetting()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetExchangeSetting'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 客服反馈列表
	 * @return \Illuminate\Http\Response
	 */
	public function serviceList()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('servicetype')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('servicetype')->setNType(0)->setSzValue(request('servicetype'));
			$arrWhere[] = $where1;
		}
		if (null !== request('UserId')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
			$arrWhere[] = $where1;
		}
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetServiceList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}


	/**
	 * 注册赠送配置
	 * @return \Illuminate\Http\Response
	 */
	public function registerSetting()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetRegistSetting'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 举报有奖配置
	 * @return \Illuminate\Http\Response
	 */
	public function reportSetting()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetReportSetting'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 后台登录日志
	 * @return \Illuminate\Http\Response
	 */
	public function loginLogs()
	{

		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));

		$arrWhere = $this->commonArr;
		if ($accountName = request('AccountName')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('AccountName')->setNType(0)->setSzValue($accountName);
			$arrWhere[] = $where1;
		}
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetLoginList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 后台操作日志
	 * @return \Illuminate\Http\Response
	 */
	public function operateLogs()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));

		$arrWhere = $this->commonArr;
		if (null !== request('msgname')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('msgname')->setNType(5)->setSzValue(request('msgname'));
			$arrWhere[] = $where1;
		}
		if (null !== request('LogRequest')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('LogRequest')->setNType(5)->setSzValue(request('LogRequest'));
			$arrWhere[] = $where1;
		}
		if (null !== request('AccountName')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('AccountName')->setNType(0)->setSzValue(request('AccountName'));
			$arrWhere[] = $where1;
		}
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetOpreateList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 充值订单
	 * @return \Illuminate\Http\Response
	 */
	public function chargeOrders()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('UserId')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('UserId')->setNType(0)->setSzValue(request('UserId'));
			$arrWhere[] = $where1;
		}
		if (null !== request('OrderId')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('OrderId')->setNType(0)->setSzValue(request('OrderId'));
			$arrWhere[] = $where1;
		}
		if (null !== request('Status')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('Status')->setNType(0)->setSzValue(request('Status'));
			$arrWhere[] = $where1;
		}
		if (null !== request('PayType')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('PayType')->setNType(0)->setSzValue(request('PayType'));
			$arrWhere[] = $where1;
		}
		if (null !== request('PayChanel')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('PayChanel')->setNType(0)->setSzValue(request('PayChanel'));
			$arrWhere[] = $where1;
		}
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('CreateTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('CreateTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetChargeList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}


	/**
	 * 玩家提现配置
	 * @return \Illuminate\Http\Response
	 */
	public function updateExchangeSetting()
	{
		$inputs = request()->only(['pkid', 'MinExchange', 'MaxExchange', 'Tax','LimitCount','UnionMinExchange','UnionMaxExchange','UnionTax', 'State']);

		$this->setUpdateWhere(['pkid' => $inputs['pkid']])
			->setUpdateValues([
				'MinExchange' => $inputs['MinExchange'],
				'MaxExchange' => $inputs['MaxExchange'],
				'Tax'         => $inputs['Tax'],
				'LimitCount'         => $inputs['LimitCount'],
                'UnionMinExchange' => $inputs['UnionMinExchange'],
				'UnionMaxExchange' => $inputs['UnionMaxExchange'],
				'UnionTax'         => $inputs['UnionTax'],
				'State'       => $inputs['State'],
			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateExchangeSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 注册赠送修改
	 * @return \Illuminate\Http\Response
	 */
	public function updateRegistSetting()
	{

		$inputs = request()->only(['pkid', 'AwardGold']);
		$this->setUpdateValues([
			'AwardGold' => $inputs['AwardGold'],
		]);

		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateRegistSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 举报有奖修改
	 * @return \Illuminate\Http\Response
	 */
	public function updateReportSetting()
	{

		$inputs = request()->only(['id', 'ntype', 'state']);
		$this->setUpdateWhere(['id' => $inputs['id']])
			->setUpdateValues([
				'ntype'    => $inputs['ntype'],
				'state'    => $inputs['state'],
			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateReportSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 游戏房间设置
	 * @return \Illuminate\Http\Response
	 */
	public function gameRoomSetting()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('AgentName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('AgentName')->setNType(0)->setSzValue(request('AgentName'));
			$arrWhere[] = $where;
		}
		if (null !== request('Name')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('Name')->setNType(0)->setSzValue(request('Name'));
			$arrWhere[] = $where;
		}
		if (null !== request('IsLocked')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('IsLocked')->setNType(0)->setSzValue(request('IsLocked'));
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetGameRoomSetting'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}
	/**
	 * 修改游戏房间设置
	 * @return \Illuminate\Http\Response
	 */
	public function updateGameRoomSetting()
	{

		$inputs = request()->only(['pkId', 'AgentName', 'Name', 'GameParams', 'MinGold', 'OrderId', 'TaxRate', 'BaseScore',
			'CurGoldPool', 'MinGoldPool', 'NormalGoldPool_L', 'NormalGoldPool_H', 'MaxGoldPool', 'ServiceRate', 'maxWinLimit',
			'maxWinRate', 'minWinRate', 'maxLoseRate', 'minLoseRate', 'IsLocked','GameTypeId'
		]);
		$this->setUpdateWhere(['pkId' => $inputs['pkId']])
			->setUpdateValues([
				'AgentName'        => $inputs['AgentName'],
				'Name'             => $inputs['Name'],
				'GameParams'       => $inputs['GameParams'],
				'MinGold'          => $inputs['MinGold'],
				'OrderId'          => $inputs['OrderId'],
				'TaxRate'          => $inputs['TaxRate'],
				'BaseScore'        => $inputs['BaseScore'],
				'CurGoldPool'      => $inputs['CurGoldPool'],
				'MinGoldPool'      => $inputs['MinGoldPool'],
				'NormalGoldPool_L' => $inputs['NormalGoldPool_L'],
				'NormalGoldPool_H' => $inputs['NormalGoldPool_H'],
				'MaxGoldPool'      => $inputs['MaxGoldPool'],
				'ServiceRate'      => $inputs['ServiceRate'],
				'maxWinLimit'      => $inputs['maxWinLimit'],
				'maxWinRate'       => $inputs['maxWinRate'],
				'minWinRate'       => $inputs['minWinRate'],
				'maxLoseRate'      => $inputs['maxLoseRate'],
				'minLoseRate'      => $inputs['minLoseRate'],
				'GameTypeId'       => $inputs['GameTypeId'],
				'IsLocked'         => $inputs['IsLocked'],
			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateGameRoomSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 修改游戏房间 停服 开服
	 * @return \Illuminate\Http\Response
	 */
	public function updateGameRoomStatus()
	{
		$inputs = request()->only([ 'AgentName', 'Name', 'Status']);
		$this->setUpdateWhere(['AgentName' => $inputs['AgentName'],'Name' => $inputs['Name']])
			 ->setUpdateValues(['Status' => $inputs['Status']]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateGameRoomSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}


	/**
	 * 机器人配置查询
	 * @return \Illuminate\Http\Response
	 */
	public function robotSetting()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('RoomName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('RoomName')->setNType(5)->setSzValue(request('RoomName'));
			$arrWhere[] = $where;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetRobotSetting'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}


	/**
	 * 修改机器人配置
	 * @return \Illuminate\Http\Response
	 */
	public function updateRobotSetting()
	{
		$inputs = request()->only([ 'pkId', 'RoomName','GoldMax', 'GoldMin','RobotCount']);
		$this->setUpdateWhere(['pkId' => $inputs['pkId'],'RoomName' => $inputs['RoomName']])
			->setUpdateValues([
				'GoldMax' => $inputs['GoldMax'],
				'GoldMin' => $inputs['GoldMin'],
				'RobotCount' => $inputs['RobotCount']
			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateRobotSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 修改携带游戏币
	 * @return \Illuminate\Http\Response
	 */
	public function updateGold()
	{

		$inputs = request()->only(['UserID', 'Money', 'reason']);
		$this->setUpdateWhere(['UserID' => $inputs['UserID']])
			->setUpdateValues([
				'Money' => $inputs['Money'] * env('GOLD_PROP',1),
			]);
		$this->updateSetting->setSzMsg($inputs['reason']);

		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateGold'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 修改银行余额
	 * @return \Illuminate\Http\Response
	 */
	public function updateBankGold()
	{

		$inputs = request()->only(['UserID', 'BankMoney', 'reason']);
		$this->setUpdateWhere(['UserID' => $inputs['UserID']])
			->setUpdateValues([
				'BankMoney' => $inputs['BankMoney'] * env('GOLD_PROP',1),
			]);
		$this->updateSetting->setSzMsg($inputs['reason']);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateBankGold'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 修改账号信息
	 * @return \Illuminate\Http\Response
	 */
	public function updateAccount()
	{
		$inputs    = request()->only(['UserID', 'AgentID', 'NickName', 'Password', 'resetType','CurMaxLoseAdd','CurMinLoseAdd','CurMinWinAdd','CurMaxWinAdd']);
		$resetKeys = [
			'BankPassword' => true,
			'Mobile'       => true,
			'Email'        => true,
			'UnionCard'    => true,
			'RealName'     => true,
		];
		$updates   = [];
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
			// 修改昵称 密码
			if (null !== array_get($inputs, 'NickName')) {
				$updates['NickName'] = $inputs['NickName'];
			}
			if (null !== array_get($inputs, 'AgentID')) {
				$updates['AgentID'] = $inputs['AgentID'];
			}
			if (null !== array_get($inputs, 'Password')) {
				$updates['Password'] = $inputs['Password'];
			}
			if (null !== array_get($inputs, 'CurMaxLoseAdd')) {
				$updates['CurMaxLose'] = $inputs['CurMaxLoseAdd'];
			}
			if (null !== array_get($inputs, 'CurMinLoseAdd')) {
				$updates['CurMinLose'] = $inputs['CurMinLoseAdd'];
			}
			if (null !== array_get($inputs, 'CurMinWinAdd')) {
				$updates['CurMinWin'] = $inputs['CurMinWinAdd'];
			}
			if (null !== array_get($inputs, 'CurMaxWinAdd')) {
				$updates['CurMaxWin'] = $inputs['CurMaxWinAdd'];
			}
		}
		$this->setUpdateWhere(['UserID' => $inputs['UserID']])
			->setUpdateValues($updates);

		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data           = $this->outerRespond($proto, config('outsideurls.AGUpdateAccount'), S2C_AGOperateRespond::class);
			$data['inputs'] = $updates;
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 封锁账号
	 * @return \Illuminate\Http\Response
	 */
	public function updateAccountState()
	{
		$inputs = request()->only(['UserID', 'State', 'reason']);

		$updates = [];
		if (null !== array_get($inputs, 'State')) {
			$updates['State'] = $inputs['State'];
		}
		$this->setUpdateWhere(['UserID' => $inputs['UserID']])
			->setUpdateValues($updates);
		$this->updateSetting->setSzMsg($inputs['reason']);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data           = $this->outerRespond($proto, config('outsideurls.AGUpdateAccount'), S2C_AGOperateRespond::class);
			$data['inputs'] = $updates;
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 新增滚动公告：依次传入1标题，2内容，3开始时间，4结束时间
	 * @return \Illuminate\Http\Response
	 */
	public function insertMessage()
	{
		$inputs = request()->only(['MsgTitle', 'MsgContent', 'StartTime', 'EndTime','platform']);
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();

		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGInsertMessage'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 新增系统公告：依次传入1标题，2内容，3开始时间，4结束时间
	 * @return \Illuminate\Http\Response
	 */
	public function insertSysMessage()
	{
		$inputs = request()->only(['MsgTitle', 'MsgContent', 'StartTime', 'EndTime','platform']);
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGInsertSysMessage'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 删除公告
	 * @return \Illuminate\Http\Response
	 */
	public function deleteMessage()
	{
		$where = new tyAgentKeyPair();
		$where->setSzKey('pkid')->setNType(0)->setSzValue('18');
		$this->updateSetting->setArrWheres([$where]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGDeleteMessage'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 查询月入百万配置
	 * @return \Illuminate\Http\Response
	 */
	public function YRBWSetting()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetYRBWSetting'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 修改月入百万配置
	 * @return \Illuminate\Http\Response
	 */
	public function updateYRBWSetting()
	{

		$inputs = request()->only(['pk_id', 'ntype', 'name', 'id', 'state']);
		$this->setUpdateWhere(['pk_id' => $inputs['pk_id']])
			->setUpdateValues([
				'ntype'    => $inputs['ntype'],
				'name'     => $inputs['name'],
				'id'       => $inputs['id'],
				'state'    => $inputs['state'],
			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateYRBWSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 增加月入百万配置:依次传入，1类型（eg:qq,wx），2微信号和qq号（eg:wxagent，15687963541）
	 * @return \Illuminate\Http\Response
	 */
	public function insertYRBWSettingRespond()
	{
		// $inputs = request()->only(['pkid','ntype','name','nickname','state']);
		$inputs = request()->only(['ntype', 'name']);
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGInsertYRBWSettingRespond'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 删除月入百万配置
	 * @return \Illuminate\Http\Response
	 */
	public function deleteYRBWSettingRespond()
	{

		$inputs = request()->only(['pkid', 'ntype', 'name', 'nickname', 'state']);
		$where  = new tyAgentKeyPair();
		$where->setSzKey('pkid')->setSzValue($inputs['pkid']);
		$updates = [];
		$this->updateSetting->setArrWheres([$where])->setArrUpdates($updates);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGDeleteYRBWSettingRespond'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}


	/**
	 * 获取后台账号列表
	 * @return \Illuminate\Http\Response
	 */
	public function gmAccountList()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('AccountName')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('AccountName')->setNType(5)->setSzValue(request('AccountName'));
			$arrWhere[] = $where1;
		}
		if (null !== request('NickName')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('NickName')->setNType(5)->setSzValue(request('NickName'));
			$arrWhere[] = $where1;
		}
		if (null !== request('Mobile')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('Mobile')->setNType(0)->setSzValue(request('Mobile'));
			$arrWhere[] = $where1;
		}
		if (null !== request('Status')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('Status')->setNType(0)->setSzValue(request('Status'));
			$arrWhere[] = $where1;
		}

		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('CreateTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('CreateTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetGMAccountList'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 增加后台账号：依次传入1.账号，2.昵称，3.密码
	 * @return \Illuminate\Http\Response
	 */
	public function insertGMAccount()
	{
		$inputs = request()->only(['AccountName', 'NickName', 'Pwd', 'RoleLevel']); //字段顺序和 依次传入1.账号，2.昵称，3.密码 一致
		$this->insertSetting->setArrValues(array_values($inputs));
		$proto = $this->insertSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGInsertGMAccount'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 *修改后台账号
	 * @return \Illuminate\Http\Response
	 */
	public function updateGMAccount()
	{
		$nlvAgentKey      = $this->token . '_agent';//是否是代理key
		
		$isAgent          = Redis::get($nlvAgentKey); //是否是代理
		if($isAgent){
			$wheres['AgencyID'] = Redis::get($this->token . ':agent_id:');
				$inputs  = request()->only(['AccountName', 'NickName', 'RoleLevel', 'Pwd', 'Status']);
		$updates = [];
			if (null !== array_get($inputs, 'NickName')) {
				$updates['NickName'] = $inputs['NickName'];
			}

			if (null !== array_get($inputs, 'Pwd')) {
				$updates['AcPwd'] = $inputs['Pwd'];
			}
		
		$this->setUpdateWhere($wheres)
			->setUpdateValues($updates);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateAgentAccount'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
		}else{
		$inputs  = request()->only(['AccountName', 'NickName', 'RoleLevel', 'Pwd', 'Status','IPPermission']);
		$updates = [];
		if (null !== array_get($inputs, 'NickName')) {
			$updates['NickName'] = $inputs['NickName'];
		}
		if (null !== array_get($inputs, 'RoleLevel')) {
			$updates['RoleLevel'] = $inputs['RoleLevel'];
		}
		if (null !== array_get($inputs, 'Pwd')) {
			$updates['Pwd'] = $inputs['Pwd'];
		}
		$updates['IPPermission'] = array_get($inputs, 'IPPermission','');

		$this->setUpdateWhere(['AccountName' => $inputs['AccountName']])
			->setUpdateValues($updates);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateGMAccount'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
		}
		
	

	}

	/**
	 * 删除后台账号
	 * @return \Illuminate\Http\Response
	 */
	public function deleteGMAccount()
	{
		$inputs = request()->only(['AccountName']);
		$where  = new tyAgentKeyPair();
		$where->setSzKey('AccountName')->setSzValue($inputs['AccountName']);
		$updates = [];
		$update  = new tyAgentKeyPair();
		$update->setSzKey('Status')->setSzValue('0');
		$updates[] = $update;
		$this->updateSetting->setArrWheres([$where])->setArrUpdates($updates);
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGDeleteGMAccount'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 玩家提现审核
	 * @return \Illuminate\Http\Response
	 */
	public function updateExchange()
	{

		$inputs = request()->only(['OrderID', 'Status','remark']);
		$this->setUpdateWhere(['OrderID' => $inputs['OrderID']])
			->setUpdateValues([
				'Status' => $inputs['Status'],
			]);
		if(null!==request('remark')){
            $this->updateSetting->setSzMsg(request('remark'));
        }
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateExchange'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}


	/**
	 * 充值配置
	 * @return \Illuminate\Http\Response
	 */
	public function exchangeChannel()
	{

        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetExchangeChannel'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 充值配置修改
	 * @return \Illuminate\Http\Response
	 */
	public function updateExchangeChannel()
	{

		$inputs = request()->only(['pkid', 'Status','ChannelCallbackURL','ChannelURL','MerchandID']);
        $updates = [];
        if(null!=request('ChannelURL')){
            $updates['ChannelURL'] = request('ChannelURL');
        }
        if(null!=request('ChannelCallbackURL')){
            $updates['ChannelCallbackURL'] = request('ChannelCallbackURL');
        }
        if(null!=request('MerchandID')){
            $updates['MerchandID'] = request('MerchandID');
        }
        if(null!=request('Status')){
            $updates['Status'] = request('Status');
        }
		$this->setUpdateWhere(['pkid' => $inputs['pkid']])
			->setUpdateValues($updates);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateExchangeChannel'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 提现配置
	 * @return \Illuminate\Http\Response
	 */
	public function rechargeChannel()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetRechargeChannel'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 提现配置修改
	 * @return \Illuminate\Http\Response
	 */
	public function updateRechargeChannel()
	{
//		$inputs = request()->only(["pkid",  "ChannelURL", "ChannelCallbackURL", "MerchandID", "Status"]);
		$updates = [];
		if(null!=request('ChannelURL')){
            $updates['ChannelURL'] = request('ChannelURL');
        }
        if(null!=request('ChannelCallbackURL')){
            $updates['ChannelCallbackURL'] = request('ChannelCallbackURL');
        }
        if(null!=request('MerchandID')){
            $updates['MerchandID'] = request('MerchandID');
        }
        if(null!=request('Status')){
            $updates['Status'] = request('Status');
        }

		$this->setUpdateWhere(['pkid' => request('pkid')])
			->setUpdateValues($updates);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateRechargeChannel'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 客服反馈
	 * @return \Illuminate\Http\Response
	 */
	public function updateServiceList()
	{
		$inputs = request()->only(['pkId', 'Answer']);

		$this->setUpdateWhere(['pkId' => $inputs['pkId']])
			->setUpdateValues([
				'Answer' => $inputs['Answer'],
			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateServiceList'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 金额配置查询
	 * @return \Illuminate\Http\Response
	 */
	public function rechargeSetting()
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
			$data = $this->outerRespond($proto, config('outsideurls.AGGetRechargeSetting'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 修改金额配置
	 * @return \Illuminate\Http\Response
	 */
	public function updateRechargeSetting()
	{
		$inputs = request()->only(['pkid', 'ChargeNum']);

		$this->setUpdateWhere(['pkid' => $inputs['pkid']])
			->setUpdateValues([
				'ChargeNum' => $inputs['ChargeNum'],
			]);
		$proto = $this->updateSetting->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGUpdateRechargeSetting'), S2C_AGOperateRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}

	}

	/**
	 * 统计
	 * @return \Illuminate\Http\Response
	 */
	public function potatoRobotStatics()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if ($startTime = request('start_time')) {
			$where3 = new tyAgentKeyPair();
			$where3->setSzKey('RecordTime')->setNType(2)->setSzValue($startTime);
			$arrWhere[] = $where3;
		}
		if ($endTime = request('end_time')) {
			$where4 = new tyAgentKeyPair();
			$where4->setSzKey('RecordTime')->setNType(4)->setSzValue($endTime);
			$arrWhere[] = $where4;
		}
		if (count($arrWhere)) {
			$this->queryMessage->setArrWheres($arrWhere);
		}

		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetPotatoRobotStatics'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 用户输赢
	 * @return \Illuminate\Http\Response
	 */
	public function userWinTotal()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('UserID')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('UserID')->setNType(0)->setSzValue(request('UserID'));
			$arrWhere[] = $where;
		}
		if (null !== request('GameName')) {
			$where = new tyAgentKeyPair();
			$where->setSzKey('GameName')->setNType(0)->setSzValue(request('GameName'));
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
			$data = $this->outerRespond($proto, config('outsideurls.AGGetUserWinTotal'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 * 用户充值
	 * @return \Illuminate\Http\Response
	 */
	public function userChargeTotal()
	{
		$this->queryMessage->setMinIndex(request('minIndex', 0));
		$this->queryMessage->setMaxIndex(request('maxIndex', 30));
		$arrWhere = $this->commonArr;
		if (null !== request('UserID')) {
			$where1 = new tyAgentKeyPair();
			$where1->setSzKey('UserID')->setNType(0)->setSzValue(request('UserID'));
			$arrWhere[] = $where1;
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
			$data = $this->outerRespond($proto, config('outsideurls.AGGetUserChargeTotal'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}


	/**
	 *  税收统计 (游戏税收页面)
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function taxStatics()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetTaxStatics'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 *  在线统计 (在线玩家页面)
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function onlineStatics()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetOLStatics'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 *  提现统计（提现订单页面）
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function withdrawStatics()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetWithdrawStatics'), S2C_AGGetListRespond::class);
			return $this->apiResponse->json($data);
		} catch (\Exception $e) {
			$this->apiResponse->error($e->getMessage(), 500);
		}
	}

	/**
	 *  充值统计（充值订单页面）
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function chargeStatics()
	{
        $arrWhere = $this->commonArr;
        if (count($arrWhere)) {
            $this->queryMessage->setArrWheres($arrWhere);
        }
		$proto = $this->queryMessage->serializeToString();
		// 请求外部接口
		try {
			$data = $this->outerRespond($proto, config('outsideurls.AGGetChargeStatics'), S2C_AGGetListRespond::class);
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
}
