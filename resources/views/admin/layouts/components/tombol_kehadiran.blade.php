{{-- Dikirim POST + CSRF lewat modal #confirm-aktifkan (konfirmasi_aktifkan); route tujuan WAJIB Route::post. --}}
@if (can('u'))
    @if ($active == '0')
        <a href="#" data-href="{{ $url }}" data-toggle="modal" data-target="#confirm-aktifkan" data-method="POST" data-body="Kehadiran perangkat ini akan diaktifkan." class="btn bg-aqua btn-sm" title="Aktifkan Kehadiran Perangkat"><i class="fa fa-ban"></i></a>
    @else
        <a href="#" data-href="{{ $url }}" data-toggle="modal" data-target="#confirm-aktifkan" data-method="POST" data-body="Kehadiran perangkat ini akan dinonaktifkan." class="btn bg-aqua btn-sm" title="Nonaktifkan Kehadiran Perangkat"><i class="fa fa-check"></i></a>
    @endif
@endif
