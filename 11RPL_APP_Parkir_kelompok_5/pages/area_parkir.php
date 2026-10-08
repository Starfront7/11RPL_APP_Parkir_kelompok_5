<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/koneksi.php';


/* =========================
   TAMBAH AREA PARKIR
   ========================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {

    $nama_area = $_POST['nama_area'];
    $kapasitas = $_POST['kapasitas'];

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO tb_area_parkir (nama_area, kapasitas)
         VALUES (?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $nama_area,
        $kapasitas
    );

    mysqli_stmt_execute($stmt);

    echo "<script>
            alert('Area parkir berhasil ditambahkan!');
            window.location='area_parkir.php';
          </script>";
    exit;
}


/* =========================
   UBAH AREA PARKIR
   ========================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ubah'])) {

    $id_area = $_POST['id_area'];
    $nama_area = $_POST['nama_area'];
    $kapasitas = $_POST['kapasitas'];

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE tb_area_parkir
         SET nama_area = ?, kapasitas = ?
         WHERE id_area = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sii",
        $nama_area,
        $kapasitas,
        $id_area
    );

    mysqli_stmt_execute($stmt);

    echo "<script>
            alert('Area parkir berhasil diubah!');
            window.location='area_parkir.php';
          </script>";
    exit;
}


/* =========================
   HAPUS AREA PARKIR
   ========================= */

if (isset($_GET['hapus'])) {

    $id_area = $_GET['hapus'];

    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM tb_area_parkir WHERE id_area = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id_area
    );

    mysqli_stmt_execute($stmt);

    echo "<script>
            alert('Area parkir berhasil dihapus!');
            window.location='area_parkir.php';
          </script>";
    exit;
}


/* =========================
   DATA AREA PARKIR
   ========================= */

$data_area = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_area_parkir"
);

?>


<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Area Parkir - Parkir Ku</title>


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
                       class="menu active">

                        <i class="fa-solid fa-square-parking"></i>

                        <span>Area Parkir</span>

                    </a>

                </li>


                <!-- KENDARAAN -->

                <li class="mb-1">

                    <a href="kendaraan.php"
                       class="menu">

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

                        Data Area Parkir

                    </div>


                    <small class="text-muted">

                        Kelola area parkir Parkir Ku

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

                    Tambah Area

                </button>

            </div>



            <!-- TABLE AREA PARKIR -->

            <div class="card content-card">


                <div class="card-header bg-white">

                    <h5 class="font-weight-bold mb-0">

                        <i class="fa-solid fa-square-parking mr-2"></i>

                        Daftar Area Parkir

                    </h5>

                </div>


                <div class="card-body">


                    <div class="table-responsive">


                        <table class="table table-hover">


                            <thead class="thead-light">

                                <tr>

                                    <th>No</th>

                                    <th>Nama Area</th>

                                    <th>Kapasitas</th>

                                    <th>Aksi</th>

                                </tr>

                            </thead>


                            <tbody>


                            <?php

                            $no = 1;

                            if (
                                $data_area &&
                                mysqli_num_rows($data_area) > 0
                            ) {

                                while (
                                    $row =
                                    mysqli_fetch_assoc($data_area)
                                ) {

                            ?>


                                <tr>

                                    <td>

                                        <?php echo $no++; ?>

                                    </td>


                                    <td>

                                        <i class="fa-solid fa-location-dot mr-2"></i>

                                        <?php

                                        echo htmlspecialchars(
                                            $row['nama_area']
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <span class="badge badge-primary">

                                            <?php
                                            echo $row['kapasitas'];
                                            ?>

                                            Slot

                                        </span>

                                    </td>


                                    <td>


                                        <!-- EDIT -->

                                        <button
                                            class="btn btn-warning btn-sm"
                                            data-toggle="modal"
                                            data-target="#modalEdit<?php echo $row['id_area']; ?>">

                                            <i class="fa-solid fa-pen"></i>

                                            Edit

                                        </button>


                                        <!-- HAPUS -->

                                        <a
                                            href="area_parkir.php?hapus=<?php echo $row['id_area']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus area parkir ini?')">

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
                                    id="modalEdit<?php echo $row['id_area']; ?>"
                                    tabindex="-1">

                                    <div class="modal-dialog">

                                        <div class="modal-content">


                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    <i class="fa-solid fa-pen mr-2"></i>

                                                    Edit Area Parkir

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
                                                        name="id_area"
                                                        value="<?php echo $row['id_area']; ?>">


                                                    <div class="form-group">

                                                        <label>
                                                            Nama Area
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="nama_area"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($row['nama_area']); ?>"
                                                            required>

                                                    </div>


                                                    <div class="form-group">

                                                        <label>
                                                            Kapasitas
                                                        </label>

                                                        <input
                                                            type="number"
                                                            name="kapasitas"
                                                            class="form-control"
                                                            value="<?php echo $row['kapasitas']; ?>"
                                                            min="1"
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
                                        colspan="4"
                                        class="text-center text-muted py-5">

                                        <i class="fa-solid fa-square-parking fa-2x mb-2"></i>

                                        <br>

                                        Belum ada data area parkir.

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
     MODAL TAMBAH AREA
     ========================================== -->

<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fa-solid fa-square-parking mr-2"></i>

                    Tambah Area Parkir

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


                    <div class="form-group">

                        <label>

                            Nama Area

                        </label>

                        <input
                            type="text"
                            name="nama_area"
                            class="form-control"
                            placeholder="Contoh: Area A"
                            required>

                    </div>


                    <div class="form-group">

                        <label>

                            Kapasitas

                        </label>

                        <input
                            type="number"
                            name="kapasitas"
                            class="form-control"
                            placeholder="Contoh: 50"
                            min="1"
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