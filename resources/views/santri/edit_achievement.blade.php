<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <title>Edit Prestasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
        }

        .card {
            background-color: #1e293b;
            border: 1px solid #334155;
        }

        .form-control,
        .form-select {
            background-color: #0f172a;
            border-color: #334155;
            color: white;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #0f172a;
            border-color: #198754;
            /* Warna hijau success */
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
            color: white;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100 p-4">

    <div class="card col-md-5 mx-auto shadow-lg border-success">
        <div class="card-header bg-success text-white fw-bold text-center">
            🏆 Edit Data Prestasi
        </div>
        <div class="card-body p-4">

            <form action="{{ route('achievement.update', $achievement->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="small text-secondary">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $achievement->tanggal }}" required>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary">Nama Prestasi</label>
                    <input type="text" name="nama_prestasi" class="form-control" value="{{ $achievement->nama_prestasi }}" required>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary">Tingkat</label>
                    <select name="tingkat" class="form-select" required>
                        <option value="Sekolah" {{ $achievement->tingkat == 'Sekolah' ? 'selected' : '' }}>🏫 Sekolah</option>
                        <option value="Kecamatan" {{ $achievement->tingkat == 'Kecamatan' ? 'selected' : '' }}>🏘️ Kecamatan</option>
                        <option value="Kabupaten" {{ $achievement->tingkat == 'Kabupaten' ? 'selected' : '' }}>🏛️ Kabupaten</option>
                        <option value="Provinsi" {{ $achievement->tingkat == 'Provinsi' ? 'selected' : '' }}>⭐ Provinsi</option>
                        <option value="Nasional" {{ $achievement->tingkat == 'Nasional' ? 'selected' : '' }}>🏰 Nasional</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="small text-secondary">Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ $achievement->keterangan }}</textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success fw-bold">Simpan Perubahan</button>
                    <a href="{{ route('santri.show', $achievement->santri_id) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>