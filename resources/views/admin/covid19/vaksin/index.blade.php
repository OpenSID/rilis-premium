@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.jquery_ui')
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
    @if ($pesan_vaksin = session('pesan_vaksin'))
        <div class="box box-info">
            <div class="box-header with-border">
                @include('admin.layouts.components.tombol_kembali', ['url' => url('vaksin_covid/clear'), 'label' => 'Data Vaksin'])
            </div>
            <div class="box-body">
                <h4>Hasil Impor Data Penerima Vaksin</h4>
                <p>{!! $pesan_vaksin !!}</p>
            </div>
        </div>
    @else
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
                        @if (can('u'))
                            <a href="{{ url('vaksin_covid/form') }}" title="Tambah Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Data</a>
                            <a href="{{ url('vaksin_covid/impor') }}" title="Impor Data" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-import" data-target="#impor" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false"><i class="fa fa-upload"></i>Impor</a>
                        @endif
                        <a href="{{ url('vaksin_covid/clear') }}" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <form id="mainform" name="mainform" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-8">
                                        <select class="form-control input-sm" name="vaksin" onchange="formAction('mainform', '{{ url('vaksin_covid/filter/vaksin') }}')" style="margin-bottom: 5px;">
                                            <option value="">-- Status Vaksin --</option>
                                            <option value="1" @selected($vaksin == '1')>Vaksin Dosis 1</option>
                                            <option value="2" @selected($vaksin == '2')>Vaksin Dosis 2</option>
                                            <option value="3" @selected($vaksin == '3')>Vaksin Dosis 3</option>
                                            <option value="4" @selected($vaksin == '4')>Belum</option>
                                            <option value="5" @selected($vaksin == '5')>Tunda</option>
                                        </select>
                                        <select class="form-control input-sm " name="dusun" onchange="formAction('mainform','{{ url('vaksin_covid/filter/dusun') }}')" style="margin-bottom: 5px;">
                                            <option value="">-- Pilih {{ ucwords(setting('sebutan_dusun')) }} --</option>
                                            @foreach ($list_dusun as $data)
                                                <option value="{{ $data['dusun'] }}" @selected($dusun == $data['dusun'])>{{ ucwords(strtolower($data['dusun']), ' -') }}</option>
                                            @endforeach
                                        </select>
                                        <select class="form-control input-sm " name="jenis_vaksin" onchange="formAction('mainform','{{ url('vaksin_covid/filter/jenis_vaksin') }}')" style="margin-bottom: 5px;">
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
                                            <input name="umur" id="umur" class="form-control ui-autocomplete-input" placeholder="Rentang Umur" title="Contoh : 20-30" type="text" value="{{ $umur == 0 ? '' : $umur }}" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '{{ url('vaksin_covid/filter/umur') }}');$('#mainform').submit();}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group input-group-sm pull-right">
                                            <input name="cari" id="cari" class="form-control ui-autocomplete-input" placeholder="Cari..." type="text" value="{{ $cari }}" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '{{ url('vaksin_covid/search') }}');$('#'+'mainform').submit();}" autocomplete="off">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default" onclick="$('#mainform').attr('action', '{{ url('vaksin_covid/search') }}');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="tabeldata">
                                    <thead class="bg-gray color-palette">
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>Nik</th>
                                            <th>Nama</th>
                                            <th>No KK</th>
                                            <th>Umur</th>
                                            <th>{{ ucwords(setting('sebutan_dusun')) }}</th>
                                            <th>Alamat</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @include('admin.covid19.vaksin.impor')
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tgl-datepicker').datetimepicker({
                format: 'DD-MM-YYYY',
                useCurrent: false
            });

            $('.tgl-datepicker').on('dp.change', function(e) {
                formAction('mainform', '{{ url('vaksin_covid/filter/tanggal_vaksin') }}')
            });

            $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vaksin_covid.datatables') }}",
                    method: 'POST',
                },
                columns: [
                    {data: 'DT_RowIndex', class: 'padat', searchable: false, orderable: false},
                    {data: 'aksi', class: 'aksi', searchable: false, orderable: false},
                    {data: 'nik', name: 'p.nik', class: 'padat'},
                    {data: 'nama', name: 'p.nama'},
                    {data: 'no_kk', name: 'kk.no_kk', class: 'padat'},
                    {data: 'umur', name: 'umur', class: 'padat'},
                    {data: 'dusun', name: 'ck.dusun'},
                    {data: 'alamat', name: 'alamat', searchable: false, orderable: false},
                    {data: 'keterangan_vaksin', searchable: false, orderable: false},
                ],
                order: [[3, 'asc']],
                drawCallback: function() {
                    $('#tabeldata tbody tr').each(function(index, el) {
                        if ($(el).find('button.unduh').parent().find('li').length == 0) {
                            $(el).find('button.unduh').addClass('disabled');
                            $(el).find('a.tampil').addClass('disabled');
                        }
                    });
                }
            });

            $("#cari").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        type: "POST",
                        url: `${SITE_URL}vaksin_covid/autocomplete`,
                        dataType: "json",
                        data: {
                            cari: request.term
                        },
                        success: function(data) {
                            response(JSON.parse(data));
                            csrf_semua_form();
                        }
                    });
                },
                minLength: 2,
            });
        });
    </script>
@endpush
