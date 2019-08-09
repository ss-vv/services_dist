<?php
// 外部系统请求urls
return [
	// 后台登录接口
	'AGGMLogin'                  => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGMLogin?proto=',
	// 运营首页
	'AGGetPlatformStatics'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetPlatformStatics?proto=',
	// 用户查询接口
	'AGGetUserList'              => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetUserList?proto=',
	// 金币日志
	'AGGetGoldList'              => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetGoldList?proto=',
	// 游戏查询
	'AGGetGameList'              => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetGameList?proto=',
	// 银行金币调整记录
	'AGGetGoldRecordList'        => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetGoldRecordList?proto=',
	// 金币调整记录
	'AGGetBankGoldRecordList'        => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetBankGoldRecordList?proto=',
	// 账号封禁记录
	'AGGetBanRecordList'         => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetBanRecordList?proto=',
	// 游戏公告
	'AGGetMessageList'           => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetMessageList?proto=',
	// 游戏税收
	'AGGetTaxList'               => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetTaxList?proto=',
	// 在线用户
	'AGGetOLUserList'            => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetOLUserList?proto=',
	// 举报记录
	'AGGetReportList'            => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetReportList?proto=',
	// 提现订单
	'AGGetExchangeList'          => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetExchangeList?proto=',
	// 提现设置
	'AGGetExchangeSetting'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetExchangeSetting?proto=',
	// 客服反馈列表
	'AGGetServiceList'           => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetServiceList?proto=',
	// 客服反馈
	'AGUpdateServiceList'        => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateServiceList?proto=',
	// 注册赠送配置
	'AGGetRegistSetting'         => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetRegistSetting?proto=',
	// 举报有奖配置
	'AGGetReportSetting'         => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetReportSetting?proto=',
	// 后台登录日志
	'AGGetLoginList'             => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetLoginList?proto=',
	// 后台操作日志
	'AGGetOpreateList'           => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetOpreateList?proto=',
	// 充值订单
	'AGGetChargeList'            => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetChargeList?proto=',
	// 玩家提现配置
	'AGUpdateExchangeSetting'    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateExchangeSetting?proto=',
	// 注册赠送修改
	'AGUpdateRegistSetting'      => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateRegistSetting?proto=',
	// 举报有奖修改
	'AGUpdateReportSetting'      => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateReportSetting?proto=',
	// 游戏房间设置
	'AGGetGameRoomSetting'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetGameRoomSetting?proto=',
	// 修改游戏房间设置
	'AGUpdateGameRoomSetting'    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateGameRoomSetting?proto=',
	// 修改携带游戏币
	'AGUpdateGold'               => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateGold?proto=',
	// 修改银行余额
	'AGUpdateBankGold'           => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateBankGold?proto=',
	// 修改账号信息
	'AGUpdateAccount'            => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateAccount?proto=',
	// 新增滚动公告：依次传入1标题，2内容，3开始时间，4结束时间
	'AGInsertMessage'            => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertMessage?proto=',
	// 新增系统公告：依次传入1标题，2内容，3开始时间，4结束时间
	'AGInsertSysMessage'         => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertSysMessage?proto=',
	// 修改游戏公告
	'AGUpdateMessageList'        => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateMessageList?proto=',
	// 删除公告
	'AGDeleteMessage'            => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGDeleteMessage?proto=',
	// 查询月入百万配置
	'AGGetYRBWSetting'           => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetYRBWSetting?proto=',
	// 修改月入百万配置
	'AGUpdateYRBWSetting'        => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateYRBWSetting?proto=',
	// 增加月入百万配置:依次传入，1类型（eg:qq,wx），2微信号和qq号（eg:wxagent，15687963541）//
	'AGInsertYRBWSettingRespond' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertYRBWSettingRespond?proto=',
	// 删除月入百万配置
	'AGDeleteYRBWSettingRespond' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGDeleteYRBWSettingRespond?proto=',
	// 获取后台账号列表
	'AGGetGMAccountList'         => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetGMAccountList?proto=',
	// 增加后台账号：依次传入1.账号，2.昵称，3.密码
	'AGInsertGMAccount'          => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertGMAccount?proto=',
	// 修改后台账号
	'AGUpdateGMAccount'          => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateGMAccount?proto=',
	// 删除后台账号
	'AGDeleteGMAccount'          => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGDeleteGMAccount?proto=',
	// 玩家提现审核
	'AGUpdateExchange'           => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateExchange?proto=',
	// 支付 ：
	// 充值配置
	'AGGetExchangeChannel'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetExchangeChannel?proto=',
	// 充值配置修改
	'AGUpdateExchangeChannel'    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateExchangeChannel?proto=',
	// 提现配置
	'AGGetRechargeChannel'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetRechargeChannel?proto=',
	// 提现配置修改
	'AGUpdateRechargeChannel'    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateRechargeChannel?proto=',

	// 代理登录接口
	'AGLogin'                    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGLogin?proto=',
	// 代理推广首页
	'AGGetPromoStatics'          => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetPromoStatics?proto=',
	// 代理管理
	'AGGetAgentList'             => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetAgentList?proto=',
	// 代理推广统计
	'AGGetPromoList'             => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetPromoList?proto=',
	// 代理提现订单
	'AGGetSettleRecord'          => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetSettleRecord?proto=',
	// 代理推广二维码
	'AGGetPromoURL'              => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetPromoURL?proto=',
	// 代理推广余额
	'AGGetAccount'               => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetAccount?proto=',
	// 代理转账记录
	'AGGetTransferRecord'        => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetTransferRecord?proto=',
	// 代理商人绑定信息
	'AGGetMerchantSetting'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetMerchantSetting?proto=',
	// 代理系统公告
	'AGGetAgentMessage'          => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetAgentMessage?proto=',
	// 代理上分记录
	'AGGetBuyRecord'             => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetBuyRecord?proto=',
	// 代理收入统计
	'AGGetIncomeList'            => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetIncomeList?proto=',
	// 代理收入明细
	'AGGetIncomeDetailList'      => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetIncomeDetailList?proto=',
	// 代理转账
	'AGInsertTransfer'           => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertTransfer?proto=',
	// 代理提现审核
	'AGUpdateSettlement'         => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateSettlement?proto=',
	// 代理申请提现
	'AGInsertSettlement'         => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertSettlement?proto=',
	// 代理增加商人绑定,依次传入，1类型（eg:qq,wx），2微信号和qq号（eg:wxagent，15687963541）//
	'AGInsertMerchantSetting'    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertMerchantSetting?proto=',
	// 代理修改商人绑定
	'AGUpdateMerchantSetting'    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateMerchantSetting?proto=',
	// 代理删除商人绑定
	'AGDeleteMerchantSetting'    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGDeleteMerchantSetting?proto=',
	// 增加代理公告依次传入，1标题，2内容，3开始时间，4结束时间，5排序（排序数字越大越靠前，同样排序的最新的靠前显示）
	'AGInsertAgentMessage'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertAgentMessage?proto=',
	// 修改代理公告
	'AGUpdateAgentMessage'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateAgentMessage?proto=',
	// 删除代理公告
	'AGDeleteAgentMessage'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGDeleteAgentMessage?proto=',
	// 增加代理:依次传入1.账号，2.昵称，3.密码，4.返点比例
	'AGInsertAgentAccount'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertAgentAccount?proto=',
	// 修改代理账号
	'AGUpdateAgentAccount'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateAgentAccount?proto=',
	// 删除代理账号
	'AGDeleteAgentAccount'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGDeleteAgentAccount?proto=',
	// 金额配置查询
	'AGGetRechargeSetting'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetRechargeSetting?proto=',
	// 修改金额配置
	'AGUpdateRechargeSetting'    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateRechargeSetting?proto=',
	// 统计
	'AGGetPotatoRobotStatics'    => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetPotatoRobotStatics?proto=',
	// 用户输赢
	'AGGetUserWinTotal'          => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetUserWinTotal?proto=',
	// 用户充值
	'AGGetUserChargeTotal'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetUserChargeTotal?proto=',
	// 运营登陆
	'AGYYLogin'                  => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGYYLogin?proto=',
	// 客服登陆
	'AGKFLogin'                  => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGKFLogin?proto=',

	// 税收统计
	'AGGetTaxStatics'            => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetTaxStatics?proto=',
	// 在线统计
	'AGGetOLStatics'             => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetOLStatics?proto=',
	// 提现统计
	'AGGetWithdrawStatics'       => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetWithdrawStatics?proto=',
	// 充值统计
	'AGGetChargeStatics'         => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetChargeStatics?proto=',

	// 机器人配置
	'AGGetRobotSetting'         => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetRobotSetting?proto=',
    // 机器人配置修改
	'AGUpdateRobotSetting'      => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateRobotSetting?proto=',

	// 玩家登陆
	'AGWJLogin'      => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGWJLogin?proto=',
	// 玩家收入详情
	'AGWJIncomeDetail' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGWJIncomeDetail?proto=',
    // 发送验证码
	'SendSMS' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/SendSMS?AccountName=',
	// api调用日志
	'AGGetApiLogList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetApiLogList?proto=',

	// 用户道具查询
	'AGGetMyItem' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetMyItem?proto=',
	// GM增加道具接口
	'AGUpdateMyItem' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateMyItem?proto=',
	// 用户道具修改日志
	'AGGetItemRecordList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetItemRecordList?proto=',
	// 商城列表
	'AGGetShopList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetShopList?proto=',
	// 商城修改
	'AGUpdateShopList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateShopList?proto=',
	// 商城增加
	'AGInsertShopList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertShopList?proto=',
	// 商城删除
	'AGDeleteShopList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGDeleteShopList?proto=',
	// 代理创建约战房间
	'AGInsertGame' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertGame?proto=',
	// 代理删除约战房间
	'AGDeleteGame' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGDeleteGame?proto=',
	// 获取约战房间设置参数
	'AGGetGameCustomSetting' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetGameCustomSetting?proto=',

	// 请求玩家列表
	'AGWJGetUserList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGWJGetUserList?proto=',
    // 获取推广统计
	'AGWJGetPromoList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGWJGetPromoList?proto=',
    // 返回推广链接
	'AGWJGetPromoURL' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGWJGetPromoURL?proto=',

    // 获取推广设置
	'AGGetPromoSetting' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetPromoSetting?proto=',
    // 更新推广设置
	'AGUpdatePromoSetting' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdatePromoSetting?proto=',
    // 游戏输赢统计
	'AGGetGameWinTotal' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetGameWinTotal?proto=',

    // 获取奖励配置
    'AGGetAwardSetting' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetAwardSetting?proto=',
    // 更新奖励配置
    'AGUpdateAwardSetting' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateAwardSetting?proto=',
     //玩家曲线//
    'AGGoldDetail' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGoldDetail?proto=',
     //玩家曲线//
    'AGGoldSource' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGoldSource?proto=',

     //查询红包设置
    'AGGetRedPacketSetting' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetRedPacketSetting?proto=',
     //修改红包设置
    'AGUpdateRedPacketSetting' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateRedPacketSetting?proto=',
     //发红包
    'AGInsertRedPacket' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertRedPacket?proto=',
     //发用户邮件
    'AGInsertUserMessage' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertUserMessage?proto=',
     //查询排行奖励
    'AGGetRanklistSetting' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetRanklistSetting?proto=',
     //修改排行奖励
    'AGUpdateRanklistSetting' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateRanklistSetting?proto=',
    //查询红包领取情况
    'AGGetRedPacketList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetRedPacketList?proto=',
    //查询系统红包领取情况
    'AGGetSysRedPacket' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetSysRedPacket?proto=',
    ///查询排行玩家
    'AGGetRankUserlist' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetRankUserlist?proto=',

    //查询过滤
    'AGGetFilter' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetFilter?proto=',
    //修改过滤字
    'AGUpdateFilter' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateFilter?proto=',
    //新增过滤字
    'AGInsertFilter' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertFilter?proto=',
    //查询忽略帐号
    'AGGetLimitUser' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetLimitUser?proto=',
    //修改忽略帐号
    'AGUpdateLimitUser' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateLimitUser?proto=',
    //新增忽略帐号
    'AGInsertLimitUser' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGInsertLimitUser?proto=',

    //下分记录查询
    'AGGetAgentTransferRecord' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetAgentTransferRecord?proto=',
    //收分纪录查询
    'AGGetPlayerTransferRecord' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetPlayerTransferRecord?proto=',
    //收分记录审核
    'AGUpdatePlayerTransferRecord' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdatePlayerTransferRecord?proto=',

    //分包管理
    'AGGetAgentPackages' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetAgentPackages?proto=',
    //修改分包管理
    'AGUpdateAgentPackages' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGUpdateAgentPackages?proto=',

    //全民推广统计查询
    'AGGetWJStatic' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetWJStatic?proto=',
    //玩家游戏轨迹
    'AGGetPlayerFootmark' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetPlayerFootmark?proto=',
     //新手分析
    'AGGetNewPlayerList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/AGGetNewPlayerList?proto=',

    //订单查询
    'APIGetRecordList' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/APIGetRecordList?proto=',
    //api账号查询
    'APIGetAccount' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/APIGetAccount?proto=',
    //api账号新增
    'APIInsertAccount' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/APIInsertAccount?proto=',
    //api账号编辑
    'APIUpdateAccount' => env('OUT_BASE_URL', 'http://65.52.175.24:30000') . '/APIUpdateAccount?proto=',

];