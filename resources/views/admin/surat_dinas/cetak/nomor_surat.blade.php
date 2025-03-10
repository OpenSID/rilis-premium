<div class="form-group">
    <input type="hidden" name="id_surat" value="{{ $surat['id'] }}">
    <label class="col-sm-3 control-label">Nomor Surat</label>
    <div class="col-sm-4">
        <input id="nomor" class="form-control input-sm digits required" type="text" placeholder="Nomor Surat" name="nomor" value="{{ $surat_terakhir['no_surat_berikutnya'] }}">
        <p class="help-block text-red small">
            {{ $surat_terakhir['ket_nomor'] }}<strong>{{ $surat_terakhir['no_surat'] }}</strong> (tgl:
            {{ $surat_terakhir['tanggal'] }})</p>
    </div>
    @if (!empty(setting('format_nomor_surat')))
        <div class="col-sm-4">
            <p class="help-block"><em>Format nomor surat: </em><span id="format_nomor">{{ $format_nomor_surat }}</span></p>
        </div>
    @endif
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#nomor').change(function() {
                var nomor = $('#nomor').val();
                var url = $('#url_surat').val();
                $.ajax({
                    type: "POST",
                    url: "{{ ci_route('surat_dinas_cetak.format_nomor_surat') }}",
                    dataType: 'json',
                    data: {
                        nomor: nomor,
                        url: url
                    },
                }).then(function(nomor) {
                    $('#format_nomor').text(nomor);
                });
            });
        });
    </script>
@endpush
