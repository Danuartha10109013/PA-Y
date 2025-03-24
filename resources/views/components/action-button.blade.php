<button
    type="button"
    class="btn {{ $class ?? 'btn-primary' }}"
    onclick="updateStatusPinjaman('{{ $status }}')"
    id="{{ $id ?? '' }}"
    {{ $attributes }}>
    {{ $slot }}
</button>
