<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 客服工作时长记录表
class ServiceTime extends Model
{
    protected $fillable = ['service_id','status','on_time','off_time'];
}
