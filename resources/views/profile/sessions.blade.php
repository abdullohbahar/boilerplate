@extends('layouts.app')

@section('title', 'Active Sessions — ' . config('app.name'))
@section('page-title', 'Active Sessions')

@section('breadcrumb')
	<li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
	<li class="breadcrumb__item"><a href="{{ route('profile') }}">Profile</a></li>
	<li class="breadcrumb__item" aria-current="page">Sessions</li>
@endsection

@section('page-action')
	<form method="POST" action="{{ route('profile.sessions.destroy-others') }}"
		onsubmit="return confirm('Log out all other sessions?')">
		@csrf
		@method('DELETE')
		<button type="submit" class="button button--danger button--sm">Log out all other devices</button>
	</form>
@endsection

@section('content')
<div class="card max-w-2xl">
	<div class="card__header"><span class="card__title">Browser sessions</span></div>
	<ul class="list-group">
		@foreach ($sessions as $session)
			<li class="list-group__item">
				<svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24" aria-hidden="true" class="text-muted-foreground shrink-0">
					<g fill="none" stroke="currentColor" stroke-width="1.5">
						<path d="M2 12c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12v2c0 3.771 0 5.657-1.172 6.828S17.771 22 14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14z" />
						<path stroke-linecap="round" d="M9 22v-4m6 4v-4m-7 0h8" />
					</g>
				</svg>
				<div class="flex-1 min-w-0">
					<div class="flex items-center gap-2">
						<span class="font-medium text-sm">{{ $session->agent }}</span>
						@if ($session->is_current)
							<span class="badge badge--success text-xs">This device</span>
						@endif
					</div>
					<div class="text-xs text-muted-foreground">{{ $session->ip }} · {{ $session->last_active }}</div>
				</div>
				@if (! $session->is_current)
					<form method="POST" action="{{ route('profile.sessions.destroy', $session->id) }}">
						@csrf
						@method('DELETE')
						<button type="submit" class="button button--ghost button--danger button--sm">Revoke</button>
					</form>
				@endif
			</li>
		@endforeach
	</ul>
</div>
@endsection
