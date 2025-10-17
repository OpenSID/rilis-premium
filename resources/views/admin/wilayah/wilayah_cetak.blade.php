@extends('admin.layouts.print_layout')

@section('title', 'Data Wilayah')

@section('styles')
    <!-- TODO: Pindahkan ke external css -->
    <style>
        td {
            mso-number-format: "\@";
        }
    </style>
@endsection

@section('header')
    <div class="header" align="center">
        <label align="left">{{ get_identitas() }}</label>
        <h3>Tabel Data Kependudukan berdasarkan Populasi Per Wilayah</h3>
    </div>
    <br>
@endsection

@section('content')
    <table class="border thick">
        <thead>
            <tr class="border thick">
                <th>No</th>
                <th>Nama {{ ucwords(setting('sebutan_dusun')) }}</th>
                <th>Nama RW</th>
                <th>Nama RT</th>
                <th>NIK Kepala/Ketua</th>
                <th>Nama Kepala/Ketua</th>
                <th>RW</th>
                <th>RT</th>
                <th>KK</th>
                <th>L+P</th>
                <th>L</th>
                <th>P</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($dusuns as $indeks => $dusun)
                <tr>
                    <td align="center">{{ $no++ }}</td>
                    <td>{{ strtoupper($dusun->dusun) }}</td>
                    <td></td>
                    <td></td>
                    <td>{{ $dusun->kepala->nik ?? '' }}</td>
                    <td>{{ $dusun->kepala->nama ?? '' }}</td>
                    <td align="right">{{ $dusun->rws_count }}</td>
                    <td align="right">{{ $dusun->rts_count }}</td>
                    <td align="right">{{ $dusun->keluarga_aktif_count }}</td>
                    <td align="right">{{ $dusun->penduduk_pria_count + $dusun->penduduk_wanita_count }}</td>
                    <td align="right">{{ $dusun->penduduk_pria_count }}</td>
                    <td align="right">{{ $dusun->penduduk_wanita_count }}</td>
                </tr>
                @foreach ($dusun->rws as $rw)
                    <tr>
                        <td align="center">{{ $no++ }}</td>
                        <td></td>
                        <td>{{ strtoupper($rw->rw) }}</td>
                        <td></td>
                        @if (!in_array($rw->rw, ['-', '']))
                            <td>{{ $rw->kepala->nik ?? '' }}</td>
                            <td>{{ $rw->kepala->nama ?? '' }}</td>
                        @else
                            <td></td>
                            <td></td>
                        @endif
                        <td align="right"></td>
                        <td align="right">{{ $rw->rts_count }}</td>
                        <td align="right">{{ $rw->keluarga_aktif_count }}</td>
                        <td align="right">{{ $rw->penduduk_pria_count + $rw->penduduk_wanita_count }}</td>
                        <td align="right">{{ $rw->penduduk_pria_count }}</td>
                        <td align="right">{{ $rw->penduduk_wanita_count }}</td>
                    </tr>
                    @foreach ($rw->rts->where('rw', $rw->rw)->where('rt', '!=', '-') as $rt)
                        <tr>
                            <td align="center">{{ $no++ }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ strtoupper($rt->rt) }}</td>
                            <td>{{ $rt->kepala->nik ?? '' }}</td>
                            <td>{{ $rt->kepala->nama ?? '' }}</td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right">{{ $rt->keluarga_aktif_count }}</td>
                            <td align="right">{{ $rt->penduduk_pria_count + $rt->penduduk_wanita_count }}</td>
                            <td align="right">{{ $rt->penduduk_pria_count }}</td>
                            <td align="right">{{ $rt->penduduk_wanita_count }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color:#BDD498;font-weight:bold;">
                <td colspan="6" align="left"><label>TOTAL</label></td>
                <td align="right">{{ $dusuns->sum('rws_count') }}</td>
                <td align="right">{{ $dusuns->sum('rts_count') }}</td>
                <td align="right">{{ $dusuns->sum('keluarga_aktif_count') }}</td>
                <td align="right">{{ $dusuns->sum('penduduk_pria_count') + $dusuns->sum('penduduk_wanita_count') }}</td>
                <td align="right">{{ $dusuns->sum('penduduk_pria_count') }}</td>
                <td align="right">{{ $dusuns->sum('penduduk_wanita_count') }}</td>
            </tr>
        </tfoot>
    </table>
@endsection

@section('signature')
    @include('admin.layouts.components.blok_ttd_pamong', ['total_col' => 12, 'spasi_kiri' => 2, 'spasi_tengah' => 6])
@endsection
