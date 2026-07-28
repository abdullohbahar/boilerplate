@props([
	'action',
	'value' => null,
	'placeholder' => 'Search...',
	'name' => 'search',
])

<form method="GET" action="{{ $action }}" class="flex items-center gap-2 ms-auto">
	<div class="input-group">
		<input type="search" name="{{ $name }}" class="input" placeholder="{{ $placeholder }}"
			value="{{ $value }}" />
	</div>
	@if ($value)
		<a href="{{ $action }}" class="button button--ghost button--neutral">{{ __('app.cancel') }}</a>
	@endif
</form>
