@if (auth()->user()->roles == 'ketua')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        @if (session('swal'))
            <script>
                Swal.fire(@json(session('swal')));
            </script>
        @endif

        <div class="content-background" style="background: white">
            @include('layouts.partials.pinjaman.emergency')
        </div>
        <x-script-ketua />
    @endsection
@endif
