@include('admin.layouts.components.asset_datatables')

<div class="box box-info">
    <div class="box-header with-border">
        @php
            $listCetakUnduh = [
                [
                    'url' => 'bumindes_rencana_pembangunan/dialog/cetak',
                    'judul' => 'Cetak',
                    'icon' => 'fa fa-print',
                    'modal' => true,
                ],
                [
                    'url' => 'bumindes_rencana_pembangunan/dialog/unduh',
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
    </div>
    <div class="box-body">
        <div class="row mepet">
            <div class="col-sm-2">
                <select id="tahun" class="form-control input-sm select2">
                    <option value="">Pilih Tahun</option>
                    @foreach ($tahun as $item)
                        <option>{{ $item->tahun_anggaran }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr class="batas">
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                <thead class="bg-gray color-palette">
                    <tr>
                        <th rowspan="2">NOMOR URUT</th>
                        <th rowspan="2">NAMA PROYEK / KEGIATAN</th>
                        <th rowspan="2">LOKASI</th>
                        <th colspan="4">SUMBER DANA</th>
                        <th rowspan="2">JUMLAH</th>
                        <th rowspan="2">PELAKSANA</th>
                        <th rowspan="2">MANFAAT</th>
                        <th rowspan="2">KET</th>
                    </tr>
                    <tr>
                        <th>PEMERINTAH</th>
                        <th>PROVINSI</th>
                        <th>KAB/KOTA</th>
                        <th>SWADAYA</th>
                    </tr>
                </thead>
            </table>
        </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('bumindes_rencana_pembangunan.datatables') }}",
                    data: function(req) {
                        req.tahun = $('#tahun').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'alamat',
                        name: 'wilayah.dusun',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'sumber_biaya_pemerintah',
                        name: 'sumber_biaya_pemerintah',
                        class: 'padat',
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'sumber_biaya_provinsi',
                        name: 'sumber_biaya_provinsi',
                        class: 'padat',
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'sumber_biaya_kab_kota',
                        name: 'sumber_biaya_kab_kota',
                        class: 'padat',
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'sumber_biaya_swadaya',
                        name: 'sumber_biaya_swadaya',
                        class: 'padat',
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'sumber_biaya_jumlah',
                        name: 'sumber_biaya_jumlah',
                        class: 'padat',
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pelaksana_kegiatan',
                        name: 'pelaksana_kegiatan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'manfaat',
                        name: 'manfaat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            });

            $('#tahun').change(function() {
                TableData.draw()
            })
        });
    </script>
@endpush
