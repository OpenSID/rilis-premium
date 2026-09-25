
<div class="tab-pane active">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-info">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabeldata">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Modul</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    var TableData = $('#tabeldata').DataTable({
        responsive: true,
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ config('services.opendesa.server_layanan') . '/api/v1/pemesanan' }}",
            method: 'GET',
            headers: {
                'Authorization': 'Bearer {{ $token_layanan }}',
                'Accept': 'application/json'
            },
            dataFilter: function(response) {
                const json = JSON.parse(response);

                // Layanan modul dikenali dari kategorinya ('Modul'), sama seperti
                // StatusLangganan::modulDipesan(). Nama layanan tidak lagi selalu
                // diawali "Modul", sehingga awalan nama hanya dipakai sebagai
                // cadangan bila payload tidak menyertakan kategori.
                const isModul = l => {
                    const kategori = l?.nama_kategori
                        ?? l?.detail?.nama_kategori
                        ?? l?.detail?.kategori?.nama
                        ?? l?.detail?.kategori
                        ?? l?.kategori?.nama
                        ?? l?.kategori;

                    if (typeof kategori === 'string' && kategori.trim() !== '') {
                        return kategori.trim().toLowerCase() === 'modul';
                    }

                    return typeof l?.detail?.nama === 'string' && /^modul/i.test(l.detail.nama.trim());
                };

                const filteredMessages = (json.messages || []).filter(item => {
                    return (item.pemesanan_layanan || []).some(isModul);
                });

                filteredMessages.forEach((item, index) => {
                    const layananModul = (item.pemesanan_layanan || []).filter(isModul);

                    item.no = index + 1;
                    item.harga = layananModul
                        .map(l => l?.detail?.harga ?? '-')
                        .join('<br>') || '-';
                    item.modul_nama = layananModul
                        .map(l => $('<div>').text(l?.detail?.nama ?? '-').html())
                        .join('<br>') || '-';
                });

                return JSON.stringify({
                    data: filteredMessages,
                    recordsTotal: filteredMessages.length,
                    recordsFiltered: filteredMessages.length
                });
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
                class: 'padat',
                orderable: false,
                searchable: false
            },
            {
                data: 'modul_nama',
                name: 'modul_nama',
                orderable: false,
                searchable: false
            },
            {
                data: 'harga',
                name: 'harga',
                orderable: false,
                searchable: false
            }
        ],
        pageLength: 10,
        aaSorting: []
    });
});
</script>
@endpush


