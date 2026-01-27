<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <title>Daftar Admin Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            background-color: #1e293b;
            border: 1px solid #334155;
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
        }

        .input-group-text {
            background-color: #0f172a;
            border-color: #6c757d;
            color: #6c757d;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="card register-card shadow-lg p-4">
        <div class="card-body">
            <h3 class="text-center text-white fw-bold mb-4">📝 Daftar Admin</h3>

            @if($errors->any())
            <div class="alert alert-danger small">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="text-secondary small">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control bg-dark text-white border-secondary" required>
                </div>
                <div class="mb-3">
                    <label class="text-secondary small">Email Address</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary" required>
                </div>

                <div class="mb-3">
                    <label class="text-secondary small">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="regPass" class="form-control bg-dark text-white border-secondary" required>
                        <span class="input-group-text border-secondary" onclick="togglePassword('regPass', 'iconRegPass')">
                            <i class="bi bi-eye-slash" id="iconRegPass"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-secondary small">Ulangi Password</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="regConfirm" class="form-control bg-dark text-white border-secondary" required>
                        <span class="input-group-text border-secondary" onclick="togglePassword('regConfirm', 'iconRegConfirm')">
                            <i class="bi bi-eye-slash" id="iconRegConfirm"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold py-2 mb-3">Daftar Sekarang</button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none text-secondary small">Sudah punya akun? Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        }
    </script>

</body>

</html>