<?php
session_start();
require_once 'functions.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu.');
        window.location.href = 'login.php';
    </script>";
    exit;
}


$id_user = $_SESSION['id_user'];
$posts = PostsUser($id_user);

$judul = htmlspecialchars($_SESSION['username']);
require_once 'partials/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h1 class="card-title">Halaman Admin <?= $_SESSION['username']; ?></h1>
                    <p class="card-text">Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="post/tambah.php" class="btn btn-primary">Tambah Post</a>
                        <a href="riwayat.php" class="btn btn-success">Riwayat Post</a>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>

            <!-- List Post -->
            <div class="mt-4">
                <h2>Post Anda</h2>
                <?php if ($posts && count($posts) > 0): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($post['title']); ?></h5>
                                <p class="card-text"><?= $post['content']; ?></p>
                                <small class="text-muted">Dibuat pada: <?= htmlspecialchars($post['created_at']); ?></small>
                            </div>
                            <?php if ($post['is_published'] == 1): ?>
                                <div class="d-inline-flex ms-3 mb-3">
                                    <a href="post/edit.php?id=<?= htmlspecialchars($post['id']); ?>" class="btn btn-warning btn-sm me-2">
                                        Edit
                                    </a>
                                    <a href="post/hapus.php?id=<?= htmlspecialchars($post['id']); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus post ini?');">
                                        Hapus
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="d-inline-flex ms-3 mb-3 text-danger">Postingan dihapus</div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-muted mt-3">Belum ada postingan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>