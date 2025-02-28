    <div class="form-group" data-kategori="{{ $keyname ?? '' }}">
        <label for="{{ str_replace(['[form_', ']'], '', $groupLabel[0]->kode) }}" class="col-sm-3 control-label">{{ $label }}</label>
        <div class="col-sm-9 row">
            @foreach ($groupLabel as $item)
                @php
                    // $nama = isset($keyname) ? underscore($item->nama, true, true) . '_' . $keyname : underscore($item->nama, true, true);
                    $nama = str_replace(['[form_', ']'], '', $item->kode);
                    $class = buat_class($item->atribut, '', $item->required);
                    $widthClass = $item->kolom ? 'col-sm-' . $item->kolom : 'col-sm-12';
                    $dataKaitkan = strlen($item->kaitkan_kode ?? '') > 10 ? "data-kaitkan='" . $item->kaitkan_kode . "'" : '';
                @endphp
                @if ($item->tipe == 'select-manual')
                    <div class="{{ $widthClass }}">
                        <select name="{{ $nama }}" {!! $class !!} {!! $dataKaitkan !!}>
                            <option value="">-- {{ $item->deskripsi }} --</option>
                            @foreach ($item->pilihan as $key => $pilih)
                                <option @selected(old($nama) == $pilih) value="{{ $pilih }}">{{ $pilih }}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif ($item->tipe == 'select-otomatis')
                    <div class="{{ $widthClass }}">
                        <select name="{{ $nama }}" {!! $class !!} placeholder="{{ $item->deskripsi }}">
                            <option value="">-- {{ $item->deskripsi }} --</option>
                            @foreach (ref($item->refrensi) as $key => $pilih)
                                <option @selected(old($nama) == $pilih->nama) value="{{ $pilih->nama }}">{{ $pilih->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @elseif ($item->tipe == 'textarea')
                    <div class="{{ $widthClass }}">
                        <textarea name="{{ $nama }}" {!! $class !!} placeholder="{{ $item->deskripsi }}">{{ old($nama) }}</textarea>
                    </div>
                @elseif ($item->tipe == 'date' || $item->tipe == 'hari' || $item->tipe == 'hari-tanggal')
                    <div class="{{ $widthClass }}">
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" @if (strpos($item->atribut, 'datepicker') !== false) {!! buat_class($item->atribut, 'form-control input-sm', $item->required) !!} @else {!! buat_class($item->atribut, 'form-control input-sm tgl', $item->required) !!} @endif name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ old($nama) }}" />
                        </div>
                    </div>
                @elseif ($item->tipe == 'time')
                    <div class="{{ $widthClass }}">
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <input type="text" {!! buat_class($item->atribut, 'form-control input-sm jam', $item->required) !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ old($nama) }}" />
                        </div>
                    </div>
                @elseif ($item->tipe == 'datetime')
                    <div class="{{ $widthClass }}">
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" {!! buat_class($item->atribut, 'form-control input-sm tgl_jam', $item->required) !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ old($nama) }}" />
                        </div>
                    </div>
                @else
                    <div class="{{ $widthClass }}" {!! count($groupLabel) > 2 ? 'style="margin-bottom: 10px"' : '' !!}>
                        <input type="{{ $item->tipe }}" {!! $class !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ old($nama) }}" />
                    </div>
                @endif
            @endforeach
        </div>
    </div>
