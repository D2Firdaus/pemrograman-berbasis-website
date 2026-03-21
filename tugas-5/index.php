<?php
define('PAJAK', 0.10);

$barang = [
    1 => ['nama' => 'Keyboard', 'harga' => 150000],
    2 => ['nama' => 'Mouse', 'harga' => 75000],
    3 => ['nama' => 'Headphone', 'harga' => 250000],
    4 => ['nama' => 'USB Hub', 'harga' => 95000],
    5 => ['nama' => 'Webcam HD', 'harga' => 320000],
    6 => ['nama' => 'Kabel HDMI', 'harga' => 45000],
    7 => ['nama' => 'Mousepad XL', 'harga' => 60000],
];

function rp($n)
{
    return 'Rp ' . number_format($n, 0, ',', '.');
}

// Session
if (session_status() === PHP_SESSION_NONE)
    session_start();
if (!isset($_SESSION['keranjang']))
    $_SESSION['keranjang'] = [];

// Proses POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Tambah barang
    if (isset($_POST['id_barang']) && !isset($_POST['hapus']) && !isset($_POST['bayar'])) {
        $id = (int) $_POST['id_barang'];
        $qty = max(1, (int) $_POST['jumlah']);
        if ($id && isset($barang[$id])) {
            $_SESSION['keranjang'][$id] = ($_SESSION['keranjang'][$id] ?? 0) + $qty;
        }
    }

    // Hapus item
    if (isset($_POST['hapus'])) {
        unset($_SESSION['keranjang'][(int) $_POST['hapus']]);
    }

    // Bayar
    if (isset($_POST['bayar'])) {
        $_SESSION['keranjang'] = [];
        header('Location: index.php?sukses=1');
        exit;
    }

    header('Location: index.php');
    exit;
}

// Hitung total
$keranjang = $_SESSION['keranjang'];
$subtotal = 0;
foreach ($keranjang as $id => $qty) {
    $subtotal += $barang[$id]['harga'] * $qty;
}
$pajak = $subtotal * PAJAK;
$total_bayar = $subtotal + $pajak;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kasir Sederhana</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>

    <div class="wrap">

        <!-- KIRI: Pilih Barang + Keranjang -->
        <div class="card">
            <h2>Pilih Barang</h2>
            <form method="POST">
                <label>Barang</label>
                <select name="id_barang" required>
                    <option value="">— pilih barang —</option>
                    <?php foreach ($barang as $id => $b): ?>
                        <option value="<?= $id ?>"><?= $b['nama'] ?> — <?= rp($b['harga']) ?></option>
                    <?php endforeach ?>
                </select>

                <label>Jumlah</label>
                <input type="number" name="jumlah" value="1" min="1" max="99">

                <button type="submit">+ Tambah ke Keranjang</button>
            </form>

            <hr class="divider">
            <h2>Keranjang</h2>

            <?php if (empty($keranjang)): ?>
                <p class="empty">Belum ada item dipilih</p>
            <?php else: ?>
                <?php foreach ($keranjang as $id => $qty): ?>
                    <div class="item-row">
                        <div>
                            <div><?= $barang[$id]['nama'] ?></div>
                            <div class="muted"><?= rp($barang[$id]['harga']) ?> × <?= $qty ?></div>
                        </div>
                        <div class="item-kanan">
                            <span><?= rp($barang[$id]['harga'] * $qty) ?></span>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="hapus" value="<?= $id ?>">
                                <button class="danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>

        <!-- KANAN: Total & Bayar -->
        <div class="card">
            <h2>Ringkasan</h2>

            <div class="total-row"><span>Subtotal</span><span><?= rp($subtotal) ?></span></div>
            <div class="total-row"><span>Pajak (10%)</span><span><?= rp($pajak) ?></span></div>
            <div class="total-row grand"><span>Total</span><span><?= rp($total_bayar) ?></span></div>

            <hr class="divider">
            <h2>Pembayaran</h2>

            <?php if (!empty($keranjang)): ?>
                <form method="POST" onsubmit="return validasi()">
                    <label>Uang Diterima</label>
                    <input type="number" name="uang_tunai" id="uangTunai" placeholder="0" min="0" oninput="hitungKembali()">

                    <div class="kembalian" id="kembaliBox" style="display:none">
                        <span>Kembalian</span>
                        <span id="dispKembali">Rp 0</span>
                    </div>

                    <br>
                    <button type="submit" name="bayar" class="primary" id="btnBayar" disabled>
                        Proses Bayar
                    </button>
                </form>
            <?php else: ?>
                <p class="empty">Keranjang kosong</p>
            <?php endif ?>

            <?php if (isset($_GET['sukses'])): ?>
                <div class="sukses">✓ Transaksi berhasil! Keranjang telah direset.</div>
            <?php endif ?>
        </div>

    </div>

    <!-- Kirim total ke JS -->
    <script>const totalBayar = <?= (int) $total_bayar ?>;</script>
    <script src="js/kasir.js"></script>
</body>

</html>