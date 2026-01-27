<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    // Ini agar kita bisa mengisi semua kolom (Mass Assignment)
    protected $guarded = [];

    // Relasi: Santri punya banyak Pelanggaran
    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    // Relasi: Santri punya banyak Prestasi
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }
}
