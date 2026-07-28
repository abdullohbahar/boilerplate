@extends('layouts.app')

@section('title', 'Activity Log — ' . config('app.name'))
@section('page-title', 'Activity Log')

@section('breadcrumb')
	<li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
	<li class="breadcrumb__item" aria-current="page">Activity Log</li>
@endsection

@section('content')
<div class="card">
	<div class="card__header">
		<form method="GET" action="{{ route('admin.activity.index') }}" class="flex items-center gap-2 ms-auto">
			<div class="input-group">
				<input type="search" name="search" class="input" placeholder="Search description or user…"
					value="{{ request('search') }}" />
			</div>
			@if (request('search'))
				<a href="{{ route('admin.activity.index') }}" class="button button--ghost button--neutral">Clear</a>
			@endif
		</form>
	</div>

	<div class="table-container">
		<table class="table">
			<thead>
				<tr>
					<th>Description</th>
					<th>Subject</th>
					<th>User</th>
					<th>When</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($activities as $activity)
					<tr>
						<td class="font-medium">{{ $activity->description }}</td>
						<td class="text-muted-foreground text-sm">
							@if ($activity->subject_type)
								{{ class_basename($activity->subject_type) }}
								@if ($activity->subject_id)
									<span class="text-xs opacity-60">#{{ $activity->subject_id }}</span>
								@endif
							@else
								—
							@endif
						</td>
						<td>
							@if ($activity->causer)
								<div class="flex flex-col">
									<span class="text-sm font-medium">{{ $activity->causer->name }}</span>
									<span class="text-xs text-muted-foreground">{{ $activity->causer->email }}</span>
								</div>
							@else
								<span class="text-muted-foreground">—</span>
							@endif
						</td>
						<td class="text-muted-foreground text-sm" title="{{ $activity->created_at->format('d M Y H:i:s') }}">
							{{ $activity->created_at->diffForHumans() }}
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" class="text-center text-muted-foreground py-8">No activity found.</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	@if ($activities->hasPages())
		<div class="card__footer">
			{{ $activities->links() }}
		</div>
	@endif
</div>
@endsection
