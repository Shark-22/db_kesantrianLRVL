<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. QUERY DASAR
        $query = Santri::query();

        // Filter Angkatan
        if ($request->has('angkatan') && $request->angkatan != '') {
            $query->where('angkatan', $request->angkatan);
        }

        // 2. SETUP FILTER TANGGAL
        // Jika user tidak pilih tanggal, defaultnya ambil SEMUA data (dari tahun 2000 sampai masa depan)
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // 3. AMBIL DATA DENGAN FILTER TANGGAL DI RELASI
        // Kita gunakan 'closure' function untuk memfilter data pelanggaran & prestasi
        $allSantri = $query->with([
            'violations' => function ($q) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $q->whereBetween('tanggal', [$startDate, $endDate]);
                }
            },
            'achievements' => function ($q) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $q->whereBetween('tanggal', [$startDate, $endDate]);
                }
            }
        ])->get();

        // 4. LOGIKA PERHITUNGAN (Mapping)
        // Karena data violations/achievements di atas SUDAH TERFILTER tanggalnya,
        // Kita tinggal hitung saja seperti biasa.
        $mappedSantri = $allSantri->map(function ($s) {

            // Hitung Poin Pelanggaran (Sesuai Filter Tanggal)
            $skor = 0;
            foreach ($s->violations as $v) {
                $level = strtolower($v->tingkat ?? $v->level ?? $v->jenis_pelanggaran ?? '');
                if (str_contains($level, 'berat')) {
                    $skor += 50;
                } elseif (str_contains($level, 'sedang')) {
                    $skor += 30;
                } elseif (str_contains($level, 'ringan')) {
                    $skor += 10;
                } else {
                    $skor += 5;
                }
            }
            $s->calculated_score = $skor;

            // Hitung Jumlah Prestasi (Sesuai Filter Tanggal)
            $s->calculated_pahala = $s->achievements->count();

            return $s;
        });

        // A. TOP PELANGGAR
        $topViolators = $mappedSantri->where('calculated_score', '>', 0)
            ->sortByDesc('calculated_score')
            ->values();

        // B. TOP TELADAN
        $topAchievers = $mappedSantri->where('calculated_pahala', '>', 0)
            ->sortByDesc('calculated_pahala')
            ->values();

        // C. RANKING KEDISIPLINAN (Semua Santri)
        // Diurutkan: Poin Pelanggaran Terkecil (ASC) -> Nama Abjad (ASC)
        $cleanSantri = $mappedSantri->sortBy([
            ['calculated_score', 'asc'], // Poin 0 ada di paling atas
            ['nama', 'asc'],             // Jika poin sama, urutkan nama A-Z
        ])->values();

        // Data untuk Dropdown
        $listAngkatan = Santri::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');

        return view('leaderboard.index', compact('topViolators', 'topAchievers', 'cleanSantri', 'listAngkatan'));
    }
}
