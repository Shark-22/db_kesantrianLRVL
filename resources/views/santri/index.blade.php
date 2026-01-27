<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kesantrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
            background-image: radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 1) 0, transparent 50%), radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 1) 0, transparent 50%), radial-gradient(at 100% 0%, hsla(339, 49%, 30%, 1) 0, transparent 50%);
            min-height: 100vh;
            color: #e2e8f0;
        }

        .glass-card {
            background: rgba(33, 37, 41, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        .btn-gradient {
            background: linear-gradient(45deg, #4776E6 0%, #8E54E9 100%);
            border: none;
            color: white;
            transition: transform 0.2s;
        }

        .btn-gradient:hover {
            transform: scale(1.02);
            color: white;
            box-shadow: 0 0 15px rgba(142, 84, 233, 0.5);
        }

        .table-custom {
            --bs-table-bg: transparent;
            --bs-table-color: #e2e8f0;
        }

        /* Hover efek agar bisa diklik */
        a.list-group-item-action:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            cursor: pointer;
            transition: 0.3s;
        }

        .bg-brown {
            background-color: #cd7f32 !important;
            color: white;
        }
    </style>
</head>

<body class="p-4">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-5 glass-card p-3">
            <div class="text-start">
                <h2 class="fw-bold text-white mb-0">📊 Dashboard Kesantrian</h2>
                <p class="text-secondary mb-0 small">Selamat Datang, <strong>{{ Auth::user()->name ?? 'Admin' }}</strong></p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger btn-sm px-4 rounded-pill fw-bold">Logout 🚪</button>
            </form>
        </div>

        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="background: #10b981; color: white;">
            {{ session('success') }}
        </div>
        @endif

        <div class="row mb-4">

            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card border-0 h-100 shadow-sm glass-card" style="background: rgba(220, 53, 69, 0.15) !important; color: #ff8787;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3 border-bottom border-danger pb-2">
                            <i class="bi bi-exclamation-triangle-fill fs-3 me-2 text-danger"></i>
                            <h5 class="fw-bold mb-0 text-uppercase text-white">
                                {{ $judulWorst ?? 'Pelanggar Terbanyak' }}
                            </h5>
                        </div>

                        @if(isset($topViolators) && $topViolators->count() > 0)
                        <div class="list-group list-group-flush rounded">
                            @foreach($topViolators as $index => $bad)
                            <a href="{{ route('santri.show', $bad->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-transparent border-bottom border-secondary text-white px-2 py-2 text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <span class="badge rounded-pill me-3 bg-danger text-white" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <span class="fw-bold d-block text-white">{{ $bad->nama }}</span>
                                        <small style="color: #ffc9c9;">{{ $bad->asrama }} - Angk. {{ $bad->angkatan }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-danger rounded-pill px-3 shadow-sm border border-light">{{ $bad->violations_count }} Kasus</span>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="bi bi-emoji-smile fs-1 text-success mb-2"></i>
                            <p class="mb-0 fw-bold text-success">Alhamdulillah!</p>
                            <small class="text-white-50">Tidak ada pelanggaran tercatat bulan ini.</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 h-100 shadow-sm glass-card" style="background: rgba(25, 135, 84, 0.15) !important; color: #69db7c;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3 border-bottom border-success pb-2">
                            <i class="bi bi-trophy-fill fs-3 me-2 text-warning"></i>
                            <h5 class="fw-bold mb-0 text-uppercase" style="color: #ffc107;">
                                {{ $judulBest ?? 'Kandidat Teladan' }}
                            </h5>
                        </div>

                        @if(isset($bestCandidates) && $bestCandidates->count() > 0)
                        <div class="list-group list-group-flush rounded">
                            @foreach($bestCandidates as $index => $winner)
                            <a href="{{ route('santri.show', $winner->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-transparent border-bottom border-secondary text-white px-2 py-2 text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <span class="badge rounded-pill me-3 {{ $index==0 ? 'bg-warning text-dark' : ($index==1 ? 'bg-secondary' : 'bg-brown') }}" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <span class="fw-bold d-block">{{ $winner->nama }}</span>
                                        <small class="text-secondary" style="color: #cbd5e1 !important;">{{ $winner->asrama }} - Angk. {{ $winner->angkatan }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-success rounded-pill px-3 shadow-sm">{{ $winner->violations_count }} Poin</span>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <p class="small text-secondary mb-0 text-center py-3">Belum ada data kandidat.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card glass-card h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-info mb-4 fw-bold">➕ Input Data Santri</h5>

                        <form action="{{ route('santri.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="text-secondary small">NIS</label>
                                <input type="text" name="nis" class="form-control bg-dark border-secondary text-white" placeholder="12345" required>
                            </div>
                            <div class="mb-3">
                                <label class="text-secondary small">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control bg-dark border-secondary text-white" placeholder="Nama Santri" required>
                            </div>
                            <div class="mb-3">
                                <label class="text-secondary small">Asal Kota</label>
                                <input type="text" name="asal" class="form-control bg-dark border-secondary text-white" placeholder="Kota Asal" required>
                            </div>
                            <div class="mb-3">
                                <label class="text-secondary small">Angkatan</label>
                                <input type="text" name="angkatan" class="form-control bg-dark border-secondary text-white" placeholder="Angakatam ke - (Angka)" required>
                            </div>
                            <div class="mb-4">
                                <label class="text-secondary small">Asrama</label>
                                <input type="text" name="asrama" class="form-control bg-dark border-secondary text-white" placeholder="Nama Asrama">
                            </div>

                            <button type="submit" class="btn btn-gradient w-100 py-2 fw-bold">Simpan Data</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card glass-card h-100">
                    <div class="card-body p-4">

                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                            <h5 class="card-title text-warning mb-0 fw-bold">📋 Data Santri</h5>

                            <form action="{{ route('santri.index') }}" method="GET" class="d-flex gap-2 flex-grow-1 justify-content-end align-items-center">

                                <select name="filter_angkatan" class="form-select form-select-sm bg-dark text-white border-secondary" style="max-width: 140px;" onchange="this.form.submit()">
                                    <option value="">Semua Angk.</option>
                                    @foreach($dataAngkatan as $angk)
                                    <option value="{{ $angk }}" {{ request('filter_angkatan') == $angk ? 'selected' : '' }}>
                                        Angkatan {{ $angk }}
                                    </option>
                                    @endforeach
                                </select>

                                <div class="input-group input-group-sm" style="max-width: 200px;">
                                    <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Cari Nama/NIS..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-custom align-middle">
                                <thead class="border-bottom border-secondary">
                                    <tr class="text-secondary text-uppercase small text-center">
                                        <th>NIS</th>
                                        <th class="text-start">NAMA</th>
                                        <th>ASAL</th>
                                        <th>ANGKATAN</th>
                                        <th>ASRAMA</th>
                                        <th>CATATAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($santris as $s)
                                    <tr class="border-bottom border-secondary">
                                        <td class="fw-bold text-info text-center">{{ $s->nis }}</td>

                                        <td class="text-start">
                                            <div class="fw-bold">{{ $s->nama }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark border border-secondary">{{ $s->asal }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark border border-secondary">Angk. {{ $s->angkatan }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark border border-secondary text-warning">{{ $s->asrama }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('santri.show', $s->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">Detail</a>
                                                <a href="{{ route('santri.edit', $s->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">Edit</a>
                                                <form action="{{ route('santri.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-secondary">
                                            <i class="bi bi-search fs-1 d-block mb-3 opacity-50"></i>
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
    </div>

</body>

</html>