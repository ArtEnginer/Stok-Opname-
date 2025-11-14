<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthGroupUser extends Model
{
    protected $table = 'auth_groups_users';
    protected $fillable = [
        'user_id',
        'group',
    ];

    public $timestamps = false; 
}
