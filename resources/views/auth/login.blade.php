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

	<button type="submit" class="button button--primary button--block button--lg">
		Sign in
		<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
			<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
		</svg>
	</button>
</form>

<p class="text-center text-sm text-muted-foreground mt-4">
	New here? <a href="{{ route('register') }}" class="link">Create an account</a>
</p>
@endsection
