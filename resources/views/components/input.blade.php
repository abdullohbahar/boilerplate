@props([
	'label'  => null,
	'name',
	'type'   => 'text',
	'value'  => null,
	'hint'   => null,
	'id'     => null,
])

@php $inputId = $id ?? $name; @endphp

<div class="field">
	@if ($label)
		<label for="{{ $inputId }}" class="field__label">{{ $label }}</label>
	@endif
	<div class="input-group">
		<input
			id="{{ $inputId }}"
			name="{{ $name }}"
			type="{{ $type }}"
			value="{{ old($name, $value) }}"
			{{ $attributes->merge(['class' => 'input' . ($errors->has($name) ? ' is-invalid' : '')]) }}
		/>
	</div>
	@error($name)
		<p class="field__error">{{ $message }}</p>
	@enderror
	@if ($hint)
		<p class="field__hint">{{ $hint }}</p>
	@endif
</div>
