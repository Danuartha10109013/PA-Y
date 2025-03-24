@props(['statusKetua'])

<td>
    @if ($statusKetua === 'Diterima')
        <span class="badge badge-border-success">Diterima Ketua</span>
    @elseif($statusKetua === 'Ditolak')
        <span class="badge badge-border-danger">Ditolak Ketua</span>
    @else
        <span class="badge badge-border-warning">Pengajuan</span>
    @endif
</td>
