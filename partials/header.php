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
                    <a class="nav-item nav-link active" href="<?= BASE_URL; ?>">Home <span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link" href="post.php">Post</a>
                    <a class="nav-item nav-link" href="#">About</a>
                </div>
            </div>
            <div class="d-flex">
                <?php if (isset($_SESSION['username'])) : ?>
                    <a class="nav-item nav-link m-3" href="<?= BASE_URL; ?>admin.php">admin</a>
                    <a class="nav-item nav-link m-3" href="<?= BASE_URL; ?>logout.php">Logout</a>
                <?php else : ?>
                    <a class="nav-item nav-link" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>