@extends('errors.layout')

@section('title', '500 Server Error — ' . config('app.name'))
@section('code', '500')
@section('heading', 'Something went wrong')
@section('message', 'We\'re having trouble on our end. Please try again in a moment.')

@section('actions')
	<a href="{{ url()->previous('/') }}" class="button button--neutral">Go back</a>
	<a href="{{ url('/') }}" class="button button--primary">Go to dashboard</a>
@endsection
