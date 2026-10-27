@extends('theme::layouts.right-sidebar')

@section('content')
<div class="heading-module l-flex">
	<div class="heading-module-inner l-flex">
		<i class="fa fa-folder-open"></i>
		<h1>			
			Album Galeri			
		</h1>
	</div>
</div>
@if (isset($parent))
<div class="c-flex" style="margin:15px 0;">
	<h1>{{ $title }}</h1>
</div>
@endif
<div>
	<div class="row-custom mlr-min10" id="galeri-list"></div>
	@include('theme::commons.pagination')
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
                    url: routeGaleri + `?sort=-tgl_upload&page[number]=${pageNumber}&page[size]=${pageSizes}`, // Gunakan pageSizes
                    type: "GET",
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
                    galeriList.innerHTML = `<div class="box-def hoverstyle">
							<div class="emptydata c-flex">
								<div>
									<svg viewBox="0 0 24 24">
										<path d="M13 13H11V7H13M11 15H13V17H11M15.73 3H8.27L3 8.27V15.73L8.27 21H15.73L21 15.73V8.27L15.73 3Z" />
									</svg>
									<p>Mohon maaf, untuk saat ini data Galeri belum tersedia...!</p>
								</div>
							</div>
						</div>`
                    return
                }

                dataGaleri.data.forEach(item => {
                    const card = document.createElement('div');					
                    const image = item.attributes.src_gambar ? `<img src="${item.attributes.src_gambar}" title="${item.attributes.nama}" alt="${item.attributes.nama}"/>` : ``
					card.className = `galeri-col box-def`
                    card.innerHTML = `					
						<a href="${item.attributes.url_detail}">
							<div class="box-def-inner">
							@if (isset($parent))
							<a data-fancybox="gallery" href="${item.attributes.url_video || item.attributes.src_gambar}" >
								<div class="image-slider" style="position:relative;">
									${image}
									${item.attributes.is_video ? ikonVideo : ''}
								</div>
								<div class="c-flex" style="margin-top:10px;">
									<p>${item.attributes.nama}</p>
								</div>
							</a> 
							@else 
								<div class="image-slider">
									${image}
								</div>
								<div class="c-flex" style="margin-top:10px;">
									<p><b>Album : ${item.attributes.nama}</b></p>
								</div>
							@endif								
							</div>
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
