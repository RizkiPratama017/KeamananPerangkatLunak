<?php
session_start();
require_once '../functions.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu.');
        window.location.href = '../login.php';
    </script>";
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
        alert('ID post tidak ditemukan.');
        window.location.href = '../riwayat.php';
    </script>";
    exit;
}

$id_post = $_GET['id'];

if (kembali($id_post) > 0) {
    echo "<script>
        alert('Post berhasil dikembalikan.');
        window.location.href = '../riwayat.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal mengembalikan post.');
        window.location.href = '../riwayat.php';
    </script>";
}
