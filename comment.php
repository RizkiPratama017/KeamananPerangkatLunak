<?php
session_start(); // Mulai sesi

require_once 'functions.php';

// Cek apakah pengguna telah login dan sesi sudah diatur
if (!isset($_SESSION['username'])) {
    die("Pengguna belum login. Harap login terlebih dahulu.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah data komentar dan post_id telah diterima
    if (isset($_POST['comment'], $_POST['post_id']) && !empty($_POST['comment']) && !empty($_POST['post_id'])) {
        $comment = $_POST['comment'];
        $post_id = $_POST['post_id'];
        $id_user = $_SESSION['id_user']; // Pastikan sesi user sudah diatur
        $username = $_SESSION['username']; // Pastikan sesi user sudah diatur

        // Koneksi ke database
        $conn = koneksi();

        // Periksa nilai post_id
        $sql_check = "SELECT id FROM posts WHERE id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $post_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows === 0) {
            die("post_id tidak valid: " . htmlspecialchars($post_id));
        }

        // Query untuk memasukkan data komentar
        $sql = "INSERT INTO comments (post_id, id_user, username, comment, created_at) VALUES (?, ?, ?, ?, now())";
        $stmt = $conn->prepare($sql);

        // Bind parameter
        $stmt->bind_param("iiss", $post_id, $id_user, $username, $comment);

        // Eksekusi query
        if ($stmt->execute()) {
            // Redirect ke halaman postingan
            header("Location: post.php?id=" . htmlspecialchars($post_id));
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Tutup statement
        $stmt->close();
    } else {
        echo "Data komentar atau post_id tidak valid.";
    }
} else {
    echo "Request tidak valid.";
}

// Tutup koneksi
$conn->close();
?>
