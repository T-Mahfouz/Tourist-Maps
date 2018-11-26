@extends('admin.layout.app')


@section('title',trans('lang.contactlinks'))

@section('header')
	@include('admin.components.header')
@endsection



@section('main')
	@include('admin.components.subcomponents.contactLinks')
@endsection


@section('sidebar')
	@include('admin.components.sidebar')
@endsection


@section('footer')
	@include('admin.components.footer')
@endsection