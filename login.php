<?php
session_start();

require_once 'functions.php';

$pdo = koneksi();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];


    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);


    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        if (password_verify($password, $row["password"])) {
            $_SESSION['id_user'] = $row["id"];
            $_SESSION['username'] = $row["username"];
            $_SESSION['login'] = true;
            header('location:index.php');
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MASUK</title>
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
                            <img src="img/login-image.jpg" class="img-fluid rounded-start" alt="Login Image">
                        </div>
                        <!-- Kolom Form Login -->
                        <div class="col-md-6">
                            <div class="card-body">
                                <h2 class="text-center pb-2">MASUK</h2>
                                <!-- Error -->
                                <?php if (isset($error)) : ?>
                                    <div class="alert alert-danger" role="alert" id="alert">
                                        Username atau Password salah!
                                    </div>
                                <?php endif; ?>

                                <form action="" method="post" autocomplete="off">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="showpass" onclick="showPass()">
                                        <label class="form-check-label" for="showpass">Show password</label>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100" name="login">Masuk</button>
                                </form>
                                <hr>
                                <p class="text-center mb-2">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showPass() {
            var x = document.getElementById("password");
            x.type = x.type === "password" ? "text" : "password";
        }
    </script>
</body>

</html>