<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";
    protected $fillable = ['config_id','name','email'];
}
