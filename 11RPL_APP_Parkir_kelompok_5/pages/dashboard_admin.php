<?php
session_start();
include "../config/koneksi.php";

/* =====================================================
   DATA DASHBOARD
   ===================================================== */

// Total kendaraan
$q_kendaraan = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM tb_kendaraan"
);

$data_kendaraan = mysqli_fetch_assoc($q_kendaraan);


// Kendaraan yang masih berada di parkiran
$q_masuk = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM tb_transaksi
     WHERE status = 'masuk'"
);

$data_masuk = mysqli_fetch_assoc($q_masuk);


// Kendaraan yang sudah keluar
$q_keluar = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM tb_transaksi
     WHERE status = 'keluar'"
);

$data_keluar = mysqli_fetch_assoc($q_keluar);


// Ambil 5 transaksi terbaru
// Tidak menggunakan id_transaksi
$q_transaksi = mysqli_query(
    $koneksi,
    "SELECT *
     FROM tb_transaksi
     LIMIT 5"
);

?>

<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Parkir Ku</title>


    <!-- Bootstrap 4 -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">


    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


    <style>
        body {
            margin: 0;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }


        /* ==============================
       SIDEBAR
       ============================== */

        .sidebar {
            min-height: 100vh;
            background: #007bff;
        }


        .sidebar-title {
            color: white;
            font-size: 20px;
            font-weight: bold;
            padding: 10px 5px;
        }


        .sidebar hr {
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }


        .menu {
            display: flex;
            align-items: center;

            color: white;

            text-decoration: none !important;

            padding: 10px 12px;

            border-radius: 6px;

            transition: 0.2s;
        }


        .menu:hover {
            background: rgba(255, 255, 255, 0.20);
            color: white;
        }


        .menu.active {
            background: rgba(255, 255, 255, 0.25);
        }


        .menu i {
            width: 25px;
            text-align: center;
            margin-right: 8px;
        }


        /* ==============================
       HEADER
       ============================== */

        .dashboard-title {
            font-size: 25px;
            font-weight: bold;
        }


        /* ==============================
       STATISTIK
       ============================== */

        .stat-card {
            border: none;

            border-radius: 10px;

            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.08);

            transition: 0.2s;
        }


        .stat-card:hover {
            transform: translateY(-3px);
        }


        .stat-title {
            color: #777;
            font-size: 13px;
        }


        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-top: 5px;
        }


        .stat-icon {
            width: 55px;
            height: 55px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 22px;
        }


        .icon-blue {
            background: #e3f2fd;
            color: #007bff;
        }


        .icon-green {
            background: #e8f5e9;
            color: #28a745;
        }


        .icon-red {
            background: #ffebee;
            color: #dc3545;
        }


        /* ==============================
       TRANSAKSI
       ============================== */

        .table-card {
            border: none;

            border-radius: 10px;

            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.08);
        }


        .table-card .card-header {
            background: white;

            border-bottom: 1px solid #eee;
        }


        table th {
            font-size: 13px;
        }


        table td {
            font-size: 13px;
            vertical-align: middle !important;
        }


        /* ==============================
       STATUS
       ============================== */

        .status-masuk {
            background: #28a745;
            color: white;

            padding: 6px 10px;

            border-radius: 5px;

            font-size: 12px;
        }


        .status-keluar {
            background: #dc3545;
            color: white;

            padding: 6px 10px;

            border-radius: 5px;

            font-size: 12px;
        }


        /* ==============================
       RESPONSIVE
       ============================== */

        @media (max-width: 768px) {

            .sidebar {
                min-height: auto;
            }

            .dashboard-title {
                font-size: 21px;
            }

        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">


            <!-- ==================================================
         SIDEBAR
         ================================================== -->

            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3">


                <div class="sidebar-title">

                    <i class="fa-solid fa-square-parking mr-2"></i>

                    Parkir Ku

                </div>


                <hr>


                <ul class="list-unstyled">


                    <!-- DASHBOARD -->

                    <li class="mb-1">

                        <a href="dashboard_admin.php" class="menu active">

                            <i class="fa-solid fa-house"></i>

                            <span>Dashboard</span>

                        </a>

                    </li>


                    <!-- USER -->

                    <li class="mb-1">

                        <a href="user.php" class="menu">

                            <i class="fa-solid fa-users"></i>

                            <span>User</span>

                        </a>

                    </li>


                    <!-- TARIF -->

                    <li class="mb-1">

                        <a href="tarif.php" class="menu">

                            <i class="fa-solid fa-money-bill"></i>

                            <span>Tarif</span>

                        </a>

                    </li>


                    <!-- AREA PARKIR -->

                    <li class="mb-1">

                        <a href="area_parkir.php" class="menu">

                            <i class="fa-solid fa-square-parking"></i>

                            <span>Area Parkir</span>

                        </a>

                    </li>


                    <!-- KENDARAAN -->

                    <li class="mb-1">

                        <a href="kendaraan.php" class="menu">

                            <i class="fa-solid fa-car"></i>

                            <span>Kendaraan</span>

                        </a>

                    </li>


                    <!-- TRANSAKSI -->

                    <li class="mb-1">

                        <a href="transaksi.php" class="menu">

                            <i class="fa-solid fa-receipt"></i>

                            <span>Transaksi</span>

                        </a>

                    </li>


                    <!-- LOGOUT -->

                    <li class="mt-3">

                        <a href="login.php" class="menu">

                            <i class="fa-solid fa-right-from-bracket"></i>

                            <span>Logout</span>

                        </a>

                    </li>


                </ul>


            </nav>



            <!-- ==================================================
         KONTEN
         ================================================== -->

            <main class="col-md-9 ml-sm-auto col-lg-10 px-4 py-4">


                <!-- HEADER -->

                <div class="d-flex
                    justify-content-between
                    align-items-center
                    border-bottom
                    pb-3
                    mb-4">


                    <div>

                        <div class="dashboard-title">

                            Dashboard Utama

                        </div>


                        <small class="text-muted">

                            Sistem Informasi Parkir Ku

                        </small>

                    </div>


                    <div class="text-muted">

                        <i class="fa-regular fa-calendar mr-1"></i>

                        <?php echo date("d-m-Y"); ?>

                    </div>


                </div>



                <!-- ==================================================
             KARTU STATISTIK
             ================================================== -->

                <div class="row">


                    <!-- TOTAL KENDARAAN -->

                    <div class="col-md-6 col-lg-4 mb-4">

                        <div class="card stat-card">

                            <div class="card-body">

                                <div class="d-flex
                                    justify-content-between
                                    align-items-center">


                                    <div>

                                        <div class="stat-title">

                                            Total Kendaraan

                                        </div>


                                        <div class="stat-number">

                                            <?php
                                            echo $data_kendaraan['total'];
                                            ?>

                                        </div>

                                    </div>


                                    <div class="stat-icon icon-blue">

                                        <i class="fa-solid fa-car"></i>

                                    </div>


                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- KENDARAAN MASUK -->

                    <div class="col-md-6 col-lg-4 mb-4">

                        <div class="card stat-card">

                            <div class="card-body">

                                <div class="d-flex
                                    justify-content-between
                                    align-items-center">


                                    <div>

                                        <div class="stat-title">

                                            Kendaraan Masuk

                                        </div>


                                        <div class="stat-number">

                                            <?php
                                            echo $data_masuk['total'];
                                            ?>

                                        </div>

                                    </div>


                                    <div class="stat-icon icon-green">

                                        <i class="fa-solid fa-right-to-bracket"></i>

                                    </div>


                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- KENDARAAN KELUAR -->

                    <div class="col-md-6 col-lg-4 mb-4">

                        <div class="card stat-card">

                            <div class="card-body">

                                <div class="d-flex
                                    justify-content-between
                                    align-items-center">


                                    <div>

                                        <div class="stat-title">

                                            Kendaraan Keluar

                                        </div>


                                        <div class="stat-number">

                                            <?php
                                            echo $data_keluar['total'];
                                            ?>

                                        </div>

                                    </div>


                                    <div class="stat-icon icon-red">

                                        <i class="fa-solid fa-right-from-bracket"></i>

                                    </div>


                                </div>

                            </div>

                        </div>

                    </div>


                </div>



                <!-- ==================================================
             TRANSAKSI TERBARU
             ================================================== -->

                <div class="card table-card">


                    <div class="card-header">


                        <div class="d-flex
                            justify-content-between
                            align-items-center">


                            <h5 class="font-weight-bold mb-0">

                                <i class="fa-solid fa-clock-rotate-left mr-2"></i>

                                Transaksi Terbaru

                            </h5>


                            <a href="transaksi.php" class="btn btn-primary btn-sm">

                                <i class="fa-solid fa-list mr-1"></i>

                                Lihat Semua

                            </a>


                        </div>


                    </div>



                    <div class="card-body">


                        <div class="table-responsive">


                            <table class="table table-hover">


                                <thead class="thead-light">

                                    <tr>

                                        <th>No</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>


                                <tbody>


                                    <?php

                                    $no = 1;


                                    if (
                                        $q_transaksi &&
                                        mysqli_num_rows($q_transaksi) > 0
                                    ) {


                                        while (
                                            $row =
                                            mysqli_fetch_assoc($q_transaksi)
                                        ) {

                                            ?>


                                            <tr>


                                                <td>

                                                    <?php
                                                    echo $no++;
                                                    ?>

                                                </td>


                                                <td>


                                                    <?php

                                                    if (
                                                        isset($row['status']) &&
                                                        $row['status'] == 'masuk'
                                                    ) {

                                                        ?>

                                                        <span class="status-masuk">

                                                            <i class="fa-solid fa-car mr-1"></i>

                                                            Kendaraan Masuk

                                                        </span>


                                                        <?php

                                                    } else {

                                                        ?>

                                                        <span class="status-keluar">

                                                            <i class="fa-solid fa-check mr-1"></i>

                                                            Kendaraan Keluar

                                                        </span>


                                                        <?php

                                                    }

                                                    ?>


                                                </td>


                                            </tr>


                                            <?php

                                        }

                                    } else {

                                        ?>


                                        <tr>

                                            <td colspan="2" class="text-center text-muted py-5">

                                                <i class="fa-solid fa-inbox fa-2x mb-2"></i>

                                                <br>

                                                Belum ada transaksi.

                                            </td>

                                        </tr>


                                        <?php

                                    }

                                    ?>


                                </tbody>

                            </table>


                        </div>


                    </div>

                </div>


            </main>


        </div>

    </div>

</body>

</html>