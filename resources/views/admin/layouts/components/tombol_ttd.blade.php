{{-- Dikirim POST + CSRF lewat modal #confirm-aktifkan (konfirmasi_aktifkan); route tujuan WAJIB Route::post. --}}
@if (can('u'))
    @if ($active == 0)
        <a href="#" data-href="{{ $url }}" data-toggle="modal" data-target="#confirm-aktifkan" data-method="POST"
           data-body="Pamong ini akan dijadikan penandatangan {{ $label }}."
           class="btn bg-purple btn-sm"
           title="Jadikan TTD {{ $label }}">{{ $label }}</a>
    @else
        <a href="#" data-href="{{ $url }}" data-toggle="modal" data-target="#confirm-aktifkan" data-method="POST"
           data-body="Pamong ini tidak lagi menjadi penandatangan {{ $label }}."
           class="btn bg-navy btn-sm"
           title="Bukan TTD {{ $label }}">{{ $label }}</a>
    @endif
@endif
