@extends('template')

@section('content')
    @if ($layout)
        @include("layouts.$layout")
    @else
        @include('layouts.right-sidebar')
    @endif
@endsection