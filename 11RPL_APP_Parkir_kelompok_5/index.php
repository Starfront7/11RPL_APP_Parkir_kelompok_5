<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Parkir - Beranda</title>
    <!-- Memuat Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    body {
        background-color: #f8f9fa;
    }

    .hero-section {
        padding: 100px 0;
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1506521781263-d8422e82f27a?auto=format&fit=crop&w=1920&q=80') no-repeat center center;
        background-size: cover;
        color: white;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
    }

    .feature-card {
        border: none;
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
    }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-parking text-warning me-2"></i>Sistem Parkir
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <!-- Mengarah langsung ke halaman login (ubah 'login.php' sesuai nama file login Anda nantinya) -->
                        <a class="btn btn-warning text-dark fw-semibold px-4" href="pages/login.php">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <header class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">Selamat Datang di Sistem Informasi Parkir</h1>
                    <p class="lead mb-4">Solusi modern untuk pengelolaan data kendaraan, pencatatan masuk dan keluar,
                        serta pemantauan area parkir secara efisien dan terstruktur.</p>
                    <a href="pages/login.php" class="btn btn-warning btn-lg text-dark fw-bold px-5 py-3 shadow">
                        <i class="fas fa-arrow-right-to-bracket me-2"></i> Masuk ke Sistem
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-4">
                <div class="col">
                    <h2 class="fw-bold">Fitur Utama</h2>
                    <p class="text-muted">Kemudahan dalam pengelolaan manajemen parkir</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow-sm p-4 text-center">
                        <div class="card-body">
                            <div class="text-warning mb-3">
                                <i class="fas fa-car fa-3x"></i>
                            </div>
                            <h4 class="card-title fw-bold">Pencatatan Kendaraan</h4>
                            <p class="card-text text-muted">Mencatat nomor polisi, jenis kendaraan, serta waktu masuk
                                dan keluar secara akurat.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow-sm p-4 text-center">
                        <div class="card-body">
                            <div class="text-warning mb-3">
                                <i class="fas fa-shield-halved fa-3x"></i>
                            </div>
                            <h4 class="card-title fw-bold">Keamanan & Kontrol</h4>
                            <p class="card-text text-muted">Akses sistem berbasis login untuk memastikan hanya petugas
                                berwenang yang dapat mengelola data.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow-sm p-4 text-center">
                        <div class="card-body">
                            <div class="text-warning mb-3">
                                <i class="fas fa-chart-line fa-3x"></i>
                            </div>
                            <h4 class="card-title fw-bold">Laporan Terstruktur</h4>
                            <p class="card-text text-muted">Mempermudah rekapitulasi data transaksi dan aktivitas parkir
                                harian maupun bulanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer class="bg-primary text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2026 Sistem Parkir. 11 RPL Kelompok 5</p>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>