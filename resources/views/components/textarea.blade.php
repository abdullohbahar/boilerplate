@props([
	'label' => null,
	'name',
	'rows'  => 4,
	'hint'  => null,
	'id'    => null,
])

@php $inputId = $id ?? $name; @endphp

<div class="field">
	@if ($label)
		<label for="{{ $inputId }}" class="field__label">{{ $label }}</label>
	@endif
	<div class="input-group">
		<textarea
			id="{{ $inputId }}"
			name="{{ $name }}"
			rows="{{ $rows }}"
			{{ $attributes->merge(['class' => 'input' . ($errors->has($name) ? ' is-invalid' : '')]) }}
		>{{ old($name, $slot) }}</textarea>
	</div>
	@error($name)
		<p class="field__error">{{ $message }}</p>
	@enderror
	@if ($hint)
		<p class="field__hint">{{ $hint }}</p>
	@endif
</div>
