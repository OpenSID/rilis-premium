<form id="form_penduduk" action="{{ $form_action_penduduk }}" method="POST">
    @csrf
    <div class="col-sm-6">
        <div class="form-group @error('nik') has-error @enderror">
            <label for="nik">NIK</label>
            <input name="nik" class="form-control input-sm required nik" type="text" placeholder="Nomor NIK" value="{{ old('nik', $penduduk?->nik) }}">
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group @error('nama') has-error @enderror">
            <label for="i_nama">Nama Lengkap</label>
            <input type="text" class="form-control input-sm required nama" name="nama" placeholder="Nama Lengkap Tanpa Gelar" value="{{ strtoupper((string) old('nama', $penduduk?->nama)) }}">
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group @error('sex') has-error @enderror">
            <label for="sex">Jenis Kelamin</label>
            <select class="form-control input-sm required" name="sex">
                <option value="">-- Pilih Jenis Kelamin --</option>
                @foreach ($jenis_kelamin as $id => $nama)
                    <option @selected(old('sex', $penduduk?->sex) == $id) value="{{ $id }}"> {{ $nama }} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group @error('agama_id') has-error @enderror">
            <label for="agama_id">Agama</label>
            <select class="form-control input-sm required" name="agama_id">
                <option value="">-- Pilih Agama --</option>
                @foreach ($agama as $id => $nama)
                    <option @selected(old('agama_id', $penduduk?->agama_id) == $id) value="{{ $id }}">{{ $nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group @error('tempatlahir') has-error @enderror">
            <label for="tempatlahir">Tempat Lahir</label>
            <input type="text" class="form-control input-sm" name="tempatlahir" placeholder="Tempat Lahir" value="{{ strtoupper((string) old('tempatlahir', $penduduk?->tempatlahir)) }}">
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group @error('tanggallahir') has-error @enderror">
            <label for="tanggallahir">Tanggal Lahir</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right tgl-datepicker required" name="tanggallahir" type="text" value="{{ old('tanggallahir', $penduduk ? tgl_indo_out($penduduk->tanggallahir, '') : '') }}">
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group @error('golongan_darah_id') has-error @enderror">
            <label for="golongan_darah_id">Golongan Darah</label>
            <select class="form-control input-sm required" name="golongan_darah_id">
                <option value="">Pilih Golongan Darah</option>
                @foreach ($golongan_darah as $data)
                    <option @selected(old('golongan_darah_id', $penduduk?->golongan_darah_id) == $data->id) value="{{ $data->id }}"> {{ strtoupper($data->nama) }} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group @error('status') has-error @enderror">
            <label for="status">Status Penduduk </label>
            <select class="form-control input-sm required" name="status">
                @foreach ($status_penduduk as $data)
                    @if ($data->id != 1)
                        <option @selected(old('status', $penduduk?->status) == $data->id) value="{{ $data->id }}"> {{ strtoupper($data->nama) }} </option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group @error('alamat_sekarang') has-error @enderror">
            <label for="alamat">Alamat</label>
            <textarea name="alamat_sekarang" class="form-control input-sm" rows="2">{{ strtoupper((string) old('alamat_sekarang', $penduduk?->alamat_sekarang)) }}</textarea>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label> {{ ucwords(setting('sebutan_dusun')) }} </label>
            <select id="dusun" name="dusun" class="form-control input-sm required">
                <option value="">Pilih {{ ucwords(setting('sebutan_dusun')) }}</option>
                @foreach ($dusun as $data)
                    <option @selected(old('dusun', $penduduk?->dusun) == $data->dusun) value="{{ $data->dusun }}"> {{ $data->dusun }} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>RW</label>
            <select id="rw" class="form-control input-sm required" name="rw" data-source="{{ url('wilayah/list_rw/') }}" data-valueKey="rw" data-displayKey="rw">
                <option class="placeholder" value="">Pilih RW</option>
                @foreach ($rw as $data)
                    <option @selected(old('rw', $penduduk?->rw) == $data->rw) value="{{ $data->rw }}"> {{ $data->rw }} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-4">
        <div id="isi_rt" class="form-group @error('id_cluster') has-error @enderror">
            <label>RT</label>
            <select id="id_cluster" class="form-control input-sm required" name="id_cluster" data-source="{{ url('wilayah/list_rt/') }}" data-valueKey="id" data-displayKey="rt">
                <option class="placeholder" value="">Pilih RT </option>
                @foreach ($rt as $data)
                    <option @selected(old('id_cluster', $penduduk?->id_cluster) == $data->id) value="{{ $data->id }}"> {{ $data->rt }} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group @error('tgl_peristiwa') has-error @enderror">
            <label for="tgl_peristiwa">Tanggal Masuk</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right tgl-datepicker required" name="tgl_peristiwa" type="text" value="{{ old('tgl_peristiwa', $penduduk ? tgl_indo_out($penduduk->tgl_peristiwa, '') : '') }}">
            </div>
        </div>
    </div>
</form>
