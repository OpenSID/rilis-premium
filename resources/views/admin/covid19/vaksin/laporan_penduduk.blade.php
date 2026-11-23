@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_datatables')

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
                <div class="box-header with-border">
                    <a href="javascript:;" title="Cetak" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#cetakBox"><i class="fa fa-print"></i> Cetak</a>
                    <a href="javascript:;" title="Unduh" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#unduhBox"><i class="fa fa-file-excel-o"></i> Unduh</a>
                    <a href="{{ url('vaksin_covid/clear/laporan_penduduk') }}" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
                </div>
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <form id="mainform" name="mainform" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-control input-sm" name="vaksin" onchange="formAction('mainform', '{{ url('vaksin_covid/filter/vaksin/laporan_penduduk') }}')" style="margin-bottom: 5px;">
                                        <option value="">-- Status Vaksin --</option>
                                        <option value="1" @selected($vaksin == '1')>Vaksin Dosis 1</option>
                                        <option value="2" @selected($vaksin == '2')>Vaksin Dosis 2</option>
                                        <option value="3" @selected($vaksin == '3')>Vaksin Dosis 3</option>
                                        <option value="4" @selected($vaksin == '4')>Belum</option>
                                        <option value="5" @selected($vaksin == '5')>Tunda</option>
                                    </select>
                                    <select class="form-control input-sm " name="dusun" onchange="formAction('mainform','{{ url('vaksin_covid/filter/dusun/laporan_penduduk') }}')" style="margin-bottom: 5px;">
                                        <option value="">-- Pilih {{ ucwords(setting('sebutan_dusun')) }} --</option>
                                        @foreach ($list_dusun as $data)
                                            <option value="{{ $data['dusun'] }}" @selected($dusun == $data['dusun'])>{{ ucwords(strtolower($data['dusun']), ' -') }}</option>
                                        @endforeach
                                    </select>
                                    <select class="form-control input-sm " name="jenis_vaksin" onchange="formAction('mainform','{{ url('vaksin_covid/filter/jenis_vaksin/laporan_penduduk') }}')" style="margin-bottom: 5px;">
                                        <option value="">-- Pilih Jenis Vaksin --</option>
                                        @foreach ($list_vaksin as $data)
                                            <option value="{{ $data }}" @selected($jenis_vaksin == $data)>{{ ucwords(strtolower($data), ' -') }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group input-group-sm" style="margin-bottom: 5px;">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control input-sm tgl-datepicker" name="tanggal_vaksin" value="{{ $tanggal_vaksin }}">
                                    </div>
                                    <div class="input-group input-group-sm" style="margin-bottom: 5px;">
                                        <div class="input-group-addon">
                                            <i class="fa fa-filter"></i>
                                        </div>
                                        <input name="umur" id="umur" class="form-control ui-autocomplete-input" placeholder="Rentang Umur" title="Contoh : 20-30" type="text" value="{{ $umur == 0 ? '' : $umur }}" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '{{ url('vaksin_covid/filter/umur/laporan_penduduk') }}');$('#mainform').submit();}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="tabeldata">
                                <thead class="bg-gray color-palette">
                                    <tr>
                                        <th>No</th>
                                        <th>No KK</th>
                                        <th>Nama</th>
                                        <th>Nik</th>
                                        <th>Alamat {{ ucwords(setting('sebutan_dusun')) }}</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Umur</th>
                                        <th>Vaksin I</th>
                                        <th>Vaksin II</th>
                                        <th>Vaksin III</th>
                                        <th>Belum</th>
                                        <th>Tunda</th>
                                        <th>Ket</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
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
                @include('admin.covid19.vaksin.dialog_cetak', ['form_action' => url('vaksin_covid/laporan_penduduk_cetak/cetak'), 'aksi' => 'Cetak'])
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
                @include('admin.covid19.vaksin.dialog_cetak', ['form_action' => url('vaksin_covid/laporan_penduduk_cetak/unduh'), 'aksi' => 'Unduh'])
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tgl-datepicker').datetimepicker({
                format: 'DD-MM-YYYY',
                useCurrent: false
            });

            $('.tgl-datepicker').on('dp.change', function(e) {
                formAction('mainform', '{{ url('vaksin_covid/filter/tanggal_vaksin/laporan_penduduk') }}')
            });
            $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vaksin_covid.datatables') }}",
                    method: 'POST',
                    data: {laporan: 1},
                },
                columns: [
                    {data: 'DT_RowIndex', class: 'padat', searchable: false, orderable: false},
                    {data: 'no_kk', name: 'kk.no_kk'},
                    {data: 'nama', name: 'p.nama'},
                    {data: 'nik', name: 'p.nik'},
                    {data: 'dusun', name: 'ck.dusun'},
                    {data: 'jenis_kelamin', name: 's.nama'},
                    {data: 'tempatlahir', name: 'p.tempatlahir'},
                    {data: 'tanggallahir', name: 'p.tanggallahir', searchable: false},
                    {data: 'umur', name: 'umur', class: 'padat'},
                    {data: 'v1', class: 'padat', searchable: false, orderable: false},
                    {data: 'v2', class: 'padat', searchable: false, orderable: false},
                    {data: 'v3', class: 'padat', searchable: false, orderable: false},
                    {data: 'belum', class: 'padat', searchable: false, orderable: false},
                    {data: 'tunda', class: 'padat', searchable: false, orderable: false},
                    {data: 'ket', searchable: false, orderable: false},
                ],
                order: [[2, 'asc']]
            });
        });
    </script>
@endpush
