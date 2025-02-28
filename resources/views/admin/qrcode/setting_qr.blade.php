@extends('admin.layouts.index')

@push('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
    <style type="text/css">
        .tetap {
            resize: none;
        }

        .btn-margin {
            margin-right: 5px;
        }

        #qr-reader {
            width: 450px;
        }

        @media(max-width: 600px) {
            #qr-reader {
                width: 300px;
            }
        }

        .empty {
            display: block;
            width: 100%;
            height: 20px;
        }
    </style>
@endpush

@section('title')
    <h1>
        QR Code
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">QR Code</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        @if (can('u'))
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buat QR Code</h3>
                        <a href="{{ site_url('qrcode') }}" class="btn btn-social btn-success btn-sm" style="float: right;" title="Baru"><i class="fa fa-plus"></i> Baru</a>
                    </div>
                    <form id="validasi" name="mainform" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="isiqr">Isi Kode :</label>
                                <textarea class="form-control input-sm tetap required" rows="5" id="isiqr" name="isiqr" maxlength="300">{{ $qrcode['isiqr'] }}</textarea>
                                <label for="isiqr" generated="true" class="error" id="error_isiqr">Kolom ini diperlukan.</label>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <div class="form-group">
                                        <label for="changeqr">Sisipkan Logo :</label>
                                        <select class="form-control input-sm" id="changeqr" name="changeqr" onchange="load(this.value);">
                                            @foreach ($list_changeqr as $key => $list)
                                                ?>
                                                <option value="{{ $key + 1 }}" {{ selected($qrcode['changeqr'], $key + 1) }}>{{ $list }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-8" id="change_key">
                                    <div class="form-group">
                                        <label for="logoqr"><code> Kosongkan untuk QR Code polos </code></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control input-sm" id="logoqr" name="logoqr">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info btn-danger btn-sm" id="kosongkan"><i class="fa fa-refresh" title="Kosongkan"></i>&nbsp;</button>
                                                <button type="button" class="btn btn-info btn-info btn-sm" id="file_browser1" data-toggle="modal" data-target="#myModal"><i class="fa fa-search"></i>&nbsp;</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="sizeqr">Ukuran :</label>
                                    <select class="form-control input-sm" id="sizeqr" name="sizeqr">
                                        @foreach ($list_sizeqr as $key => $list)
                                            <option value="{{ $key + 1 }}" @selected($qrcode['sizeqr'] == $key + 1)>{{ $list . 'px x ' . $list . 'px' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="foreqr">Warna Depan :</label>
                                    <div class="input-group my-colorpicker2">
                                        <div class="input-group-addon input-sm">
                                            <i></i>
                                        </div>
                                        <input type="text" id="foreqr" name="foreqr" class="form-control input-sm" value="{{ $qrcode['foreqr'] }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button id="reset" type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                            <button id="generate" type="button" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Buat</button>
                        </div>
                    </form>
                </div>

                <div class="box box-info" id="hasil_qrcode">
                    <div class="box-header with-border">
                        <h3 class="box-title">Hasil QR Code</h3>
                    </div>
                    <div class="box-body text-center">
                        <img class="img-thumbnail" id="file_qrcode">
                        <br />
                        <a id="unduh_qrcode" class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank" rel="noopener noreferrer"><i class="fa fa-download"></i> Unduh</a>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Scan QR Code</h3>
                    <button class="btn btn-primary btn-sm" style="float: right;" onClick="window.location.reload();">Scan Baru</button>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <center>
                            <div id="qr-reader"></div>
                            <div class="empty"></div>
                            <div id="qr-reader-results"></div>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    </div>

    <!-- File Manager -->
    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'>Atur QR Code</h4>
                </div>
                <div class="modal-body">
                    <iframe width="100%" height="400px" src="{{ base_url('assets/kelola_file/dialog.php?type=1&lang=id&field_id=logoqr&fldr=&akey=' . $session->fm_key) }}" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/html5-qrcode-scanner.js') }}"></script>
    <script src="{{ asset('js/html5-qrcode.js') }}"></script>
    <script src="{{ asset('js/qrcode.js') }}"></script>
    <script src="{{ asset('js/qrcode-scanner.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $('document').ready(function() {
            $('#changeqr').change();
            $("#hasil_qrcode").hide();
            $("#error_isiqr").hide();
        });

        function load(key) {
            if (key == 1) {
                $('#change_key').hide();
                $('#logoqr').val('');
            } else {
                $('#change_key').show();
                $('#logoqr').val("{{ $qrcode['logoqr'] }}");
            }
        }

        $('#kosongkan').on('click', function() {
            $('#logoqr').val('');
        });

        $('#reset').on('click', function() {
            $("#hasil_qrcode").hide();
        });

        $('#generate').on('click', function() {
            if (!$('#isiqr').val()) {
                $('#isiqr').focus();
                $('#isiqr').closest('.form-group').addClass('has-error');
                $("#error_isiqr").show();
            } else {
                $('#isiqr').closest('.form-group').removeClass('has-error');
                $("#error_isiqr").hide();
            }

            var isiqr = $('#isiqr').val();
            var changeqr = $('#changeqr').val();
            var logoqr = $('#logoqr').val();
            var sizeqr = $('#sizeqr').val();
            var foreqr = $('#foreqr').val();

            $.ajax({
                url: "{{ site_url('qr_code/qrcode_generate') }}",
                type: 'POST',
                data: {
                    isiqr: isiqr,
                    changeqr: changeqr,
                    logoqr: logoqr,
                    sizeqr: sizeqr,
                    foreqr: foreqr
                },
                success: function(data) {
                    $("#hasil_qrcode").show();
                    $("#file_qrcode").attr('src', data);
                    $("#unduh_qrcode").attr('href', data).attr('download', 'unduh_qrcode_' + (Math.floor(Math.random() * 1000) + 1) + '.png');
                    return true;
                }
            });

            $("#hasil_qrcode").hide();
            return false;
        });
    </script>
@endpush
