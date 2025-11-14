<?php

namespace App\Entities;

use CodeIgniter\Shield\Entities\User as ShieldEntity;

class User extends ShieldEntity
{
    protected $casts = [
        'id' => '?string',
        'active' => 'int_bool',
        'permissions' => 'array',
        'groups' => 'array',
    ];
}
