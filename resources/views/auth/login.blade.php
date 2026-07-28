@extends('layouts.auth')

@section('title', 'Sign in — ' . config('app.name'))
@section('auth-title', 'Welcome back.')
@section('auth-description', 'Sign in to your account to continue.')

@section('content')
<div>
	<h1 class="text-2xl">Welcome back</h1>
	<p class="text-muted-foreground mt-1">Sign in to your {{ config('app.name') }} account.</p>
</div>

@if (session('status'))
	<div class="alert alert--success mt-4" role="alert">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4 mt-6">
	@csrf

	<div class="field">
		<label for="email" class="field__label">Email</label>
		<div class="input-group input-group--lg">
			<span class="input-group__text">
				<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
					<g fill="none" stroke="currentColor" stroke-width="1.5">
						<path d="M2 12c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12s0 5.657-1.172 6.828S17.771 20 14 20h-4c-3.771 0-5.657 0-6.828-1.172S2 15.771 2 12Z" />
						<path stroke-linecap="round" d="m6 8l2.159 1.8c1.837 1.53 2.755 2.295 3.841 2.295s2.005-.765 3.841-2.296L18 8" />
					</g>
				</svg>
			</span>
			<input type="email" class="input @error('email') is-invalid @enderror"
				id="email" name="email" value="{{ old('email') }}"
				placeholder="you@example.com" autocomplete="email" required />
		</div>
		@error('email')
			<p class="field__error">{{ $message }}</p>
		@enderror
	</div>

	<div class="field">
		<div class="flex items-center justify-between gap-2">
			<label for="password" class="field__label">Password</label>
			<a href="{{ route('password.request') }}" class="link text-xs">Forgot?</a>
		</div>
		<div class="input-group input-group--lg">
			<span class="input-group__text">
				<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
					<g fill="none" stroke="currentColor" stroke-width="1.5">
						<path d="M2 16c0-2.828 0-4.243.879-5.121C3.757 10 5.172 10 8 10h8c2.828 0 4.243 0 5.121.879C22 11.757 22 13.172 22 16s0 4.243-.879 5.121C20.243 22 18.828 22 16 22H8c-2.828 0-4.243 0-5.121-.879C2 20.243 2 18.828 2 16Z" />
						<circle cx="12" cy="16" r="2" />
						<path stroke-linecap="round" d="M6 10V8a6 6 0 1 1 12 0v2" />
					</g>
				</svg>
			</span>
			<input type="password" class="input" id="password" name="password"
				placeholder="••••••••••" autocomplete="current-password" required />
		</div>
	</div>

	<div class="field__item">
		<input class="checkbox" type="checkbox" id="remember" name="remember" />
		<label class="field__label" for="remember">Keep me signed in</label>
	</div>

	<x-captcha />

	<button type="submit" class="button button--primary button--block button--lg">
		Sign in
		<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
			<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
		</svg>
	</button>
</form>

@if (config('services.google.enabled') || config('services.github.enabled'))
	<div class="flex items-center gap-3 mt-4">
		<hr class="flex-1" />
		<span class="text-xs text-muted-foreground">or continue with</span>
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
	New here? <a href="{{ route('register') }}" class="link">Create an account</a>
</p>
@endsection
