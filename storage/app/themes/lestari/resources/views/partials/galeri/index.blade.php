@extends('theme::layouts.full-content')

@section('content')
    @include('theme::partials.header')
    <div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center mb-20">
				<h1>
					Galeri Foto
				</h1>
			</div>
		</div>
		<div class="margin-page">
			<div class="box-body">
				<div class="row">
				<div class="col-sm-12 gallerystyle">
					<div id="galeri-list"></div>
					<div class="flex-center mt-20">@include('theme::commons.pagination')</div>
				</div>
				</div>
			</div>
		</div>
		
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
	</div>
@endsection

@push('scripts')
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
                    url: routeGaleri + `?sort=-tgl_upload&page[number]=${pageNumber}&page[size]=${pageSizes}`,
                    type: 'POST',
                    beforeSend: function() {
                        const galeriList = document.getElementById('galeri-list');
                    },
                    dataType: 'json',
                    data: {

                    },
                    success: function(data) {
                        displayGaleri(data);
                        initPagination(data);
                    }
                });
            }

            // Ikon putar untuk video YouTube
            const ikonVideo = `<span style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);pointer-events:none;"><svg width="64" height="45" viewBox="0 0 68 48" aria-hidden="true"><path d="M66.5 7.7c-.8-2.9-2.5-5.4-5.4-6.2C55.8.1 34 0 34 0S12.2.1 6.9 1.6c-3 .7-4.6 3.2-5.4 6.1C.1 13 0 24 0 24s.1 11 1.5 16.3c.8 2.9 2.5 5.4 5.4 6.2C12.2 47.9 34 48 34 48s21.8-.1 27.1-1.6c3-.8 4.6-3.2 5.4-6.2C67.9 35 68 24 68 24s-.1-11-1.5-16.3z" fill="#f00"/><path d="M45 24 27 14v20z" fill="#fff"/></svg></span>`;

            const displayGaleri = function(dataGaleri) {
                const galeriList = document.getElementById('galeri-list');
                galeriList.innerHTML = '';
                if (!dataGaleri.data.length) {
                    galeriList.innerHTML = `<div class="alert alert-info" role="alert">Data tidak tersedia</div>`
                    return
                }
                const ulBlock = document.createElement('div');
                ulBlock.className = 'row';
                dataGaleri.data.forEach(item => {
                    const card = document.createElement('div');
                    const image = item.attributes.src_gambar ? `
					<div class="image-article imagefull brd-10" style="position:relative;">
					<img src="${item.attributes.src_gambar}" alt="${item.attributes.nama}"/>${item.attributes.parrent && item.attributes.is_video ? ikonVideo : ''}</div>` : ``
                    card.innerHTML = `
					<div class="col-lg-6 hover-effect">
					<a href="${item.attributes.parrent ? (item.attributes.url_video || item.attributes.src_gambar) : item.attributes.url_detail}" class="gallery-thumbnail" ${item.attributes.parrent ? `data-fancybox="images" data-caption="${item.attributes.nama}"`: ''}>
						<div class="box-shadow mt-20 brd-10 trans-def">
						<div class="gallery-box align-center">
							${image}
							<p>${item.attributes.nama}</p>
						</div>
						</div>
					</a>
					</div>
				`;
                    card.onclick = function() {}
                    ulBlock.appendChild(card);
                });
                galeriList.appendChild(ulBlock);
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
