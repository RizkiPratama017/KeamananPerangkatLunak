<?php

define('BASE_URL', '/KeamananPerangkatLunak/');

// Koneksi ke DB
if (!function_exists('koneksi')) {
    function koneksi()
    {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;dbname=kpl_tubes",
                "root",
                "",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            return $pdo;
        } catch (PDOException $e) {
            die("Koneksi error: " . $e->getMessage());
        }
    }
}


if (!function_exists('query')) {
    function query($query, $params = [])
    {
        $pdo = koneksi();
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    $query = "SELECT posts.*, users.username AS author 
              FROM posts 
              JOIN users ON posts.id_user = users.id 
              ORDER BY posts.created_at DESC";
    return query($query);
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
    $pdo = koneksi();

    $username = strtolower(stripslashes($data["username"]));
    $password = password_hash($data["password"], PASSWORD_DEFAULT);

    // Cek username
    $stmt = $pdo->prepare("SELECT username FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->fetch()) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
        return false;
    }

    // Insert user baru
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute([
        'username' => $username,
        'password' => $password
    ]);

    return $stmt->rowCount();
}

// tambah post
if (!function_exists('tambahpost')) {
    function tambahpost($title, $content, $image, $keywords, $id_user, $created_at)
    {
        $pdo = koneksi();

        $stmt = $pdo->prepare("INSERT INTO posts (title, content, image, keywords, id_user, is_published, created_at) 
            VALUES (:title, :content, :image, :keywords, :id_user, 1, :created_at)");

        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'image' => $image,
            'keywords' => $keywords,
            'id_user' => $id_user,
            'created_at' => $created_at
        ]);

        return $stmt->rowCount();
    }
}

// postingan yang diupload oleh user yang sedang login
if (!function_exists('PostsUser')) {
    function PostsUser($id_user)
    {
        $pdo = koneksi();
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id_user = :id_user ORDER BY created_at DESC");
        $stmt->execute(['id_user' => $id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


// ambil riwayat revisi post berdasarkan id_user
if (!function_exists('RiwayatPostUser')) {
    function RiwayatPostUser($id_user)
    {
        $pdo = koneksi();
        $stmt = $pdo->prepare("SELECT 
                    pr.id AS revision_id, 
                    pr.post_id, 
                    pr.title, 
                    pr.content, 
                    pr.image, 
                    pr.updated_at, 
                    p.is_published 
                  FROM post_revisions pr 
                  INNER JOIN posts p ON pr.post_id = p.id
                  WHERE p.id_user = :id_user
                  ORDER BY pr.updated_at DESC");

        $stmt->execute(['id_user' => $id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

//edit post
if (!function_exists('editPost')) {
    function editPost($id_post, $title, $content, $keywords, $file)
    {
        $pdo = koneksi();

        // Ambil data post sebelumnya
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id_post]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            return false;
        }

        // Simpan ke tabel post_revision
        $stmt = $pdo->prepare("INSERT INTO post_revisions (post_id, title, content, image, updated_at) 
                              VALUES (:post_id, :title, :content, :image, :updated_at)");
        $stmt->execute([
            'post_id' => $id_post,
            'title' => $post['title'],
            'content' => $post['content'],
            'image' => $post['image'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Gunakan gambar lama jika tidak ada yang diunggah
        $image = $post['image'];
        if (!empty($file['image']['name'])) {
            $image_name = basename($file['image']['name']);
            $image_path = '../img/' . $image_name;
            move_uploaded_file($file['image']['tmp_name'], $image_path);
            $image = $image_name;
        }

        // Update data di database
        $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content, keywords = :keywords, image = :image 
                              WHERE id = :id");
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'keywords' => $keywords,
            'image' => $image,
            'id' => $id_post
        ]);

        return $stmt->rowCount();
    }
}

// hapus post
function hapus($id_post)
{
    $pdo = koneksi();

    // Ambil data post
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id_post]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        return false;
    }

    // Simpan ke tabel post_revisions sebelum menghapus
    $stmt = $pdo->prepare("INSERT INTO post_revisions (post_id, title, content, image, updated_at) 
                          VALUES (:post_id, :title, :content, :image, :updated_at)");
    $stmt->execute([
        'post_id' => $post['id'],
        'title' => $post['title'],
        'content' => $post['content'],
        'image' => $post['image'],
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    // Update status publikasi
    $stmt = $pdo->prepare("UPDATE posts SET is_published = :status WHERE id = :id");
    $stmt->execute([
        'status' => 0,
        'id' => $id_post
    ]);

    return $stmt->rowCount();
}

function kembali($id_post)
{
    $pdo = koneksi();

    // Ambil data post
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id_post]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        return false;
    }

    // Simpan ke revisi
    $stmt = $pdo->prepare("INSERT INTO post_revisions (post_id, title, content, image, updated_at) 
                          VALUES (:post_id, :title, :content, :image, :updated_at)");
    $stmt->execute([
        'post_id' => $post['id'],
        'title' => $post['title'],
        'content' => $post['content'],
        'image' => $post['image'],
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    // Update status publikasi
    $stmt = $pdo->prepare("UPDATE posts SET is_published = :status WHERE id = :id");
    $stmt->execute([
        'status' => 1,
        'id' => $id_post
    ]);

    return $stmt->rowCount();
}
