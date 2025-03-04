<?php
session_start();
require_once 'functions.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = "Token CSRF tidak valid.";
        header("Location: post.php?id=" . htmlspecialchars($_POST['post_id']));
        exit;
    }

    // Cek apakah data komentar dan post_id telah diterima
    if (isset($_POST['comment'], $_POST['post_id']) && !empty($_POST['comment']) && !empty($_POST['post_id'])) {
        $comment = $_POST['comment'];
        $post_id = $_POST['post_id'];

        // Validasi post_id
        if (!is_numeric($post_id)) {
            $_SESSION['error_message'] = "ID post tidak valid.";
            header("Location: post.php?id=" . htmlspecialchars($post_id));
            exit;
        }

        // Validasi input komentar
        if (strlen($comment) > 500) {
            $_SESSION['error_message'] = "Komentar tidak boleh lebih dari 500 karakter.";
            header("Location: post.php?id=" . htmlspecialchars($post_id));
            exit;
        } elseif (!preg_match("/^[a-zA-Z0-9\s.,!?]*$/", $comment)) {
            $_SESSION['error_message'] = "Komentar hanya boleh mengandung huruf, angka, spasi, dan tanda baca tertentu.";
            header("Location: post.php?id=" . htmlspecialchars($post_id));
            exit;
        }

        try {
            // Koneksi ke database
            $pdo = koneksi();

            // Periksa nilai post_id
            $stmt_check = $pdo->prepare("SELECT id FROM posts WHERE id = :post_id");
            $stmt_check->execute(['post_id' => $post_id]);

            if (!$stmt_check->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['error_message'] = "post_id tidak valid.";
                header("Location: post.php?id=" . htmlspecialchars($post_id));
                exit;
            }

            // Set nilai user berdasarkan status login
            if (isset($_SESSION['username'])) {
                $id_user = $_SESSION['id_user'];
                $username = $_SESSION['username'];
            } else {
                $id_user = null;
                $username = 'Anonim';
            }

            // Query untuk memasukkan data komentar
            $stmt = $pdo->prepare("INSERT INTO comments (post_id, id_user, username, comment, created_at) 
                                 VALUES (:post_id, :id_user, :username, :comment, NOW())");

            // Execute dengan named parameters
            $success = $stmt->execute([
                'post_id' => $post_id,
                'id_user' => $id_user,
                'username' => $username,
                'comment' => $comment
            ]);

            if ($success) {
                $_SESSION['success_message'] = "Komentar berhasil ditambahkan!";
                header("Location: post.php?id=" . htmlspecialchars($post_id));
                exit;
            } else {
                throw new PDOException("Gagal menyimpan komentar.");
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $_SESSION['error_message'] = "Terjadi kesalahan dalam memproses komentar.";
            header("Location: post.php?id=" . htmlspecialchars($post_id));
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Data komentar atau post_id tidak valid.";
        header("Location: post.php?id=" . htmlspecialchars($_POST['post_id']));
        exit;
    }
} else {
    $_SESSION['error_message'] = "Request tidak valid.";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Komentar</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body style="background-color: #eaeaea;">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center pb-2">Tambah Komentar</h2>
                        <form action="" method="post" autocomplete="off">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="mb-3">
                                <label for="comment" class="form-label">Komentar</label>
                                <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                            </div>
                            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($_GET['post_id']); ?>">
                            <button type="submit" class="btn btn-success w-100" name="submit_comment">Kirim Komentar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
