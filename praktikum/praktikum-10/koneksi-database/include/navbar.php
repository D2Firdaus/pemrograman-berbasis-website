<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require_once __DIR__ . "/../config/config.php"; ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <a class="navbar-brand fw-bold" href="<?= $base_url; ?>index.php">Toko Buku Online</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url; ?>pages/daftar_buku.php">Daftar
                        Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url; ?>pages/tambah_buku.php">Tambah
                        Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url; ?>pages/lihat_pesanan.php">Lihat
                        Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url; ?>pages/buat_pesanan.php">Buat
                        Pesanan</a>
                </li>

            </ul>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>