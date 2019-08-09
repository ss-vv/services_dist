<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use App\Models\PlayerSummary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PlayerChatSummary extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'summary:player_chat_msg';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '手动统计玩家聊天记录:用于部署该功能时已存在聊天记录的情况';
	protected $chatMessage;

	/**
	 * Create a new command instance.
	 *
	 * @param ChatMessage $chatMessage
	 */
	public function __construct(ChatMessage $chatMessage)
	{
		parent::__construct();
		$this->chatMessage = $chatMessage;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		/*  SELECT player_id,count(*) FROM chat_messages WHERE u_type = 1 GROUP BY player_id ;
			 -- 获取玩家id 以及 提问次数

			SELECT * FROM chat_messages where u_type = 1 and player_id= 101 ORDER BY id DESC  LIMIT 1;
			-- 根据玩家id 获取玩家最后发送时间 以及 发送问题

			SELECT * FROM chat_messages where u_type = 2 and player_id= 101 ORDER BY id DESC  LIMIT 1;;
			-- 根据玩家id 获取 客服id 客服最后回答时间 以及回答内容*/
		$query         = $this->chatMessage->newQuery()->select('player_id', DB::raw('count(*) as send_num '))->where('u_type', 1);
		$playerSendArr = $query->groupBy('player_id')->get();

		foreach ($playerSendArr as $item) {
			$playerSendMsg = $this->chatMessage->newQuery()->select('player_id', 'created_at', 'msg')->where('player_id', array_get($item, 'player_id'))->where('u_type', 1)->latest()
				->first();

			$serviceSendMsg = $this->chatMessage->newQuery()->select('service_id', 'created_at', 'msg')->where('player_id', array_get($item, 'player_id'))->where('u_type', 2)->latest()
				->first();
			if ($playerSendMsg) {
				$playerSummary = PlayerSummary::find(array_get($item, 'player_id'));
				if ($playerSummary) {
					$updates = [
						'send_num'       => array_get($item, 'send_num'),
						'last_send_time' => array_get($playerSendMsg, 'created_at'),
						'last_send_msg'  => array_get($playerSendMsg, 'msg'),
					];

					if (array_get($serviceSendMsg, 'service_id')) {
						$updates['service_id'] = array_get($serviceSendMsg, 'service_id');
					}
					if (array_get($serviceSendMsg, 'created_at')) {
						$updates['last_replay_time'] = array_get($serviceSendMsg, 'created_at');
					}
					if (array_get($serviceSendMsg, 'created_at')) {
						$updates['last_replay_msg'] = array_get($serviceSendMsg, 'msg');
					}
					$playerSummary->update($updates);

				}else{
					PlayerSummary::create([
						'player_id'=>array_get($item,'player_id'),
						'service_id'=>array_get($serviceSendMsg,'service_id',''),
						'send_num'=> array_get($item,'send_num'),
						'last_send_time'=>array_get($playerSendMsg,'created_at'),
						'last_send_msg'=>array_get($playerSendMsg,'msg'),
						'last_replay_time' =>  array_get($serviceSendMsg,'created_at'),
						'last_replay_msg' =>  array_get($serviceSendMsg,'msg'),
					]);
				}
			}

		}

	}
}
