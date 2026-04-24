<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/koneksi.php";

$redirect = $base_url . 'pages/daftar_buku.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $check_sql = "SELECT COUNT(*) FROM detail_pesanan WHERE Buku_ID = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        header("Location: " . $redirect . "?status=terikat");
    } else {
        $delete_sql = "DELETE FROM buku WHERE ID = ?";
        $stmt_delete = $conn->prepare($delete_sql);
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            header("Location: " . $redirect . "?status=berhasil");
        } else {
            header("Location: " . $redirect . "?status=gagal");
        }
        $stmt_delete->close();
    }
} else {
    header("Location: " . $redirect . "?status=invalid");
}

$conn->close();
exit;
?>