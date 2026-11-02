<form id="validasi" action="{{ $form_action }}" method="post" target="_blank">
    <div class="modal-body">
        @if ($sensor_nik)
            <div class="row">
                <div class="col-sm-12">
                    <label for="nama">Centang kotak berikut apabila NIK/No. KK ingin disensor</label>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="sensor_nik" class="form-check-input" id="privasi_nik">
                            <label class="form-check-label" for="privasi_nik">Sensor NIK/No. KK</label>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (isset($show_template) && $show_template)
            <div class="form-group">
                <label for="format_ttd">Format Tanda Tangan</label>
                <select class="form-control input-sm select2" name="template" id="format_ttd">
                    <option value="1">Format 1 - Camat, Kepala Desa, Bupati</option>
                    <option value="2">Format 2 - Pelaksana, Koordinator, Wali Nagari</option>
                </select>
            </div>
            <div class="form-group" id="form_pelaksana" style="display: none;">
                <label for="pamong_pelaksana">Pelaksana Kegiatan</label>
                <select class="form-control input-sm required" name="pamong_pelaksana" id="pamong_pelaksana" style="width: 100%;">
                    <option value="">Pilih Staf {{ ucwords(setting('sebutan_pemerintah_desa')) }}</option>
                    @foreach ($pamong as $data)
                        <option value="{{ $data['pamong_id'] }}">{{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="form-group">
            <label for="pamong_ttd" id="label_pamong_ttd">Laporan Ditandatangani</label>
            <select class="form-control input-sm select2 required" name="pamong_ttd">
                <option value="">Pilih Staf {{ ucwords(setting('sebutan_pemerintah_desa')) }}</option>
                @foreach ($pamong as $data)
                    <option value="{{ $data['pamong_id'] }}" @selected($pamong_ttd['pamong_id'] == $data['pamong_id'])>{{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="pamong_ketahui" id="label_pamong_ketahui">Laporan Diketahui</label>
            <select class="form-control input-sm select2 required" name="pamong_ketahui">
                <option value="">Pilih Staf {{ ucwords(setting('sebutan_pemerintah_desa')) }}</option>
                @foreach ($pamong as $data)
                    <option value="{{ $data['pamong_id'] }}" @selected($pamong_ketahui['pamong_id'] == $data['pamong_id'])>{{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class='fa fa-check'></i> {{ ucwords($aksi) }}</button>
    </div>
</form>
@include('admin.layouts.components.validasi_form')
<script>
    $(document).ready(function() {
        $('.modal:visible').find('form').validate()

        $('#format_ttd').on('change', function() {
            var format2 = $(this).val() === '2';
            $('#form_pelaksana').toggle(format2);

            var $pelaksana = $('#pamong_pelaksana');
            if (format2 && !$pelaksana.data('select2')) {
                $pelaksana.select2({
                    dropdownParent: $('#modalBox')
                });
            }

            $('#label_pamong_ttd').text(format2 ? 'Diverifikasi (Koordinator)' : 'Laporan Ditandatangani');
            $('#label_pamong_ketahui').text(format2 ? 'Mengetahui (Wali Nagari)' : 'Laporan Diketahui');
        });

        $('#validasi').submit(function() {
            if (!$(this).valid()) {
                return false
            }
            $('#modalBox').modal('hide')
        })
    })
</script>
