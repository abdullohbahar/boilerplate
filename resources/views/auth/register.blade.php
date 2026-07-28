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

@if (config('services.google.enabled') || config('services.github.enabled'))
	<div class="flex items-center gap-3 mt-4">
		<hr class="flex-1" />
		<span class="text-xs text-muted-foreground">or sign up with</span>
		<hr class="flex-1" />
	</div>
	<div class="flex flex-col gap-2 mt-3">
		@if (config('services.google.enabled'))
			<a href="{{ route('social.redirect', 'google') }}" class="button button--neutral button--block">
				<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
					<path fill="currentColor" d="M21.35 11.1h-9.17v2.73h6.51c-.33 3.81-3.5 5.44-6.5 5.44C8.36 19.27 5 16.25 5 12c0-4.1 3.2-7.27 7.2-7.27c3.09 0 4.9 1.97 4.9 1.97L19 4.72S16.56 2 12.1 2C6.42 2 2.03 6.8 2.03 12c0 5.05 4.13 10 10.22 10c5.35 0 9.25-3.67 9.25-9.09c0-1.15-.15-1.81-.15-1.81" />
				</svg>
				Google
			</a>
		@endif
		@if (config('services.github.enabled'))
			<a href="{{ route('social.redirect', 'github') }}" class="button button--neutral button--block">
				<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
					<path fill="currentColor" d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5c.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34c-.46-1.16-1.11-1.47-1.11-1.47c-.91-.62.07-.6.07-.6c1 .07 1.53 1.03 1.53 1.03c.87 1.52 2.34 1.07 2.91.83c.09-.65.35-1.09.63-1.34c-2.22-.25-4.55-1.11-4.55-4.92c0-1.11.38-2 1.03-2.71c-.1-.25-.45-1.29.1-2.64c0 0 .84-.27 2.75 1.02c.79-.22 1.65-.33 2.5-.33s1.71.11 2.5.33c1.91-1.29 2.75-1.02 2.75-1.02c.55 1.35.2 2.39.1 2.64c.65.71 1.03 1.6 1.03 2.71c0 3.82-2.34 4.66-4.57 4.91c.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2" />
				</svg>
				GitHub
			</a>
		@endif
	</div>
@endif

<p class="text-center text-sm text-muted-foreground mt-4">
	Already have an account? <a href="{{ route('login') }}" class="link">Sign in</a>
</p>
@endsection
