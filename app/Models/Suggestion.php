<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = [
        'ip',
        'mobile',
        'content',
        'width',
        'height',
        'renderer',
        'vendor',
        'device_pixel_ratio',
        'platform',
        'appCodeName',
        'appName',
        'appVersion',
    ];
}
