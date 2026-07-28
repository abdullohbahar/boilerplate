@extends('layouts.app')

@section('title', 'Add Role — ' . config('app.name'))
@section('page-title', 'Add Role')

@section('breadcrumb')
	<x-breadcrumb :items="[['label' => 'Dashboard', 'route' => 'dashboard'], ['label' => 'Roles', 'route' => 'admin.roles.index'], ['label' => 'Add']]" />
@endsection

@section('content')
<div class="card max-w-2xl">
	<div class="card__header">
		<span class="card__title">Role details</span>
	</div>
	<div class="card__body">
		<form x-data="formAjax" @submit.prevent="submit"
			method="POST" action="{{ route('admin.roles.store') }}">
			@csrf

			<div x-show="serverError" x-cloak class="alert alert--danger mb-4" x-text="serverError" role="alert"></div>

			<div class="flex flex-col gap-4">
				<div class="field">
					<label for="name" class="field__label">Name</label>
					<div class="input-group">
						<input type="text" id="name" name="name" class="input"
							:class="{ 'is-invalid': errors.name }"
							value="{{ old('name') }}"
							placeholder="e.g. editor" />
					</div>
					<p class="field__hint">Lowercase letters, numbers, hyphens and underscores only.</p>
					<p class="field__error" x-show="errors.name" x-text="errors.name?.[0] ?? ''"></p>
				</div>

				<div class="flex items-center gap-3">
					<button type="submit" class="button button--primary" :disabled="loading">
						<span x-show="!loading">Create role</span>
						<span x-show="loading" x-cloak>Creating…</span>
					</button>
					<a href="{{ route('admin.roles.index') }}" class="button button--ghost button--neutral">Cancel</a>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
