<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi: Pelanggaran ini milik Santri siapa?
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
