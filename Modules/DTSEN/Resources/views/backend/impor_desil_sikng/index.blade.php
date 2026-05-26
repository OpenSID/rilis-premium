@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Impor Desil SIKNg
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('dtsen/pendataan') }}"><i class="fa fa-home"></i> DTSEN</a></li>
    <li class="active">Impor Desil</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <x-btn-button judul="Impor Data Excel" icon="fa fa-upload" modal="true" modalTarget="modal-impor" type="btn-primary" url="#" />
            @if (can('h'))
                <x-hapus-button
                    url="dtsen/impor-desil-sikng/delete-all"
                    :confirmDelete="true"
                    :selectData="true"
                    judul="Hapus Terpilih"
                />
            @endif
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover nowrap" id="tabeldata">
                    <thead class="bg-gray disabled color-palette">
                        <tr>
                            <th><input type="checkbox" id="checkall" /></th>
                            <th>No</th>
                            <th class="padat">Aksi</th>
                            <th>Nomor KK</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Desil</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>

    <!-- Modal Impor -->
    <div class="modal fade" id="modal-impor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-upload"></i> Impor Data Desil dari Excel</h4>
                </div>
                {!! form_open_multipart(ci_route('dtsen/impor-desil-sikng/impor'), 'method="POST"') !!}
                <div class="modal-body">
                    <p class="text-muted">
                        Pastikan format file Excel sesuai dengan yang disarankan.<br>
                        Kolom: <strong>Nomor KK, Desil, NIK, Nama</strong><br>
                        <i class="fa fa-info-circle text-warning"></i>
                        <small>Format kolom <strong>NIK</strong> dan <strong>Nomor KK</strong> di Excel harus <strong>Text</strong> (bukan Number), agar 16 digit tidak terpotong menjadi notasi ilmiah (mis. 1.23E+15).</small>
                    </p>
                    <div class="form-group">
                        <label for="file" class="control-label">File .xlsx untuk diimpor : </label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="file_path" name="userfile_label">
                            <input type="file" class="hidden" id="file" name="userfile" accept=".xlsx" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                            </span>
                        </div>
                        <code>Data dengan NIK sama akan ditimpa.</code>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="hapus_data_lama" value="1"> Hapus semua data lama sebelum impor
                            </label>
                        </div>
                        <br/>
                        <a href="{{ $formatImpor }}" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block text-center"><i class="fa fa-file-excel-o"></i> Contoh Format Impor desil SIKNg</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> Impor</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    @include('admin.layouts.components.konfirmasi_hapus')

    <!-- Hidden form untuk hapus per baris via POST -->
    {!! form_open(ci_route('dtsen/impor-desil-sikng/delete'), 'id="form-delete-row" style="display:none"') !!}
        <input type="hidden" name="id" id="delete-row-id">
    {!! form_close() !!}
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('dtsen/impor-desil-sikng/datatables') }}",
                columns: [
                    { data: 'ceklist', orderable: false, searchable: false },
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'aksi', orderable: false, searchable: false },
                    { data: 'nomor_kk', name: 'nomor_kk' },
                    { data: 'nik', name: 'nik' },
                    { data: 'nama', name: 'nama' },
                    { data: 'desil', name: 'desil' }
                ],
                order: [
                    [3, 'asc']
                ],
                language: {
                    'url': "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}"
                }
            });

            $('#file_browser').click(function(e) {
                e.preventDefault();
                $('#file').click();
            });
            $('#file').change(function() {
                $('#file_path').val($(this).val().replace(/C:\\fakepath\\/i, ''));
            });

            // Tandai modal hapus dibuka oleh tombol per-baris
            $(document).on('click', '.btn-delete-row', function() {
                $('#delete-row-id').val($(this).data('id'));
                $('#confirm-delete').data('mode', 'single-row');
            });

            // Bersihkan state setiap kali modal hapus ditutup,
            // supaya ID lama tidak "nyangkut" saat user pindah ke Hapus Terpilih
            $('#confirm-delete').on('hidden.bs.modal', function() {
                $('#delete-row-id').val('');
                $(this).removeData('mode');
            });

            // Submit form hapus baris HANYA bila modal dibuka oleh tombol per-baris.
            // Untuk Hapus Terpilih, biarkan handler bawaan deleteAllBox() yang menangani.
            $('#confirm-delete').on('click', '#ok-delete', function(e) {
                if ($('#confirm-delete').data('mode') !== 'single-row') {
                    return;
                }
                e.preventDefault();
                e.stopImmediatePropagation();
                var deleteId = $('#delete-row-id').val();
                if (deleteId) {
                    $('#form-delete-row').submit();
                }
            });
        });
    </script>
@endpush
