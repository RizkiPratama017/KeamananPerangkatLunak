<?php
session_start();
require_once '../functions.php';
require_once '../logger.php';

$id_post = validateGetInt('id', BASE_URL . 'admin.php');

$query = "SELECT * FROM posts WHERE id = '$id_post'";
$post = query($query);

$judul = "hapus";
$username = $_SESSION['username'] ?? 'Unknown';
?>

<?php require_once '../partials/header.php' ?>

<?php
if (hapus($id_post) > 0) {
    logActivity("User '$username' menghapus post dengan ID $id_post.");
    echo "<script>
            alert('data berhasil dihapus!');
            document.location.href = '../admin.php';
        </script>";
} else {
    logError("User '$username' gagal menghapus post dengan ID $id_post.");
}
?>


<?php require_once '../partials/footer.php' ?>
