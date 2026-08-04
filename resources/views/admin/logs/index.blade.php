@extends('layouts.app')

@section('title', 'Log Viewer — ' . config('app.name'))
@section('page-title', 'Log Viewer')

@section('breadcrumb')
	<x-breadcrumb :items="[['label' => 'Dashboard', 'route' => 'dashboard'], ['label' => 'Log Viewer']]" />
@endsection

@section('content')
<div class="card">
	<div class="card__header flex flex-wrap items-center gap-2">
		<form method="GET" action="{{ route('admin.logs.index') }}" class="flex gap-2 flex-wrap">
			@foreach ($levels as $lvl)
				<button type="submit" name="level" value="{{ $lvl }}"
					class="badge {{ $filterLevel === $lvl ? 'badge--primary' : '' }} cursor-pointer">
					{{ strtoupper($lvl) }}
				</button>
			@endforeach
		</form>
		<span class="text-muted-foreground text-sm ml-auto">{{ count($entries) }} entries</span>
	</div>

	<div class="table-container">
		<table class="table">
			<thead>
				<tr>
					<th style="width:160px">Time</th>
					<th style="width:100px">Level</th>
					<th>Message</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($entries as $entry)
					<tr x-data="{ open: false }">
						<td class="text-muted-foreground text-sm font-mono">{{ $entry['time'] }}</td>
						<td>
							@php
								$color = match($entry['level']) {
									'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY' => 'badge--danger',
									'WARNING' => 'badge--warning',
									'INFO', 'NOTICE' => 'badge--info',
									default => '',
								};
							@endphp
							<span class="badge {{ $color }}">{{ $entry['level'] }}</span>
						</td>
						<td>
							<div class="font-mono text-sm break-all">{{ $entry['message'] }}</div>
							@if ($entry['context'])
								<button @click="open = !open" class="text-xs text-muted-foreground hover:underline mt-1">
									<span x-text="open ? 'Hide context' : 'Show context'"></span>
								</button>
								<pre x-show="open" class="text-xs bg-base-200 rounded p-2 mt-1 overflow-x-auto whitespace-pre-wrap">{{ trim($entry['context']) }}</pre>
							@endif
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="3" class="text-center text-muted-foreground py-8">No log entries found.</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@endsection
