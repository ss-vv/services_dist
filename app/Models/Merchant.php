<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = [
        'merchant_id',
        'token',
    ];
    public function withdrawals()
    {
        return $this->hasMany('App\Models\Withdrawal');
    }
}
