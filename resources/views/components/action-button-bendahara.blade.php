<button
    type="button"
    class="btn {{ $class ?? 'btn-primary' }}"
    onclick="updateStatusPinjaman('{{ $status }}')">
    {{ $slot }}
</button>
