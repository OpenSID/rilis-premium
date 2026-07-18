<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box box-primary box-solid">
    <div class="bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
        <h3 class="text-md font-semibold text-white text-center">
            <i class="fas fa-bars mr-1"></i>{{ strtoupper($judul_widget) }}
        </h3>
    </div>
    <div class="h-1 bg-green-500 mb-2"></div>
    <div class="box-body content">
        <ul class="divide-y">
            @foreach ($menu_kiri as $data)
                <li><a href="{{ site_url('artikel/kategori/' . $data['slug']) }}" class="py-2 block">{{ $data['kategori'] }}</a>
                    @if (count($data['submenu'] ?? []) > 0)
                        <ul class="divide-y">
                            @foreach ($data['submenu'] as $submenu)
                                <li><a href="{{ site_url('artikel/kategori/' . $submenu['slug']) }}" class="py-2 block">{{ $submenu['kategori'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
