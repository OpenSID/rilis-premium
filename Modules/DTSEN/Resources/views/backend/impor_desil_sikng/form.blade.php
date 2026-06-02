{!! form_open('dtsen/impor-desil-sikng/update/' . $item->id, 'class="form-horizontal" id="mainform"') !!}
<div class="modal-body">
    <div class="form-group">
        <label class="control-label col-sm-3" for="nomor_kk">Nomor KK</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="nomor_kk" id="nomor_kk" value="{{ $item->nomor_kk }}" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="nik">NIK</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="nik" id="nik" value="{{ $item->nik }}" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="nama">Nama</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="nama" id="nama" value="{{ $item->nama }}" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3" for="desil">Desil</label>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm" name="desil" id="desil" value="{{ $item->desil }}" maxlength="10">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal">
        <i class="fa fa-times"></i> Tutup
    </button>
    <button type="submit" class="btn btn-social btn-info btn-sm">
        <i class="fa fa-check"></i> Simpan
    </button>
</div>
{!! form_close() !!}
