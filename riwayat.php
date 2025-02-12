<?php
session_start();

require_once 'functions.php';
$judul = "Riwayat Post";
require_once 'partials/header.php';

// user harus login
if (!isset($_SESSION['id_user'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$id_user = $_SESSION['id_user'];
$revisions = RiwayatPostUser($id_user);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center mb-4">Riwayat Revisi & Penarikan Post</h2>

                    <?php if (empty($revisions)): ?>
                        <p class="text-center">Belum ada riwayat revisi atau penarikan.</p>
                    <?php else: ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Isi</th>
                                    <th>Gambar</th>
                                    <th>Revisi Pada</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($revisions as $rev): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= isset($rev['title']) ? htmlspecialchars($rev['title']) : 'Tidak tersedia'; ?></td>
                                        <td><?= isset($rev['content']) ? substr(strip_tags($rev['content']), 0, 100) . '...' : 'Tidak tersedia'; ?></td>
                                        <td>
                                            <?php if (!empty($rev['image'])): ?>
                                                <img src="img/<?= htmlspecialchars($rev['image']); ?>" width="100">
                                            <?php else: ?>
                                                Tidak ada gambar
                                            <?php endif; ?>
                                        </td>
                                        <td><?= isset($rev['updated_at']) ? date("d-m-Y H:i", strtotime($rev['updated_at'])) : 'Tidak tersedia'; ?></td>
                                        <td>
                                            <?php if (isset($rev['is_published'])): ?>
                                                <?php if ($rev['is_published'] == 1): ?>
                                                    <span class="badge bg-success">Dipublikasikan</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Ditarik</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Tidak diketahui</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (isset($rev['is_published']) && $rev['is_published'] == 0): ?>
                                                <a href="post/kembali.php?id=<?= $rev['post_id']; ?>" class="btn btn-primary btn-sm" onclick="return confirm('Apakah Anda yakin ingin mengembalikan post ini?');">
                                                    Kembalikan
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="admin.php" class="btn btn-secondary">Kembali</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>