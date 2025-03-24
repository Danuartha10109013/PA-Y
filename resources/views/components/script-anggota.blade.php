<script>
    function goBackToHome(element) {
        const roles = element.getAttribute('data-roles');
        const routes = {
            'anggota': "{{ route('home-anggota') }}",
            'admin': "{{ route('home-admin') }}"
        };
        window.location.href = routes[roles] || '#';
    }

    function showRekeningFields() {
        const virtualAccount = document.getElementById("virtual_account").value;
        const sectionRekeningBaru = document.getElementById("section_rekening_baru");
        sectionRekeningBaru.style.display = virtualAccount === "0" ? "block" : "none";
        if (virtualAccount !== "0") {
            sectionRekeningBaru.querySelectorAll("input, select").forEach(input => input.value = '');
        }
    }

    function calculateAngsuran() {
        const amount = parseInt(document.getElementById("amount").value.replace(/[^\d]/g, '')) || 0;
        const jangkaWaktu = parseInt(document.getElementById("jangka_waktu").value) || 0;

        if (amount && jangkaWaktu) {
            const bungaPerTahun = 0.1; // Bunga 10% per tahun
            const bungaPerBulan = bungaPerTahun / 12; // Mengubah bunga menjadi bulanan
            const totalPinjaman = amount * (1 + bungaPerBulan * jangkaWaktu); // Total pinjaman dengan bunga
            const nominalAngsuran = (totalPinjaman / jangkaWaktu).toFixed(0); // Nominal angsuran per bulan

            // Format hasil dalam format Rupiah
            document.getElementById("nominal_angsuran_display").value =
                `Rp ${new Intl.NumberFormat('id-ID').format(nominalAngsuran)}`;
            document.getElementById("nominal_angsuran").value = nominalAngsuran;
        } else {
            // Reset jika input tidak valid
            document.getElementById("nominal_angsuran_display").value = '';
            document.getElementById("nominal_angsuran").value = '';
        }
    }


    function formatIDR(input, hiddenFieldId) {
        const value = input.value.replace(/[^0-9]/g, '');
        input.value = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value).replace('Rp', 'IDR ').trim();
        document.getElementById(hiddenFieldId).value = parseInt(value, 10) || 0;
    }
</script>