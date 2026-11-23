@if (can('u'))
    <a href="{{ url("vaksin_covid/form?terdata={$data->id}") }}" class="btn bg-orange btn-flat btn-sm" title="Update Vaksin"><i class="fa fa-edit"></i></a>
@endif
<div class="btn-group">
    <button type="button" class="btn btn-social btn-flat btn-info btn-sm unduh" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-arrow-circle-down"></i> Unduh
    </button>
    <ul class="dropdown-menu" role="menu">
        @if ($data->tunda)
            @if ($data->surat_dokter != null)
                <li>
                    <a href="{{ url("vaksin_covid/berkas/{$data->id_penduduk}/surat_dokter/true") }}" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-file"></i> Unduh Surat Dokter</a>
                </li>
            @endif
        @else
            @for ($i = 1; $i <= 3; $i++)
                @if ($data->{"dokumen_vaksin_{$i}"} != null || $data->{"dokumen_vaksin_{$i}"} != '')
                    <li>
                        <a href="{{ url("vaksin_covid/berkas/{$data->id_penduduk}/dokumen_vaksin_{$i}/true") }}" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-file"></i> Unduh Sertifikat Vaksin {{ $i }}</a>
                    </li>
                @endif
            @endfor
        @endif
    </ul>
</div>
<a href="{{ url("vaksin_covid/tampil_sertifikat/{$data->id_penduduk}") }}" class="btn bg-blue btn-flat btn-sm tampil" title="Tampilkan"><i class="fa fa-eye"></i></a>
