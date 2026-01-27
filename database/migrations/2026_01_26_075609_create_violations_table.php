<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            // Ini membuat hubungan ke tabel santri
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');

            $table->date('tanggal');
            $table->string('jenis_pelanggaran'); // Contoh: "Kabur", "Telat Sholat"

            // Kolom Tingkatan (Hanya bisa pilih: ringan, sedang, berat)
            $table->enum('tingkat', ['ringan', 'sedang', 'berat']);

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
