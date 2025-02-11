<?php

define('BASE_URL', '/KeamananPerangkatLunak/');

// Koneksi ke DB
if (!function_exists('koneksi')) {
    function koneksi()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "kpl_tubes";
        $conn = mysqli_connect("$servername", "$username", "$password", "$dbname") or die('koneksi eror');
        return $conn;
    }
}

if (!function_exists('query')) {
    function query($query)
    {
        $conn = koneksi();
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }
}

// Fungsi untuk mengambil data dari tabel users
function getUsers()
{
    $query = "SELECT * FROM users";
    return query($query);
}

// Fungsi untuk mengambil data dari tabel posts
function getPosts()
{
    $conn = koneksi();

    $query = "SELECT posts.*, users.username AS author 
              FROM posts 
              JOIN users ON posts.id_user = users.id 
              ORDER BY posts.created_at DESC";

    $result = mysqli_query($conn, $query);
    $posts = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }

    return $posts;
}

// Fungsi untuk mengambil data dari tabel comments
function getComments()
{
    $query = "SELECT * FROM comments";
    return query($query);
}

// registrasi
function register($data)
{
    $conn = koneksi();

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // Cek apakah username sudah ada
    $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
        return false;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
        return false;
    }

    // Enkripsi password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database
    $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// tambah post
if (!function_exists('tambahpost')) {
    function tambahpost($title, $content, $image, $keywords, $id_user, $created_at)
    {
        $conn = koneksi();

        $query = "INSERT INTO posts (title, content, image, keywords, id_user, is_published, created_at) 
                  VALUES ('$title', '$content', '$image', '$keywords', '$id_user', 1, '$created_at')";

        mysqli_query($conn, $query) or die(mysqli_error($conn));
        return mysqli_affected_rows($conn);
    }
}

// postingan yang diupload oleh user yang sedang login
if (!function_exists('PostsUser')) {
    function PostsUser($id_user)
    {
        $conn = koneksi();
        $query = "SELECT * FROM posts WHERE id_user = '$id_user' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        $posts = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $posts[] = $row;
        }

        return $posts;
    }
}

// ambil riwayat revisi post berdasarkan id_user
if (!function_exists('RiwayatPostUser')) {
    function RiwayatPostUser($id_user)
    {
        $conn = koneksi();
        $query = "SELECT 
                    pr.id AS revision_id, 
                    pr.post_id, 
                    pr.title, 
                    pr.content, 
                    pr.image, 
                    pr.updated_at, 
                    p.is_published 
                  FROM post_revisions pr 
                  INNER JOIN posts p ON pr.post_id = p.id
                  WHERE p.id_user = '$id_user'
                  ORDER BY pr.updated_at DESC";

        $result = mysqli_query($conn, $query);

        $revisions = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $revisions[] = $row;
            }
        }

        return $revisions;
    }
}

if (!function_exists('editPost')) {
    function editPost($id_post, $title, $content, $keywords, $file)
    {
        $conn = koneksi();

        // Ambil data post sebelumnya
        $post = query("SELECT * FROM posts WHERE id = '$id_post'");
        if (!$post) {
            return false;
        }
        $post = $post[0];

        // Gunakan gambar lama jika tidak ada yang diunggah
        $image = $post['image'];
        if (!empty($file['image']['name'])) {
            $image_name = basename($file['image']['name']);
            $image_path = '../img/' . $image_name;
            move_uploaded_file($file['image']['tmp_name'], $image_path);
            $image = $image_name;
        }

        // Update data di database
        $update_query = "UPDATE posts SET title='$title', content='$content', keywords='$keywords', image='$image' WHERE id='$id_post'";
        mysqli_query($conn, $update_query) or die(mysqli_error($conn));

        return mysqli_affected_rows($conn);
    }
}


