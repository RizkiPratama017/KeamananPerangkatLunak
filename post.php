<?php
session_start();
require_once 'functions.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan di URL.";
    exit;
}

$id_post = $_GET['id'];
$pdo = koneksi();

// Query untuk mengambil post
$query = "SELECT p.*, u.username 
          FROM posts p
          INNER JOIN users u ON p.id_user = u.id
          WHERE p.id = :id AND p.is_published = 1";

$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id_post]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "Postingan tidak ditemukan atau belum dipublikasikan.";
    exit;
}

$judul = $post['title'];

// Ambil komentar dari database
$query_comments = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at DESC";
$stmt_comments = $pdo->prepare($query_comments);
$stmt_comments->execute(['post_id' => $id_post]);
$comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

require_once 'partials/header.php';
?>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 800px;">
        <?php if (!empty($post['image'])) : ?>
            <img src="img/<?= htmlspecialchars($post['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']); ?>">
        <?php endif; ?>
        <div class="card-body">
            <p class="card-text"><?= htmlspecialchars($post['username']); ?> | <?= htmlspecialchars($post['created_at']); ?></p>
            <p class="card-text">Keyword : <?= htmlspecialchars($post['keywords']); ?></p>
            <h5 class="card-title"><?= htmlspecialchars($post['title']); ?></h5>
            <p class="card-text"><?= $post['content']; ?></p>
        </div>
    </div>
</div>

<div class="col mt-4">
    <h4>Komentar</h4>
    <?php if (empty($comments)): ?>
        <p>Belum ada komentar. Jadilah yang pertama untuk berkomentar!</p>
    <?php else: ?>
        <?php foreach ($comments as $comment) : ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($comment['username']); ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($comment['comment'])); ?></p>
                    <p class="card-text"><small class="text-muted"><?= htmlspecialchars($comment['created_at']); ?></small></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="col mt-4">
    <form method="POST" action="comment.php">
        <div class="mb-3">
            <textarea class="form-control" name="comment" placeholder="Tambahkan Komentar...." required></textarea>
            <input type="hidden" value="<?= htmlspecialchars($post['id']); ?>" name="post_id" />
        </div>
        <div class="text-end">
            <button class="btn btn-primary" type="submit">Kirim</button>
        </div>
    </form>
</div>

<?php require_once 'partials/footer.php'; ?>