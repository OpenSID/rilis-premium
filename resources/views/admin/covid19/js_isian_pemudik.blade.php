<script type="text/javascript">
    $(document).ready(function() {
        $('#tanggal_tiba').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $("#pantau").change();
    });

    function pemudik(asal) {
        if (asal == 1) {
            $('.pemudik').show();
            $('#asal_pemudik').addClass('required');
        } else {
            $('.pemudik').hide();
            $('#asal_pemudik').removeClass('required');
        }
    }
</script>
