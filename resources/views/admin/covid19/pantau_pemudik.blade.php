@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')
@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>Pemantauan Isolasi Mandiri Saat Pandemi Covid-19</h1>
@endsection

@section('breadcrumb')
    <li class="active">Data Pemudik</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><strong>Form Pemantauan</strong></h3>
                </div>
                <div class="box-body">
                    <form id="validasi" action="{{ route('covid19.add_pantau') }}" method="POST">
                        @csrf
                        <input type="hidden" id="this_url" value="{{ url('covid19/pantau') }}">
                        <input type="hidden" name="status_covid" id="status_covid">
                        <input type="hidden" id="page" name="page" value="{{ $page }}">

                        <div class="form-group @error('terdata') has-error @enderror">
                            <label for="nama">NIK/Nama</label>
                            <select class="form-control select2" id="terdata" name="terdata">
                                <option value="">-- Silakan Masukan NIK / Nama--</option>
                                @foreach ($pemudik_array as $item)
                                    <option value="{{ $item->id }}" data-statuscovid="{{ $item->covid_id }}" data-tgltiba="{{ $item->tanggal_datang }}" @selected(old('terdata') == $item->id)> {{ $item->terdata_id . ' - ' . $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group @error('tgl_jam') has-error @enderror">
                            <label for="tgl_jam">Tanggal/Jam</label>
                            <input type="text" class="form-control input-sm" name="tgl_jam" id="tgl_jam" value="{{ old('tgl_jam', $datetime_now) }}">
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="tgl_tiba">Tanggal Tiba</label>
                                    <input type="text" class="form-control input-sm" name="tgl_tiba" id="tgl_tiba" value="{{ $datetime_now }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="h_plus">Data H+</label>
                                    <input type="text" class="form-control input-sm" name="h_plus" id="h_plus" value="3" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group @error('suhu') has-error @enderror">
                            <label for="suhu">Suhu Tubuh</label>
                            <input type="text" class="form-control input-sm" name="suhu" id="suhu" placeholder="36.75" value="{{ old('suhu') }}">
                        </div>

                        <div class="table-responsive-sm">
                            <table class="table table-borderless table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-left">Centang jika mengalami kondisi berikut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="20%" class="text-center"><input type="checkbox" class="form-check-input" name="batuk" @checked(old('batuk'))></td>
                                        <td>Batuk</td>
                                    </tr>
                                    <tr>
                                        <td width="20%" class="text-center"><input type="checkbox" class="form-check-input" name="flu" @checked(old('flu'))></td>
                                        <td>Flu</td>
                                    </tr>
                                    <tr>
                                        <td width="20%" class="text-center"><input type="checkbox" class="form-check-input" name="sesak" @checked(old('sesak'))></td>
                                        <td>Sesak nafas</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label for="keluhan">Keluhan Lain</label>
                            <textarea name="keluhan" class="form-control input-sm" placeholder="Keluhan Lain" rows="5">{{ old('keluhan') }}</textarea>
                        </div>
                    </form>
                </div>
                @if (can('u'))
                    <div class="box-footer">
                        <div class="box-tools pull-right">
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right" onclick="$('#validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ url("covid19/daftar/cetak/{$filter_tgl}/{$filter_nik}") }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Cetak</a>
                    <a href="{{ url("covid19/daftar/unduh/{$filter_tgl}/{$filter_nik}") }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Unduh</a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <form id="filterform" name="filterform" method="post">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select class="form-control select2 input-sm" name="unique_date_select" id="unique_date_select" style="width: 100%;">
                                        <option value="0">-- Pilih Tanggal --</option>
                                        @foreach ($unique_date as $row)
                                            <option value="{{ $row->tanggal }}" @selected($filter_tgl == $row->tanggal)> {{ $row->tanggal }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-5">
                                <div class="form-group">
                                    <select class="form-control select2 input-sm" name="unique_nik_select" id="unique_nik_select">
                                        <option value="0">-- Pilih NIK/Nama --</option>
                                        @foreach ($unique_nik as $row)
                                            <option value="{{ $row->id_pemudik }}" @selected($filter_nik == $row->id_pemudik)> {{ $row->nik . ' - ' . $row->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="tabeldata">
                            <thead>
                                <tr>
                                    <th class="padat">NO</th>
                                    @if (can('h'))
                                        <th class="padat">AKSI</th>
                                    @endif
                                    <th>DATA H+</th>
                                    <th>TANGGAL TIBA</th>
                                    <th>WAKTU PANTAU</th>
                                    <th>NIK</th>
                                    <th>NAMA</th>
                                    <th>USIA</th>
                                    <th>JK</th>
                                    <th>SUHU</th>
                                    <th>BATUK</th>
                                    <th>FLU</th>
                                    <th>SESAK</th>
                                    <th>KELUHAN</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('covid19.datatables_pantau') }}",
                    method: 'POST',
                    data: function(d) {
                        d.tanggal = "{{ $filter_tgl }}";
                        d.nik = "{{ $filter_nik }}";
                    }
                },
                order: [],
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    @if (can('h'))
                        {
                            data: 'aksi',
                            class: 'aksi',
                            searchable: false,
                            orderable: false
                        },
                    @endif
                    {
                        data: 'h_plus',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggal_datang',
                        name: 's.tanggal_datang',
                        searchable: false
                    },
                    {
                        data: 'tanggal_jam',
                        name: 'p.tanggal_jam',
                        searchable: false
                    },
                    {
                        data: 'nik',
                        name: 'o.nik'
                    },
                    {
                        data: 'nama',
                        name: 'o.nama'
                    },
                    {
                        data: 'umur',
                        name: 'umur',
                        searchable: false
                    },
                    {
                        data: 'jk',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'suhu_tubuh',
                        name: 'p.suhu_tubuh',
                        searchable: false
                    },
                    {
                        data: 'batuk',
                        name: 'p.batuk',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'flu',
                        name: 'p.flu',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'sesak_nafas',
                        name: 'p.sesak_nafas',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'keluhan_lain',
                        name: 'p.keluhan_lain'
                    },
                    {
                        data: 'status_covid_nama',
                        name: 'status_covid_nama',
                        searchable: false,
                        orderable: false
                    }
                ],
            });

            $('#tgl_jam').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
            });

            function change_arrival_date() {
                var retval = 0;
                if ($("#terdata").val() != "") {
                    $("#status_covid").val($("#terdata").find(':selected').data('statuscovid'));
                    var temp1 = new Date($("#terdata").find(':selected').data('tgltiba'));
                    var tgl_tiba = new Date(temp1.getFullYear() + "-" + (temp1.getMonth() + 1) + "-" + temp1.getDate());

                    var temp2 = new Date($('#tgl_jam').val());
                    var tgl_catat = new Date(temp2.getFullYear() + "-" + (temp2.getMonth() + 1) + "-" + temp2.getDate());

                    var timediff = tgl_catat - tgl_tiba;
                    var diffdays = Math.floor(timediff / 86400000);

                    $("#tgl_tiba").val($("#terdata").find(':selected').data('tgltiba'));
                    $("#h_plus").val(diffdays);

                    retval = diffdays;
                } else {
                    $("#tgl_tiba").val("");
                    $("#h_plus").val("");
                }

                return retval;
            }

            $("#tgl_tiba").val("");
            $("#h_plus").val("");
            $("#terdata").change(function() {
                var diff_day = change_arrival_date();

                var tgl_tiba = moment().subtract(diff_day, 'days').millisecond(0).second(0).minute(0).hour(0);
                var date_now = moment();

                $('#tgl_jam').data("DateTimePicker").options({
                    minDate: tgl_tiba,
                    maxDate: date_now
                });
            });

            $('#tgl_jam').on('dp.change', function(e) {
                change_arrival_date();
            });

            function pindahFilter() {
                var url = $("#this_url").val();
                url += "/" + $("#page").val();
                url += "/" + $("#unique_date_select").val();
                url += "/" + $("#unique_nik_select").val();
                $(location).attr('href', url);
            }

            $("#unique_date_select").change(pindahFilter);
            $("#unique_nik_select").change(pindahFilter);

            $("#validasi").validate({
                rules: {
                    terdata: "required",
                    tgl_jam: "required",
                    suhu: {
                        required: true,
                        number: true,
                        min: 10,
                        max: 50,
                    },
                },
                messages: {
                    terdata: "Harus memilih NIK/Nama",
                    tgl_jam: "Tanggal/Jam harus diisi",
                    suhu: {
                        required: "Suhu harus tercatat",
                        number: "Harus diisi angka",
                        min: "Suhu minimal 10 derajat celcius",
                        max: "Suhu maksimal 50 derajat celcius",
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
