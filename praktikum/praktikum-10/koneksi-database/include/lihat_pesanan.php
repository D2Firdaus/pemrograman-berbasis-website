<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/koneksi.php";

$per_page = 10;
$page     = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset   = ($page - 1) * $per_page;

$total_pesanan = $conn->query("SELECT COUNT(*) FROM pesanan")->fetch_row()[0];
$total_page    = ceil($total_pesanan / $per_page);

$stmt_id = $conn->prepare("SELECT ID FROM pesanan ORDER BY Tanggal_Pesanan DESC, ID DESC LIMIT ? OFFSET ?");
$stmt_id->bind_param("ii", $per_page, $offset);
$stmt_id->execute();
$result_id = $stmt_id->get_result();
$stmt_id->close();

$ids = [];
while ($r = $result_id->fetch_assoc()) {
    $ids[] = $r['ID'];
}

$pesanan_list = [];

if (!empty($ids)) {
    $placeholders = implode(',', $ids);

    $sql = "SELECT p.ID, p.Tanggal_Pesanan, p.Total_Harga, pl.Nama,
                   b.Judul, dp.Kuantitas, dp.Harga_Per_Satuan,
                   (dp.Kuantitas * dp.Harga_Per_Satuan) AS Subtotal
            FROM pesanan p
            JOIN pelanggan pl ON p.Pelanggan_ID = pl.ID
            JOIN detail_pesanan dp ON dp.Pesanan_ID = p.ID
            JOIN buku b ON b.ID = dp.Buku_ID
            WHERE p.ID IN ($placeholders)
            ORDER BY p.Tanggal_Pesanan DESC, p.ID DESC";

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $id = $row['ID'];
        if (!isset($pesanan_list[$id])) {
            $pesanan_list[$id] = [
                'ID'              => $row['ID'],
                'Nama'            => $row['Nama'],
                'Tanggal_Pesanan' => $row['Tanggal_Pesanan'],
                'Total_Harga'     => $row['Total_Harga'],
                'detail'          => [],
            ];
        }
        $pesanan_list[$id]['detail'][] = [
            'Judul'            => $row['Judul'],
            'Kuantitas'        => $row['Kuantitas'],
            'Harga_Per_Satuan' => $row['Harga_Per_Satuan'],
            'Subtotal'         => $row['Subtotal'],
        ];
    }
}

$conn->close();
?>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="table-dark">
            <tr>
                <th class="text-center">ID</th>
                <th>Pelanggan</th>
                <th class="text-center">Tanggal</th>
                <th>Judul Buku</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Harga Satuan</th>
                <th class="text-center">Subtotal</th>
                <th class="text-center">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pesanan_list)): ?>
                <tr>
                    <td colspan="8" class="text-center">Belum ada pesanan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pesanan_list as $p): ?>
                    <?php $rowspan = count($p['detail']); ?>
                    <?php foreach ($p['detail'] as $i => $d): ?>
                        <tr class="align-middle" data-pesanan="<?= $p['ID'] ?>">
                            <?php if ($i === 0): ?>
                                <td class="text-center" rowspan="<?= $rowspan ?>"><?= $p['ID'] ?></td>
                                <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($p['Nama']) ?></td>
                                <td class="text-center" rowspan="<?= $rowspan ?>"><?= $p['Tanggal_Pesanan'] ?></td>
                            <?php endif; ?>
                            <td><?= htmlspecialchars($d['Judul']) ?></td>
                            <td class="text-center"><?= $d['Kuantitas'] ?></td>
                            <td class="text-center">Rp <?= number_format($d['Harga_Per_Satuan'], 0, ',', '.') ?></td>
                            <td class="text-center">Rp <?= number_format($d['Subtotal'], 0, ',', '.') ?></td>
                            <?php if ($i === 0): ?>
                                <td class="text-center" rowspan="<?= $rowspan ?>">Rp <?= number_format($p['Total_Harga'], 0, ',', '.') ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($total_page > 1): ?>
    <nav>
        <ul class="pagination justify-content-end">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>"><</a>
            </li>
            <?php for ($i = 1; $i <= $total_page; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $page >= $total_page ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>">></a>
            </li>
        </ul>
    </nav>
<?php endif; ?>

<script>
document.querySelectorAll('tr[data-pesanan]').forEach(row => {
    row.addEventListener('mouseenter', () => {
        const id = row.dataset.pesanan;
        document.querySelectorAll(`tr[data-pesanan="${id}"]`).forEach(r => r.classList.add('table-active'));
    });
    row.addEventListener('mouseleave', () => {
        const id = row.dataset.pesanan;
        document.querySelectorAll(`tr[data-pesanan="${id}"]`).forEach(r => r.classList.remove('table-active'));
    });
});
</script>