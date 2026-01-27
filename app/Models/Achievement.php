<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    // BARIS INI PENTING AGAR TIDAK ERROR SAAT UPDATE/CREATE
    protected $guarded = [];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
