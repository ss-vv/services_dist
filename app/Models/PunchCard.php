<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 客服打卡记录表
class PunchCard extends Model
{
    protected $fillable = ['service_time_id','service_id','status','type','punch_time','created_at','updated_at'];
}
