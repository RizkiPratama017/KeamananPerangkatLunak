<?php
session_start();
require_once '../functions.php';

$id_post = $_GET['id'];

$query = "SELECT * FROM posts WHERE id = '$id_post'";
$post = query($query);

$judul = "hapus";

?>

<?php require_once '../partials/header.php' ?>

<?php
if(hapus($id_post) > 0) {
    echo "<script>
            alert('data berhasil dihapus!');
            document.location.href = '../admin.php';
        </script>";
}
?>

<?php require_once '../partials/footer.php' ?>
