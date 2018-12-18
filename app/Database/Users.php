<?php

namespace App\Database;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
  public $timestamps = false;
  protected $table = 'users';
  protected $primaryKey = 'user_id';
}
