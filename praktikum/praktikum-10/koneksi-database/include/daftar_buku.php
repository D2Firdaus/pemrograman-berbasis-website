<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid mt-4">

        <?php if (isset($_GET['status'])): ?>
            <div class="alert <?php
                echo match($_GET['status']) {
                    'berhasil' => 'alert-success',
                    'terikat', 'gagal' => 'alert-danger',
                    default => 'alert-warning'
                };
            ?> alert-dismissible fade show" role="alert">
                <?php
                echo match($_GET['status']) {
                    'berhasil'   => 'Data berhasil dihapus.',
                    'diperbarui' => 'Data berhasil diperbarui.',
                    'terikat'    => 'Gagal! Buku terikat dengan data pesanan.',
                    'gagal'      => 'Gagal menghapus data.',
                    default      => 'Akses tidak valid.'
                };
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="GET" action="" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md">
                    <label class="form-label" for="judul">Judul</label>
                    <input type="text" id="judul" name="judul" class="form-control" placeholder="Masukkan Judul"
                        value="<?php echo isset($_GET['judul']) ? htmlspecialchars($_GET['judul']) : ''; ?>">
                </div>
                <div class="col-md">
                    <label class="form-label" for="penulis">Penulis</label>
                    <input type="text" id="penulis" name="penulis" class="form-control" placeholder="Masukkan Penulis"
                        value="<?php echo isset($_GET['penulis']) ? htmlspecialchars($_GET['penulis']) : ''; ?>">
                </div>
                <div class="col-md">
                    <label class="form-label" for="tahun_terbit">Tahun Terbit</label>
                    <input type="text" id="tahun_terbit" name="tahun_terbit" class="form-control"
                        placeholder="Masukkan Tahun Terbit"
                        value="<?php echo isset($_GET['tahun_terbit']) ? htmlspecialchars($_GET['tahun_terbit']) : ''; ?>">
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
                <div class="col-md-auto">
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th class="text-center">Tahun Terbit</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    require_once __DIR__ . "/../config/config.php";
                    require_once __DIR__ . "/../config/koneksi.php";

                    $judul = isset($_GET['judul']) ? $_GET['judul'] : "";
                    $penulis = isset($_GET['penulis']) ? $_GET['penulis'] : "";
                    $tahun_terbit = isset($_GET['tahun_terbit']) ? $_GET['tahun_terbit'] : "";

                    if ($judul != "" || $penulis != "" || $tahun_terbit != "") {
                        $sql = "SELECT * FROM buku WHERE Judul LIKE ? AND Penulis LIKE ? AND Tahun_Terbit LIKE ?";
                        $stmt = $conn->prepare($sql);

                        $s_judul = "%$judul%";
                        $s_penulis = "%$penulis%";
                        $s_tahun = "%$tahun_terbit%";
                        $stmt->bind_param("sss", $s_judul, $s_penulis, $s_tahun);
                        $stmt->execute();

                        $result = $stmt->get_result();
                    } else {
                        $sql = "SELECT * FROM buku";
                        $result = $conn->query($sql);
                    }

                    if (!$result) {
                        die("Query Gagal: " . $conn->error);
                    }

                    if ($result->num_rows == 0) {
                        echo "<tr><td colspan='8' class='text-center'>Data tidak ditemukan</td></tr>";
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            $id = $row['ID'];

                            echo "<tr class='align-middle'>";
                            echo "<td class='text-center'>{$row['ID']}</td>";
                            echo "<td>{$row['Judul']}</td>";
                            echo "<td>{$row['Penulis']}</td>";
                            echo "<td class='text-center'>{$row['Tahun_Terbit']}</td>";
                            echo "<td class='text-center'>Rp " . number_format($row['Harga'], 0, ',', '.') . "</td>";
                            echo "<td class='text-center'>{$row['stok']}</td>";

                            echo "<td class='text-center'>
    <a href='{$base_url}pages/edit_buku.php?id=$id' class='btn btn-warning btn-sm'>Edit</a>
</td>";

                            echo "<td class='text-center'>
    <form action='{$base_url}include/hapus_buku.php' method='POST'
          onsubmit='return confirm(\"Yakin hapus buku ini?\")'>
        <input type='hidden' name='id' value='$id'>
        <button type='submit' class='btn btn-danger btn-sm'>Hapus</button>
    </form>
</td>";

                            echo "</tr>";
                        }
                    }

                    $conn->close();
                    ?>

                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>