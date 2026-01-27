<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <title>Login Admin</title>
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

        .login-card {
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

    <div class="card login-card shadow-lg p-4">
        <div class="card-body">
            <h3 class="text-center text-white fw-bold mb-4">🕵️ DATABASE KESANTRIAN</h3>
            <h3 class="text-center text-white fw-bold mb-4">🔐 Login Admin</h3>

            @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="text-secondary small">Email Address</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary" placeholder="masukan E-mail" required>
                </div>

                <div class="mb-4">
                    <label class="text-secondary small">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control bg-dark text-white border-secondary" placeholder="masukan password" required>
                        <span class="input-group-text border-secondary" onclick="togglePassword('password', 'toggleIcon')">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Masuk Sistem</button>

                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="text-decoration-none text-secondary small">Belum punya akun? Daftar</a>
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