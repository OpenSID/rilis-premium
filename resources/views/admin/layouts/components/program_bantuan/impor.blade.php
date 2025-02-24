@if (can('u'))
    <div class="modal fade" id="impor">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Impor Program Bantuan</h4>
                </div>
                <form id="mainform" action="{{ site_url('program_bantuan/impor') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file" class="control-label">File Program Bantuan : </label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path" name="userfile" required>
                                <input type="file" class="hidden" id="file" name="userfile" accept=".xls,.xlsx,.xlsm">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                            <br />
                            <label class="control-label">Impor Program:</label>
                            <p class="help-block">
                                &emsp;<input type="checkbox" id="ganti_program" name="ganti_program" value="1">
                                <label for="ganti_program">Ganti data lama jika data ditemukan sama</label>
                            </p>

                            <label class="control-label">Impor Peserta:</label>
                            <p class="help-block">
                                &emsp;<input type="checkbox" id="kosongkan_peserta" name="kosongkan_peserta" value="1">
                                <label for="kosongkan_peserta">Kosongkan data peserta program bantuan</label>
                            </p>
                            <p class="help-block">
                                &emsp;<input type="checkbox" id="ganti_peserta" name="ganti_peserta" value="1">
                                <label for="ganti_peserta">Ganti data lama jika data ditemukan sama</label>
                            </p>
                            <p class="help-block">
                                &emsp;<input type="checkbox" id="rand_kartu_peserta" name="rand_kartu_peserta" value="1">
                                <label for="rand_kartu_peserta">Acak No. Kartu Peserta jika kosong</label>
                            </p>

                            <a href="{{ $formatImpor }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block text-center"><i class="fa fa-file-excel-o"></i> Contoh Format Impor Program Bantuan</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class="fa fa-check"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
