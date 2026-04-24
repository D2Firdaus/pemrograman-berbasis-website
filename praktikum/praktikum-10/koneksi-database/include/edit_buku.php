<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/koneksi.php";

$redirect = $base_url . 'pages/daftar_buku.php';

// validasi ID
if (!isset($_GET['id'])) {
    header("Location: " . $redirect . "?status=invalid");
    exit;
}

$id = $_GET['id'];

// ambil data buku
$sql = "SELECT * FROM buku WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: " . $redirect . "?status=notfound");
    exit;
}

$buku = $result->fetch_assoc();
$stmt->close();

// proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $judul        = $_POST['judul'];
    $penulis      = $_POST['penulis'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $harga        = $_POST['harga'];
    $stok         = $_POST['stok'];

    $stmt_update = $conn->prepare("UPDATE buku SET Judul=?, Penulis=?, Tahun_Terbit=?, Harga=?, stok=? WHERE ID=?");
    $stmt_update->bind_param("ssidii", $judul, $penulis, $tahun_terbit, $harga, $stok, $id);

    if ($stmt_update->execute()) {
        header("Location: " . $redirect . "?status=diperbarui");
    } else {
        header("Location: " . $redirect . "?status=gagal");
    }

    $stmt_update->close();
    $conn->close();
    exit;
}

$conn->close();
?>

<div class="container-fluid mt-4">
    <form action="" method="POST">
        <div class="row g-2 align-items-end">

            <div class="col-md">
                <label class="form-label">Judul</label>
                <input type="text" name="judul" required class="form-control"
                    value="<?php echo htmlspecialchars($buku['Judul']); ?>">
            </div>

            <div class="col-md">
                <label class="form-label">Penulis</label>
                <input type="text" name="penulis" required class="form-control"
                    value="<?php echo htmlspecialchars($buku['Penulis']); ?>">
            </div>

            <div class="col-md">
                <label class="form-label">Tahun Terbit</label>
                <input type="text" name="tahun_terbit" required class="form-control"
                    value="<?php echo htmlspecialchars($buku['Tahun_Terbit']); ?>">
            </div>

            <div class="col-md">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" required class="form-control"
                    value="<?php echo $buku['Harga']; ?>">
            </div>

            <div class="col-md">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" required class="form-control"
                    value="<?php echo $buku['stok']; ?>">
            </div>

            <div class="col-md-auto">
                <button type="submit" name="submit" class="btn btn-primary w-100">Simpan</button>
            </div>

            <div class="col-md-auto">
                <a href="<?php echo $redirect; ?>" class="btn btn-secondary w-100">Batal</a>
            </div>

        </div>
    </form>
</div>