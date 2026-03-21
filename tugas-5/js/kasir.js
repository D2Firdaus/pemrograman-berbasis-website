// Total bayar dikirim dari PHP lewat variabel global
// Pastikan di kasir.php ada: <script>const totalBayar = <?= (int)$total_bayar ?>;</script>

function hitungKembali() {
  const uang = parseFloat(document.getElementById('uangTunai').value) || 0;
  const box  = document.getElementById('kembaliBox');
  const disp = document.getElementById('dispKembali');
  const btn  = document.getElementById('btnBayar');

  if (!uang) {
    box.style.display = 'none';
    btn.disabled = true;
    return;
  }

  const kem = uang - totalBayar;
  box.style.display = 'flex';

  if (kem >= 0) {
    disp.textContent = 'Rp ' + Math.round(kem).toLocaleString('id-ID');
    box.className = 'kembalian';
    btn.disabled = false;
  } else {
    disp.textContent = '- Rp ' + Math.round(Math.abs(kem)).toLocaleString('id-ID');
    box.className = 'kembalian kurang';
    btn.disabled = true;
  }
}

function validasi() {
  const uang = parseFloat(document.getElementById('uangTunai').value) || 0;
  if (uang < totalBayar) {
    alert('Uang kurang!');
    return false;
  }
  return true;
}