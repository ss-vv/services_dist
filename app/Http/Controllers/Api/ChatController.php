<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\PlayerSummaryResource;
use App\Models\ChatMessage;
use App\Models\PlayerSummary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;


class ChatController extends ApiController
{

	protected $chatMessage;
	protected $playerSummary;
	protected $serviceId;


	public function __construct(ChatMessage $chatMessage, PlayerSummary $playerSummary)
	{
		parent::__construct();
		$this->chatMessage = $chatMessage;
		$this->playerSummary = $playerSummary;
		// $token             = request()->header('Authorization');
		// $serviceId   = Redis::get($token . '_service_id');
		// if(!$serviceId){
		// 	$this->apiResponse->error('登录超时!',401);
		// }else{
		// 	$this->serviceId = $serviceId;
		// }
	}

	/**
	 * 查询客服未处理的玩家信息
	 */
	public function messageNotReplay()
	{
		$perPage = request('per_page',10);
		$playerId     = request('player_id');
		$token        = request()->header('Authorization');
		$serviceId   = Redis::get($token . '_service_id');
		if(!$serviceId){
			$this->apiResponse->error('登录超时!',401);
		}
		$query = $this->chatMessage->newQuery()->select('player_id',DB::raw('max(id) as max_id '));
		if($playerId){
			$query->where('player_id',$playerId);
		}
		$ids = $query->groupBy('player_id')->pluck('max_id');
		$chatMessages = $this->chatMessage->newQuery()->whereIn('id',$ids)->where('u_type',ChatMessage::USER_SEND)->where('service_id',$serviceId)->orderBy('id', 'desc')->paginate($perPage);
		// $chatPlayers = $this->chatMessage->paginate();
		return $this->apiResponse->paginator($chatMessages, ChatMessageResource::class);
	}

	/**
	 * 查询玩家和客服的聊天记录  by 玩家id
	 */
	public function chatMessages()
	{
		$playerId     = request('player_id');
		$chatMessages = $this->chatMessage->where('player_id', $playerId)
			->orderBy('id', 'desc')
			->paginate();
		return $this->apiResponse->paginator($chatMessages, ChatMessageResource::class);
	}

	/**
	 * 查询玩家聊天统计信息
	 * @return \Illuminate\Http\Response
	 */
	public function playerSummaries(){
		$playerId     = request('player_id');
		$perPage = request('per_page',20);
		$playerSummaries = $this->playerSummary->when($playerId, function ($query) use ($playerId) {
			return $query->where('player_id', $playerId);
		})->orderByDesc('last_send_time')->paginate($perPage);
		return $this->apiResponse->paginator($playerSummaries, PlayerSummaryResource::class);

	}

}