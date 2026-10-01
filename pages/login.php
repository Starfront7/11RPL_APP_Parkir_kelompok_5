<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Parkir</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5 col-xl-4">
                <div class="card shadow-lg border-3 p-3">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-dark">Aplikasi Parkir</h4>
                        </div>

                        <form action="../modules/proses_login.php" method="POST">
                            <div class="mb-3">
                                <label for="username"
                                    class="form-label text-secondary small fw-semibold">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-person text-muted"></i></span>
                                    <input type="text" id="username" name="username"
                                        class="form-control bg-light border-start-0 ps-0"
                                        placeholder="Masukkan username" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password"
                                    class="form-label text-secondary small fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-lock text-muted"></i></span>
                                    <input type="password" id="password" name="password"
                                        class="form-control bg-light border-start-0 ps-0"
                                        placeholder="Masukkan password" required>
                                </div>
                            </div>

                            <button type="submit"
                                class="btn btn-primary bg-gradient w-100 py-2 fw-semibold shadow-sm">Masuk</button>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-4 text-muted small">
                    &copy; 2026 Aplikasi Parkir. Kelompok 5 11 RPL
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>