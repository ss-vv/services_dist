<?php

namespace App\Console\Commands;

use App\Models\ChatMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HandleMsgNotReplay extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'handle:msg_not_replay';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '处理5分钟没有回复的玩家问题';

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
		$query         = $this->chatMessage->newQuery()->select('player_id', DB::raw('max(id) as max_id '));
		$ids           = $query->groupBy('player_id')->pluck('max_id');
		$now           = Carbon::now();
		$fiveMinBefore = $now->subMinute(5);
		$updateIds     = $this->chatMessage->newQuery()->whereIn('id', $ids)->where('u_type', ChatMessage::USER_SEND)
			->where('status', 1)->where('created_at', '<=', $fiveMinBefore)->pluck('id');
		if ($updateIds && count($updateIds)) {
			$this->chatMessage->whereIn('id', $updateIds)->update([
				'service_id' => 0,
				'status'     => 0,
			]);
		}
	}
}
