<?php

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
    $query = "SELECT * FROM posts";
    return query($query);
}

// Fungsi untuk mengambil data dari tabel comments
function getComments()
{
    $query = "SELECT * FROM comments";
    return query($query);
}
