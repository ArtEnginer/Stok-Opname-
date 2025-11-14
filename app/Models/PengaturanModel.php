<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanModel extends Model
{
    protected $table = 'pengaturan';
    protected $fillable = [
        "id",
        "nominal_per_kg",
    ];

    // Disable timestamps jika tabel tidak memiliki created_at dan updated_at
    public $timestamps = true;
}
