<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalServiceTime extends Model
{
    protected $fillable = ['service_id','status','on_time','off_time'];
}
