@extends('layouts.app')

@section('title', 'Activity Log — ' . config('app.name'))
@section('page-title', 'Activity Log')

@section('breadcrumb')
	<x-breadcrumb :items="[['label' => 'Dashboard', 'route' => 'dashboard'], ['label' => 'Activity Log']]" />
@endsection

@section('content')
<div class="card">
	<div class="card__header">
		<x-search-bar :action="route('admin.activity.index')" :value="request('search')" placeholder="Search description or user…" />
	</div>

	<div class="table-container">
		<table class="table">
			<thead>
				<tr>
					<th>Description</th>
					<th>Subject</th>
					<th>User</th>
					<x-sortable-th column="created_at" label="When" />
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

	<x-pagination :paginator="$activities" />
</div>
@endsection
