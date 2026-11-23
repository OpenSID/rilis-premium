<script type="text/javascript">
    $(document).ready(function() {
        $("#dusun").change(function() {
            let dusun = $(this).val();
            $('#isi_rt').hide();
            var rw = $('#rw');
            select_options(rw, urlencode(dusun));
        });

        $("#rw").change(function() {
            let dusun = $("#dusun").val();
            let rw = $(this).val();

            $('#isi_rt').show();
            var rt = $('#id_cluster');
            var params = urlencode(dusun) + '/' + urlencode(rw);
            select_options(rt, params);
        });

        $('.tgl-datepicker').datetimepicker({
            format: 'DD-MM-YYYY'
        });

        $("#form_penduduk").validate({
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
