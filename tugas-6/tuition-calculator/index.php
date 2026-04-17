<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuition Discount Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrapper">
        <!-- soal a -->
        <form action="" method="GET">

            <h1>Tuition Calculator</h1>
            <label for="nama">Nama:</label>
            <input type="text" name="nama" required>

            <label for="npm">NPM:</label>
            <input type="text" name="npm" minlength="13" maxlength="13" required>

            <label for="prodi">Prodi:</label>
            <input type="text" name="prodi" required>

            <label for="semester">Semester:</label>
            <input type="number" name="semester" min="1" max="14" required>

            <label for="biayaukt">Biaya UKT:</label>
            <input type="number" name="biayaukt" required>

            <input type="submit" name="submit">
        </form>

        <?php
        if (isset($_GET["submit"])) {
            if (
                // guard clause tambahan untuk memastikan semua field diisi dan npm berupa angka
                !empty($_GET["nama"]) && !empty($_GET["npm"]) && !empty($_GET["prodi"]) && !empty($_GET["semester"]) && !empty($_GET["biayaukt"]) && is_numeric($_GET["npm"])
            ) {
                $nama = $_GET["nama"];
                $npm = $_GET["npm"];
                $prodi = $_GET["prodi"];
                $semester = $_GET["semester"];
                $biayaukt = $_GET["biayaukt"];
                $diskon = 0;
                $jumlahbayar = $biayaukt;

                // soal b
                if ($biayaukt >= 5000000) {
                    $diskon += 0.1;

                    // soal c
                    // soal hanya menyebut diskon tambahan untuk yang membayar lebih dari 5 juta dan semester lebih dari 8, jadi saya asumsikan diskon tambahan hanya berlaku untuk yang membayar lebih dari 5 juta saja
                    if ($semester > 8) {
                        $diskon += 0.05;
                    }
                }

                $jumlahbayar = $biayaukt - ($biayaukt * $diskon);
            } else if (!is_numeric($_GET["npm"])) {
                echo "NPM harus berupa angka!";
            } else {
                echo "Semua field harus diisi!";
            }
        }
        ?>

        <div class="result" id="result">
            <?php
            if (isset($nama) && isset($npm) && isset($prodi) && isset($semester) && isset($biayaukt)) {
                echo "<h2>Hasil Perhitungan</h2>";
                echo "Nama: " . $nama . "<br>";
                echo "NPM: " . $npm . "<br>";
                echo "Prodi: " . $prodi . "<br>";
                echo "Semester: " . $semester . "<br>";
                echo "Biaya UKT: Rp." . number_format($biayaukt, 0, ',', '.') . "<br>";
                echo "Diskon: " . ($diskon * 100) . "%<br>";
                echo "<hr>";
                echo "Jumlah Bayar: Rp." . number_format($jumlahbayar, 0, ',', '.') . "<br>";
            }
            ?>
        </div>
    </div>
</body>

</html>