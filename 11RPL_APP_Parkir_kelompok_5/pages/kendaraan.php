<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/koneksi.php';


/* =========================
   TAMBAH KENDARAAN
   ========================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {

    $plat = strtoupper($_POST['plat']);
    $jenis = $_POST['jenis'];
    $pemilik = $_POST['pemilik'];

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO tb_kendaraan (plat, jenis, pemilik)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $plat,
        $jenis,
        $pemilik
    );

    mysqli_stmt_execute($stmt);

    echo "<script>
            alert('Kendaraan berhasil ditambahkan!');
            window.location='kendaraan.php';
          </script>";
    exit;
}


/* =========================
   UBAH KENDARAAN
   ========================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ubah'])) {

    $id_kendaraan = $_POST['id_kendaraan'];
    $plat = strtoupper($_POST['plat']);
    $jenis = $_POST['jenis'];
    $pemilik = $_POST['pemilik'];

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE tb_kendaraan
         SET plat = ?, jenis = ?, pemilik = ?
         WHERE id_kendaraan = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $plat,
        $jenis,
        $pemilik,
        $id_kendaraan
    );

    mysqli_stmt_execute($stmt);

    echo "<script>
            alert('Kendaraan berhasil diubah!');
            window.location='kendaraan.php';
          </script>";
    exit;
}


/* =========================
   HAPUS KENDARAAN
   ========================= */

if (isset($_GET['hapus'])) {

    $id_kendaraan = $_GET['hapus'];

    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM tb_kendaraan
         WHERE id_kendaraan = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id_kendaraan
    );

    mysqli_stmt_execute($stmt);

    echo "<script>
            alert('Kendaraan berhasil dihapus!');
            window.location='kendaraan.php';
          </script>";
    exit;
}


/* =========================
   DATA KENDARAAN
   ========================= */

$data_kendaraan = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_kendaraan"
);

?>


<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Data Kendaraan - Parkir Ku</title>


    <!-- BOOTSTRAP -->

    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">


    <!-- FONT AWESOME -->

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


    <style>

        body {
            margin: 0;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }


        /* =========================
           SIDEBAR
           ========================= */

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
            border-top: 1px solid rgba(255,255,255,0.3);
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
            background: rgba(255,255,255,0.20);
            color: white;
        }


        .menu.active {
            background: rgba(255,255,255,0.25);
        }


        .menu i {
            width: 25px;
            text-align: center;
            margin-right: 8px;
        }


        /* =========================
           KONTEN
           ========================= */

        .page-title {
            font-size: 25px;
            font-weight: bold;
        }


        .content-card {
            border: none;
            border-radius: 10px;

            box-shadow:
                0 2px 10px rgba(0,0,0,0.08);
        }


        /* =========================
           TABLE
           ========================= */

        .table th {
            font-size: 13px;
        }


        .table td {
            font-size: 13px;
            vertical-align: middle;
        }


        /* =========================
           TOMBOL
           ========================= */

        .btn-tambah {
            border-radius: 6px;
        }


        /* =========================
           FORM
           ========================= */

        .form-control {
            font-size: 14px;
        }


        /* =========================
           RESPONSIVE
           ========================= */

        @media (max-width: 768px) {

            .sidebar {
                min-height: auto;
            }

            .page-title {
                font-size: 21px;
            }

        }

    </style>

</head>


<body>


<div class="container-fluid">

    <div class="row">


        <!-- ==========================================
             SIDEBAR BIRU
             ========================================== -->

        <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3">


            <div class="sidebar-title">

                <i class="fa-solid fa-square-parking mr-2"></i>

                Parkir Ku

            </div>


            <hr>


            <ul class="list-unstyled">


                <!-- DASHBOARD -->

                <li class="mb-1">

                    <a href="dashboard_admin.php"
                       class="menu">

                        <i class="fa-solid fa-house"></i>

                        <span>Dashboard</span>

                    </a>

                </li>


                <!-- USER -->

                <li class="mb-1">

                    <a href="user.php"
                       class="menu">

                        <i class="fa-solid fa-users"></i>

                        <span>User</span>

                    </a>

                </li>


                <!-- TARIF -->

                <li class="mb-1">

                    <a href="tarif.php"
                       class="menu">

                        <i class="fa-solid fa-money-bill"></i>

                        <span>Tarif</span>

                    </a>

                </li>


                <!-- AREA PARKIR -->

                <li class="mb-1">

                    <a href="area_parkir.php"
                       class="menu">

                        <i class="fa-solid fa-square-parking"></i>

                        <span>Area Parkir</span>

                    </a>

                </li>


                <!-- KENDARAAN -->

                <li class="mb-1">

                    <a href="kendaraan.php"
                       class="menu active">

                        <i class="fa-solid fa-car"></i>

                        <span>Kendaraan</span>

                    </a>

                </li>


                <!-- TRANSAKSI -->

                <li class="mb-1">

                    <a href="transaksi.php"
                       class="menu">

                        <i class="fa-solid fa-receipt"></i>

                        <span>Transaksi</span>

                    </a>

                </li>


                <!-- LOGOUT -->

                <li class="mt-3">

                    <a href="login.php"
                       class="menu">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        <span>Logout</span>

                    </a>

                </li>


            </ul>

        </nav>



        <!-- ==========================================
             KONTEN UTAMA
             ========================================== -->

        <main class="col-md-9 ml-sm-auto col-lg-10 px-4 py-4">


            <!-- HEADER -->

            <div class="d-flex
                        justify-content-between
                        align-items-center
                        border-bottom
                        pb-3
                        mb-4">


                <div>

                    <div class="page-title">

                        Data Kendaraan

                    </div>


                    <small class="text-muted">

                        Kelola data kendaraan Parkir Ku

                    </small>

                </div>


                <div class="text-muted">

                    <i class="fa-regular fa-calendar mr-1"></i>

                    <?php echo date("d-m-Y"); ?>

                </div>


            </div>



            <!-- TOMBOL TAMBAH -->

            <div class="mb-3">

                <button
                    class="btn btn-primary btn-tambah"
                    data-toggle="modal"
                    data-target="#modalTambah">

                    <i class="fa-solid fa-plus mr-1"></i>

                    Tambah Kendaraan

                </button>

            </div>



            <!-- TABLE KENDARAAN -->

            <div class="card content-card">


                <div class="card-header bg-white">

                    <h5 class="font-weight-bold mb-0">

                        <i class="fa-solid fa-car mr-2"></i>

                        Daftar Kendaraan

                    </h5>

                </div>


                <div class="card-body">


                    <div class="table-responsive">


                        <table class="table table-hover">


                            <thead class="thead-light">

                                <tr>

                                    <th>No</th>

                                    <th>Plat Nomor</th>

                                    <th>Jenis Kendaraan</th>

                                    <th>Pemilik</th>

                                    <th>Aksi</th>

                                </tr>

                            </thead>


                            <tbody>


                            <?php

                            $no = 1;

                            if (
                                $data_kendaraan &&
                                mysqli_num_rows($data_kendaraan) > 0
                            ) {

                                while (
                                    $row =
                                    mysqli_fetch_assoc($data_kendaraan)
                                ) {

                            ?>


                                <tr>

                                    <td>

                                        <?php echo $no++; ?>

                                    </td>


                                    <td>

                                        <strong>

                                            <i class="fa-solid fa-id-card mr-2"></i>

                                            <?php

                                            echo htmlspecialchars(
                                                $row['plat']
                                            );

                                            ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <?php

                                        if ($row['jenis'] == 'Motor') {

                                            echo '<span class="badge badge-info">
                                                    <i class="fa-solid fa-motorcycle mr-1"></i>
                                                    Motor
                                                  </span>';

                                        } elseif ($row['jenis'] == 'Mobil') {

                                            echo '<span class="badge badge-primary">
                                                    <i class="fa-solid fa-car mr-1"></i>
                                                    Mobil
                                                  </span>';

                                        } elseif ($row['jenis'] == 'Truk') {

                                            echo '<span class="badge badge-warning">
                                                    <i class="fa-solid fa-truck mr-1"></i>
                                                    Truk
                                                  </span>';

                                        } else {

                                            echo '<span class="badge badge-secondary">'
                                                    . htmlspecialchars($row['jenis']) .
                                                 '</span>';

                                        }

                                        ?>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $row['pemilik']
                                        );

                                        ?>

                                    </td>


                                    <td>


                                        <!-- EDIT -->

                                        <button
                                            class="btn btn-warning btn-sm"
                                            data-toggle="modal"
                                            data-target="#modalEdit<?php echo $row['id_kendaraan']; ?>">

                                            <i class="fa-solid fa-pen"></i>

                                            Edit

                                        </button>


                                        <!-- HAPUS -->

                                        <a
                                            href="kendaraan.php?hapus=<?php echo $row['id_kendaraan']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus kendaraan ini?')">

                                            <i class="fa-solid fa-trash"></i>

                                            Hapus

                                        </a>


                                    </td>

                                </tr>



                                <!-- ==========================================
                                     MODAL EDIT
                                     ========================================== -->

                                <div
                                    class="modal fade"
                                    id="modalEdit<?php echo $row['id_kendaraan']; ?>"
                                    tabindex="-1">

                                    <div class="modal-dialog">

                                        <div class="modal-content">


                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    <i class="fa-solid fa-pen mr-2"></i>

                                                    Edit Kendaraan

                                                </h5>


                                                <button
                                                    type="button"
                                                    class="close"
                                                    data-dismiss="modal">

                                                    <span>&times;</span>

                                                </button>

                                            </div>


                                            <form method="POST">


                                                <div class="modal-body">


                                                    <input
                                                        type="hidden"
                                                        name="id_kendaraan"
                                                        value="<?php echo $row['id_kendaraan']; ?>">


                                                    <div class="form-group">

                                                        <label>

                                                            Plat Nomor

                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="plat"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($row['plat']); ?>"
                                                            required>

                                                    </div>


                                                    <div class="form-group">

                                                        <label>

                                                            Jenis Kendaraan

                                                        </label>

                                                        <select
                                                            name="jenis"
                                                            class="form-control"
                                                            required>

                                                            <option
                                                                value="Motor"
                                                                <?php
                                                                if ($row['jenis'] == 'Motor') {
                                                                    echo 'selected';
                                                                }
                                                                ?>>

                                                                Motor

                                                            </option>

                                                            <option
                                                                value="Mobil"
                                                                <?php
                                                                if ($row['jenis'] == 'Mobil') {
                                                                    echo 'selected';
                                                                }
                                                                ?>>

                                                                Mobil

                                                            </option>

                                                            <option
                                                                value="Truk"
                                                                <?php
                                                                if ($row['jenis'] == 'Truk') {
                                                                    echo 'selected';
                                                                }
                                                                ?>>

                                                                Truk

                                                            </option>

                                                            <option
                                                                value="Bus"
                                                                <?php
                                                                if ($row['jenis'] == 'Bus') {
                                                                    echo 'selected';
                                                                }
                                                                ?>>

                                                                Bus

                                                            </option>

                                                        </select>

                                                    </div>


                                                    <div class="form-group">

                                                        <label>

                                                            Pemilik

                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="pemilik"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($row['pemilik']); ?>"
                                                            required>

                                                    </div>


                                                </div>


                                                <div class="modal-footer">


                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-dismiss="modal">

                                                        Batal

                                                    </button>


                                                    <button
                                                        type="submit"
                                                        name="ubah"
                                                        class="btn btn-warning">

                                                        <i class="fa-solid fa-save mr-1"></i>

                                                        Simpan Perubahan

                                                    </button>


                                                </div>


                                            </form>


                                        </div>

                                    </div>

                                </div>


                            <?php

                                }

                            } else {

                            ?>


                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center text-muted py-5">

                                        <i class="fa-solid fa-car fa-2x mb-2"></i>

                                        <br>

                                        Belum ada data kendaraan.

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



<!-- ==========================================
     MODAL TAMBAH KENDARAAN
     ========================================== -->

<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fa-solid fa-car mr-2"></i>

                    Tambah Kendaraan

                </h5>


                <button
                    type="button"
                    class="close"
                    data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <form method="POST">


                <div class="modal-body">


                    <!-- PLAT -->

                    <div class="form-group">

                        <label>

                            Plat Nomor

                        </label>

                        <input
                            type="text"
                            name="plat"
                            class="form-control"
                            placeholder="Contoh: BP 1234 XX"
                            style="text-transform: uppercase;"
                            required>

                    </div>


                    <!-- JENIS -->

                    <div class="form-group">

                        <label>

                            Jenis Kendaraan

                        </label>

                        <select
                            name="jenis"
                            class="form-control"
                            required>

                            <option value="">
                                -- Pilih Jenis --
                            </option>

                            <option value="Motor">
                                Motor
                            </option>

                            <option value="Mobil">
                                Mobil
                            </option>

                            <option value="Truk">
                                Truk
                            </option>

                            <option value="Bus">
                                Bus
                            </option>

                        </select>

                    </div>


                    <!-- PEMILIK -->

                    <div class="form-group">

                        <label>

                            Nama Pemilik

                        </label>

                        <input
                            type="text"
                            name="pemilik"
                            class="form-control"
                            placeholder="Masukkan nama pemilik"
                            required>

                    </div>


                </div>


                <div class="modal-footer">


                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                        Batal

                    </button>


                    <button
                        type="submit"
                        name="tambah"
                        class="btn btn-primary">

                        <i class="fa-solid fa-save mr-1"></i>

                        Simpan

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>



<!-- JAVASCRIPT -->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


</body>

</html>