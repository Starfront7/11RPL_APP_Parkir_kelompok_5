<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/koneksi.php';

// Create - menambah tarif baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $stmt = mysqli_prepare($koneksi,
        'INSERT INTO tb_tarif (jenis_kendaraan, tarif_per_jam) VALUES (?, ?)');
    mysqli_stmt_bind_param($stmt, 'sd', $_POST['jenis'], $_POST['tarif']);
    mysqli_stmt_execute($stmt);
}

// Read - menampilkan seluruh data tarif
$data = mysqli_query($koneksi, 'SELECT * FROM tb_tarif');

// Update - mengubah tarif berdasarkan id_tarif
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ubah'])) {
    $stmt = mysqli_prepare($koneksi,
        'UPDATE tb_tarif SET tarif_per_jam = ? WHERE id_tarif = ?');
    mysqli_stmt_bind_param($stmt, 'di', $_POST['tarif'], $_POST['id_tarif']);
    mysqli_stmt_execute($stmt);
}

// Delete - menghapus data tarif berdasarkan id_tarif
if (isset($_GET['hapus'])) {
    $stmt = mysqli_prepare($koneksi, 'DELETE FROM tb_tarif WHERE id_tarif = ?');
    mysqli_stmt_bind_param($stmt, 'i', $_GET['hapus']);
    mysqli_stmt_execute($stmt);
}
?>