<div class="row">
    <div class="col-sm-12">
        {{-- <div class="box-header">
            @if (auth('admin')->id() == super_admin())
                <a href="#" onclick="submitPostRequest('{{ route('info_sistem.perbaiki_file_desa') }}', 'POST'); return false;" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block " title="perbaiki file tidak valid"><i class="fa fa-check"></i> Perbaiki File Tidak Valid</a>
            @endif
        </div> --}}
        <div class="box-body">
            <div class="css-treeview">
                @if (!empty($files))
                    <ul class="tree-folder">
                        @foreach ($files as $folder => $daftarFile)
                            <li class="text-red" data-path="{{ preg_replace('/\/+/', '/', DIRECTORY_SEPARATOR . $folder) }}">{{ $folder }}
                                <i class="fa fa-times-circle-o fa-lg pull-right" style="color:red"></i>
                                <ul class="tree-folder">
                                    @foreach ($daftarFile as $file)
                                        <li class="text-red" data-path="{{ preg_replace('/\/+/', '/', $folder . DIRECTORY_SEPARATOR . $file) }}">{{ $file }}
                                            <i class="fa fa-times-circle-o fa-lg pull-right" style="color:red"></i>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
