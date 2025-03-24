<script>
    document.getElementById('select-all').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.checkbox-item').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        toggleActionButtons();
    });

    document.querySelectorAll('.checkbox-item').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            toggleActionButtons();
        });
    });

    function toggleActionButtons() {
        const anyChecked = document.querySelectorAll('.checkbox-item:checked').length > 0;
        document.getElementById('btn-terima').disabled = !anyChecked;
        document.getElementById('btn-tolak').disabled = !anyChecked;
    }


    function updateStatusPinjaman(status) {
        const confirmMessage = status === 'Diterima' ?
            "Apakah anda yakin ingin menyetujui anggota ini?" :
            "Apakah anda yakin ingin menolak anggota ini?";

        Swal.fire({
            title: "Approve Anggota?",
            text: confirmMessage,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes!"
        }).then(result => {
            if (result.isConfirmed) {
                document.querySelectorAll('.checkbox-item:checked').forEach(checkbox => {
                    const id = checkbox.getAttribute('data-id');
                    $.ajax({
                        url: `/manager/pinjaman/${id}/${status}`,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            status: status
                        },
                        success: function() {
                            Swal.fire({
                                title: "Success!",
                                text: status === 'Diterima' ?
                                    "Anggota telah disetujui." :
                                    "Anggota telah ditolak.",
                                icon: "success"
                            }).then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire("Error!", `Terjadi kesalahan: ${xhr.responseText}`,
                                "error");
                        }
                    });

                });
            }
        });
    }

    function filterData(status) {
        $.ajax({
            url: `/data/filter/${status}`,
            type: 'GET',
            success: function(response) {
                $('tbody').html(response);
            }
        });
    }

    function updateShowingInfo() {
        const totalData = 24;
        const perPage = 8;
        const currentPage = 1;
        const start = (currentPage - 1) * perPage + 1;
        const end = Math.min(currentPage * perPage, totalData);

        document.getElementById('start').innerText = start;
        document.getElementById('end').innerText = end;
        document.getElementById('total').innerText = totalData;
    }

    $(document).ready(() => {
        updateShowingInfo();

        ['all', 'diterima', 'pengajuan', 'ditolak'].forEach((status, index) => {
            $.ajax({
                url: `/count-data/${status}`,
                type: 'GET',
                success: function(response) {
                    $('.btn-wrapper .count').eq(index).text(response.count);
                }
            });
        });
    });

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
