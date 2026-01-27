<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\Violation;
use App\Models\Achievement; // Tambahan Model Prestasi
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SantriController extends Controller
{
    // ==========================================
    // 1. FITUR UTAMA (DASHBOARD & SANTRI)
    // ==========================================

    public function index(Request $request)
    {
        // QUERY UTAMA
        $query = Santri::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                    ->orWhere('nis', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('filter_angkatan') && $request->filter_angkatan != '') {
            $query->where('angkatan', $request->filter_angkatan);
        }

        $santris = $query->orderBy('nis', 'asc')->get();
        $dataAngkatan = Santri::select('angkatan')->distinct()->orderBy('angkatan')->pluck('angkatan');

        // DASHBOARD LOGIC
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $baseQuery = Santri::withCount(['violations' => function ($q) use ($bulanIni, $tahunIni) {
            $q->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni);
        }]);

        $judulWorst = "Pelanggar Terbanyak";
        $judulBest  = "Kandidat Teladan";

        if ($request->has('filter_angkatan') && $request->filter_angkatan != '') {
            $baseQuery->where('angkatan', $request->filter_angkatan);
            $judulWorst .= " (Angk. " . $request->filter_angkatan . ")";
            $judulBest  .= " (Angk. " . $request->filter_angkatan . ")";
        }

        $topViolators = (clone $baseQuery)->having('violations_count', '>', 0)->orderBy('violations_count', 'desc')->take(3)->get();
        $bestCandidates = (clone $baseQuery)->orderBy('violations_count', 'asc')->inRandomOrder()->take(3)->get();

        return view('santri.index', compact('santris', 'dataAngkatan', 'topViolators', 'bestCandidates', 'judulWorst', 'judulBest'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:santris,nis',
            'nama' => 'required',
            'asal' => 'required',
            'angkatan' => 'required|numeric',
        ]);

        Santri::create($request->all());
        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil ditambahkan!');
    }

    public function show($id)
    {
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
        $santri->delete();
        return redirect()->route('santri.index')->with('success', 'Data Santri berhasil dihapus.');
    }


    // ==========================================
    // 2. FITUR PELANGGARAN (VIOLATION)
    // ==========================================

    // INI YANG TADI HILANG -> storeViolation
    public function storeViolation(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_pelanggaran' => 'required',
            'tingkat' => 'required',
        ]);

        Violation::create([
            'santri_id' => $id,
            'tanggal' => $request->tanggal,
            'jenis_pelanggaran' => $request->jenis_pelanggaran,
            'tingkat' => $request->tingkat,
            'keterangan' => $request->keterangan
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
        return redirect()->route('santri.show', $violation->santri_id)->with('success', 'Data pelanggaran diupdate.');
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
            'nama_prestasi' => 'required',
            'tingkat' => 'required',
        ]);

        Achievement::create([
            'santri_id' => $id,
            'tanggal' => $request->tanggal,
            'nama_prestasi' => $request->nama_prestasi,
            'tingkat' => $request->tingkat,
            'keterangan' => $request->keterangan
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
        return redirect()->route('santri.show', $a->santri_id)->with('success', 'Data prestasi diupdate.');
    }

    public function destroyAchievement($id)
    {
        $a = Achievement::findOrFail($id);
        $a->delete();
        return redirect()->back()->with('success', 'Data prestasi dihapus.');
    }
}
