@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaduan
        <small>{{ $action }}</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengaduan_admin') }}">Daftar Pengaduan</a></li>
    <li class="active">{{ $action }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <x-kembali-button judul="Kembali Ke Daftar Pengaduan" url="pengaduan_admin" />
        </div>
        <div class="box-body">
            <ul class="timeline timeline-inverse">
                <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-blue">
                        {{ tgl_indo($pengaduan->created_at) }}
                    </span>
                </li>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <li>
                    <i class="fa fa-user bg-blue"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{ $pengaduan->created_at->format('H:i') }}</span>

                        <h3 class="timeline-header"><b>{{ $pengaduan->nama }}</b> {{ $pengaduan->judul }}</h3>

                        <div class="timeline-body">
                            {{ $pengaduan->isi }}
                        </div>
                        <div class="timeline-footer">
                            <p><i class="fa fa-id-card"></i> {{ $pengaduan->nik }}</p>
                            <p><i class="fa fa-phone"></i> {{ $pengaduan->telepon }}</p>
                            <p><i class="fa fa-envelope"></i> {{ $pengaduan->email }}</p>
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->
                <!-- timeline item -->
                @if ($pengaduan->foto)
                    <li>
                        <i class="fa fa-image bg-blue"></i>

                        <div class="timeline-item">
                            <div class="timeline-body">
                                <img class="img-responsive" src="{{ to_base64(LOKASI_PENGADUAN . $pengaduan->foto) }}">
                            </div>
                        </div>
                    </li>
                @endif
                <!-- END timeline item -->
                <!-- timeline item : tanggapan & dokumentasi, urut kronologis -->
                @php
                    $riwayat = $pengaduan->child
                        ->map(static fn ($item) => ['tipe' => 'tanggapan', 'data' => $item])
                        ->concat($pengaduan->dokumentasi->map(static fn ($dok) => ['tipe' => 'dokumentasi', 'data' => $dok]))
                        ->sortBy(static fn ($row) => $row['data']->created_at)
                        ->values();
                    $tanggalSebelumnya = null;
                @endphp
                @foreach ($riwayat as $row)
                    @php($item = $row['data'])
                    @if ($tanggalSebelumnya !== $item->created_at->format('Y-m-d'))
                        @php($tanggalSebelumnya = $item->created_at->format('Y-m-d'))
                        <li class="time-label">
                            <span class="bg-green">
                                {{ tgl_indo($item->created_at) }}
                            </span>
                        </li>
                    @endif
                    @if ($row['tipe'] === 'tanggapan')
                        <li>
                            <i class="fa fa-comments bg-green"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> {{ $item->created_at->format('H:i') }}</span>

                                <h3 class="timeline-header"><span class="text-primary text-bold">{{ $item->nama }} </span> Menanggapi</h3>

                                <div class="timeline-body">
                                    {{ $item->isi }}
                                </div>
                            </div>
                        </li>
                    @else
                        <li>
                            <i class="fa fa-camera bg-purple"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> {{ $item->created_at->format('H:i') }}</span>

                                <h3 class="timeline-header">
                                    <span class="label {{ \App\Enums\JenisDokumentasiPengaduanEnum::label()[$item->jenis] }}">{{ \App\Enums\JenisDokumentasiPengaduanEnum::valueOf($item->jenis) }}</span>
                                    Dokumentasi oleh <b>{{ $item->petugas?->nama ?? '-' }}</b>
                                    @unless ($item->tampil_publik)
                                        <span class="label label-default">Internal</span>
                                    @endunless
                                </h3>

                                <div class="timeline-body">
                                    <img class="img-responsive" src="{{ to_base64(LOKASI_PENGADUAN . $item->gambar) }}">
                                    @if ($item->keterangan)
                                        <p style="margin-top:10px;">{{ $item->keterangan }}</p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
                <!-- END timeline item -->
                <!-- timeline item : info penyelesaian -->
                @if ($pengaduan->tanggal_selesai)
                    <li class="time-label">
                        <span class="bg-green">
                            Selesai {{ tgl_indo($pengaduan->tanggal_selesai) }}
                        </span>
                    </li>
                    <li>
                        <i class="fa fa-check-circle bg-green"></i>

                        <div class="timeline-item">
                            <div class="timeline-body">
                                Diselesaikan oleh <b>{{ $pengaduan->petugas?->nama ?? '-' }}</b>
                            </div>
                        </div>
                    </li>
                @endif
                <!-- END timeline item -->
                <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                </li>
            </ul>
        </div>
    </div>
@endsection
