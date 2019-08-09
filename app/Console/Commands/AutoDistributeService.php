<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use App\Models\ServiceTime;
use Carbon\Carbon;
use GatewayClient\Gateway;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoDistributeService extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'distribute:service';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '给未分配客服的玩家分配客服';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		Gateway::$registerAddress = env('GETWAY_REGISTER_ADDRESS', '127.0.0.1:1238');
		// 查询未分配客服的玩家聊天信息
		$noServiceMsgs = ChatMessage::where('service_id', 0)->where('status', 0)->latest()->get();
		// 存在未分配的玩家信息s 去做处理
		if ($noServiceMsgs) {
			foreach ($noServiceMsgs as $chatMsg) {
				//未分配玩家id的最后一条记录 （这条记录可能分配 可能未分配）
				$lastChatMessage = ChatMessage::where('player_id', $chatMsg->player_id)->latest()->first();
				// 最后一条记录已经分配 这条记录未分配 则更新记录
				if ($lastChatMessage->service_id) {
					ChatMessage::where('id', $chatMsg->id)
						->update([
							'service_id' => $lastChatMessage->service_id,
							'status'     => 1,
						]);
				}
				else {
					//最后一条记录也未分配  则 检查是否有可以服务的客服
					$todayStart = Carbon::today()->toDateTimeString();
					$now        = Carbon::now()->toDateTimeString();
					// 当前上班的客服ids
					$onServiceIds = ServiceTime::where('status', 1)->where('on_time', '>=', $todayStart)->where('on_time', '<=', $now)->pluck('service_id');

					// 当前有正在上班的客服
					if (count($onServiceIds)) {
						// 查询已经分配玩家的客服id 按照分配的玩家数量从小到大排序
						$query = DB::table('chat_messages')->select( 'player_id', 'service_id')->whereIn('service_id',$onServiceIds)->where('created_at','>',$todayStart)->where('created_at','<=',$now)
							->distinct();
						$alreadyServices= DB::table(DB::raw("({$query->toSql()}) as t"))->mergeBindings($query)->select(DB::raw(' t.service_id, count(t.player_id) as p_counts'))
							->groupBy('t.service_id')
							->orderBy('p_counts','ASC')
							->get();
						// 如果存在已经分配玩家的客服
						if ($alreadyServices) {
							$serviceIds = []; //存放上班客服中已经和玩家有聊天的客服ids
							foreach ($alreadyServices as $service) {
								$serviceIds[] = $service->service_id;
							}
							$diffFirst = $onServiceIds->diff($serviceIds)->first();  // 上班客服ids 与 聊天客服ids 取差集 获取未分配的客服第一个 id
							// 是否存在未分配的客服
							if ($diffFirst) {
								$firstCanServiceId = $diffFirst;
							}
							else {
								$firstCanServiceId = $alreadyServices[0]->service_id;
							}
						}
						else {
							// 上班的客服中所有客服都没和玩家聊天 则取上班客服ids中的第一个
							$firstCanServiceId = $onServiceIds[0];
						}
						// 更新记录分配service_id
						ChatMessage::where('id', $chatMsg->id)
							->update([
								'service_id' => $firstCanServiceId,
								'status'     => 1,
							]);
						$inputs = [
							'service_id' => $firstCanServiceId,
							'player_id'  => $chatMsg->player_id,
							'created_at' => $chatMsg->created_at,
							'u_type'     => $chatMsg->u_type,
							'status'     => 1,
							'msg'        => $chatMsg->msg,
						];
						// 推送消息给客服 $firstCanServiceId
						Gateway::sendToUid($firstCanServiceId, json_encode($inputs));
					}


				}
			}
		}


	}
}
