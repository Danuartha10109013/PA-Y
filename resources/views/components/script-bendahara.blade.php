    <script>
        function updateDateTime() {
            const currentDateTimeElement = document.getElementById('currentDateTime');
            const now = new Date();

            const optionsDate = {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            };

            // Format waktu
            const formattedDate = now.toLocaleDateString('en-US', optionsDate);
            const formattedTime = now.toLocaleTimeString('en-US', {
                hour12: false
            });

            // Gabungkan tanggal dan waktu
            currentDateTimeElement.textContent = `${formattedDate}, ${formattedTime}`;
        }

        // Perbarui waktu setiap detik
        setInterval(updateDateTime, 1000);
        // Inisialisasi waktu saat halaman dimuat
        updateDateTime();


        // Select All Checkbox Logic
        document.getElementById('select-all').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.checkbox-item').forEach(checkbox => checkbox.checked = isChecked);
        });

        // Individual Checkbox Change Listener
        document.querySelectorAll('.checkbox-item').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                console.log(
                    `Checkbox Pinjaman ID ${this.dataset.id} is ${this.checked ? 'checked' : 'unchecked'}.`
                );
            });
        });

        // Update Status Logic
        function updateStatusPinjaman(status) {
            const messages = {
                Diterima: {
                    confirm: "Apakah anda yakin ingin menyetujui anggota ini?",
                    success: "Anggota telah disetujui."
                },
                Ditolak: {
                    confirm: "Apakah anda yakin ingin menolak anggota ini?",
                    success: "Anggota telah ditolak."
                }
            };

            Swal.fire({
                title: "Approve Anggota?",
                text: messages[status].confirm,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes!"
            }).then(result => {
                if (result.isConfirmed) {
                    const selectedIds = Array.from(document.querySelectorAll('.checkbox-item:checked')).map(cb => cb
                        .dataset.id);

                    selectedIds.forEach(id => {
                        $.post(`/bendahara/pinjaman/${id}/${status}`, {
                            _token: "{{ csrf_token() }}",
                            status: status
                        }).done(() => {
                            Swal.fire("Success!", messages[status].success, "success").then(() =>
                                location.reload());
                        }).fail(xhr => {
                            Swal.fire("Error!", `Terjadi kesalahan: ${xhr.responseText}`, "error");
                        });
                    });
                }
            });
        }

        // Filter Data Function
        function filterData(status) {
            $.get(`/data/filter/${status}`, response => $('tbody').html(response));
        }

        // Update Showing Info
        const totalData = 24,
            perPage = 8;
        let currentPage = 1;

        function updateShowingInfo() {
            const start = (currentPage - 1) * perPage + 1;
            const end = Math.min(currentPage * perPage, totalData);
            document.getElementById('start').textContent = start;
            document.getElementById('end').textContent = end;
            document.getElementById('total').textContent = totalData;
        }

        updateShowingInfo();

        // Preview Image Function
        function previewImage(imageUrl) {
            // Buat elemen overlay
            const overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            overlay.style.zIndex = '1000';
            overlay.style.display = 'flex';
            overlay.style.justifyContent = 'center';
            overlay.style.alignItems = 'center';
            overlay.onclick = function() {
                document.body.removeChild(overlay); // Hapus overlay saat di-klik
            };

            // Tambahkan gambar ke overlay
            const image = document.createElement('img');
            image.src = imageUrl;
            image.style.maxWidth = '50%'; // Ukuran pratinjau lebih kecil (50% dari lebar layar)
            image.style.maxHeight = '50%'; // Ukuran pratinjau lebih kecil (50% dari tinggi layar)
            image.style.border = '2px solid white';
            image.style.borderRadius = '10px';

            overlay.appendChild(image);
            document.body.appendChild(overlay);
        }
    </script>
