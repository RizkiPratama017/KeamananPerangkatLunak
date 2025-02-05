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

// login
