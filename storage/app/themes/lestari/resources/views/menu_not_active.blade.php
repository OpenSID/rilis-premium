@extends('theme::template')

@section('layout')
    @if (theme_config('tampilan_beranda', 'modern') === 'klasik')
        @include('theme::partials.header')
        <div class="contentpage">
            <div class="margin-page">
                @include('theme::partials.not_found')
            </div>
            @include('theme::partials.modulepage')
            @include('theme::partials.footer')
        </div>
    @else
        @include('theme::partials.header')
        @include('theme::partials.beranda.kerangka', [
            'bagian' => ['tidak_ditemukan'],
            'kerangkaKelas' => 'lv-shell lv-shell--isi',
            'lvTidakDitemukan' => [
                'judul' => ($judulPesan ?? '') ?: 'Halaman belum tersedia',
                'pesan' => strip_tags($isiPesan ?? '') ?: 'Halaman ini belum dibuka untuk umum atau sedang disiapkan. Silakan kembali ke beranda atau cari informasi lain di bawah.',
                'menu' => empty($judulPesan),
            ],
        ])
        @include('theme::partials.footer')
    @endif
@endsection
