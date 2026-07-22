@extends('layouts.auth')

@section('title', 'Forgot password — ' . config('app.name'))
@section('auth-title', 'Reset your password.')
@section('auth-description', 'We\'ll send a reset link to your email.')

@section('content')
<div>
	<h1 class="text-2xl">Forgot password</h1>
	<p class="text-muted-foreground mt-1">Enter your email to receive a reset link.</p>
</div>

@if (session('status'))
	<div class="alert alert--success mt-4" role="alert">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4 mt-6">
	@csrf

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

	<button type="submit" class="button button--primary button--block button--lg">
		Send reset link
		<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
			<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
		</svg>
	</button>
</form>

<p class="text-center text-sm text-muted-foreground mt-4">
	<a href="{{ route('login') }}" class="link">Back to sign in</a>
</p>
@endsection
