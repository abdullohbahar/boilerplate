@extends('layouts.auth')

@section('title', 'Two-factor authentication — ' . config('app.name'))
@section('auth-title', 'Two-factor authentication.')
@section('auth-description', 'Confirm access to your account.')

@section('content')
<div>
	<h1 class="text-2xl">Two-factor authentication</h1>
	<p class="text-muted-foreground mt-1">Enter the code from your authenticator app, or a backup code.</p>
</div>

<form method="POST" action="{{ route('two-factor.challenge') }}" class="flex flex-col gap-4 mt-6">
	@csrf

	<div class="field">
		<label for="code" class="field__label">Authentication code</label>
		<div class="input-group input-group--lg">
			<input type="text" class="input @error('code') is-invalid @enderror"
				id="code" name="code"
				placeholder="000000 or XXXX-XXXX"
				autocomplete="one-time-code"
				inputmode="numeric"
				autofocus />
		</div>
		@error('code')
			<p class="field__error">{{ $message }}</p>
		@enderror
	</div>

	<button type="submit" class="button button--primary button--block button--lg">
		Verify
		<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
			<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12h16m0 0l-6-6m6 6l-6 6" />
		</svg>
	</button>
</form>

<p class="text-center text-sm text-muted-foreground mt-4">
	<a href="{{ route('login') }}" class="link">Back to login</a>
</p>
@endsection
