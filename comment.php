<?php
session_start(); // Mulai sesi

require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah data komentar dan post_id telah diterima
    if (isset($_POST['comment'], $_POST['post_id']) && !empty($_POST['comment']) && !empty($_POST['post_id'])) {
        $comment = $_POST['comment'];
        $post_id = $_POST['post_id'];

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

        // Jika pengguna login
        if (isset($_SESSION['username'])) {
            $id_user = $_SESSION['id_user'];
            $username = $_SESSION['username'];
        } else { // Jika pengguna anonim
            $id_user = null; // Atur id_user menjadi null
            $username = 'Anonim'; // Gunakan nama "Anonim"
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
