@extends('theme::layouts.full-content')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ ci_route() }}">Beranda</a></li>
            @if (isset($parent))
                <li><a href="{{ ci_route('galeri') }}">Galeri</a></li>
                <li aria-current="page">{{ $title }}</li>
            @else
                <li aria-current="page">Galeri</li>
            @endif
        </ol>
    </nav>
    <h1 class="text-h2">
        @if (isset($parent))
            Album Galeri
        @else
            Album
        @endif {{ $title }}
    </h1>

    <div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 lg:gap-5 main-content py-4" id="galeri-list"></div>
        @include('theme::commons.pagination')
    </div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/pagination.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var parent = `{{ $parent }}`;
            var routeGaleri = `{{ ci_route('internal_api.galeri') }}`;
            let pageSizes = 6;
            let status = '';

            if (parent) {
                routeGaleri = `{{ ci_route('internal_api.galeri') }}/${parent}`;
                pageSizes = 10;
            }

            const loadGaleri = function(pageNumber) {
                $.ajax({
                    url: routeGaleri + `?sort=-tgl_upload&page[number]=${pageNumber}&page[size]=${pageSizes}`, // Gunakan pageSizes
                    type: 'POST',
                    beforeSend: function() {
                        const galeriList = document.getElementById('galeri-list');
                    },
                    dataType: 'json',
                    success: function(data) {
                        displayGaleri(data);
                        initPagination(data);
                    }
                });
            };


            // Ikon putar untuk video YouTube
            const ikonVideo = `<span style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);pointer-events:none;"><svg width="64" height="45" viewBox="0 0 68 48" aria-hidden="true"><path d="M66.5 7.7c-.8-2.9-2.5-5.4-5.4-6.2C55.8.1 34 0 34 0S12.2.1 6.9 1.6c-3 .7-4.6 3.2-5.4 6.1C.1 13 0 24 0 24s.1 11 1.5 16.3c.8 2.9 2.5 5.4 5.4 6.2C12.2 47.9 34 48 34 48s21.8-.1 27.1-1.6c3-.8 4.6-3.2 5.4-6.2C67.9 35 68 24 68 24s-.1-11-1.5-16.3z" fill="#f00"/><path d="M45 24 27 14v20z" fill="#fff"/></svg></span>`;

            const displayGaleri = function(dataGaleri) {
                const galeriList = document.getElementById('galeri-list');
                galeriList.innerHTML = '';
                if (!dataGaleri.data.length) {
                    galeriList.innerHTML = `<div class="alert text-primary-100">Maaf album galeri belum tersedia!</div>`
                    return
                }

                dataGaleri.data.forEach(item => {
                    const card = document.createElement('div');
                    const image = item.attributes.src_gambar ? `<div style="position:relative;"><img class="h-44 w-full object-cover object-center" src="${item.attributes.src_gambar}" title="${item.attributes.nama}" alt="${item.attributes.nama}"/>${item.attributes.is_video ? ikonVideo : ''}</div>` : ``
                    card.innerHTML = `
					<a @if (isset($parent)) data-fancybox="images" data-src="${item.attributes.url_video || item.attributes.src_gambar}" data-caption="${item.attributes.nama}" @else href="${item.attributes.url_detail}" @endif class="w-full bg-gray-100 block relative">
						${image}
						<p class="py-2 text-center block">${item.attributes.nama}</p>
					</a>					
				`;
                    galeriList.appendChild(card);
                });
            }
            $('.pagination').on('click', '.btn-page', function() {
                var params = {};
                var page = $(this).data('page');
                loadGaleri(page);
            });
            loadGaleri(1);
        });
    </script>
@endpush
