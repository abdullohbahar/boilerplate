@extends('layouts.app')

@section('title', 'Two-Factor Authentication — ' . config('app.name'))
@section('page-title', 'Two-Factor Authentication')

@section('breadcrumb')
	<x-breadcrumb :items="[['label' => 'Dashboard', 'route' => 'dashboard'], ['label' => 'Profile', 'route' => 'profile'], ['label' => 'Two-Factor Authentication']]" />
@endsection

@section('content')
<div class="grid grid-cols-12 gap-4 max-w-3xl">

	{{-- Status / Backup codes after enable --}}
	@if (session('backup_codes'))
		<div class="col-span-12">
			<div class="alert alert--success" role="alert">
				<p class="font-semibold mb-2">Two-factor authentication enabled!</p>
				<p class="text-sm mb-3">Save these backup codes in a safe place. Each code can only be used once.</p>
				<div class="grid grid-cols-2 gap-1 font-mono text-sm">
					@foreach (session('backup_codes') as $code)
						<span>{{ $code }}</span>
					@endforeach
				</div>
			</div>
		</div>
	@endif

	<div class="col-span-12">
		<div class="card">
			<div class="card__header">
				<span class="card__title">Two-Factor Authentication (2FA)</span>
				@if (auth()->user()->two_factor_enabled)
					<span class="badge badge--success ms-2">Enabled</span>
				@else
					<span class="badge badge--neutral ms-2">Disabled</span>
				@endif
			</div>
			<div class="card__body">

				@if (auth()->user()->two_factor_enabled)
					<p class="text-muted-foreground mb-4">
						2FA is active on your account. You'll need your authenticator app each time you sign in.
					</p>
					<form method="POST" action="{{ route('profile.2fa.disable') }}"
						onsubmit="return confirm('Disable two-factor authentication?')">
						@csrf
						@method('DELETE')
						<div class="field mb-4 max-w-xs">
							<label for="password" class="field__label">Confirm your password</label>
							<input type="password" id="password" name="password" class="input @error('password') is-invalid @enderror"
								placeholder="Your current password" />
							@error('password')
								<p class="field__error">{{ $message }}</p>
							@enderror
						</div>
						<button type="submit" class="button button--danger">Disable 2FA</button>
					</form>
				@else
					<p class="text-muted-foreground mb-6">
						Add an extra layer of security by enabling 2FA. You'll need a TOTP app like Google Authenticator or Authy.
					</p>

					<ol class="list-decimal list-inside flex flex-col gap-6">
						<li>
							<span class="font-medium">Scan this QR code with your authenticator app.</span>
							<div class="mt-3 p-3 bg-white inline-block rounded border">
								{!! $qrCodeUrl !!}
							</div>
							<p class="text-sm text-muted-foreground mt-2">
								Can't scan? Use this secret key instead:
								<code class="font-mono bg-muted px-1 rounded text-xs select-all">{{ $secret }}</code>
							</p>
						</li>
						<li>
							<span class="font-medium">Enter the 6-digit code from your app to confirm setup.</span>
							<form method="POST" action="{{ route('profile.2fa.enable') }}" class="mt-3 flex items-start gap-3 max-w-xs">
								@csrf
								<div class="field flex-1">
									<input type="text" name="code" class="input @error('code') is-invalid @enderror"
										placeholder="000000"
										inputmode="numeric"
										autocomplete="one-time-code"
										autofocus />
									@error('code')
										<p class="field__error">{{ $message }}</p>
									@enderror
								</div>
								<button type="submit" class="button button--primary">Enable</button>
							</form>
						</li>
					</ol>
				@endif

			</div>
		</div>
	</div>

</div>
@endsection
