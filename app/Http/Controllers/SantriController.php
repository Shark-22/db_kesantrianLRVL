<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\Violation;
use App\Models\Achievement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SantriController extends Controller
{
    // ==========================================
    // 1. FITUR UTAMA (DASHBOARD & SANTRI)
    // ==========================================

    public function index(Request $request)
    {
        // 1. SIAPKAN QUERY DASAR
        $query = Santri::query();

        // Filter Angkatan
        if ($request->has('angkatan') && $request->angkatan != '') {
            $query->where('angkatan', $request->angkatan);
        }

        // 2. LOGIKA TABEL BAWAH (Paginasi)
        $queryTabel = clone $query;

        // Pencarian Nama/NIS
        if ($request->has('search')) {
            $queryTabel->where(function ($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('nis', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Tampilkan 20 data per halaman (agar tidak terlalu panjang scrollnya)
        $santris = $queryTabel->latest()->paginate(30);

        // 3. LOGIKA WIDGET ATAS (Hitung Poin Manual)
        $allSantri = $query->with(['violations', 'achievements'])->get();

        // --- A. Hitung Top Pelanggar (SESUAI REQUEST BARU) ---
        $topViolators = $allSantri->map(function ($s) {
            $skor = 0;
            foreach ($s->violations as $v) {
                $level = strtolower($v->tingkat ?? $v->level ?? $v->jenis_pelanggaran ?? '');

                // === RUMUS BARU: 50, 30, 10 ===
                if (str_contains($level, 'berat')) {
                    $skor += 50;
                } elseif (str_contains($level, 'sedang')) {
                    $skor += 30;
                } elseif (str_contains($level, 'ringan')) {
                    $skor += 10;
                } else {
                    $skor += 5;
                } // Default jika kosong
            }
            $s->total_dosa = $skor;
            return $s;
        })
            ->where('total_dosa', '>', 0)
            ->sortByDesc('total_dosa')
            ->values()
            ->take(3);

        // --- B. Hitung Top Teladan (JUMLAH PRESTASI SAJA) ---
        $topAchievers = $allSantri->map(function ($s) {

            // Cukup hitung jumlah baris data prestasinya
            // (1 Prestasi = 1 Poin hitungan)
            $s->total_pahala = $s->achievements->count();

            return $s;
        })
            ->where('total_pahala', '>', 0)
            ->sortByDesc('total_pahala')
            ->values()
            ->take(3);
        // Data untuk Dropdown
        $dataAngkatan = Santri::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');

        return view('santri.index', compact('santris', 'dataAngkatan', 'topViolators', 'topAchievers'));
    }

    // [BARU] FUNGSI MENYIMPAN DATA SANTRI BARU (Tadi Hilang)
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|numeric|unique:santris,nis', // Pastikan NIS unik
            'nama' => 'required',
            'angkatan' => 'required|numeric',
            'asrama' => 'required'
        ]);

        Santri::create($request->all());

        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil ditambahkan!');
    }

    // [BARU] FUNGSI MENAMPILKAN DETAIL PROFIL (Tadi Hilang)
    public function show($id)
    {
        // Ambil data santri beserta pelanggaran & prestasi
        $santri = Santri::with(['violations', 'achievements'])->findOrFail($id);

        return view('santri.show', compact('santri'));
    }

    public function edit($id)
    {
        $santri = Santri::findOrFail($id);
        return view('santri.edit', compact('santri'));
    }

    public function update(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);
        $santri->update($request->all());
        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        $santri->delete(); // Data violations & achievements otomatis terhapus jika di migration pakai onDelete('cascade')
        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil dihapus.');
    }


    // ==========================================
    // 2. FITUR PELANGGARAN (VIOLATION)
    // ==========================================

    public function storeViolation(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_pelanggaran' => 'required', // Pastikan nama kolom di DB sesuai
            'tingkat' => 'required',
        ]);

        Violation::create([
            'santri_id' => $id,
            'tanggal' => $request->tanggal,
            'jenis_pelanggaran' => $request->jenis_pelanggaran, // Sesuaikan field DB
            'tingkat' => $request->tingkat,
            'keterangan' => $request->keterangan ?? '-' // Default strip jika kosong
        ]);

        return redirect()->back()->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function editViolation($id)
    {
        $violation = Violation::findOrFail($id);
        return view('santri.edit_violation', compact('violation'));
    }

    public function updateViolation(Request $request, $id)
    {
        $violation = Violation::findOrFail($id);
        $violation->update($request->all());
        // Redirect kembali ke halaman detail santri
        return redirect()->route('santri.show', $violation->santri_id)->with('success', 'Data pelanggaran berhasil diupdate.');
    }

    public function destroyViolation($id)
    {
        $v = Violation::findOrFail($id);
        $v->delete();
        return redirect()->back()->with('success', 'Data pelanggaran dihapus.');
    }


    // ==========================================
    // 3. FITUR PRESTASI (ACHIEVEMENT)
    // ==========================================

    public function storeAchievement(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_prestasi' => 'required', // Cek apakah di DB namanya 'nama_prestasi' atau 'judul' atau 'prestasi'
            'tingkat' => 'required',
        ]);

        Achievement::create([
            'santri_id' => $id,
            'tanggal' => $request->tanggal,
            'nama_prestasi' => $request->nama_prestasi, // Sesuaikan field DB
            'tingkat' => $request->tingkat,
            'keterangan' => $request->keterangan ?? '-'
        ]);

        return redirect()->back()->with('success', 'Prestasi berhasil dicatat! 🏆');
    }

    public function editAchievement($id)
    {
        $achievement = Achievement::findOrFail($id);
        return view('santri.edit_achievement', compact('achievement'));
    }

    public function updateAchievement(Request $request, $id)
    {
        $a = Achievement::findOrFail($id);
        $a->update($request->all());
        return redirect()->route('santri.show', $a->santri_id)->with('success', 'Data prestasi berhasil diupdate.');
    }

    public function destroyAchievement($id)
    {
        $a = Achievement::findOrFail($id);
        $a->delete();
        return redirect()->back()->with('success', 'Data prestasi dihapus.');
    }

    // ==========================================
    // 4. FITUR CETAK PDF
    // ==========================================

    public function cetakPdf($id)
    {
        // Ambil data santri beserta pelanggaran & prestasinya
        $santri = Santri::with(['violations', 'achievements'])->findOrFail($id);

        // Load tampilan PDF
        $pdf = Pdf::loadView('santri.cetak_pdf', compact('santri'));

        // Atur ukuran kertas (Opsional, biasanya A4 Portrait)
        $pdf->setPaper('a4', 'portrait');

        // Stream file PDF
        return $pdf->stream('Laporan_Santri_' . $santri->nama . '.pdf');
    }
}
