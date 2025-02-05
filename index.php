<?php
session_start();

require_once 'functions.php';

$posts = getPosts();

$judul = "Postingers";
$nama = "John Doe";

?>

<?php require_once 'partials/header.php' ?>

<div class="container">
    <div class="jumbotron mt-5">
        <h1 class="display-4">Selamat Datang di Website Saya!</h1>
        <p class="lead">Halo, nama saya <?= $nama; ?></p>
        <hr class="my-4">
        <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
        <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
    </div>

    <h2 class="mt-5">Daftar Postingan</h2>
    <ul class="list-group">
        <?php foreach ($posts as $post) : ?>
            <li class="list-group-item">
                <h4><?= $post['title']; ?></h4>
                <p><?= $post['content']; ?></p>
                <small>By <?= $post['author']; ?> | <?= $post['created_at']; ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php require_once 'partials/footer.php' ?>