<?php
session_start();
require_once '../functions.php';

$posts = getPosts();

$judul = "Postingers";

$id_post = $_GET['id'];

$query = "SELECT * FROM posts WHERE id = '$id_post'";
$post = query($query);

$judul = $post[0]['title'];

?>

<?php require_once '../partials/header.php' ?>

<?php
if (isset($_GET['id'])) {
    $id_postingan = $_GET['id'];
    echo "ID Postingan: " . $id_postingan;
} else {
    echo "ID tidak ditemukan di URL.";
    exit;
}
?>

<?php require_once '../partials/footer.php' ?>