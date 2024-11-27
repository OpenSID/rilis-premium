@if(isset($halaman))
    @include("partials.{$halaman}")
@else
    @include('commons.not_found')
@endif