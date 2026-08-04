@props([
	'label'       => null,
	'name',
	'options'     => [],
	'placeholder' => null,
	'hint'        => null,
	'id'          => null,
])

@php
$inputId = $id ?? $name;
// Normalise options to ['value' => 'label'] from Collection of objects with id/name too
$normalised = collect($options)->mapWithKeys(function ($item, $key) {
	if (is_object($item)) { return [$item->id ?? $item->value => $item->name ?? $item->label]; }
	if (is_array($item)) { return [$item['value'] ?? $key => $item['label'] ?? $item['name'] ?? $item['value']]; }
	return [$key => $item];
});
@endphp

<div class="field">
	@if ($label)
		<label for="{{ $inputId }}" class="field__label">{{ $label }}</label>
	@endif
	<div class="input-group">
		<select
			id="{{ $inputId }}"
			name="{{ $name }}"
			{{ $attributes->merge(['class' => 'input' . ($errors->has($name) ? ' is-invalid' : '')]) }}
		>
			@if ($placeholder)
				<option value="">{{ $placeholder }}</option>
			@endif
			@foreach ($normalised as $val => $text)
				<option value="{{ $val }}" @selected(old($name) == $val)>{{ $text }}</option>
			@endforeach
		</select>
	</div>
	@error($name)
		<p class="field__error">{{ $message }}</p>
	@enderror
	@if ($hint)
		<p class="field__hint">{{ $hint }}</p>
	@endif
</div>
