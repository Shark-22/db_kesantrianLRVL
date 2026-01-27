<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <title>Edit Santri</title>
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

    <div class="card col-md-5 mx-auto shadow-lg">
        <div class="card-header bg-warning text-dark fw-bold text-center">
            ✏️ Edit Data Santri
        </div>
        <div class="card-body p-4">

            <form action="{{ route('santri.update', $santri->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="small text-secondary">NIS</label>
                    <input type="text" name="nis" class="form-control" value="{{ $santri->nis }}" required>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ $santri->nama }}" required>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary">Asal Kota</label>
                    <input type="text" name="asal" class="form-control" value="{{ $santri->asal }}" required>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary">Angkatan</label>
                    <input type="text" name="angkatan" class="form-control" value="{{ $santri->angkatan }}" required>
                </div>
                <div class="mb-4">
                    <label class="small text-secondary">Asrama</label>
                    <input type="text" name="asrama" class="form-control" value="{{ $santri->asrama }}">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning fw-bold">Update Data</button>
                    <a href="{{ route('santri.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>