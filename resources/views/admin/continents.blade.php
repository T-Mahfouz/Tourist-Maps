@extends('admin.layout.app')


@section('title',trans('lang.continents'))

@section('header')
	@include('admin.components.header')
@endsection



@section('main')
	@include('admin.components.subcomponents.continents')
@endsection


@section('sidebar')
	@include('admin.components.sidebar')
@endsection


@section('footer')
	@include('admin.components.footer')
@endsection