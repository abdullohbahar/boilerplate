@extends('errors.layout')

@section('title', 'Under Maintenance — ' . config('app.name'))
@section('code', '503')
@section('heading', 'Be right back.')
@section('message', $exception?->getMessage() ?: 'We\'re performing scheduled maintenance. Please check back soon.')

@section('actions')
	<button onclick="location.reload()" class="button button--neutral">Refresh</button>
@endsection
