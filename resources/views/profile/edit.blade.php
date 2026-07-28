@extends('layouts.app')

@section('title', 'Edit Profile — ' . config('app.name'))
@section('page-title', 'Edit Profile')

@section('breadcrumb')
	<li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
	<li class="breadcrumb__item"><a href="{{ route('profile') }}">Profile</a></li>
	<li class="breadcrumb__item" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="flex flex-col gap-4 max-w-2xl">

	{{-- Profile information --}}
	<div class="card">
		<div class="card__header">
			<span class="card__title">Profile information</span>
			<p class="text-sm text-muted-foreground mt-1">Update your name, email address, and avatar.</p>
		</div>
		<div class="card__body">
			<form x-data="formAjax" @submit.prevent="submit"
				method="POST" action="{{ route('profile.update') }}"
				enctype="multipart/form-data">
				@csrf
				@method('PUT')

				<div x-show="serverError" x-cloak
					class="alert alert--danger mb-4"
					x-text="serverError"
					role="alert">
				</div>
				<div x-show="message" x-cloak
					class="alert alert--success mb-4"
					x-text="message"
					role="alert">
				</div>

				<div class="flex flex-col gap-4">
					<div class="field">
						<label class="field__label">Avatar</label>
						<div class="flex items-center gap-4">
							<span class="avatar avatar--circle" data-stisla-avatar style="--avatar-size: 4rem;">
								@if ($user->fileUrl('avatar'))
									<img src="{{ $user->fileUrl('avatar') }}" alt="{{ $user->name }}" class="avatar__image" />
								@else
									<span class="avatar__fallback text-xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
								@endif
							</span>
							<div class="input-group">
								<input type="file" id="avatar" name="avatar" class="input"
									:class="{ 'is-invalid': errors.avatar }"
									accept="image/*" />
							</div>
						</div>
						<p class="field__hint">Max 2 MB. Will be resized to 400×400.</p>
						<p class="field__error" x-show="errors.avatar" x-text="errors.avatar?.[0] ?? ''"></p>
					</div>

					<div class="field">
						<label for="name" class="field__label">Name</label>
						<div class="input-group">
							<input type="text" id="name" name="name"
								class="input"
								:class="{ 'is-invalid': errors.name }"
								value="{{ old('name', $user->name) }}" />
						</div>
						<p class="field__error" x-show="errors.name" x-text="errors.name?.[0] ?? ''"></p>
					</div>

					<div class="field">
						<label for="email" class="field__label">Email</label>
						<div class="input-group">
							<input type="text" id="email" name="email"
								class="input"
								:class="{ 'is-invalid': errors.email }"
								value="{{ old('email', $user->email) }}" />
						</div>
						<p class="field__error" x-show="errors.email" x-text="errors.email?.[0] ?? ''"></p>
					</div>

					<div class="flex items-center gap-3">
						<button type="submit" class="button button--primary" :disabled="loading">
							<span x-show="!loading">Save changes</span>
							<span x-show="loading" x-cloak>Saving…</span>
						</button>
						<a href="{{ route('profile') }}" class="button button--ghost button--neutral">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>

	{{-- Change password --}}
	<div class="card">
		<div class="card__header">
			<span class="card__title">Change password</span>
			<p class="text-sm text-muted-foreground mt-1">Use a strong password of at least 8 characters.</p>
		</div>
		<div class="card__body">
			<form x-data="formAjax" @submit.prevent="submit"
				method="POST" action="{{ route('profile.password') }}">
				@csrf
				@method('PUT')

				<div x-show="serverError" x-cloak
					class="alert alert--danger mb-4"
					x-text="serverError"
					role="alert">
				</div>
				<div x-show="message" x-cloak
					class="alert alert--success mb-4"
					x-text="message"
					role="alert">
				</div>

				<div class="flex flex-col gap-4">
					<div class="field">
						<label for="current_password" class="field__label">Current password</label>
						<div class="input-group">
							<input type="password" id="current_password" name="current_password"
								class="input"
								:class="{ 'is-invalid': errors.current_password }"
								autocomplete="current-password" />
						</div>
						<p class="field__error" x-show="errors.current_password" x-text="errors.current_password?.[0] ?? ''"></p>
					</div>

					<div class="field">
						<label for="password" class="field__label">New password</label>
						<div class="input-group">
							<input type="password" id="password" name="password"
								class="input"
								:class="{ 'is-invalid': errors.password }"
								autocomplete="new-password" />
						</div>
						<p class="field__error" x-show="errors.password" x-text="errors.password?.[0] ?? ''"></p>
					</div>

					<div class="field">
						<label for="password_confirmation" class="field__label">Confirm new password</label>
						<div class="input-group">
							<input type="password" id="password_confirmation" name="password_confirmation"
								class="input"
								autocomplete="new-password" />
						</div>
					</div>

					<div>
						<button type="submit" class="button button--primary" :disabled="loading">
							<span x-show="!loading">Update password</span>
							<span x-show="loading" x-cloak>Updating…</span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
@endsection
