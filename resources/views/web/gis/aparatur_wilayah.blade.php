@include('admin.layouts.components.asset_cycle')

<style type="text/css">
    .cycle-slideshow {
        max-height: none;
        margin-bottom: 0px;
        border: 0px;
    }
</style>
<!-- widget Aparatur Dusun/rw/rt -->
<div class="modal-body">
    <div class="box box-primary box-solid">
        <div class="box-body">
            <div id="aparatur_wilayah" class="cycle-slideshow">
                <div class="cycle-overlay"></div>
                <img src="{{ AmbilFoto($penduduk['foto'], '', $penduduk['id_sex']) }}"
                    data-cycle-title="<span class='cycle-overlay-title'>{{ $penduduk['nama'] }}</span>"
                    data-cycle-desc="{{ $jabatan }}">
            </div>
        </div>
    </div>
</div>