@extends('errors.layout')

@section('title', '419 Page Expired — ' . config('app.name'))
@section('code', '419')
@section('heading', 'Page expired')
@section('message', 'Your session has expired. Please refresh the page and try again.')

@section('actions')
	<a href="{{ url()->current() }}" class="button button--primary">Refresh page</a>
@endsection
