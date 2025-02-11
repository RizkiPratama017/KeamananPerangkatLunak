<?php
session_start();
require_once 'functions.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan di URL.";
    exit;
}

$id_post = $_GET['id'];


$query = "SELECT p.*, u.username 
          FROM posts p
          INNER JOIN users u ON p.id_user = u.id
          WHERE p.id = '$id_post' AND p.is_published = 1";

$post = query($query);

if (empty($post)) {
    echo "Postingan tidak ditemukan atau belum dipublikasikan.";
    exit;
}

$judul = $post[0]['title'];

require_once 'partials/header.php';
?>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 800px;">
        <?php if (!empty($post[0]['image'])) : ?>
            <img src="img/<?= htmlspecialchars($post[0]['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($post[0]['title']); ?>">
        <?php endif; ?>
        <div class="card-body">
            <p class="card-text"><?= htmlspecialchars($post[0]['username']); ?> | <?= htmlspecialchars($post[0]['created_at']); ?></p>
            <p class="card-text">Keyword : <?= htmlspecialchars($post[0]['keywords']); ?></p>
            <h5 class="card-title"><?= htmlspecialchars($post[0]['title']); ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($post[0]['content'])); ?></p>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>