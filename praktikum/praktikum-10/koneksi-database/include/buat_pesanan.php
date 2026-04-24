<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/koneksi.php";

$status = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pelanggan_id = $_POST['pelanggan_id'];
    $buku_ids     = $_POST['buku_id'];
    $kuantitas    = $_POST['kuantitas'];

    $total_harga = 0;
    $buku_list   = [];

    foreach ($buku_ids as $i => $buku_id) {
        $qty = (int) $kuantitas[$i];
        if ($qty <= 0) continue;

        $stmt_buku = $conn->prepare("SELECT Harga, stok FROM buku WHERE ID = ?");
        $stmt_buku->bind_param("i", $buku_id);
        $stmt_buku->execute();
        $row = $stmt_buku->get_result()->fetch_assoc();
        $stmt_buku->close();

        if (!$row || $row['stok'] < $qty) continue;

        $harga_satuan = $row['Harga'];
        $total_harga += $harga_satuan * $qty;
        $buku_list[] = [
            'buku_id'      => $buku_id,
            'kuantitas'    => $qty,
            'harga_satuan' => $harga_satuan,
        ];
    }

    if (empty($buku_list)) {
        $status = 'gagal';
    } else {
        $tanggal = date('Y-m-d');
        $stmt_pesanan = $conn->prepare("INSERT INTO pesanan (Tanggal_Pesanan, Pelanggan_ID, Total_Harga) VALUES (?, ?, ?)");
        $stmt_pesanan->bind_param("sid", $tanggal, $pelanggan_id, $total_harga);
        $stmt_pesanan->execute();
        $pesanan_id = $conn->insert_id;
        $stmt_pesanan->close();

        foreach ($buku_list as $item) {
            $stmt_detail = $conn->prepare("INSERT INTO detail_pesanan (Pesanan_ID, Buku_ID, Kuantitas, Harga_Per_Satuan) VALUES (?, ?, ?, ?)");
            $stmt_detail->bind_param("iiid", $pesanan_id, $item['buku_id'], $item['kuantitas'], $item['harga_satuan']);
            $stmt_detail->execute();
            $stmt_detail->close();

            $stmt_stok = $conn->prepare("UPDATE buku SET stok = stok - ? WHERE ID = ?");
            $stmt_stok->bind_param("ii", $item['kuantitas'], $item['buku_id']);
            $stmt_stok->execute();
            $stmt_stok->close();
        }

        $status = 'berhasil';
    }
}

$pelanggan_list = $conn->query("SELECT ID, Nama FROM pelanggan ORDER BY Nama");
$buku_list_all  = $conn->query("SELECT ID, Judul, Harga, stok FROM buku WHERE stok > 0 ORDER BY Judul");
$conn->close();
?>

<?php if ($status): ?>
    <div class="alert <?= $status == 'berhasil' ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
        <?= $status == 'berhasil' ? 'Pesanan berhasil dibuat.' : 'Pesanan gagal dibuat. Periksa stok atau data yang dimasukkan.' ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="" method="POST">
    <div class="mb-3">
        <label class="form-label">Pelanggan</label>
        <select name="pelanggan_id" class="form-select" required>
            <option value="">Pilih Pelanggan</option>
            <?php while ($p = $pelanggan_list->fetch_assoc()): ?>
                <option value="<?= $p['ID'] ?>"><?= htmlspecialchars($p['Nama']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Pilih Buku & Kuantitas</label>
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Pilih</th>
                    <th>Judul</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Kuantitas</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($b = $buku_list_all->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" name="buku_id[]" value="<?= $b['ID'] ?>" onchange="toggleQty(this)">
                        </td>
                        <td><?= htmlspecialchars($b['Judul']) ?></td>
                        <td class="text-center">Rp <?= number_format($b['Harga'], 0, ',', '.') ?></td>
                        <td class="text-center"><?= $b['stok'] ?></td>
                        <td class="text-center">
                            <input type="number" name="kuantitas[]" value="1" min="1" max="<?= $b['stok'] ?>"
                                class="form-control form-control-sm text-center" style="width:80px; margin:auto;" disabled>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Buat Pesanan</button>
    </div>
</form>

<script>
function toggleQty(checkbox) {
    const row = checkbox.closest('tr');
    const qtyInput = row.querySelector('input[type="number"]');
    qtyInput.disabled = !checkbox.checked;
    if (!checkbox.checked) qtyInput.value = 1;
}
</script>