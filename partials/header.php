<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman <?= $judul; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL; ?>">Postingers</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link active" href="<?= BASE_URL; ?>">Home</a>
                    <a class="nav-item nav-link" href="#">Post</a>
                    <a class="nav-item nav-link" href="#">About</a>
                </div>
            </div>

            <!-- Cek apakah user sudah login -->
            <?php if (isset($_SESSION['username'])) : ?>
                <!-- Dropdown Username -->
                <div class="dropdown">
                    <button class="nav-link dropdown-toggle text-dark" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $_SESSION['username']; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>admin.php">Dashboard</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL; ?>logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php else : ?>
                <!-- Link Login -->
                <a class="nav-item nav-link text-dark" href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>