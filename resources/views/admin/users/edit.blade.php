@extends('layouts.app')

@section('title', 'Edit User — ' . config('app.name'))
@section('page-title', 'Edit User')

@section('breadcrumb')
	<li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
	<li class="breadcrumb__item"><a href="{{ route('admin.users.index') }}">Users</a></li>
	<li class="breadcrumb__item" aria-current="page">{{ $user->name }}</li>
@endsection

@section('content')
<div class="card max-w-2xl">
	<div class="card__header">
		<span class="card__title">User details</span>
	</div>
	<div class="card__body">
		<form x-data="formAjax" @submit.prevent="submit"
			method="POST" action="{{ route('admin.users.update', $user) }}">
			@csrf
			@method('PUT')

			<div x-show="serverError" x-cloak class="alert alert--danger mb-4" x-text="serverError" role="alert"></div>

			<div class="flex flex-col gap-4">
				<div class="field">
					<label for="name" class="field__label">Name</label>
					<div class="input-group">
						<input type="text" id="name" name="name" class="input"
							:class="{ 'is-invalid': errors.name }"
							value="{{ old('name', $user->name) }}" />
					</div>
					<p class="field__error" x-show="errors.name" x-text="errors.name?.[0] ?? ''"></p>
				</div>

				<div class="field">
					<label for="email" class="field__label">Email</label>
					<div class="input-group">
						<input type="email" id="email" name="email" class="input"
							:class="{ 'is-invalid': errors.email }"
							value="{{ old('email', $user->email) }}" />
					</div>
					<p class="field__error" x-show="errors.email" x-text="errors.email?.[0] ?? ''"></p>
				</div>

				<div class="field">
					<label for="role" class="field__label">Role</label>
					<div class="input-group">
						<select id="role" name="role" class="input" :class="{ 'is-invalid': errors.role }">
							<option value="">Select role…</option>
							@foreach ($roles as $role)
								<option value="{{ $role->name }}"
									@selected(old('role', $user->roles->first()?->name) === $role->name)>
									{{ ucfirst($role->name) }}
								</option>
							@endforeach
						</select>
					</div>
					<p class="field__error" x-show="errors.role" x-text="errors.role?.[0] ?? ''"></p>
				</div>

				<div class="field">
					<label for="password" class="field__label">
						New password
						<span class="text-muted-foreground font-normal text-xs ms-1">(leave blank to keep current)</span>
					</label>
					<div class="input-group">
						<input type="password" id="password" name="password" class="input"
							:class="{ 'is-invalid': errors.password }"
							autocomplete="new-password" />
					</div>
					<p class="field__error" x-show="errors.password" x-text="errors.password?.[0] ?? ''"></p>
				</div>

				<div class="field">
					<label for="password_confirmation" class="field__label">Confirm new password</label>
					<div class="input-group">
						<input type="password" id="password_confirmation" name="password_confirmation"
							class="input" autocomplete="new-password" />
					</div>
				</div>

				<div class="flex items-center gap-3">
					<button type="submit" class="button button--primary" :disabled="loading">
						<span x-show="!loading">Save changes</span>
						<span x-show="loading" x-cloak>Saving…</span>
					</button>
					<a href="{{ route('admin.users.index') }}" class="button button--ghost button--neutral">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
