<?php
session_start();

require_once '../functions.php';
$judul = "Tambah";
require_once '../partials/header.php';


if (!isset($_SESSION['id_user'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu.');
        window.location.href = '../login.php';
    </script>";
    exit;
}


if (isset($_POST["tambah"])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $keywords = $_POST['keywords'];
    $id_user = $_SESSION['id_user'];
    $created_at = date("Y-m-d H:i:s");

    // menghandle gambar yang diupload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = '../img/';
        $imagePath = $uploadDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $image = basename($_FILES['image']['name']);
        }
    }

    // simpan ke database
    if (tambahpost($title, $content, $image, $keywords, $id_user, $created_at) > 0) {
        echo "<script>
            alert('Post berhasil ditambahkan');
            window.location.href = '../admin.php';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Post gagal ditambahkan');
            window.location.href = 'tambah.php'; 
        </script>";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center mb-4">Tambah Post</h2>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']; ?>">

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul:</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Masukkan judul post" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Konten:</label>
                            <textarea name="content" id="content" class="form-control" rows="5" placeholder="Tulis konten di sini..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keywords" class="form-label">Kata Kunci:</label>
                            <input type="text" name="keywords" id="keywords" class="form-control" placeholder="Masukkan kata kunci" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar:</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>

                        <div class="text-center">
                            <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>