<?php 
session_start();
require_once 'functions.php';

$posts = getPosts();

if (isset($_GET['id'])) {
    $id_postingan = $_GET['id']; 
    echo "ID Postingan: " . $id_postingan;
} else {
    echo "ID tidak ditemukan di URL.";
    exit;
}

?>

<?php require_once 'partials/header.php' ?>



<?php require_once 'partials/footer.php' ?>