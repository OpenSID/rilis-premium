@if (!empty($folders))
    <ul class="tree-folder">
        @foreach ($folders as $folder)
            @php($sesuai = $folder['permission'] === decoct(DESAPATHPERMISSION))
            <li class="{{ $sesuai ? 'text-green' : 'text-red' }}" data-path="{{ $folder['path'] }}">{{ $folder['nama'] }}({{ $folder['permission'] }})
                @if ($sesuai)
                    <i class="fa fa-check-circle-o fa-lg pull-right" style="color:green"></i>
                @else
                    <i class="fa fa-times-circle-o fa-lg pull-right" style="color:red"></i>
                @endif
                @include('admin.setting.info_sistem.tree_folder', ['folders' => $folder['anak']])
            </li>
        @endforeach
    </ul>
@endif
