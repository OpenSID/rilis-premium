@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaturan Database
    </h1>
@endsection

@section('breadcrumb')
    <li class="active"><a href="{{ route('database.index') }}">Pengaturan Database</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li {!! $act_tab == 1 ? 'class="active"' : '' !!}>
                <a href="{{ route('database.index') }}">Backup
                    {{ config('opensid.demo_mode') == false ? ' /Restore' : '' }}
                </a>
            </li>
            @if (can('u'))
                <li {!! $act_tab == 2 ? 'class="active"' : '' !!}><a href="{{ route('database.migrasi_cri') }}">Migrasi DB</a></li>
            @endif
            @if (is_super_admin())
                <li {!! $act_tab == 3 ? 'class="active"' : '' !!}><a href="{{ route('database.bersih_folder') }}"><i class="fa fa-trash-o"></i> Bersihkan Folder Desa</a></li>
            @endif
            @if (app()->environment('development'))
                <li {!! $act_tab == 4 ? 'class="active"' : '' !!}><a href="{{ route('database.gabungan_dev') }}"><i class="fa fa-code"></i> Database Gabungan (Dev)</a></li>
            @endif
        </ul>
        <div class="tab-content">
            @include($content)
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script>
            function showLoadingForm(text = 'Sedang memproses data', autoClose = false, autoCloseDelay = 2000) {
                Swal.fire({
                    title: 'Mohon tunggu...',
                    text: text,
                    allowOutsideClick: false,
                    allowEscapeKey: !autoClose,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Auto-close untuk download/proses yang tidak reload page
                if (autoClose) {
                    setTimeout(() => {
                        Swal.close();
                    }, autoCloseDelay);
                }

                const submitBtn = document.querySelector('form button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                }
            }
        </script>
    @endpush
