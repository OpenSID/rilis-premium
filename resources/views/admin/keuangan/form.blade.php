<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-tambah" action="{{ route('keuangan_manual.template') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Template</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input
                            type="text"
                            class="form-control input-sm required"
                            id="tahun"
                            name="tahun"
                            value="{{ old('tahun', date('Y')) }}"
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    {!! batal() !!}
                    <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
