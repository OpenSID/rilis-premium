@php
    $tipe = $tipe ?? 'info';
    $judul = $judul ?? 'Informasi';
    $ikon = $tipe === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
@endphp
<div
    class="modal fade"
    id="notif"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
    data-backdrop="false"
    data-keyboard="false"
>
    <div class="modal-dialog notifikasi">
        <div class="modal-content">
            <div class="modal-header bg-{{ $tipe }}">
                <h4 class="modal-title" id="myModalLabel"><i class="fa {{ $ikon }}"></i> {{ $judul }}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{!! $pesan !!}</p>
            </div>
            <div class="modal-footer">
                <a href="{!! $aksi !!}" class="btn btn-social btn-{{ $tipe }} btn-sm"><i class="fa fa-check"></i> OK</a>
            </div>
        </div>
    </div>
</div>
