@php
$initial = [];
foreach (['success', 'error', 'warning', 'info'] as $type) {
	if (session($type)) {
		$initial[] = ['type' => $type, 'message' => session($type)];
	}
}
@endphp

<div
	x-data="toast({{ json_encode($initial) }})"
	x-init="init()"
	class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 w-80"
	aria-live="polite"
	aria-atomic="false"
>
	<template x-for="t in toasts" :key="t.id">
		<div
			x-show="t.visible"
			x-transition:enter="transition ease-out duration-200"
			x-transition:enter-start="opacity-0 translate-y-2"
			x-transition:enter-end="opacity-100 translate-y-0"
			x-transition:leave="transition ease-in duration-150"
			x-transition:leave-start="opacity-100"
			x-transition:leave-end="opacity-0"
			class="alert flex items-start justify-between gap-3 shadow-md"
			:class="{
				'alert--success': t.type === 'success',
				'alert--danger':  t.type === 'error',
				'alert--warning': t.type === 'warning',
				'alert--info':    t.type === 'info',
			}"
			role="alert"
		>
			<span x-text="t.message" class="flex-1 text-sm"></span>
			<button
				@click="dismiss(t.id)"
				type="button"
				class="opacity-60 hover:opacity-100 shrink-0 leading-none"
				aria-label="Dismiss"
			>&times;</button>
		</div>
	</template>
</div>
