<?php
session_start();

require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user']['id_user'])) {
    header('Location: login.php');
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$pesan = '';
$jenis_pesan = 'success';
$id_user = (int) $_SESSION['user']['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['masuk'])) {
            $id_kendaraan = filter_input(INPUT_POST, 'id_kendaraan', FILTER_VALIDATE_INT);
            $id_area = filter_input(INPUT_POST, 'id_area', FILTER_VALIDATE_INT);

            if (!$id_kendaraan || !$id_area) {
                throw new RuntimeException('Pilih kendaraan dan area parkir yang valid.');
            }

            $koneksi->begin_transaction();

            $stmt = mysqli_prepare($koneksi, "
                SELECT k.id_kendaraan, t.id_tarif
                FROM tb_kendaraan k
                INNER JOIN tb_tarif t ON t.jenis_kendaraan = k.jenis_kendaraan
                WHERE k.id_kendaraan = ?
                LIMIT 1
                FOR UPDATE
            ");
            mysqli_stmt_bind_param($stmt, 'i', $id_kendaraan);
            mysqli_stmt_execute($stmt);
            $kendaraan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

            if (!$kendaraan) {
                throw new RuntimeException('Kendaraan atau tarif untuk jenis tersebut tidak ditemukan.');
            }

            $stmt = mysqli_prepare($koneksi, "
                SELECT id_parkir
                FROM tb_transaksi
                WHERE id_kendaraan = ? AND status = 'masuk'
                LIMIT 1
                FOR UPDATE
            ");
            mysqli_stmt_bind_param($stmt, 'i', $id_kendaraan);
            mysqli_stmt_execute($stmt);

            if (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))) {
                throw new RuntimeException('Kendaraan ini masih tercatat berada di area parkir.');
            }

            $stmt = mysqli_prepare($koneksi, '
                UPDATE tb_area_parkir
                SET terisi = terisi + 1
                WHERE id_area = ? AND terisi < kapasitas
            ');
            mysqli_stmt_bind_param($stmt, 'i', $id_area);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) !== 1) {
                throw new RuntimeException('Area parkir penuh atau tidak ditemukan.');
            }

            $id_tarif = (int) $kendaraan['id_tarif'];
            $stmt = mysqli_prepare($koneksi, "
                INSERT INTO tb_transaksi
                    (id_kendaraan, waktu_masuk, id_tarif, status, id_user, id_area)
                VALUES (?, NOW(), ?, 'masuk', ?, ?)
            ");
            mysqli_stmt_bind_param($stmt, 'iiii', $id_kendaraan, $id_tarif, $id_user, $id_area);
            mysqli_stmt_execute($stmt);

            $koneksi->commit();
            $pesan = 'Kendaraan berhasil dicatat masuk.';
        } elseif (isset($_POST['keluar'])) {
            $id_parkir = filter_input(INPUT_POST, 'id_parkir', FILTER_VALIDATE_INT);

            if (!$id_parkir) {
                throw new RuntimeException('Transaksi parkir tidak valid.');
            }

            $koneksi->begin_transaction();

            $stmt = mysqli_prepare($koneksi, "
                SELECT tr.id_parkir, tr.id_area, tr.waktu_masuk, tf.tarif_per_jam
                FROM tb_transaksi tr
                INNER JOIN tb_tarif tf ON tf.id_tarif = tr.id_tarif
                WHERE tr.id_parkir = ? AND tr.status = 'masuk'
                FOR UPDATE
            ");
            mysqli_stmt_bind_param($stmt, 'i', $id_parkir);
            mysqli_stmt_execute($stmt);
            $transaksi = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

            if (!$transaksi) {
                throw new RuntimeException('Transaksi aktif tidak ditemukan.');
            }

            $durasi_jam = max(1, (int) ceil((time() - strtotime($transaksi['waktu_masuk'])) / 3600));
            $biaya_total = $durasi_jam * (float) $transaksi['tarif_per_jam'];

            $stmt = mysqli_prepare($koneksi, "
                UPDATE tb_transaksi
                SET waktu_keluar = NOW(), durasi_jam = ?, biaya_total = ?, status = 'keluar'
                WHERE id_parkir = ? AND status = 'masuk'
            ");
            mysqli_stmt_bind_param($stmt, 'idi', $durasi_jam, $biaya_total, $id_parkir);
            mysqli_stmt_execute($stmt);

            $stmt = mysqli_prepare($koneksi, '
                UPDATE tb_area_parkir
                SET terisi = GREATEST(terisi - 1, 0)
                WHERE id_area = ?
            ');
            mysqli_stmt_bind_param($stmt, 'i', $transaksi['id_area']);
            mysqli_stmt_execute($stmt);

            $koneksi->commit();
            $pesan = 'Kendaraan berhasil keluar. Total biaya: Rp ' . number_format($biaya_total, 0, ',', '.');
        }
    } catch (Throwable $error) {
        if ($koneksi->thread_id && $koneksi->errno === 0) {
            $koneksi->rollback();
        } elseif (isset($koneksi)) {
            try {
                $koneksi->rollback();
            } catch (Throwable $ignored) {
            }
        }

        $jenis_pesan = 'danger';
        $pesan = $error instanceof RuntimeException
            ? $error->getMessage()
            : 'Transaksi gagal diproses. Periksa data dan koneksi database.';
    }
}

$kendaraan_tersedia = mysqli_query($koneksi, "
    SELECT k.id_kendaraan, k.plat_nomor, k.jenis_kendaraan, k.pemilik
    FROM tb_kendaraan k
    INNER JOIN tb_tarif t ON t.jenis_kendaraan = k.jenis_kendaraan
    WHERE NOT EXISTS (
        SELECT 1 FROM tb_transaksi tr
        WHERE tr.id_kendaraan = k.id_kendaraan AND tr.status = 'masuk'
    )
    ORDER BY k.plat_nomor
");
$area_tersedia = mysqli_query($koneksi, '
    SELECT id_area, nama_area, kapasitas, terisi
    FROM tb_area_parkir
    WHERE terisi < kapasitas
    ORDER BY nama_area
');
$transaksi_aktif = mysqli_query($koneksi, "
    SELECT tr.id_parkir, tr.waktu_masuk, k.plat_nomor, k.jenis_kendaraan,
           k.pemilik, a.nama_area, tf.tarif_per_jam
    FROM tb_transaksi tr
    INNER JOIN tb_kendaraan k ON k.id_kendaraan = tr.id_kendaraan
    INNER JOIN tb_area_parkir a ON a.id_area = tr.id_area
    INNER JOIN tb_tarif tf ON tf.id_tarif = tr.id_tarif
    WHERE tr.status = 'masuk'
    ORDER BY tr.waktu_masuk DESC
");
$riwayat = mysqli_query($koneksi, "
    SELECT tr.id_parkir, tr.waktu_masuk, tr.waktu_keluar, tr.durasi_jam,
           tr.biaya_total, k.plat_nomor, k.jenis_kendaraan, a.nama_area
    FROM tb_transaksi tr
    INNER JOIN tb_kendaraan k ON k.id_kendaraan = tr.id_kendaraan
    INNER JOIN tb_area_parkir a ON a.id_area = tr.id_area
    WHERE tr.status = 'keluar'
    ORDER BY tr.waktu_keluar DESC
    LIMIT 20
");

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaksi Parkir</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 bg-primary sidebar p-3 min-vh-100">
            <h4 class="text-white font-weight-bold">Parkir Ku</h4>
            <hr>
            <a href="dashboard_<?= h($_SESSION['user']['role'] ?? 'petugas'); ?>.php" class="btn btn-primary btn-block text-left text-white">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
            <a href="transaksi.php" class="btn btn-primary btn-block text-left text-white">
                <i class="fas fa-ticket mr-2"></i> Transaksi
            </a>
        </nav>

        <main class="col-md-9 col-lg-10 px-4 py-4">
            <h2 class="mb-4"><i class="fas fa-ticket mr-2"></i>Transaksi Parkir</h2>

            <?php if ($pesan !== ''): ?>
                <div class="alert alert-<?= h($jenis_pesan); ?>" role="alert"><?= h($pesan); ?></div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-header font-weight-bold">Catat Kendaraan Masuk</div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-row align-items-end">
                            <div class="form-group col-md-5">
                                <label for="id_kendaraan">Kendaraan</label>
                                <select class="form-control" id="id_kendaraan" name="id_kendaraan" required>
                                    <option value="">Pilih kendaraan</option>
                                    <?php while ($kendaraan = mysqli_fetch_assoc($kendaraan_tersedia)): ?>
                                        <option value="<?= (int) $kendaraan['id_kendaraan']; ?>">
                                            <?= h($kendaraan['plat_nomor']); ?> - <?= h(ucfirst($kendaraan['jenis_kendaraan'])); ?> (<?= h($kendaraan['pemilik']); ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="id_area">Area parkir</label>
                                <select class="form-control" id="id_area" name="id_area" required>
                                    <option value="">Pilih area</option>
                                    <?php while ($area = mysqli_fetch_assoc($area_tersedia)): ?>
                                        <option value="<?= (int) $area['id_area']; ?>">
                                            <?= h($area['nama_area']); ?> (<?= (int) $area['kapasitas'] - (int) $area['terisi']; ?> slot tersedia)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="submit" name="masuk" class="btn btn-primary btn-block">
                                    <i class="fas fa-right-to-bracket mr-1"></i> Masuk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header font-weight-bold">Kendaraan Sedang Parkir</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-dark">
                            <tr><th>Plat Nomor</th><th>Jenis</th><th>Pemilik</th><th>Area</th><th>Waktu Masuk</th><th>Tarif/Jam</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <?php if (mysqli_num_rows($transaksi_aktif) === 0): ?>
                            <tr><td colspan="7" class="text-center text-muted">Tidak ada kendaraan yang sedang parkir.</td></tr>
                        <?php else: ?>
                            <?php while ($transaksi = mysqli_fetch_assoc($transaksi_aktif)): ?>
                                <tr>
                                    <td><?= h($transaksi['plat_nomor']); ?></td>
                                    <td><?= h(ucfirst($transaksi['jenis_kendaraan'])); ?></td>
                                    <td><?= h($transaksi['pemilik']); ?></td>
                                    <td><?= h($transaksi['nama_area']); ?></td>
                                    <td><?= h($transaksi['waktu_masuk']); ?></td>
                                    <td>Rp <?= number_format((float) $transaksi['tarif_per_jam'], 0, ',', '.'); ?></td>
                                    <td>
                                        <form method="post" onsubmit="return confirm('Catat kendaraan ini keluar?')">
                                            <input type="hidden" name="id_parkir" value="<?= (int) $transaksi['id_parkir']; ?>">
                                            <button type="submit" name="keluar" class="btn btn-success btn-sm">
                                                <i class="fas fa-right-from-bracket mr-1"></i> Keluar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header font-weight-bold">Riwayat Keluar Terakhir</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>Plat Nomor</th><th>Jenis</th><th>Area</th><th>Masuk</th><th>Keluar</th><th>Durasi</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                        <?php if (mysqli_num_rows($riwayat) === 0): ?>
                            <tr><td colspan="7" class="text-center text-muted">Belum ada riwayat transaksi.</td></tr>
                        <?php else: ?>
                            <?php while ($transaksi = mysqli_fetch_assoc($riwayat)): ?>
                                <tr>
                                    <td><?= h($transaksi['plat_nomor']); ?></td>
                                    <td><?= h(ucfirst($transaksi['jenis_kendaraan'])); ?></td>
                                    <td><?= h($transaksi['nama_area']); ?></td>
                                    <td><?= h($transaksi['waktu_masuk']); ?></td>
                                    <td><?= h($transaksi['waktu_keluar']); ?></td>
                                    <td><?= (int) $transaksi['durasi_jam']; ?> jam</td>
                                    <td>Rp <?= number_format((float) $transaksi['biaya_total'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
<?php
include "../koneksi.php";

/* TAMBAH */
if (isset($_POST['tambah'])) {

    $no_polisi = strtoupper(trim($_POST['no_polisi']));
    $jenis = $_POST['jenis_kendaraan'];
    $pemilik = trim($_POST['nama_pemilik']);

    $stmt = mysqli_prepare($conn, "
        INSERT INTO tb_kendaraan
        (no_polisi, jenis_kendaraan, nama_pemilik)
        VALUES (?, ?, ?)
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $no_polisi,
        $jenis,
        $pemilik
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Kendaraan berhasil ditambahkan');
            location='kendaraan.php';
        </script>";
        exit;
    }

    echo "<script>
        alert('Nomor polisi sudah terdaftar');
    </script>";
}


/* EDIT */
if (isset($_POST['edit'])) {

    $id = $_POST['id_kendaraan'];
    $no_polisi = strtoupper(trim($_POST['no_polisi']));
    $jenis = $_POST['jenis_kendaraan'];
    $pemilik = trim($_POST['nama_pemilik']);

    $stmt = mysqli_prepare($conn, "
        UPDATE tb_kendaraan
        SET no_polisi = ?,
            jenis_kendaraan = ?,
            nama_pemilik = ?
        WHERE id_kendaraan = ?
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $no_polisi,
        $jenis,
        $pemilik,
        $id
    );

    mysqli_stmt_execute($stmt);

    header("Location: kendaraan.php");
    exit;
}


/* HAPUS */
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM tb_kendaraan
        WHERE id_kendaraan = '$id'
    ");

    header("Location: kendaraan.php");
    exit;
}


$data = mysqli_query($conn, "
    SELECT *
    FROM tb_kendaraan
    ORDER BY id_kendaraan DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Kendaraan</title>

    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet"
          href="../assets/css/admin.css">

</head>

<body>

<div class="container-fluid">

<div class="row">

<!-- SIDEBAR -->

<nav class="col-md-3 col-lg-2 bg-primary sidebar p-3 min-vh-100">

    <h4 class="text-white font-weight-bold">
        Parkir Ku
    </h4>

    <hr>

    <a href="dashboard_owner.php"
       class="btn btn-primary btn-block text-left text-white">

        <i class="fas fa-home mr-2"></i>
        Dashboard

    </a>

    <a href="kendaraan.php"
       class="btn btn-primary btn-block text-left text-white">

        <i class="fas fa-car mr-2"></i>
        Kendaraan

    </a>

    <a href="tarif.php"
       class="btn btn-primary btn-block text-left text-white">

        <i class="fas fa-money-bill mr-2"></i>
        Tarif

    </a>

    <a href="area.php"
       class="btn btn-primary btn-block text-left text-white">

        <i class="fas fa-location-dot mr-2"></i>
        Area

    </a>

    <a href="transaksi.php"
       class="btn btn-primary btn-block text-left text-white">

        <i class="fas fa-ticket mr-2"></i>
        Transaksi

    </a>

</nav>


<!-- CONTENT -->

<main class="col-md-9 col-lg-10 px-4 py-4">

<div class="d-flex justify-content-between mb-4">

    <h2>
        <i class="fas fa-car"></i>
        Data Kendaraan
    </h2>

    <button class="btn btn-primary"
            data-toggle="modal"
            data-target="#modalTambah">

        <i class="fas fa-plus"></i>
        Tambah Kendaraan

    </button>

</div>


<div class="card shadow-sm">

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="thead-dark">

<tr>

    <th>No</th>
    <th>No Polisi</th>
    <th>Jenis</th>
    <th>Pemilik</th>
    <th>Aksi</th>

</tr>

</thead>

<tbody>

<?php
$no = 1;

while ($row = mysqli_fetch_assoc($data)):
?>

<tr>

<td><?= $no++; ?></td>

<td>
    <strong>
        <?= htmlspecialchars($row['no_polisi']); ?>
    </strong>
</td>

<td>
    <?= ucfirst($row['jenis_kendaraan']); ?>
</td>

<td>
    <?= htmlspecialchars($row['nama_pemilik']); ?>
</td>

<td>

<a href="?hapus=<?= $row['id_kendaraan']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Hapus kendaraan ini?')">

    <i class="fas fa-trash"></i>

</a>

<button class="btn btn-warning btn-sm"
        data-toggle="modal"
        data-target="#edit<?= $row['id_kendaraan']; ?>">

    <i class="fas fa-edit"></i>

</button>

</td>

</tr>


<!-- MODAL EDIT -->

<div class="modal fade"
     id="edit<?= $row['id_kendaraan']; ?>">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<div class="modal-header bg-warning">

<h5>Edit Kendaraan</h5>

<button type="button"
        class="close"
        data-dismiss="modal">

&times;

</button>

</div>

<div class="modal-body">

<input type="hidden"
       name="id_kendaraan"
       value="<?= $row['id_kendaraan']; ?>">

<div class="form-group">

<label>No Polisi</label>

<input type="text"
       name="no_polisi"
       class="form-control"
       value="<?= htmlspecialchars($row['no_polisi']); ?>"
       required>

</div>

<div class="form-group">

<label>Jenis Kendaraan</label>

<select name="jenis_kendaraan"
        class="form-control">

<option value="motor"
<?= $row['jenis_kendaraan']=='motor'?'selected':''; ?>>
Motor
</option>

<option value="mobil"
<?= $row['jenis_kendaraan']=='mobil'?'selected':''; ?>>
Mobil
</option>

<option value="lainnya"
<?= $row['jenis_kendaraan']=='lainnya'?'selected':''; ?>>
Lainnya
</option>

</select>

</div>

<div class="form-group">

<label>Nama Pemilik</label>

<input type="text"
       name="nama_pemilik"
       class="form-control"
       value="<?= htmlspecialchars($row['nama_pemilik']); ?>">

</div>

</div>

<div class="modal-footer">

<button type="button"
        class="btn btn-secondary"
        data-dismiss="modal">

Batal

</button>

<button type="submit"
        name="edit"
        class="btn btn-warning">

Simpan

</button>

</div>

</form>

</div>
</div>
</div>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>
</div>

</main>

</div>
</div>


<!-- MODAL TAMBAH -->

<div class="modal fade" id="modalTambah">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<div class="modal-header bg-primary text-white">

<h5>
Tambah Kendaraan
</h5>

<button type="button"
        class="close text-white"
        data-dismiss="modal">

&times;

</button>

</div>

<div class="modal-body">

<div class="form-group">

<label>No Polisi</label>

<input type="text"
       name="no_polisi"
       class="form-control"
       placeholder="Contoh: BM 1234 AA"
       required>

</div>

<div class="form-group">

<label>Jenis Kendaraan</label>

<select name="jenis_kendaraan"
        class="form-control"
        required>

<option value="motor">
Motor
</option>

<option value="mobil">
Mobil
</option>

<option value="lainnya">
Lainnya
</option>

</select>

</div>

<div class="form-group">

<label>Nama Pemilik</label>

<input type="text"
       name="nama_pemilik"
       class="form-control">

</div>

</div>

<div class="modal-footer">

<button type="button"
        class="btn btn-secondary"
        data-dismiss="modal">

Batal

</button>

<button type="submit"
        name="tambah"
        class="btn btn-primary">

<i class="fas fa-save"></i>
Simpan

</button>

</div>

</form>

</div>
</div>

</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>