@extends('layouts.app')

@section('title', 'App Settings — ' . config('app.name'))
@section('page-title', 'App Settings')

@section('breadcrumb')
	<li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
	<li class="breadcrumb__item" aria-current="page">Settings</li>
@endsection

@section('content')
<div class="flex flex-col gap-6">

	{{-- Settings by group --}}
	@forelse ($groups as $group => $settings)
		<div class="card">
			<div class="card__header">
				<span class="card__title">{{ ucfirst($group) }}</span>
			</div>
			<div class="card__body">
				<form method="POST" action="{{ route('admin.settings.update') }}" class="flex flex-col gap-4">
					@csrf
					@method('PUT')

					@foreach ($settings as $setting)
						<div class="field">
							<label class="field__label font-mono text-xs">{{ $setting->key }}</label>
							@if ($setting->is_encrypted)
								<input type="password" name="settings[{{ $setting->key }}]"
									class="input" placeholder="(encrypted — leave blank to keep current)" />
								<p class="field__hint">Encrypted. Enter a new value to change it.</p>
							@else
								<input type="text" name="settings[{{ $setting->key }}]"
									class="input" value="{{ $setting->value }}" />
							@endif
						</div>
					@endforeach

					<div>
						<button type="submit" class="button button--primary">Save {{ ucfirst($group) }} settings</button>
					</div>
				</form>
			</div>
		</div>
	@empty
		<div class="card">
			<div class="card__body text-muted-foreground text-center py-8">
				No settings yet. Add one below.
			</div>
		</div>
	@endforelse

	{{-- Add new setting --}}
	<div class="card">
		<div class="card__header"><span class="card__title">Add setting</span></div>
		<div class="card__body">
			<form method="POST" action="{{ route('admin.settings.store') }}" class="flex flex-col gap-4">
				@csrf

				<div class="grid grid-cols-12 gap-4">
					<div class="col-span-12 sm:col-span-4 field">
						<label for="key" class="field__label">Key <span class="text-xs text-muted-foreground">(e.g. app.name)</span></label>
						<input type="text" id="key" name="key" class="input @error('key') is-invalid @enderror"
							value="{{ old('key') }}" placeholder="group.key_name" />
						@error('key') <p class="field__error">{{ $message }}</p> @enderror
					</div>
					<div class="col-span-12 sm:col-span-4 field">
						<label for="group" class="field__label">Group</label>
						<input type="text" id="group" name="group" class="input @error('group') is-invalid @enderror"
							value="{{ old('group', 'general') }}" />
						@error('group') <p class="field__error">{{ $message }}</p> @enderror
					</div>
					<div class="col-span-12 sm:col-span-2 field">
						<label for="type" class="field__label">Type</label>
						<select id="type" name="type" class="input">
							@foreach (['string', 'boolean', 'integer', 'json'] as $type)
								<option value="{{ $type }}" @selected(old('type', 'string') === $type)>{{ $type }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-span-12 sm:col-span-2 field">
						<label class="field__label">Encrypted</label>
						<div class="field__item mt-2">
							<input type="checkbox" id="is_encrypted" name="is_encrypted" value="1" class="checkbox"
								{{ old('is_encrypted') ? 'checked' : '' }} />
							<label for="is_encrypted" class="field__label font-normal">Yes</label>
						</div>
					</div>
					<div class="col-span-12 field">
						<label for="value" class="field__label">Value</label>
						<input type="text" id="value" name="value" class="input @error('value') is-invalid @enderror"
							value="{{ old('value') }}" />
						@error('value') <p class="field__error">{{ $message }}</p> @enderror
					</div>
				</div>

				<div>
					<button type="submit" class="button button--primary">Add setting</button>
				</div>
			</form>
		</div>
	</div>

</div>
@endsection
