@component('periksa.partials.kartu', ['kode' => 'modul_asing', 'judul' => $judulMasalah['modul_asing'] ?? 'Modul asing pada grup akses', 'jumlah' => count($modul_asing), 'ikon' => 'fa-puzzle-piece', 'jenis' => 'otomatis'])
    <p>Tabel <code>grup_akses</code> berisi hak akses untuk modul yang sudah tidak ada. Klik <b>Perbaiki Data</b> untuk menghapus hak akses tersebut.</p>
    <div class="pk-table-wrap">
        <table class="pk-table">
            <thead><tr><th class="tw-w-px">No</th><th>Grup</th><th>Id Modul</th></tr></thead>
            <tbody>
                @foreach ($modul_asing as $grupAkses)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $grupAkses['grup']['nama'] }}</td>
                        <td><code>{{ $grupAkses['id_modul'] }}</code></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @slot('aksi')
        @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'modul_asing')])
    @endslot
@endcomponent
