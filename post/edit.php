<?php
session_start();
require_once '../functions.php';

$id_post = $_GET['id'];
$query = "SELECT * FROM posts WHERE id = '$id_post'";
$post = query($query);
$judul = "Edit Post";

if (!$post) {
    echo "<script>alert('Post tidak ditemukan!'); window.location.href='" . BASE_URL . "admin.php';</script>";
    exit;
}

$post = $post[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $keywords = $_POST['keywords'];

    if (editPost($id_post, $title, $content, $keywords, $_FILES)) {
        echo "<script>alert('Post berhasil diperbarui!'); window.location.href='" . BASE_URL . "admin.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan!'); window.location.href='" . BASE_URL . "admin.php';</script>";
    }
    exit;
}

require_once '../partials/header.php' ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center mb-4">Edit Post</h2>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul:</label>
                            <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Konten:</label>
                            <input id="content" type="hidden" name="content" value="<?= $post['content'] ?>">
                            <trix-editor input="content"></trix-editor>
                        </div>

                        <div class="mb-3">
                            <label for="keywords" class="form-label">Kata Kunci:</label>
                            <input type="text" name="keywords" id="keywords" class="form-control" value="<?= htmlspecialchars($post['keywords']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar:</label>
                            <input type="file" name="image" id="image" class="form-control" onchange="previewImage(event)">
                            <div class="mt-3">
                                <img id="imagePreview" src="../img/<?= $post['image'] ?>" alt="Preview" class="img-fluid" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('imagePreview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php require_once '../partials/footer.php' ?>