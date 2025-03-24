<script>
    // Update status pinjaman function
    function updateStatusPinjaman(status) {
        const confirmMessage = status === 'Diterima' ?
            "Apakah anda yakin ingin menyetujui anggota ini?" :
            "Apakah anda yakin ingin menolak anggota ini?";

        const successMessage = status === 'Diterima' ?
            "Anggota telah disetujui." :
            "Anggota telah ditolak.";

        Swal.fire({
            title: "Approve Anggota?",
            text: confirmMessage,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes!"
        }).then((result) => {
            if (result.isConfirmed) {
                const selectedCheckboxes = document.querySelectorAll('.checkbox-item:checked');

                selectedCheckboxes.forEach(checkbox => {
                    const id = checkbox.dataset.id;

                    $.ajax({
                        url: `/ketua/pinjaman/${id}/${status}`,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: status
                        },
                        success: function() {
                            Swal.fire({
                                title: "Success!",
                                text: successMessage,
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

    // Select all checkboxes handler
    document.getElementById('select-all').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.checkbox-item').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
    });

    // Checkbox individual change event listener
    document.querySelectorAll('.checkbox-item').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const id = this.dataset.id;
            console.log(`Checkbox with ID ${id} is ${this.checked ? 'checked' : 'unchecked'}.`);
        });
    });

    // Fetch counts and update buttons
    $(document).ready(function() {
        const countEndpoints = ['all', 'diterima', 'pengajuan', 'ditolak'];

        countEndpoints.forEach((status, index) => {
            $.ajax({
                url: `/count-data/${status}`,
                type: 'GET',
                success: function(response) {
                    $(`.btn-wrapper .count`).eq(index).text(response.count);
                }
            });
        });
    });

    // Filter data by status
    function filterdata(status) {
        $.ajax({
            url: `/data/filter/${status}`,
            type: 'GET',
            success: function(response) {
                $('tbody').html(response); // Update table content
            }
        });
    }

    // Pagination showing info update
    const totaldata = 24;
    const perPage = 8;
    let currentPage = 1;

    function updateShowingInfo() {
        const start = (currentPage - 1) * perPage + 1;
        const end = Math.min(currentPage * perPage, totaldata);

        document.getElementById('start').innerText = start;
        document.getElementById('end').innerText = end;
        document.getElementById('total').innerText = totaldata;
    }

    updateShowingInfo(); // Initial update

    Swal.fire({
        title: "{{ session('swal.title') }}",
        text: "{{ session('swal.text') }}",
        icon: "{{ session('swal.icon') }}",
    });

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
