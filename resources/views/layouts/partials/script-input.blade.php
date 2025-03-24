function formatIDR(input, hiddenFieldId) {
    // Ambil nilai input dan hapus semua karakter selain angka
    let value = input.value.replace(/[^0-9]/g, '');

    // Format angka menjadi IDR
    let formatted = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);

    // Tampilkan hasil format ke input
    input.value = formatted.replace('Rp', 'IDR ').trim();

    // Simpan nilai mentah (integer) ke input hidden
    document.getElementById(hiddenFieldId).value = parseInt(value, 10) || 0;
}
