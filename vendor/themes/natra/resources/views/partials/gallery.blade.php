@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="single_category wow fadeInDown">
    <h2><span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Galeri {{
            $desa["nama_desa"] }}</span></h2>
</div>

<div style="content_left">
    <div class="row">
        @if ($gallery)
        @php
        $jumlah = 0;
        @endphp
        @foreach ($gallery as $data)
        @if (file_exists(LOKASI_GALERI . "sedang_" . $data['gambar']) || $data['jenis'] == 2)
        @php
        $gambar = $data['jenis'] == 2 ? $data['gambar'] : AmbilGaleri($data['gambar'], 'kecil');
        $jumlah++;
        @endphp
        <a href="{{ci_route("galeri/{$data['id']}") }}">
            <div class="col-sm-6">
                <div class="card">
                    <img width="auto" class="img-fluid img-thumbnail" src="{{ $gambar }}" alt="{{ $data['nama'] }}" />
                    <p align="center"><b>Album : {{ $data['nama'] }}</b></p>
                    <hr />
                </div>
            </div>
        </a>
        @endif
        @endforeach

        @if ($jumlah == 0)
        <div class="alert alert-danger" role="alert">
            Data tidak tersedia
        </div>
        @endif
        @else
        <div class="alert alert-danger" role="alert">
            Data tidak tersedia
        </div>
        @endif
    </div>

    @include("commons.page")
</div>