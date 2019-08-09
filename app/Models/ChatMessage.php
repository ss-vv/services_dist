<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
	// u_type 类型 1 ：用户发送  2 ：客服回复
	const  USER_SEND = 1;
	const  SERVICE_REPLAY = 2;

    protected $fillable = ['player_id','service_id','status','u_type','msg'];
}
