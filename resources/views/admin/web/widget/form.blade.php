@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        <h1>Widget</h1>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ route('web_widget.index') }}"> Widget</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="validasi">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('admin.layouts.components.tombol_kembali', ['url' => route('web_widget.index'), 'label' => 'Widget'])
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="posisi">Posisi Widget</label>
                        <div class="col-sm-6">
                            <select id="posisi" name="posisi" class="form-control input-sm required">
                                @foreach (\App\Models\Widget::POSISI as $value => $label)
                                    <option value="{{ $value }}" @selected(old('posisi', $widget?->posisi ?? 'kanan') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">Penempatan widget mengikuti area yang disediakan tema.</span>
                        </div>
                    </div>
                    @if ($widget?->jenis_widget != \App\Models\Widget::WIDGET_SISTEM)
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="judul">Judul Widget</label>
                        <div class="col-sm-6">
                            <input id="judul" name="judul" class="form-control input-sm strip_tags judul required" type="text" placeholder="Judul Widget" value="{{ old('judul', $widget?->judul) }}"></input>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="judul">Gambar Widget</label>
                        <div class="col-sm-6">
                            <img class="img-responsive" src="{{ $gambar ?? asset('assets/images/404-image-not-found.jpg') }}" alt="Gambar Utama Widget">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path">
                                <input type="file" class="hidden" id="file" name="foto" accept=".jpg,.jpeg,.png,.gif">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info " id="file_browser"><i class="fa fa-search"></i></button>
                                </span>
                                <span class="input-group-addon" style="background-color: red; border: 1px solid #ccc;">
                                    <input type="checkbox" title="Centang Untuk Hapus Gambar" name="hapus_foto" value="hapus">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div id="statis" class="form-group">
                        <label class="col-sm-3 control-label" for="isi-statis">Nama File Widget (.php)</label>
                        <div class="col-sm-6">
                            @if ($list_widget)
                                <select id="isi-statis" name="isi-statis" class="form-control input-sm select2 required">
                                    <option value="">-- Pilih Widget --</option>
                                    @foreach ($list_widget as $theme => $widgets)
                                        <optgroup label="{{ $theme }}">
                                            @foreach ($widgets as $temaWidget)
                                                <option @selected(old('isi-statis', $widget?->isi) === str_replace('.blade.php', '', basename($temaWidget))) value="{{ str_replace('.blade.php', '', basename($temaWidget)) }}">
                                                    {{ $temaWidget }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            @else
                                <span class="help-block"><code>Widget tidak tersedia atau sudah ditambahkan semua (desa/widgets atau desa/themes/nama_tema/resources/views/widgets)</code></span>
                            @endif
                        </div>
                    </div>
                    @php($videoSetting = json_decode($widget?->setting ?? '{}', true) ?? [])
                    <div id="video-setting" class="form-group">
                        <label class="col-sm-3 control-label" for="video_url">Video YouTube</label>
                        <div class="col-sm-6">
                            <input id="video_url" name="video_url" type="text" class="form-control input-sm" value="{{ old('video_url', $videoSetting['video_id'] ?? '') }}" placeholder="URL atau ID video YouTube">
                            <label><input type="checkbox" name="video_autoplay" value="1" @checked(old('video_autoplay', ($videoSetting['autoplay'] ?? false) ? '1' : null))> Putar otomatis tanpa suara</label>
                            <span class="help-block">Pemutaran otomatis mengikuti kebijakan browser dan YouTube.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="status">Status</label>
                        <div class="col-sm-6">
                            <select name="status" id="status" class="form-control input-sm required">
                                @foreach (\App\Enums\AktifEnum::all() as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $widget?->enabled) !== null && old('status', $widget?->enabled) == $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class='box-footer'>
                    <button type='reset' class='btn btn-social  btn-danger btn-sm'><i class='fa fa-times'></i>
                        Batal</button>
                    <button type='submit' class='btn btn-social  btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#isi-statis').on('change', function() {
                var video = $(this).val() === 'video';
                $('#video-setting').toggle(video);
                $('#video_url').toggleClass('required', video);
            }).trigger('change');
            $("#jenis_widget").change(function() {
                var selectedValue = $(this).val();
                var statis = $("#statis");
                var isiStatisInput = $("#isi-statis");

                if (selectedValue == 2) {
                    statis.show();
                    isiStatisInput.addClass("required");
                } else {
                    statis.hide();
                    isiStatisInput.removeClass("required");
                }
            });

            $("#jenis_widget").trigger("change");
        });
    </script>
@endpush
