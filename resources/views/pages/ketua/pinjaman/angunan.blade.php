@if (auth()->user()->roles == 'ketua')
    @extends('layouts.dashboard-layout')
    @section('title', $title)
    @section('content')
        <div class="content-background" style="background: white">
            @include('layouts.partials.pinjaman.angunan')
        </div>
        <x-script-ketua />
    @endsection
@endif
