<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box box-primary box-solid">
    <div class="bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
        <h3 class="text-md font-semibold text-white text-center">
            <i class="fa fa-comments mr-2 mr-1"></i>{{ strtoupper($judul_widget) }}
        </h3>
    </div>
    <div class="h-1 bg-green-500 mb-2"></div>
    <div class="box-body">
        <marquee
            onmouseover="this.stop()"
            onmouseout="this.start()"
            scrollamount="2"
            direction="up"
            width="100%"
            height="150"
            align="center"
        >
            <ul class="divide-y">
                @foreach ($komen as $data)
                    <li class="py-2 space-y-2">
                        <blockquote class="italic"> {{ potong_teks($data['komentar'], 50) }}</blockquote>... <a href="{{ site_url('artikel/' . buat_slug($data)) }}" class="text-link">selengkapnya</a>
                        <p class="text-xs lg:text-sm"><i class="fas fa-comment"></i> {{ $data['owner'] }}</p>
                        <p class="text-xs lg:text-sm">{{ tgl_indo2($data['tgl_upload']) }}</p>
                    </li>
                @endforeach
            </ul>
        </marquee>
    </div>
</div>
