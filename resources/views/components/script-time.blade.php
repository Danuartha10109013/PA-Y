<script>
    function updateDateTime() {
        const currentDateTimeElement = document.getElementById('currentDateTime');
        
        // Ambil waktu sekarang
        const now = new Date();

        // Format tanggal
        const optionsDate = { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        };
        const formattedDate = now.toLocaleDateString('en-US', optionsDate);

        // Format waktu (jam:menit:detik)
        const formattedTime = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });

        // Gabungkan tanggal dan waktu
        currentDateTimeElement.textContent = `${formattedDate}, ${formattedTime}`;
    }

    // Jalankan pertama kali saat halaman dimuat
    updateDateTime();

    // Perbarui setiap detik
    setInterval(updateDateTime, 1000);
</script>
