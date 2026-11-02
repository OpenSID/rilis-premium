@if (in_array('keuangan_template_tidak_sama', $masalah))
    @component('periksa.partials.kartu', ['kode' => 'keuangan_template_tidak_sama', 'judul' => $judulMasalah['keuangan_template_tidak_sama'] ?? 'Template keuangan tidak sesuai', 'jumlah' => count($keuangan_template_tidak_sama), 'ikon' => 'fa-money', 'jenis' => 'otomatis'])
        <p>Format atau kode UUID pada tabel <code>keuangan_template</code> tidak sesuai dengan <code>KeuanganTemplateSeeder</code>. Klik <b>Perbaiki Data</b> untuk menyesuaikan kode UUID dan <code>parent_uuid</code>.</p>
        <div class="tw-overflow-x-auto">
            <table class="pk-table table" id="table-keuangan-template">
                <thead>
                    <tr>
                        <th class="tw-w-px">No</th>
                        <th>Uraian</th>
                        <th>UUID Saat Ini</th>
                        <th>Parent UUID Saat Ini</th>
                        <th>Masalah</th>
                        <th>Seharusnya</th>
                        <th>Rencana Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($keuangan_template_tidak_sama as $idx => $item)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $item['uraian'] }}</td>
                            <td><code>{{ $item['current_uuid'] }}</code></td>
                            <td>
                                @if ($item['current_parent_uuid'] === null)
                                    <span class="pk-badge pk-badge-slate">null</span>
                                @else
                                    <code>{{ $item['current_parent_uuid'] }}</code>
                                @endif
                            </td>
                            <td><span class="tw-text-rose-600">{{ $item['problems'] }}</span></td>
                            <td>
                                @if ($item['expected_uuid'] === null)
                                    <span class="pk-badge pk-badge-slate">Perlu konfirmasi manual</span>
                                @else
                                    <div class="tw-space-y-1 tw-text-xs">
                                        <div>UUID: <code>{{ $item['expected_uuid'] }}</code></div>
                                        <div>Parent:
                                            @if ($item['expected_parent_uuid'] === null)
                                                <span class="pk-badge pk-badge-slate">null</span>
                                            @else
                                                <code>{{ $item['expected_parent_uuid'] }}</code>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if (($item['tindakan'] ?? '') === 'Hapus & Pindahkan Relasi')
                                    <span class="pk-badge pk-badge-danger"><i class="fa fa-trash"></i> Hapus &amp; Pindahkan Relasi</span>
                                @elseif (($item['tindakan'] ?? '') === 'Tambah Data')
                                    <span class="pk-badge pk-badge-info"><i class="fa fa-plus"></i> Tambah Data</span>
                                @elseif (($item['tindakan'] ?? '') === 'Perlu Konfirmasi (Ambigu)')
                                    <span class="pk-badge pk-badge-warning"><i class="fa fa-question-circle"></i> Perlu Konfirmasi</span>
                                @else
                                    <span class="pk-badge pk-badge-slate"><i class="fa fa-edit"></i> Perbarui Parent UUID</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @slot('aksi')
            @include('periksa.partials.tombol', ['url' => route('periksa.perbaiki.keuangan_template_item')])
        @endslot
    @endcomponent

    <script>
        (function initTabelKeuanganTemplate() {
            if (typeof jQuery === 'undefined' || typeof jQuery.fn.DataTable === 'undefined') {
                setTimeout(initTabelKeuanganTemplate, 50);
                return;
            }

            if (!jQuery.fn.DataTable.isDataTable('#table-keuangan-template')) {
                jQuery('#table-keuangan-template').DataTable({
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
                    language: {
                        url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}"
                    },
                    columnDefs: [{ orderable: false, targets: [0, 6] }]
                });
            }
        })();
    </script>
@endif
