@if (auth()->user()->roles == 'admin')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        <div class="content-background" style="background: white">
            <h3>Riwayat Potongan Gaji Anggota</h3>
            <div class="mb-3">
                <strong>Nama Anggota:</strong> {{ $pengajuanPinjaman->user->name }}<br>
                <strong>Nomor Pinjaman:</strong> {{ $pengajuanPinjaman->nomor_pinjaman }}<br>
                <strong>Jenis Pinjaman:</strong>
                {{ ucwords(str_replace('_', ' ', $pengajuanPinjaman->jenis_pinjaman)) }}<br>
                <strong>Status Pembayaran:</strong>
                <span
                    class="badge {{ $pengajuanPinjaman->status_pembayaran === 'Aktif' ? 'badge-warning' : 'badge-success' }}">
                    {{ $pengajuanPinjaman->status_pembayaran }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead style="background-color: #EEEEEE;">
                        <tr>
                            <th>No</th>
                            <th>Jumlah Pembayaran</th>
                            <th>Bukti Pembayaran</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaryHistory as $key => $history)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>Rp {{ number_format($history->jumlah_pembayaran, 2) }}</td>
                                <td>
                                    @if ($history->bukti_pembayaran)
                                        <a href="javascript:void(0)" data-image="{{ asset($history->bukti_pembayaran) }}"
                                            onclick="previewImage(this)">
                                            <i class="fa fa-file"></i> Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada bukti</span>
                                    @endif
                                </td>

                                <td><span class="badge badge-border-{{ $history->status == 'sukses' ? 'success' : 'warning' }}">{{ ucfirst($history->status) }}</span></td>
                                <td>{{ $history->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('data.potongan.gaji') }}" class="btn btn-primary">Kembali</a>
        </div>

        <!-- Modal for Image Preview -->
        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imagePreviewModalLabel">Pratinjau Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="imagePreview" src="" alt="Bukti Pembayaran" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
        <script>
            function previewImage(element) {
                // Ambil URL gambar dari atribut data-image
                const imageUrl = element.getAttribute('data-image');
                
                // Set URL gambar ke elemen img di modal
                document.getElementById('imagePreview').src = imageUrl;
        
                // Tampilkan modal
                const previewModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
                previewModal.show();
            }
        </script>
        
    @endsection
@endif
