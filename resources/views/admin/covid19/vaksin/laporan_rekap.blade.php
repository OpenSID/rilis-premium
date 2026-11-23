@extends('admin.layouts.index')

@section('title')
    <h1>Pendataan Penerima Vaksin Covid-19</h1>
@endsection

@section('breadcrumb')
    <li class="active">Data Penerima Vaksin</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            <div class="box box-info">
                <div class="box-body no-padding">
                    @include('admin.covid19.vaksin.side')
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="box box-info">
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <form id="mainform" name="mainform" method="get" action="{{ url('vaksin_covid/laporan_rekap') }}">
                            <div class="row">
                                <div class="col-sm-8">
                                    <a href="javascript:;" title="Cetak" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#cetakBox"><i class="fa fa-print"></i> Cetak</a>
                                    <a href="javascript:;" title="Unduh" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#unduhBox"><i class="fa fa-file-excel-o"></i> Unduh</a>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm pull-right">
                                        <input name="umur" id="umur" class="form-control ui-autocomplete-input" placeholder="Rentang Umur" title="Contoh : 20-30" type="text" value="{{ $umur == 0 ? '' : $umur }}" onkeypress="if (event.keyCode == 13){$('#'+'mainform').submit();}" autocomplete="off">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default" onclick="$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    @include('admin.covid19.vaksin.laporan_rekap_table')
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="cetakBox" class="modal fade" role="dialog" style="padding-top:30px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cetak Laporan Penduduk Penerima Vaksin Covid-19</h4>
                </div>
                @include('admin.covid19.vaksin.dialog_cetak', ['form_action' => url('vaksin_covid/laporan_rekap_cetak/cetak'), 'aksi' => 'Cetak'])
            </div>
        </div>
    </div>

    <div id="unduhBox" class="modal fade" role="dialog" style="padding-top:30px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Unduh Laporan Penduduk Penerima Vaksin Covid-19</h4>
                </div>
                @include('admin.covid19.vaksin.dialog_cetak', ['form_action' => url('vaksin_covid/laporan_rekap_cetak/unduh'), 'aksi' => 'Unduh'])
            </div>
        </div>
    </div>
@endsection
