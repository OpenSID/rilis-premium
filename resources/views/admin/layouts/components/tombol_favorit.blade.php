{{-- Dikirim POST + CSRF lewat modal #confirm-aktifkan (konfirmasi_aktifkan); route tujuan WAJIB Route::post. --}}
@if (can('u'))
    @if ($show == '1')
        @if ($active == '0')
            <a href="#" data-href="{{ $url }}" data-toggle="modal" data-target="#confirm-aktifkan" data-method="POST" data-body="Surat ini akan ditambahkan ke daftar favorit." class="btn bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a>
        @elseif ($active == '1')
            <a href="#" data-href="{{ $url }}" data-toggle="modal" data-target="#confirm-aktifkan" data-method="POST" data-body="Surat ini akan dikeluarkan dari daftar favorit." class="btn bg-purple btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a>
        @endif
    @endif
@endif
