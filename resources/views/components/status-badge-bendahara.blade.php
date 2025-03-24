@props(['statusBendahara', 'statusKetua'])

<td>
    @if ($statusKetua === 'Diterima')
        <span class="badge badge-border-success">Diterima Ketua</span>
    @elseif ($statusKetua === 'Ditolak')
        <span class="badge badge-border-danger">Ditolak Ketua</span>
    @elseif ($statusBendahara === 'Diterima')
        <span class="badge badge-border-warning">Menunggu Approve Ketua</span>
    @elseif ($statusBendahara === 'Ditolak')
        <span class="badge badge-border-danger">Ditolak Bendahara</span>
    @else
        <span class="badge badge-border-warning">Pengajuan</span>
    @endif
</td>
