<?php
// Tidak perlu tag <script> kalau ini dipanggil dalam <script> utama
?>

<script>
    // Fungsi format angka sebagai Rupiah
    function formatRupiah(angka) {
        const number = parseFloat(angka) || 0;
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    // Fungsi format nilai tunjangan (bisa Rp atau %)
    function formatTunjanganValue(value, satuan = 'Rp') {
        const number = parseFloat(value) || 0;
        if (satuan === '%') {
            return number + ' %';
        }
        return formatRupiah(number);
    }
</script>