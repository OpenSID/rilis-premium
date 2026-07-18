@extends('theme::template')

@section('layout')
    @include('theme::partials.header')
    <div class="contentpage">
        <div class="margin-page">
            @include('theme::partials.not_found')
        </div>
        @include('theme::partials.modulepage')
        @include('theme::partials.footer')
    </div>
@endsection
