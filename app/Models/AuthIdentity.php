<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthIdentity extends Model
{
    protected $table = 'auth_identities';

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'secret',
        'secret2',
        'expires',
        'extra',
        'force_reset',
        'last_used_at',
    ];
}
