<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class RegionDomain extends Model
{
  public $timestamps = false;
  protected $table = 'region_domain';
  protected $primaryKey = 'region_domain_id';
  public $incrementing = false;
}
