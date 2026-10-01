<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/koneksi.php';


$username = $_POST['username'];
$password = $_POST['password'];

$stmt = mysqli_prepare($koneksi,
    'SELECT * FROM tb_user WHERE username = ? AND password = ? AND status_aktif = 1');
mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($hasil)) {
    session_start();
    $_SESSION['user'] = $user;
    header('Location: ../pages/dashboard_' . $user['role'] . '.php');
} else {
    echo 'Username atau password salah.';
}
?>