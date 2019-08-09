<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 玩家聊天统计结果表 由定时任务插入数据
 * Class ServiceAchievement
 * @package App\Models
 */
class PlayerSummary extends Model
{
	//
	protected $primaryKey = 'player_id';

	protected $fillable = ['player_id', 'service_id', 'send_num', 'last_send_time', 'last_replay_time', 'last_send_msg','last_replay_msg'];

	protected $keyType      = 'string';

	public $incrementing = false;
}
