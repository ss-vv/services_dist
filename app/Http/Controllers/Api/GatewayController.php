<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\ChatMessageResource;
use App\Models\ChatMessage;
use App\Models\PlayerSummary;
use App\Models\ServiceTime;
use GatewayClient\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class GatewayController extends ApiController
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * socket客户端client_id与玩家用户id绑定
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function playerBind(Request $request)
	{
		$this->validateRequest($request, [
			'client_id' => 'required',
			'uid'       => 'required',
		]);
		$client_id = request('client_id');
		// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值(ip不能是0.0.0.0)
		Gateway::$registerAddress = env('GETWAY_REGISTER_ADDRESS', '127.0.0.1:1238');
		$uid                      = request('uid');
		// client_id与uid绑定
		Gateway::bindUid($client_id, $uid);
		return $this->apiResponse->json(['success' => true]);
	}

	/**
	 * socket客户端client_id与客服用户id绑定
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function serviceBind(Request $request)
	{

		$this->validateRequest($request, [
			'client_id' => 'required'
		]);
		$client_id = $request->input('client_id');
		// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值(ip不能是0.0.0.0)
		Gateway::$registerAddress = env('GETWAY_REGISTER_ADDRESS', '127.0.0.1:1238');
		$token             = request()->header('Authorization');
		$uid         = Redis::get($token . '_service_id');//客服id
		if(!$uid){
			$this->apiResponse->errorUnauthorized();
		}
		// client_id与uid绑定
		Gateway::bindUid($client_id, $uid);

		Redis::setex('ws:service_client_id:' . $client_id, 36000, $uid); // 存放客服id 10个小时失效
		Redis::setex('ws:service_id:' . $uid, 36000, $uid); // 存放客服id 10个小时失效


		return $this->apiResponse->json(['success' => true]);
	}

	// 玩家发送信息
	public function sendMessage(Request $request, ChatMessage $chatMessage)
	{

		$this->validateRequest($request, [
			'player_id' => 'required',
			'msg'       => 'required|string|max:200',
		]);
		$inputs                   = $request->only(['player_id', 'msg']);
		Gateway::$registerAddress = env('GETWAY_REGISTER_ADDRESS', '127.0.0.1:1238');
		$todayStart               = Carbon::today()->toDateTimeString();
		$now                      = Carbon::now()->toDateTimeString();
		// '检查是否有可以服务的客服';

		// 当前上班的客服ids
		$onServiceIds = ServiceTime::where('status', 1)->where('on_time', '>=', $todayStart)->where('on_time', '<=', $now)->pluck('service_id');
		if (count($onServiceIds)) {
			// 查询用户是否是之前发送的上一条是否存在
			$lastChatMessage = ChatMessage::where('player_id', $inputs['player_id'])->latest()->first();
			// 如果存在 且上一条的客服id 在上班 则 发给他
			if ($lastChatMessage && $lastChatMessage->service_id &&  $onServiceIds->contains($lastChatMessage->service_id)) {
				$firstCanServiceId = $lastChatMessage->service_id;
			}
			else {// 上一条记录不存在 或者 上一条记录的客服id当前不是上班状态

				// 查询已经分配玩家的客服id 按照分配的玩家数量从小到大排序
				// $alreadyServices = DB::select('SELECT t.service_id, count(t.player_id )  AS p_counts FROM
                // (SELECT DISTINCT player_id, service_id FROM chat_messages WHERE service_id IN ( :service_ids )
                // AND created_at > :startTime AND created_at <= :endTime ) t GROUP BY t.service_id ORDER BY p_counts ASC',
				// 	['startTime' => $todayStart, 'endTime' => $now, 'service_ids' => $onServiceIds->implode(',')]);
				$query = DB::table('chat_messages')->select( 'player_id', 'service_id')->whereIn('service_id',$onServiceIds)->where('created_at','>',$todayStart)->where('created_at','<=',$now)
					->distinct();
				$alreadyServices= DB::table(DB::raw("({$query->toSql()}) as t"))->mergeBindings($query)->select(DB::raw(' t.service_id, count(t.player_id) as p_counts'))
					->groupBy('t.service_id')
					->orderBy('p_counts','ASC')
					->get();
\Log::info($alreadyServices);
				// 如果存在已经分配玩家的客服
				if ($alreadyServices) {
					$serviceIds = []; //存放上班客服中已经和玩家有聊天的客服ids
					foreach ($alreadyServices as $service) {
						$serviceIds[] = $service->service_id;
					}
					$diffFirst = $onServiceIds->diff($serviceIds)->first();

					// 是否存在未分配的客服
					if ($diffFirst) {
						$firstCanServiceId = $diffFirst;
					}
					else {
						// 玩家最后一条记录ids
						$ids = ChatMessage::select(DB::raw('max(id) AS max_id , player_id'))->groupBy('player_id')->pluck('max_id');
						// 客服未处理数量 按数量从小到大排序
						$serviceNotHandleNums = ChatMessage::select(DB::raw('count(*) as not_handle_num, service_id'))
							->whereIn('id', $ids)->where('u_type', 1)->where('status', 1)
							->groupBy('service_id')
							->orderBy('not_handle_num','ASC')
							->get();
						if($serviceNotHandleNums && count($serviceNotHandleNums)){
							$firstCanServiceId = $serviceNotHandleNums[0]['service_id'];
						}else{
							$firstCanServiceId = $alreadyServices[0]->service_id;
						}

						// $firstCanServiceId = $alreadyServices[0]->service_id;
					}
				}
				else {
					// 上班的客服中所有客服都没和玩家聊天 则取上班客服ids中的第一个
					$firstCanServiceId = $onServiceIds[0];
				}
			}
			$inputs['service_id'] = $firstCanServiceId;
			$inputs['created_at'] = Carbon::now()->toDateTimeString();
			$inputs['u_type']     = ChatMessage::USER_SEND;
			// 推送消息给客服
			Gateway::sendToUid($firstCanServiceId, json_encode($inputs));
			$chatMessage->fill([
				'player_id'  => $inputs['player_id'],
				'service_id' => $firstCanServiceId,
				'status'     => 1,
				'u_type'     => ChatMessage::USER_SEND, //1: 玩家发送 2：客服回复
				'msg'        => $inputs['msg'],
			])->save();

		}
		else {
			// 保存消息信息 等待定时任务去处理
			$chatMessage->fill([
				'player_id'  => $inputs['player_id'],
				'service_id' => '0', // 接收人为0 表示没有客服收到信息
				'status'     => 0,   // 接收状态0 未接收 表示还未发送给客服
				'u_type'     => ChatMessage::USER_SEND,   //1: 玩家发送 2：客服回复
				'msg'        => $inputs['msg'],
			])->save();
		}
		$this->updatePlayerSummary($chatMessage);
		return $this->apiResponse->item($chatMessage, ChatMessageResource::class);
		// 向任意uid的网站页面发送数据
		// Gateway::sendToUid($uid, json_encode(['message'=>$message]));
		// 向任意群组的网站页面发送数据
		// Gateway::sendToGroup($group, $message);
	}

	// 客服回复信息
	public function replayMessage(Request $request, ChatMessage $chatMessage)
	{
		$this->validateRequest($request, [
			'player_id'  => 'required',
			'msg'        => 'required|string|max:200',
		]);
		$inputs                   = $request->only(['player_id', 'msg']);
		Gateway::$registerAddress = env('GETWAY_REGISTER_ADDRESS', '127.0.0.1:1238');
		$token             = request()->header('Authorization');
		$serviceId         = Redis::get($token . '_service_id');//客服id
		if(!$serviceId){
			$this->apiResponse->errorUnauthorized();
		}
		$inputs['created_at'] = Carbon::now()->toDateTimeString();
		$inputs['u_type']     = ChatMessage::SERVICE_REPLAY;
		$inputs['service_id']     = $serviceId;
		// 向$playerId的网站页面发送数据
		Gateway::sendToUid($inputs['player_id'], json_encode($inputs));
		$chatMessage->fill([
			'player_id'  => $inputs['player_id'],
			'service_id' => $inputs['service_id'],
			'status'     => 1,
			'u_type'     => ChatMessage::SERVICE_REPLAY, // 1: 玩家发送 2：客服回复
			'msg'        => $inputs['msg'],
		])->save();
		PlayerSummary::where('player_id', $inputs['player_id'])->update([
			'service_id' => array_get($chatMessage,'service_id'),
			'last_replay_time' =>  array_get($chatMessage,'created_at'),
			'last_replay_msg' =>  array_get($chatMessage,'msg'),
		]);
		return $this->apiResponse->item($chatMessage, ChatMessageResource::class);


	}


	private function updatePlayerSummary($args){
		$playerSummary = PlayerSummary::find(array_get($args,'player_id'));
		if($playerSummary){
			$playerSummary->increment('send_num', 1, [
				'last_send_time'=>array_get($args,'created_at'),
				'last_send_msg'=>array_get($args,'msg'),
			]);
		}else{
			PlayerSummary::create([
				'player_id'=>array_get($args,'player_id'),
				'service_id'=>array_get($args,'service_id'),
				'send_num'=> 1,
				'last_send_time'=>array_get($args,'created_at'),
				'last_send_msg'=>array_get($args,'msg'),
			]);
		}
	}
}