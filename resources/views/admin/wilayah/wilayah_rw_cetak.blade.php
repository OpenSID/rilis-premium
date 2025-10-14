@extends('admin.layouts.print_layout')

@section('title', 'Data Wilayah')

@section('styles')
    <!-- TODO: Pindahkan ke external css -->
    <style>
        .textx {
            mso-number-format: "\@";
        }

        td,
        th {
            font-size: 6.5pt;
        }
    </style>
@endsection

@section('header')
    <div class="header" align="center">
        <label align="left">{{ get_identitas() }}</label>
        <h3>DATA WILAYAH ADMINISTRASI</h3>
        <h4>RW {{ strtoupper(setting('sebutan_dusun')) }} {{ strtoupper($dusun) }}</h4>
    </div>
    <br>
@endsection

@section('content')
    <table class="border thick">
        <thead>
            <tr class="border thick">
                <th width="30">No</th>
                <th width="50">RW</th>
                <th width="100">NIK Ketua RW</th>
                <th width="100">Nama Ketua RW</th>
                <th width="50">Jumlah RT</th>
                <th width="50">Jumlah KK</th>
                <th width="50">L+P</th>
                <th width="50">L</th>
                <th width="50">P</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($rws as $rw)
                <tr>
                    <td align="center">{{ $no++ }}</td>
                    <td>{{ strtoupper($rw->rw) }}</td>
                    <td>{{ $rw->kepala->nik ?? '' }}</td>
                    <td>{{ $rw->kepala->nama ?? '' }}</td>
                    <td align="right">{{ $rw->rts_count }}</td>
                    <td align="right">{{ $rw->keluarga_aktif_count }}</td>
                    <td align="right">{{ $rw->penduduk_pria_count + $rw->penduduk_wanita_count }}</td>
                    <td align="right">{{ $rw->penduduk_pria_count }}</td>
                    <td align="right">{{ $rw->penduduk_wanita_count }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color:#BDD498;font-weight:bold;">
                <td colspan="4" align="left"><label>TOTAL</label></td>
                <td align="right">{{ $rws->sum('rts_count') }}</td>
                <td align="right">{{ $rws->sum('keluarga_aktif_count') }}</td>
                <td align="right">{{ $rws->sum('penduduk_pria_count') + $rws->sum('penduduk_wanita_count') }}</td>
                <td align="right">{{ $rws->sum('penduduk_pria_count') }}</td>
                <td align="right">{{ $rws->sum('penduduk_wanita_count') }}</td>
            </tr>
        </tfoot>
    </table>
    <label>Tanggal cetak : &nbsp; </label>
    {{ tgl_indo(date('Y m d')) }}
@endsection
