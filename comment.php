<?php
session_start();
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah data komentar dan post_id telah diterima
    if (isset($_POST['comment'], $_POST['post_id']) && !empty($_POST['comment']) && !empty($_POST['post_id'])) {
        $comment = $_POST['comment'];
        $post_id = $_POST['post_id'];

        try {
            // Koneksi ke database
            $pdo = koneksi();

            // Periksa nilai post_id
            $stmt_check = $pdo->prepare("SELECT id FROM posts WHERE id = :post_id");
            $stmt_check->execute(['post_id' => $post_id]);

            if (!$stmt_check->fetch(PDO::FETCH_ASSOC)) {
                die("post_id tidak valid: " . htmlspecialchars($post_id));
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
                header("Location: post.php?id=" . htmlspecialchars($post_id));
                exit;
            } else {
                throw new PDOException("Gagal menyimpan komentar");
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            die("Terjadi kesalahan dalam memproses komentar.");
        }
    } else {
        die("Data komentar atau post_id tidak valid.");
    }
} else {
    die("Request tidak valid.");
}
