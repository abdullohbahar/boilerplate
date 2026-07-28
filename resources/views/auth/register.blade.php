@extends('layouts.auth')

@section('title', 'Create account — ' . config('app.name'))
@section('auth-title', 'Create your account.')
@section('auth-description', 'Get started in seconds.')

@section('content')
<div>
	<h1 class="text-2xl">Create account</h1>
	<p class="text-muted-foreground mt-1">Join {{ config('app.name') }} today.</p>
</div>

<form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4 mt-6">
	@csrf

	<div class="field">
		<label for="name" class="field__label">Name</label>
		<div class="input-group input-group--lg">
			<input type="text" class="input @error('name') is-invalid @enderror"
				id="name" name="name" value="{{ old('name') }}"
				placeholder="Your name" autocomplete="name" required />
		</div>
		@error('name')
			<p class="field__error">{{ $message }}</p>
		@enderror
	</div>

	<div class="field">
		<label for="email" class="field__label">Email</label>
		<div class="input-group input-group--lg">
			<input type="email" class="input @error('email') is-invalid @enderror"
				id="email" name="email" value="{{ old('email') }}"
				placeholder="you@example.com" autocomplete="email" required />
		</div>
		@error('email')
			<p class="field__error">{{ $message }}</p>
		@enderror
	</div>

	<div class="field">
		<label for="password" class="field__label">Password</label>
		<div class="input-group input-group--lg">
			<input type="password" class="input" id="password" name="password"
				placeholder="Min. 8 characters" autocomplete="new-password" required />
		</div>
		@error('password')
			<p class="field__error">{{ $message }}</p>
		@enderror
	</div>

	<div class="field">
		<label for="password_confirmation" class="field__label">Confirm password</label>
		<div class="input-group input-group--lg">
			<input type="password" class="input" id="password_confirmation"
				name="password_confirmation" placeholder="Repeat password" required />
		</div>
	</div>

	<x-captcha />

	<button type="submit" class="button button--primary button--block button--lg">
		Create account
		<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
			<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
		</svg>
	</button>
</form>

<p class="text-center text-sm text-muted-foreground mt-4">
	Already have an account? <a href="{{ route('login') }}" class="link">Sign in</a>
</p>
@endsection
