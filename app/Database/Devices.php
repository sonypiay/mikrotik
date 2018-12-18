<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
  public $timestamps = false;
  protected $table = 'devices';
  protected $primaryKey = 'device_ip';
  public $incrementing = false;
}
