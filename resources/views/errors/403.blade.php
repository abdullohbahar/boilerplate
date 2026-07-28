@extends('errors.layout')

@section('title', '403 Forbidden — ' . config('app.name'))
@section('code', '403')
@section('heading', 'Access denied')
@section('message', $exception->getMessage() ?: 'You don\'t have permission to access this page.')

@section('actions')
	<a href="{{ url()->previous('/') }}" class="button button--neutral">Go back</a>
	<a href="{{ url('/') }}" class="button button--primary">Go to dashboard</a>
@endsection
