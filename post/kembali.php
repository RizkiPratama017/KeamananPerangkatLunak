<?php
session_start();
require_once '../functions.php';
require_once '../logger.php';

if (!isset($_SESSION['id_user'])) {
    logError("Akses kembali post ditolak: pengguna belum login.");
    echo "<script>
        alert('Silakan login terlebih dahulu.');
        window.location.href = '../login.php';
    </script>";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    logError("ID post tidak valid saat proses pengembalian."); 
    echo "<script>
        alert('ID post tidak valid.');
        window.location.href = '../riwayat.php';
    </script>";
    exit;
}

$id_post = (int) $_GET['id'];

if (kembali($id_post) > 0) {
    logActivity("User '$username' mengembalikan post dengan ID $id_post.");
    echo "<script>
        alert('Post berhasil dikembalikan.');
        window.location.href = '../riwayat.php';
    </script>";
} else {
    logError("User '$username' gagal mengembalikan post dengan ID $id_post.");
    echo "<script>
        alert('Gagal mengembalikan post.');
        window.location.href = '../riwayat.php';
    </script>";
}
