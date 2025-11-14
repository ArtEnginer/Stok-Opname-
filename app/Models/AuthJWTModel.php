<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AuthJWTModel extends Model
{
    use HasUuids;

    protected $table = 'auth_jwt';
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(PenggunaModel::class, 'id', 'user_id');
    }
}
