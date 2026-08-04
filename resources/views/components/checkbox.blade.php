@props([
	'label' => null,
	'name',
	'value' => '1',
	'hint'  => null,
	'id'    => null,
])

@php $inputId = $id ?? $name; @endphp

<div class="field">
	<label class="flex items-center gap-2 cursor-pointer" for="{{ $inputId }}">
		<input
			id="{{ $inputId }}"
			name="{{ $name }}"
			type="checkbox"
			value="{{ $value }}"
			{{ $attributes }}
			@checked(old($name, false))
		/>
		@if ($label)
			<span class="field__label mb-0">{{ $label }}</span>
		@endif
	</label>
	@error($name)
		<p class="field__error">{{ $message }}</p>
	@enderror
	@if ($hint)
		<p class="field__hint">{{ $hint }}</p>
	@endif
</div>
