<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>
</head>

<body>
    <?php require_once __DIR__ . "/../config/config.php";
    require_once __DIR__ . "/../config/koneksi.php";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $judul = $_POST['judul'];
        $penulis = $_POST['penulis'];
        $tahun_terbit = $_POST['tahun_terbit'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        $stmt = $conn->prepare("INSERT INTO buku (Judul, Penulis, Tahun_Terbit, Harga, Stok) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiii", $judul, $penulis, $tahun_terbit, $harga, $stok);
        if ($stmt->execute()) {
            $message = "Buku berhasil ditambahkan!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } ?>

    <?php if (isset($message)): ?>
        <div class="container-fluid mt-4">
            <div class="alert <?php echo str_starts_with($message, 'Error') ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show"
                role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container-fluid mt-4">
        <form action="" method="POST">
            <div class="row g-2 align-items-end">
                <div class="col-md"> <label class="form-label">Judul</label> <input type="text" name="judul"
                        placeholder="Masukkan Judul" required class="form-control"> </div>
                <div class="col-md"> <label class="form-label">Penulis</label> <input type="text" name="penulis"
                        placeholder="Masukkan Penulis" required class="form-control"> </div>
                <div class="col-md"> <label class="form-label">Tahun Terbit</label> <input type="text"
                        name="tahun_terbit" placeholder="Masukkan Tahun Terbit" required class="form-control"> </div>
                <div class="col-md"> <label class="form-label">Harga</label> <input type="number" name="harga"
                        placeholder="Masukkan Harga" required class="form-control"> </div>
                <div class="col-md"> <label class="form-label">Stok</label> <input type="number" name="stok"
                        placeholder="Masukkan Stok" required class="form-control"> </div>
                <div class="col-md-auto"> <button type="submit" name="submit" class="btn btn-primary w-100"> Tambah
                    </button> </div>
            </div>
        </form>
    </div>
</body>

</html>