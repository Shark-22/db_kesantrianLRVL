<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard Santri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #0f172a; color: #e2e8f0; }
        .glass-card { background: rgba(33, 37, 41, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 15px; }
        
        /* Warna Ranking 1, 2, 3 */
        .rank-1 { color: #FFD700; font-weight: bold; font-size: 1.2em; text-shadow: 0 0 10px rgba(255, 215, 0, 0.5); }
        .rank-2 { color: #C0C0C0; font-weight: bold; }
        .rank-3 { color: #cd7f32; font-weight: bold; }
    </style>
</head>
<body class="p-4">

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('santri.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
            <h2 class="fw-bold text-white mb-0">🏆 Leaderboard Santri</h2>
            <div style="width: 150px;"></div> </div>

        <div class="glass-card p-3 mb-5">
            <form action="{{ route('leaderboard.index') }}" method="GET">
                <div class="row g-2 align-items-end">
                    
                    <div class="col-md-2">
                        <label class="text-secondary small mb-1">Angkatan</label>
                        <select name="angkatan" class="form-select bg-dark text-white border-secondary">
                            <option value="">Semua</option>
                            @foreach($listAngkatan as $angkatan)
                                <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>
                                    Angk. {{ $angkatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="text-secondary small mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control bg-dark text-white border-secondary">
                    </div>

                    <div class="col-md-3">
                        <label class="text-secondary small mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control bg-dark text-white border-secondary">
                    </div>

                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-filter"></i>
                        </button>
                    </div>

                    <div class="col-md-3">
                        <label class="text-secondary small mb-1">Cari Nama</label>
                        <input type="text" id="searchInput" class="form-control bg-dark text-white border-secondary" placeholder="Ketik nama...">
                    </div>
                </div>
            </form>
        </div>

        <div class="row g-4">
            
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 glass-card" style="border-top: 5px solid #dc3545 !important;">
                    
                    <div class="card-header border-0 bg-transparent pt-3 pb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-danger mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Top Pelanggar</h5>
                            <small class="text-secondary">Urutan poin pelanggaran terbanyak</small>
                        </div>
                        <span class="badge bg-danger rounded-pill px-3">Total: {{ $topViolators->count() }}</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-dark table-hover mb-0 align-middle">
                                <thead class="table-secondary sticky-top">
                                    <tr>
                                        <th width="40" class="text-center">#</th>
                                        <th>Nama Santri</th>
                                        <th class="text-center">Total Poin</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                 <tbody id="tablePelanggar">
                                    @forelse($topViolators as $index => $s)
                                    <tr>
                                        <td class="text-center">
                                            @if($index == 0) <i class="bi bi-trophy-fill rank-1 fs-5"></i>      @elseif($index == 1) <i class="bi bi-trophy-fill rank-2 fs-5"></i>  @elseif($index == 2) <i class="bi bi-trophy-fill rank-3 fs-5"></i>  @else {{ $loop->iteration }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-white nama-santri">{{ $s->nama }}</div>
                                            <small class="text-secondary">{{ $s->asrama }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger rounded-pill px-3">{{ $s->calculated_score }} Poin</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('santri.show', $s->id) }}" class="btn btn-sm btn-outline-light"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 glass-card" style="border-top: 5px solid #198754 !important;">
                    
                    <div class="card-header border-0 bg-transparent pt-3 pb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-success mb-0"><i class="bi bi-award-fill me-2"></i>Top Prestasi</h5>
                            <small class="text-secondary">Urutan poin prestasi terbanyak</small>
                        </div>
                        <span class="badge bg-success rounded-pill px-3">Total: {{ $topAchievers->count() }}</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-dark table-hover mb-0 align-middle">
                                <thead class="table-secondary sticky-top">
                                    <tr>
                                        <th width="40" class="text-center">#</th>
                                        <th>Nama Santri</th>
                                        <th class="text-center">Total Poin</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableTeladan">
                                    @forelse($topAchievers as $index => $s)
                                    <tr>
                                        <td class="text-center">
                                            @if($index == 0) <i class="bi bi-trophy-fill rank-1 fs-5"></i>
                                            @elseif($index == 1) <i class="bi bi-trophy-fill rank-2 fs-5"></i>
                                            @elseif($index == 2) <i class="bi bi-trophy-fill rank-3 fs-5"></i>
                                            @else {{ $loop->iteration }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-white nama-santri">{{ $s->nama }}</div>
                                            <small class="text-secondary">{{ $s->asrama }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success rounded-pill px-3">{{ $s->calculated_pahala }} Poin</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('santri.show', $s->id) }}" class="btn btn-sm btn-outline-light"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-8 mx-auto">
            <div class="card glass-card border-info">
                <div class="card-header bg-transparent border-info d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h4 class="text-info fw-bold mb-0"><i class="bi bi-sort-numeric-down me-2"></i>Peringkat Kedisiplinan</h4>
                        <small class="text-secondary">Diurutkan dari yang paling sedikit melanggar (0 Poin) sampai terbanyak.</small>
                    </div>
                    <span class="badge bg-info text-dark rounded-pill fs-6 px-3">Total: {{ count($cleanSantri) }} Santri</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="table-secondary sticky-top">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Nama Santri</th>
                                    <th>Asrama</th>
                                    <th>Angkatan</th>
                                    <th class="text-center">Total Poin</th> <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBersih">
                                @forelse($cleanSantri as $s)
                                <tr>
                                    <td class="text-center text-secondary">{{ $loop->iteration }}</td>
                                    <td class="fw-bold text-info nama-santri">{{ $s->nama }}</td>
                                    <td>{{ $s->asrama }}</td>
                                    <td>Angkatan {{ $s->angkatan }}</td>
                                    
                                    <td class="text-center">
                                        {{ $s->calculated_score }}
                                    </td>

                                    <td class="text-center">
                                        @if($s->calculated_score == 0)
                                            <span class="badge bg-info text-dark">
                                                <i class="bi bi-shield-check me-1"></i>Bersih
                                            </span>
                                        @elseif($s->calculated_score <= 25)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-exclamation-circle me-1"></i>Waspada
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Bahaya
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('santri.show', $s->id) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-secondary">
                                        Data tidak ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rowsPelanggar = document.querySelectorAll('#tablePelanggar tr');
            let rowsTeladan = document.querySelectorAll('#tableTeladan tr');

            function filterTable(rows) {
                rows.forEach(row => {
                    let text = row.querySelector('.nama-santri') ? row.querySelector('.nama-santri').innerText.toLowerCase() : '';
                    if (text.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            filterTable(rowsPelanggar);
            filterTable(rowsTeladan);
        });
    </script>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rowsPelanggar = document.querySelectorAll('#tablePelanggar tr');
            let rowsTeladan = document.querySelectorAll('#tableTeladan tr');
            let rowsBersih = document.querySelectorAll('#tableBersih tr'); // TAMBAHAN BARU

            function filterTable(rows) {
                rows.forEach(row => {
                    let text = row.querySelector('.nama-santri') ? row.querySelector('.nama-santri').innerText.toLowerCase() : '';
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            }
            
            filterTable(rowsPelanggar);
            filterTable(rowsTeladan);
            filterTable(rowsBersih); // JALANKAN UNTUK TABEL BERSIH
        });
    </script>

</body>
</html>