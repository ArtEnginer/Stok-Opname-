<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthPermissionUser extends Model
{
    protected $table = 'auth_permissions_users';
    protected $fillable = [
        'user_id',
        'permission',
    ];
}
