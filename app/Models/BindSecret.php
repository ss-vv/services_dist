<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BindSecret extends Model
{
  protected $fillable = ['user_name','secret'];
}
