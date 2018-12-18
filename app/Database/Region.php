<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
  public $timestamps = false;
  protected $table = 'region';
  protected $primaryKey = 'region_id';
  public $incrementing = false;
}
