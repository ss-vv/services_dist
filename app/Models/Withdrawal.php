<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    const STATUS_WAITING = 0;
    const STATUS_HOLDING = 1;
    const STATUS_DENY    = 2;
    const STATUS_SUCCESS = 3;

    const NOTIFY_SUCCESS = 1;
    const NOTIFY_FAILURE = 2;

    protected $fillable = [
        'merchant_id',
        'merchant_order_id',
        'order_id',
        'bill_price',
        'user_id',
        'name',
        'bank_card',
        'bank_code',
        'status',
        'notify_url',
        'tel',
        'device_ip',
        'device_type',
        'device_id',
        'extra',
        'service_id',
        'holder',
        'notify_status',
        'process_time',
    ];

    /**
     * 获取订单对应的商户信息
     */
    public function merchant()
    {
       return $this->belongsTo('App\Models\Merchant');
    }

}
