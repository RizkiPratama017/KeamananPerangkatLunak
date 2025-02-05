<?php
session_start();

require_once 'functions.php';

// cek session
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// alert user 
if (isset($_POST["register"])) {
    if (register($_POST) > 0) {
        echo "<script>
                alert('User baru berhasil ditambahkan!');
              <script>";
        header('Location: login.php');
        exit;
    } else {
        echo "<script>alert('Registrasi gagal!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAFTAR</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body style="background-color: #eaeaea;">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="row g-0">
                        <!-- Kolom Gambar -->
                        <div class="col-md-6 d-none d-md-block">
                            <img src="img/login-image.jpg" class="img-fluid rounded-start" alt="Register Image">
                        </div>
                        <!-- Kolom Form Register -->
                        <div class="col-md-6">
                            <div class="card-body">
                                <h2 class="text-center">DAFTAR</h2>
                                <form action="" method="post" autocomplete="off">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password2" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="password2" name="password2" required>
                                    </div>
                                    <button type="submit" name="register" class="btn btn-success w-100">Daftar</button>
                                </form>
                                <hr>
                                <p class="text-center mb-2">Sudah punya akun? <a href="login.php" class="text-decoration-none">Masuk</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>