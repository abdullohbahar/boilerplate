@extends('layouts.auth')

@section('title', 'Reset password — ' . config('app.name'))
@section('auth-title', 'Set a new password.')
@section('auth-description', 'Must be at least 8 characters.')

@section('content')
<div>
	<h1 class="text-2xl">Reset password</h1>
	<p class="text-muted-foreground mt-1">Enter your new password below.</p>
</div>

<form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4 mt-6">
	@csrf

	<input type="hidden" name="token" value="{{ $token }}" />

	<div class="field">
		<label for="email" class="field__label">Email</label>
		<div class="input-group input-group--lg">
			<input type="email" class="input @error('email') is-invalid @enderror"
				id="email" name="email" value="{{ old('email', request('email')) }}"
				placeholder="you@example.com" autocomplete="email" required />
		</div>
		@error('email')
			<p class="field__error">{{ $message }}</p>
		@enderror
	</div>

	<div class="field">
		<label for="password" class="field__label">New password</label>
		<div class="input-group input-group--lg">
			<input type="password" class="input @error('password') is-invalid @enderror"
				id="password" name="password"
				autocomplete="new-password" required />
		</div>
		@error('password')
			<p class="field__error">{{ $message }}</p>
		@enderror
	</div>

	<div class="field">
		<label for="password_confirmation" class="field__label">Confirm new password</label>
		<div class="input-group input-group--lg">
			<input type="password" class="input"
				id="password_confirmation" name="password_confirmation"
				autocomplete="new-password" required />
		</div>
	</div>

	<button type="submit" class="button button--primary button--block button--lg">
		Reset password
		<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
			<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
		</svg>
	</button>
</form>

<p class="text-center text-sm text-muted-foreground mt-4">
	<a href="{{ route('login') }}" class="link">Back to sign in</a>
</p>
@endsection
