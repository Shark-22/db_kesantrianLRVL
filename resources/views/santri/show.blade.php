<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Santri - {{ $santri->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* CSS DASAR */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
        }

        .card {
            background-color: #1e293b;
            border: 1px solid #334155;
        }

        /* Input Form */
        .form-control,
        .form-select {
            background-color: #0f172a;
            border-color: #334155;
            color: white !important;
        }

        /* Mencerahkan teks-teks kecil */
        label,
        ::placeholder,
        .text-secondary,
        small {
            color: #cbd5e1 !important;
            /* Abu-abu terang */
        }

        a.text-decoration-none {
            color: #94a3b8 !important;
        }

        /* PRINT MODE */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .card {
                background-color: white !important;
                color: black !important;
                border: 1px solid #ccc !important;
                box-shadow: none !important;
            }

            .table {
                color: black !important;
            }

            .col-md-8 {
                width: 100% !important;
                flex: 0 0 100% !important;
            }

            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background: none !important;
            }
        }
    </style>
</head>

<body class="p-4">

    <div class="container mb-5">

        <div class="d-flex justify-content-between mb-3 no-print">
            <a href="{{ route('santri.index') }}" class="text-decoration-none">
                &larr; Kembali ke Dashboard
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold">
                🖨️ Cetak Laporan
            </button>
        </div>

        <div class="card mb-5 border-0" style="background: linear-gradient(90deg, #1e293b 0%, #334155 100%);">
            <div class="card-body p-4">

                <h2 class="fw-bold" style="color: #ffffff !important; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                    {{ $santri->nama }}
                    <span class="fs-5 fw-normal" style="color: #cbd5e1 !important;">#{{ $santri->nis }}</span>
                </h2>

                <div class="d-flex gap-3 mt-2">
                    <span class="badge bg-dark border border-secondary p-2">Angkatan: {{ $santri->angkatan }}</span>
                    <span class="badge bg-dark border border-secondary p-2">Asal: {{ $santri->asal }}</span>
                    <span class="badge bg-dark border border-secondary p-2">Asrama: {{ $santri->asrama }}</span>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success border-0 bg-success text-white mb-4 no-print">{{ session('success') }}</div>
        @endif

        <div class="row mb-5">
            <div class="col-12">
                <h4 class="fw-bold pb-2 mb-3" style="color: #ff8080 !important; border-bottom: 2px solid #ff8080;">
                    ⚠️ Riwayat Pelanggaran
                </h4>
            </div>

            <div class="col-md-4 no-print">
                <div class="card border-danger h-100">
                    <div class="card-header bg-danger text-white fw-bold">Catatan Pelanggaran</div>
                    <div class="card-body">
                        <form action="{{ route('violation.store', $santri->id) }}" method="POST">
                            @csrf
                            <div class="mb-3"><label class="small">Tanggal</label><input type="date" name="tanggal" class="form-control" required></div>
                            <div class="mb-3"><label class="small">Jenis Kasus</label><input type="text" name="jenis_pelanggaran" class="form-control" placeholder="Contoh: Kabur" required></div>
                            <div class="mb-3">
                                <label class="small">Tingkat</label>
                                <select name="tingkat" class="form-select" required>
                                    <option value="ringan">🟡 Ringan </option>
                                    <option value="sedang">🟠 Sedang </option>
                                    <option value="berat">🔴 Berat </option>
                                </select>
                            </div>
                            <div class="mb-3"><label class="small">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
                            <button class="btn btn-outline-danger w-100">Simpan Data</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card h-100">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead class="table-secondary text-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kasus</th>
                                    <th>Level</th>
                                    <th class="no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($santri->violations as $v)
                                <tr>
                                    <td>{{ $v->tanggal }}</td>
                                    <td>{{ $v->jenis_pelanggaran }}<br><small>{{ $v->keterangan }}</small></td>
                                    <td>
                                        @if($v->tingkat == 'berat') <span class="badge bg-danger">BERAT</span>
                                        @elseif($v->tingkat == 'sedang') <span class="badge bg-warning text-dark">SEDANG</span>
                                        @else <span class="badge bg-info text-dark">RINGAN</span> @endif
                                    </td>
                                    <td class="no-print">
                                        <a href="{{ route('violation.edit', $v->id) }}" class="btn btn-sm btn-dark border-secondary">✏️</a>
                                        <form action="{{ route('violation.destroy', $v->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE') <button class="btn btn-sm btn-danger">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color: #cbd5e1;">Bersih, tidak ada pelanggaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold pb-2 mb-3" style="color: #80ff9f !important; border-bottom: 2px solid #80ff9f;">
                    🏆 Riwayat Prestasi
                </h4>
            </div>

            <div class="col-md-4 no-print">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white fw-bold">Catatan Prestasi</div>
                    <div class="card-body">
                        <form action="{{ route('achievement.store', $santri->id) }}" method="POST">
                            @csrf
                            <div class="mb-3"><label class="small">Tanggal</label><input type="date" name="tanggal" class="form-control" required></div>
                            <div class="mb-3"><label class="small">Nama Prestasi</label><input type="text" name="nama_prestasi" class="form-control" placeholder="Juara 1 Lomba..." required></div>
                            <div class="mb-3">
                                <label class="small">Tingkat</label>
                                <select name="tingkat" class="form-select" required>
                                    <option value="Sekolah">🏫 Sekolah</option>
                                    <option value="Kecamatan">🏘️ Kecamatan</option>
                                    <option value="Kabupaten">🏛️ Kabupaten</option>
                                    <option value="Provinsi">⭐ Provinsi</option>
                                    <option value="Nasional">🏰 Nasional</option>
                                </select>
                            </div>
                            <div class="mb-3"><label class="small">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
                            <button class="btn btn-outline-success w-100">Simpan Prestasi</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card h-100">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead class="table-secondary text-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Prestasi</th>
                                    <th>Tingkat</th>
                                    <th class="no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($santri->achievements as $a)
                                <tr>
                                    <td>{{ $a->tanggal }}</td>
                                    <td>
                                        <span class="fw-bold" style="color: #ffffff !important;">{{ $a->nama_prestasi }}</span>
                                        <br><small>{{ $a->keterangan }}</small>
                                    </td>
                                    <td><span class="badge border border-success text-success bg-transparent">{{ $a->tingkat }}</span></td>
                                    <td class="no-print">
                                        <a href="{{ route('achievement.edit', $a->id) }}" class="btn btn-sm btn-dark border-secondary">✏️</a>
                                        <form action="{{ route('achievement.destroy', $a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE') <button class="btn btn-sm btn-danger">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color: #cbd5e1;">Belum ada prestasi. Ayo semangati!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>