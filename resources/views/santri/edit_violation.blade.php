<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <title>Edit Pelanggaran</title>
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
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100 p-4">

    <div class="card col-md-5 mx-auto shadow-lg border-danger">
        <div class="card-header bg-danger text-white fw-bold text-center">
            ⚠️ Edit Data Pelanggaran
        </div>
        <div class="card-body p-4">

            <form action="{{ route('violation.update', $violation->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="small text-secondary">Tanggal Kejadian</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $violation->tanggal }}" required>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary">Jenis Pelanggaran</label>
                    <input type="text" name="jenis_pelanggaran" class="form-control" value="{{ $violation->jenis_pelanggaran }}" required>
                </div>

                <div class="mb-3">
                    <label class="small text-secondary">Tingkat</label>
                    <select name="tingkat" class="form-select" required>
                        <option value="ringan" {{ $violation->tingkat == 'ringan' ? 'selected' : '' }}>🟡 Ringan</option>
                        <option value="sedang" {{ $violation->tingkat == 'sedang' ? 'selected' : '' }}>🟠 Sedang</option>
                        <option value="berat" {{ $violation->tingkat == 'berat' ? 'selected' : '' }}>🔴 Berat</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="small text-secondary">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ $violation->keterangan }}</textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger fw-bold">Simpan Perubahan</button>
                    <a href="{{ route('santri.show', $violation->santri_id) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>