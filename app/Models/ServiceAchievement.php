<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 客服业绩统计结果表 由定时任务插入数据
 * Class ServiceAchievement
 * @package App\Models
 */
class ServiceAchievement extends Model
{
	//
	protected $primaryKey = 'service_id';

	protected $fillable = ['service_id', 'allot_num', 'not_handle_num', 'work_duration', 'last_replay_time'];

	protected $keyType      = 'string';

	public $incrementing = false;
}
