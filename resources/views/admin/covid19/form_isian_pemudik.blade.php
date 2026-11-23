@php
    $tujuanTersimpan = array_search($terdata?->tujuan_mudik, \App\Models\CovidPemudik::TUJUAN_MUDIK, true);
@endphp
<div class="form-group">
    <label class="col-sm-3 control-label" for="pantau">Pantau Warga</label>
    <div class="col-sm-8">
        <select class="form-control input-sm required" name="pantau" id="pantau" onchange="pemudik(this.value);">
            <option value="1" @selected(old('pantau', $terdata?->pantau) == 1)>Warga Pemudik</option>
            <option value="2" @selected(old('pantau', $terdata?->pantau) == 2)>Warga {{ ucwords(setting('sebutan_desa')) }}</option>
        </select>
    </div>
</div>

<div class="pemudik">
    <div class="form-group">
        <label for="asal_pemudik" class="col-sm-3 control-label">Asal Pemudik (kota) / Tiba Tanggal</label>
        <div class="col-sm-4">
            <input class="form-control input-sm required" type="text" name="asal_pemudik" id="asal_pemudik" value="{{ old('asal_pemudik', $terdata?->asal_mudik) }}" placeholder="Kota">
        </div>
        <div class="col-sm-4">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control input-sm pull-right required" id="tanggal_tiba" name="tanggal_tiba" value="{{ old('tanggal_tiba', $terdata?->tanggal_datang ?? now()->format('Y-m-d')) }}">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="tujuan_pemudik" class="col-sm-3 control-label">Tujuan Mudik / Durasi Mudik</label>
        <div class="col-sm-4">
            <select class="form-control input-sm" name="tujuan_pemudik" id="tujuan_pemudik">
                <option value="">-- Pilih Tujuan Mudik --</option>
                @foreach ($select_tujuan_mudik as $id => $nama)
                    <option value="{{ $id }}" @selected(old('tujuan_pemudik', $tujuanTersimpan) == $id)>{{ strtoupper($nama) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-4">
            <input class="form-control input-sm number" type="text" name="durasi_pemudik" id="durasi_pemudik" value="{{ old('durasi_pemudik', $terdata?->durasi_mudik) }}" placeholder="Jumlah Hari (angka)">
        </div>
    </div>

    <div class="form-group">
        <label for="hp_pemudik" class="col-sm-3 control-label">Kontak Pemudik (HP/Email)</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" name="hp_pemudik" id="hp_pemudik" value="{{ old('hp_pemudik', $terdata?->no_hp) }}" placeholder="No HP">
        </div>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" name="email_pemudik" id="email_pemudik" value="{{ old('email_pemudik', $terdata?->email) }}" placeholder="Email">
        </div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label" for="status_covid">Status Covid-19</label>
    <div class="col-sm-8">
        <select class="form-control input-sm required" name="status_covid" id="status_covid">
            <option value="">-- Pilih Status Covid-19 --</option>
            @foreach ($select_status_covid as $covid)
                <option value="{{ $covid->id }}" @selected(old('status_covid', $terdata?->covid_id) == $covid->id)>{{ $covid->nama }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label" for="wajib_pantau">Apakah Wajib Dipantau</label>
    <div class="col-sm-8">
        <select class="form-control input-sm" name="wajib_pantau" id="wajib_pantau">
            <option value="1" @selected(old('wajib_pantau', $terdata?->is_wajib_pantau) == '1')>Ya</option>
            <option value="0" @selected(old('wajib_pantau', $terdata?->is_wajib_pantau) == '0')>Tidak</option>
        </select>
        <span id="wajib_pantau_plus_msg" class="help-block">
            <code>Jika ya, daftar warga ini masuk dalam daftar warga yang dipantau di menu Pemantauan</code>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label" for="keluhan">Keluhan Kesehatan</label>
    <div class="col-sm-8">
        <textarea name="keluhan" id="keluhan" class="form-control input-sm" placeholder="Keluhan Kesehatan" rows="5" style="resize:none;">{{ old('keluhan', $terdata?->keluhan_kesehatan) }}</textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
    <div class="col-sm-8">
        <textarea name="keterangan" id="keterangan" class="form-control input-sm" placeholder="Keterangan" rows="5" style="resize:none;">{{ old('keterangan', $terdata?->keterangan) }}</textarea>
    </div>
</div>
