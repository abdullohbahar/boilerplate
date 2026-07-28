@props(['column', 'label'])

@php
$currentSort = request('sort');
$currentDirection = request('direction', 'asc');
$isActive = $currentSort === $column;
$nextDirection = ($isActive && $currentDirection === 'asc') ? 'desc' : 'asc';
@endphp

<th>
	<a href="{{ request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection, 'page' => null]) }}"
		class="inline-flex items-center gap-1 select-none {{ $isActive ? 'text-foreground font-semibold' : 'text-muted-foreground' }}">
		{{ $label }}
		@if ($isActive)
			<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" aria-hidden="true">
				@if ($currentDirection === 'asc')
					<path d="m18 15-6-6-6 6" />
				@else
					<path d="m6 9 6 6 6-6" />
				@endif
			</svg>
		@else
			<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="2" opacity=".4" aria-hidden="true">
				<path d="m8 9 4-4 4 4" /><path d="m16 15-4 4-4-4" />
			</svg>
		@endif
	</a>
</th>
