<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Buku</title>
</head>

<body>
    <?php
    require_once __DIR__ . "/config/config.php";
    require_once __DIR__ . "/config/koneksi.php";
    ?>

    <?php include_once __DIR__ . "/include/navbar.php"; ?>

    </div>

    <div class="container-fluid mt-4">
        <h3 class="mb-3">Daftar Buku</h3>
        <?php include_once __DIR__ . "/include/daftar_buku.php"; ?>
    </div>
</body>

</html>