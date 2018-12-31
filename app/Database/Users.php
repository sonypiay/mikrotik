<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
  public $timestamps = true;
  protected $table = 'users';
  protected $primaryKey = 'user_id';
  public $incrementing = false;
}
