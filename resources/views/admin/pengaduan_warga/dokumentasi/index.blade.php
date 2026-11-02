@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@section('title')
    <h1>
        Dokumentasi Penyelesaian Pengaduan
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengaduan_admin') }}">Daftar Pengaduan</a></li>
    <li class="active">Dokumentasi Pengaduan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <x-tambah-button :url="'pengaduan_dokumentasi/form-dokumentasi/'.$pengaduan->id" />
            @php
                $listCetakUnduh = [
                    [
                        'url' => "pengaduan_dokumentasi/dialog/{$pengaduan->id}/cetak",
                        'judul' => 'Cetak',
                        'icon' => 'fa fa-print',
                        'modal' => true,
                    ],
                    [
                        'url' => "pengaduan_dokumentasi/dialog/{$pengaduan->id}/unduh",
                        'judul' => 'Unduh',
                        'icon' => 'fa fa-download',
                        'modal' => true,
                    ]
                ];
            @endphp

            <x-split-button
                judul="Cetak/Unduh"
                :list="$listCetakUnduh"
                :icon="'fa fa-arrow-circle-down'"
                :type="'bg-purple'"
                :target="true"
            />
            <x-kembali-button judul="Kembali Ke Daftar Pengaduan" url="pengaduan_admin" />
        </div>
        <div class="box-body">
            <h5 class="text-bold">Rincian Pengaduan</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <td width="20%">Judul Pengaduan</td>
                            <td width="1">:</td>
                            <td>{{ $pengaduan->judul }}</td>
                        </tr>
                        <tr>
                            <td>Pelapor</td>
                            <td> : </td>
                            <td>{{ $pengaduan->nama }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Pengaduan</td>
                            <td> : </td>
                            <td>{{ tgl_indo2($pengaduan->created_at) }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td> : </td>
                            <td>
                                <span class="label {{ \App\Enums\StatusPengaduanEnum::label()[$pengaduan->status] }}">{{ \App\Enums\StatusPengaduanEnum::valueOf($pengaduan->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Penyelesaian</td>
                            <td> : </td>
                            <td>{{ $pengaduan->tanggal_selesai ? tgl_indo2($pengaduan->tanggal_selesai) : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Petugas Penanganan</td>
                            <td> : </td>
                            <td>{{ $pengaduan->petugas?->nama ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr style="margin-bottom: 5px; margin-top: -5px;">
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th class="padat">GAMBAR</th>
                            <th>JENIS</th>
                            <th>KETERANGAN</th>
                            <th class="padat">TAMPIL PUBLIK</th>
                            <th>PETUGAS</th>
                            <th>TANGGAL REKAM</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('pengaduan_dokumentasi.datatables-dokumentasi', $pengaduan->id) }}",
                    method: 'POST',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'gambar',
                        name: 'gambar',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jenis',
                        name: 'jenis',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tampil_publik',
                        name: 'tampil_publik',
                        class: 'padat',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'petugas',
                        name: 'petugas',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            if (ubah == 0) {
                TableData.column(1).visible(false);
            }

            // Balik status tampil publik langsung dari tabel
            $('#tabeldata').on('click', '.btn-toggle-publik', function() {
                const tombol = $(this).prop('disabled', true);

                $.ajax({
                    url: tombol.data('url'),
                    method: 'POST',
                    dataType: 'json',
                }).done(function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false,
                    });
                    TableData.ajax.reload(null, false);
                }).fail(function() {
                    tombol.prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Status tampil publik gagal diubah',
                    });
                });
            });
        });
    </script>
@endpush
