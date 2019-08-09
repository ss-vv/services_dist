<?php

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('auth/login', 'Api\JwtAuthController@login')->name('auth.login');

//  玩家 webSocket client_id 与 玩家id 绑定
Route::post('player_bind', 'Api\GatewayController@playerBind')->name('gateway.player_bind');
//  客服 webSocket client_operate/gm_accountid 与 玩家id 绑定
Route::post('service_bind', 'Api\GatewayController@serviceBind')->name('gateway.service_bind');
// 玩家发送信息
Route::post('send', 'Api\GatewayController@sendMessage')->name('gateway.send_message');
// 客服回复信息
Route::post('replay', 'Api\GatewayController@replayMessage')->name('gateway.replay_message');

// 查询客服未处理的玩家信息
Route::get('msg_not_replay', 'Api\ChatController@messageNotReplay')->name('chat.msg_not_replay');
// 查询具体某个玩家与客服的聊天记录
Route::get('chat_messages', 'Api\ChatController@chatMessages')->name('chat.chat_messages');

// 客服打卡日志
Route::get('punch_cards', 'Api\PunchCardController@punchCards')->name('punch_cards.punch_cards');
// 客服打卡
Route::post('punch_cards', 'Api\WithdrawalController@store')->name('punch_cards.store');
// 客服某段时间内最后一次打卡记录
Route::get('last_one_punch_card', 'Api\PunchCardController@lastOnePunchCard')->name('punch_cards.last_one_punch_card');
// 负责人管理接口
Route::get('service_time', 'Api\PunchCardController@serviceTime')->name('punch_cards.service_time');
// 客服业绩
Route::get('service_achievement', 'Api\ServiceAchievementController@index')->name('service_achievement.index');
// 查询提现申请记录
Route::get('withdrawal-list', 'Api\WithdrawalController@getList')->name('withdrawal.list');
// 修改提现订单状态
Route::post('update-status', 'Api\WithdrawalController@updateStatus')->name('withdrawal.update-status');



/**
 * Api/Forward 内的接口都是转发接口
 */
Route::namespace('Api\Forward')->group(function() {
	// admin 后台登陆
	Route::post('login', 'LoginController@gmLogin')->name('login.gm_login');
	// 代理登录接口
	Route::post('ag_login', 'LoginController@agLogin')->name('login.ag_login');
	// 运营登陆接口
	Route::post('yy_login', 'LoginController@agyyLogin')->name('login.ag_yy_login');
	// 客服登陆接口
	Route::post('kf_login', 'LoginController@agkfLogin')->name('login.ag_kf_login');
    // 玩家登陆
	Route::post('wj_login', 'LoginController@agwjLogin')->name('login.ag_wj_login');

//	Route::post('code', 'LoginController@wjLoginCode')->name('login.wj_login_code');
    // 玩家列表
	Route::get('agent/wj_user_list', 'AgentController@wjUserList')->name('agent.wj_user_list');
//	// 获取推广统计
	Route::get('agent/wj_promo_list', 'AgentController@wjPromoList')->name('agent.wj_promo_list');
//    // 推广链接
    Route::get('agent/wj_promo_url', 'AgentController@wjPromoUrl')->name('agent.wj_promo_url');
    // 玩家收入明细
	Route::get('agent/wj_income_detail', 'AgentController@wjIncomeDetail')->name('agent.wj_income_detail');
	// 领取收益
    Route::get('agent/user_agency_award', 'AgentController@userAgencyAward')->name('agent.GetUserAgencyAward');


});

//
// Route::namespace('Api\Forward')->middleware(['checkTokenIp'])->group(function() {
Route::namespace('Api\Forward')->middleware(['checkTokenIp','checkNlvPermission'])->group(function() {
	// Route::namespace('Api\Forward')->group(function() {
	// 运营首页数据接口
	Route::get('operate/home', 'OperateController@home')->name('operate.home');
	// 用户查询接口
	Route::get('operate/users', 'OperateController@users')->name('operate.users');
	// 金币日志接口
	Route::get('operate/gold_logs', 'OperateController@goldLogs')->name('operate.gold_logs');
	// 游戏查询接口
	Route::get('operate/game_logs', 'OperateController@gameLogs')->name('operate.game_logs');
	// 银行金币调整记录接口
	Route::get('operate/gold_adjustment', 'OperateController@goldAdjustment')->name('operate.gold_adjustment');
	// 金币调整记录接口
	Route::get('operate/bank_gold_adjustment', 'OperateController@bankGoldAdjustment')->name('operate.bank_gold_adjustment');
	// 账号封禁记录接口
	Route::get('operate/account_blocking', 'OperateController@accountBlocking')->name('operate.account_blocking');
	// 游戏公告接口
	Route::get('operate/game_message', 'OperateController@gameMessage')->name('operate.game_message');
	// 修改游戏公告
	Route::put('operate/game_message', 'OperateController@updateGameMessage')->name('operate.update_game_message');
	// 游戏税收
	Route::get('operate/game_tax', 'OperateController@gameTax')->name('operate.game_tax');
	// 在线用户接口
	Route::get('operate/users_online', 'OperateController@usersOnline')->name('operate.users_online');

	// 举报记录
	Route::get('operate/report_logs', 'OperateController@reportLogs')->name('operate.report_logs');
	// 提现订单
	Route::get('operate/exchange_orders', 'OperateController@exchangeOrders')->name('operate.exchange_orders');
	// 提现设置 (查询玩家的提现设置)
	Route::get('operate/exchange_setting', 'OperateController@exchangeSetting')->name('operate.exchange_setting');
	// 客服反馈列表
	Route::put('operate/service_list', 'OperateController@updateServiceList')->name('operate.update_service_list');
	// 客服反馈
	Route::get('operate/service_list', 'OperateController@serviceList')->name('operate.service_list');
	// 注册赠送配置
	Route::get('operate/register_setting', 'OperateController@registerSetting')->name('operate.register_setting');
	// 举报有奖配置
	Route::get('operate/report_setting', 'OperateController@reportSetting')->name('operate.report_setting');
	// 后台登录日志
	Route::get('operate/login_logs', 'OperateController@loginLogs')->name('operate.login_logs');
	// 后台操作日志
	Route::get('operate/operate_logs', 'OperateController@operateLogs')->name('operate.operate_logs');
	// 充值订单
	Route::get('operate/charge_orders', 'OperateController@chargeOrders')->name('operate.charge_orders');


	// 玩家提现配置
	Route::put('operate/exchange_setting', 'OperateController@updateExchangeSetting')->name('operate.update_exchange_setting');
	// 注册赠送修改
	Route::put('operate/register_setting', 'OperateController@updateRegistSetting')->name('operate.update_register_setting');
	// 举报有奖修改
	Route::put('operate/report_setting', 'OperateController@updateReportSetting')->name('operate.update_report_setting');
	// 游戏房间设置
	Route::get('operate/game_room_setting', 'OperateController@gameRoomSetting')->name('operate.game_room_setting');
	// 修改游戏房间设置
	Route::put('operate/game_room_setting', 'OperateController@updateGameRoomSetting')->name('operate.update_game_room_setting');
	// 修改游戏房间 停服 开服
	Route::put('operate/game_room_status', 'OperateController@updateGameRoomStatus')->name('operate.update_game_room_status');
	// 机器人配置
	Route::get('operate/robot_setting', 'OperateController@robotSetting')->name('operate.robot_setting');
	// 修改机器人配置
	Route::put('operate/robot_setting', 'OperateController@updateRobotSetting')->name('operate.update_robot_setting');
	// 修改携带游戏币
	Route::put('operate/gold', 'OperateController@updateGold')->name('operate.update_gold');
	// 修改银行余额
	Route::put('operate/bank_gold', 'OperateController@updateBankGold')->name('operate.update_bank_gold');
	// 修改账号信息 （玩家账号）
	Route::put('operate/account', 'OperateController@updateAccount')->name('operate.update_account');
	// 封锁账号
	Route::put('operate/account_state', 'OperateController@updateAccountState')->name('operate.update_account_state');
	// 新增滚动公告：依次传入1标题，2内容，3开始时间，4结束时间
	Route::post('operate/message', 'OperateController@insertMessage')->name('operate.insert_message');
	// 新增系统公告：依次传入1标题，2内容，3开始时间，4结束时间 --
	Route::post('operate/sys_message', 'OperateController@insertSysMessage')->name('operate.insert_sys_message');
	// 删除公告
	Route::delete('operate/message', 'OperateController@deleteMessage')->name('operate.delete_message');
	// 查询月入百万配置
	Route::get('operate/yrbw_setting', 'OperateController@YRBWSetting')->name('operate.yrbw_setting');
	// 修改月入百万配置
	Route::put('operate/yrbw_setting', 'OperateController@updateYRBWSetting')->name('operate.update_yrbw_setting');
	// 增加月入百万配置:依次传入，1类型（eg:qq,wx），2微信号和qq号（eg:wxagent，15687963541）//
	Route::post('operate/yrbw_setting_respond', 'OperateController@insertYRBWSettingRespond')->name('operate.insert_yrbw_setting_respond');
	// 删除月入百万配置
	Route::delete('operate/yrbw_setting_respond', 'OperateController@deleteYRBWSettingRespond')->name('operate.delete_yrbw_setting_respond');

	// 获取后台账号列表
	Route::get('operate/gm_account_list', 'OperateController@gmAccountList')->name('operate.gm_account_list');


	// 增加后台账号：依次传入1.账号，2.昵称，3.密码
	Route::post('operate/gm_account', 'OperateController@insertGMAccount')->name('operate.insert_gm_account');
	// 修改后台账号
	Route::put('operate/gm_account', 'OperateController@updateGMAccount')->name('operate.update_gm_account');
	// 删除后台账号
	Route::delete('operate/gm_account', 'OperateController@deleteGMAccount')->name('operate.delete_gm_account');
	// 玩家提现审核
	Route::put('operate/exchange', 'OperateController@updateExchange')->name('operate.update_exchange');


	// 支付 ：
	// 充值配置
	Route::get('operate/exchange_channel', 'OperateController@exchangeChannel')->name('operate.exchange_channel');
	// 充值配置修改
	Route::put('operate/exchange_channel', 'OperateController@updateExchangeChannel')->name('operate.update_exchange_channel');
	// 提现配置
	Route::get('operate/recharge_channel', 'OperateController@rechargeChannel')->name('operate.recharge_channel');
	// 提现配置修改
	Route::put('operate/recharge_channel', 'OperateController@updateRechargeChannel')->name('operate.update_recharge_channel');


	// 代理推广首页
	Route::get('agent/promo_statics', 'AgentController@promoStatics')->name('agent.promo_statics');
	// 代理管理
	Route::get('agent/agent_list', 'AgentController@agentList')->name('agent.agent_list');
	// 代理推广统计
	Route::get('agent/promo_list', 'AgentController@promoList')->name('agent.promo_list');
	// 代理提现订单
	Route::get('agent/settle_record', 'AgentController@settleRecord')->name('agent.settle_record');
	// 代理推广二维码
	Route::get('agent/promo_url', 'AgentController@promoURL')->name('agent.promo_url');
	// 代理推广余额
	Route::get('agent/account', 'AgentController@account')->name('agent.account');
	// 代理转账记录
	Route::get('agent/transfer_record', 'AgentController@transferRecord')->name('agent.transfer_record');
	// 代理商人绑定信息
	Route::get('agent/merchant_setting', 'AgentController@merchantSetting')->name('agent.merchant_setting');
	// 代理系统公告
	Route::get('agent/agent_message', 'AgentController@agentMessage')->name('agent.agent_message');
	// 代理上分记录
	Route::get('agent/buy_record', 'AgentController@buyRecord')->name('agent.buy_record');
	// 代理收入统计
	Route::get('agent/income_list', 'AgentController@incomeList')->name('agent.income_list');
	// 代理收入明细
	Route::get('agent/income_detail_list', 'AgentController@incomeDetailList')->name('agent.income_detail_list');
	// 代理转账
	Route::post('agent/transfer', 'AgentController@insertTransfer')->name('agent.insert_transfer');
	// 代理提现审核
	Route::put('agent/settlement', 'AgentController@updateSettlement')->name('agent.update_settlement');
	// 代理申请提现 (提交结算)
	Route::post('agent/settlement', 'AgentController@insertSettlement')->name('agent.insert_settlement');
	// 代理增加商人绑定,依次传入，1类型（eg:qq,wx），2微信号和qq号（eg:wxagent，15687963541）//
	Route::post('agent/merchant_setting', 'AgentController@insertMerchantSetting')->name('agent.insert_merchant_setting');
	// 代理修改商人绑定
	Route::put('agent/merchant_setting', 'AgentController@updateMerchantSetting')->name('agent.update_merchant_setting');
	// 代理删除商人绑定
	Route::delete('agent/merchant_setting', 'AgentController@deleteMerchantSetting')->name('agent.delete_merchant_setting');
	// 增加代理公告依次传入，1标题，2内容，3开始时间，4结束时间，5排序（排序数字越大越靠前，同样排序的最新的靠前显示）
	Route::post('agent/agent_message', 'AgentController@insertAgentMessage')->name('agent.insert_agent_message');
	// 修改代理公告
	Route::put('agent/agent_message', 'AgentController@updateAgentMessage')->name('agent.update_agent_message');
	// 删除代理公告
	Route::delete('agent/agent_message', 'AgentController@deleteAgentMessage')->name('agent.delete_agent_message');
	// 增加代理:依次传入1.账号，2.昵称，3.密码，4.返点比例
	Route::post('agent/agent_account', 'AgentController@insertAgentAccount')->name('agent.insert_agent_account');
	// 修改代理账号
	Route::put('agent/agent_account', 'AgentController@updateAgentAccount')->name('agent.update_agent_account');
	// 更改结算账户 结算密码
	Route::put('agent/agent_account_settle', 'AgentController@updateAgentAccountSettle')->name('agent.update_agent_account_settle');
	// 删除代理账号
	Route::delete('agent/agent_account', 'AgentController@deleteAgentAccount')->name('agent.delete_agent_account');

	// 金额配置查询
	Route::get('operate/recharge_setting', 'OperateController@rechargeSetting')->name('agent.recharge_setting');
	// 修改金额配置
	Route::put('operate/recharge_setting', 'OperateController@updateRechargeSetting')->name('agent.update_recharge_setting');

	/**** 数据报表 ****/
	// 统计
	Route::get('operate/potato_robot_statics', 'OperateController@potatoRobotStatics')->name('agent.potato_robot_statics');
	// 用户输赢
	Route::get('operate/user_win_total', 'OperateController@userWinTotal')->name('agent.user_win_total');
	// 用户充值
	Route::get('operate/user_charge_total', 'OperateController@userChargeTotal')->name('agent.user_charge_total');

	// 税收统计
	Route::get('operate/tax_statics', 'OperateController@taxStatics')->name('operate.tax_statics');
	// 在线统计
	Route::get('operate/ol_statics', 'OperateController@onlineStatics')->name('operate.ol_statics');
	// 提现统计
	Route::get('operate/withdraw_statics', 'OperateController@withdrawStatics')->name('operate.withdraw_statics');
	// 充值统计
	Route::get('operate/charge_statics', 'OperateController@chargeStatics')->name('operate.charge_statics');

	// api调用日志列表
	Route::get('agent/api_log_list', 'AgentController@apiLogList')->name('agent.api_log_list');

	// GM增加道具接口
	Route::put('agent/ag_my_item', 'AgentController@agUpdateMyItem')->name('agent.ag_update_my_item');
	// 用户道具查询
	Route::get('agent/ag_my_item', 'AgentController@agGetMyItem')->name('agent.ag_get_my_item');
	// 用户道具修改日志
	Route::get('agent/ag_item_record_list', 'AgentController@agGetItemRecordList')->name('agent.ag_get_item_record_list');
    // 商城列表
	Route::get('agent/ag_shop_list', 'AgentController@agGetShopList')->name('agent.ag_get_shop_list');
	// 商城修改
	Route::put('agent/ag_shop_list', 'AgentController@agUpdateShopList')->name('agent.ag_update_shop_list');
	// 商城增加
	Route::post('agent/ag_shop_list', 'AgentController@agInsertShopList')->name('agent.ag_insert_shop_list');
	// 商城删除
	Route::delete('agent/ag_shop_list', 'AgentController@agDeleteShopList')->name('agent.ag_delete_shop_list');

	// 代理创建约战房间
	Route::get('agent/ag_game_custom_setting', 'AgentController@agGetGameCustomSetting')->name('agent.ag_game_custom_setting');
	// 获取代理已经创建的约战房间
	Route::get('agent/ag_game_info', 'AgentController@agGetGameInfo')->name('agent.ag_game_info');

	// 获取约战房间设置参数
	Route::post('agent/ag_game', 'AgentController@agInsertGame')->name('agent.ag_insert_game');
	// 代理删除约战房间
	Route::delete('agent/ag_game', 'AgentController@agDeleteGame')->name('agent.ag_delete_game');

	// 获取推广设置
	Route::get('agent/ag_promo_setting', 'AgentController@agGetPromoSetting')->name('agent.ag_get_promo_setting');
    // 更新推广设置
	Route::put('agent/ag_promo_setting', 'AgentController@agUpdatePromoSetting')->name('agent.ag_update_promo_setting');
    // 游戏输赢统计
	Route::get('agent/ag_get_game_win_total', 'AgentController@agGetGameWinTotal')->name('agent.ag_get_game_win_total');

    // 获取奖励配置
    Route::get('agent/ag_award_setting', 'AgentController@agGetAwardSetting')->name('agent.ag_get_award_setting');
    // 更新奖励配置
    Route::put('agent/ag_award_setting', 'AgentController@agUpdateAwardSetting')->name('agent.ag_update_award_setting');

    //玩家曲线
    Route::get('agent/ag_gold_detail', 'AgentController@agGoldDetail')->name('agent.ag_gold_detail');
    //游戏币来源
    Route::get('agent/ag_gold_source', 'AgentController@agGoldSource')->name('agent.ag_gold_source');

    // 获取红包设置
    Route::get('agent/ag_red_packet_setting', 'AgentController@agGetRedPacketSetting')->name('agent.ag_get_red_packet_setting');
    // 更新红包设置
    Route::put('agent/ag_red_packet_setting', 'AgentController@agUpdateRedPacketSetting')->name('agent.ag_update_red_packet_setting');
    //发红包
    Route::post('agent/ag_red_packet', 'AgentController@agInsertRedPacket')->name('agent.ag_insert_red_packet');
    //发用户邮件
    Route::post('agent/ag_user_message', 'AgentController@agInsertUserMessage')->name('agent.ag_insert_user_message');

    // 获取红包设置
    Route::get('agent/ag_ranklist_setting', 'AgentController@agGetRanklistSetting')->name('agent.ag_get_ranklist_setting');
    // 更新红包设置
    Route::put('agent/ag_ranklist_setting', 'AgentController@agUpdateRanklistSetting')->name('agent.ag_update_ranklist_setting');
    // 查询红包领取情况
    Route::get('agent/ag_red_packet_list', 'AgentController@agGetRedPacketList')->name('agent.ag_get_red_packet_list');
    // 查询系统红包领取情况
    Route::get('agent/ag_sys_red_packet', 'AgentController@agGetSysRedPacket')->name('agent.ag_sys_red_packet');
    // 查询排行玩家
    Route::get('agent/ag_rank_user_list', 'AgentController@agGetRankUserlist')->name('agent.ag_rank_user_list');

    //查询过滤
    Route::get('agent/ag_filter', 'AgentController@agGetFilter')->name('agent.ag_get_filter');
    //新增过滤字
    Route::post('agent/ag_filter', 'AgentController@agInsertFilter')->name('agent.ag_insert_filter');
    //修改过滤字
    Route::put('agent/ag_filter', 'AgentController@agUpdateFilter')->name('agent.ag_update_filter');

    //查询忽略帐号
    Route::get('agent/ag_LimitUser', 'AgentController@agGetLimitUser')->name('agent.ag_get_LimitUser');
    //新增忽略帐号
    Route::post('agent/ag_LimitUser', 'AgentController@agInsertLimitUser')->name('agent.ag_insert_LimitUser');
    //修改忽略帐号
    Route::put('agent/ag_LimitUser', 'AgentController@agUpdateLimitUser')->name('agent.ag_update_LimitUser');

    //下分记录查询
    Route::get('agent/agent_transfer_record', 'AgentController@agGetAgentTransferRecord')->name('agent.ag_get_agent_transfer_record');
    //收分记录查询
    Route::get('agent/player_transfer_record', 'AgentController@agGetPlayerTransferRecord')->name('agent.ag_get_player_transfer_record');
    //收分记录审核
    Route::put('agent/player_transfer_record', 'AgentController@agUpdatePlayerTransferRecord')->name('agent.ag_update_player_transfer_record');

    //查询分包管理
    Route::get('agent/agent_packages', 'AgentController@agGetAgentPackages')->name('agent.ag_get_agent_packages');
    //修改分包管理
    Route::put('agent/agent_packages', 'AgentController@agUpdateAgentPackages')->name('agent.ag_update_agent_packages');
    //  全民推广统计查询
    Route::get('agent/wj_static', 'AgentController@agGetWJStatic')->name('agent.ag_get_wj_static');
    // 玩家游戏轨迹
    Route::get('agent/player_footmark', 'AgentController@agGetPlayerFootmark')->name('agent.ag_get_player_footmark');
    //新手分析
    Route::get('agent/new_player_list', 'AgentController@agGetNewPlayerList')->name('agent.ag_get_new_player_list');

    // api订单列表
    Route::get('agent/api_record', 'AgentController@apiGetRecordList')->name('agent.api_get_record_list');
    // api账号列表
    Route::get('agent/api_account', 'AgentController@apiGetAccount')->name('agent.api_get_account');
    // api账号修改
    Route::put('agent/api_account', 'AgentController@apiUpdateAccount')->name('agent.api_update_account');
    // api账号增加
    Route::post('agent/api_account', 'AgentController@apiInsertAccount')->name('agent.api_insert_account');

});

/* 后台请求外部接口 */
Route::post('admin/request_url', 'Api\RequestUrlController@toRequest' )->name('admin.api_to_request');

Route::namespace('Api')->middleware(['checkTokenIp','checkNlvPermission'])->group(function() {

	// 获取风控配置
	Route::get('admin/risks', 'RiskManagementController@index')->name('admin.risk_management');
	// 修改风控配置
	Route::put('admin/risks', 'RiskManagementController@update')->name('admin.risk_management_update');
	// 查询风控记录
	Route::get('admin/risk_record', 'RiskRecordController@index')->name('admin.risk_record');
	// 查询未处理风控记录数
	Route::get('admin/risk_record_no', 'RiskRecordController@noReadRiskRecord')->name('admin.no_read_risk_record');
	// 修改风控记录已读状态
	Route::put('admin/risk_record', 'RiskRecordController@update')->name('admin.risk_record_update');
    // 修改全部未读的风控记录为已读状态
    Route::put('admin/risk_records', 'RiskRecordController@updates')->name('admin.risk_records_update');


    //重置谷歌验证码
	Route::delete('admin/reset_google', 'BindSecretController@resetSecret')->name('admin.reset_google_captcha');

	//查询聊天记录
	Route::get('player_summary', 'ChatController@playerSummaries')->name('chat.player_summary');
    // 查询所有提现申请记录
    Route::get('all-withdrawal-list', 'WithdrawalController@getAllList')->name('withdrawal.all-list');
    // 获取客服出款统计列表
    Route::get('withdrawal-stat-list', 'WithdrawalController@getStatList')->name('withdrawal.stat-list');

});

// Route::middleware('auth:api')->get('/user', function(Request $request) {
// 	return $request->user();
// });

// 获取返回图片验证码
Route::get('/captcha/{uuid}', function(Request $request, $uuid) {
	$builder = new  CaptchaBuilder();
	$builder->setIgnoreAllEffects(true)->build(100, 30);
	$code = $builder->getPhrase();
	Redis::setex($uuid, 300, $code); // 300s 存活时间
	return response($builder->output(280))->header('Content-type', 'image/jpeg');
});
Route::middleware('cros')->namespace('Api')->group(function (){
    //保存用户反馈意见建议
    Route::post('/suggestion', 'SuggestionController@store');

});

//查询用户反馈
Route::get('/suggestion', 'Api\SuggestionController@index');

// 银行卡提现申请，对外接口
Route::post('withdraw', 'Api\WithdrawalController@withdraw')->name('Withdrawal.withdraw');