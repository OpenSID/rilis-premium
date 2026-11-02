{{--
    Tombol ubah status (aktif/nonaktif, kunci, dsb) dengan modal konfirmasi #confirm-aktifkan
    (admin.layouts.components.konfirmasi_aktifkan). Aksi selalu dikirim non-GET + CSRF,
    route tujuan WAJIB Route::post (default) atau sesuai `method`.

    Parameter:
    - url    : URL aksi
    - active : status saat ini ('1' aktif, '0' tidak aktif)
    - pesan  : (opsional) pesan konfirmasi, boleh HTML — escape data dinamis sebelum dikirim
    - method : (opsional) POST (default), PUT, PATCH, atau DELETE — selain POST via field _method
    - ajax   : (opsional) true jika endpoint mengembalikan JSON {success, message}
    - judul, icon, type : (opsional) override tampilan tombol
--}}
@if (can('u'))
    @if ($active == '0' || $active == '1')
        @php
            $judulAksi  = $judul ?? ($active == '0' ? 'Aktifkan' : 'Nonaktifkan');
            $ikonAksi   = $icon ?? ($active == '0' ? 'fa fa-lock' : 'fa fa-unlock');
            $pesanAksi  = $pesan ?? ($active == '0' ? 'Data ini akan diaktifkan.' : 'Data ini akan dinonaktifkan.');
            $methodAksi = in_array(strtoupper($method ?? ''), ['PUT', 'PATCH', 'DELETE'], true) ? strtoupper($method) : 'POST';
        @endphp
        <a
            href="#"
            data-href="{{ $url }}"
            data-toggle="modal"
            data-target="#confirm-aktifkan"
            data-method="{{ $methodAksi }}"
            data-body="{{ $pesanAksi }}"
            @if ($ajax ?? false) data-ajax="true" @endif
            class="btn {{ $type ?? 'bg-navy' }} btn-sm"
            title="{{ $judulAksi }}"
        ><i class="{{ $ikonAksi }}"></i></a>
    @endif
@endif
