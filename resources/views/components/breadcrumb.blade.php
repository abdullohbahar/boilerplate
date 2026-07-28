@props(['items' => []])

@foreach ($items as $i => $item)
	@php $isLast = $i === array_key_last($items); @endphp
	@if ($isLast)
		<li class="breadcrumb__item" aria-current="page">{{ $item['label'] }}</li>
	@else
		<li class="breadcrumb__item">
			@if (isset($item['route']))
				<a href="{{ route($item['route'], $item['params'] ?? []) }}">{{ $item['label'] }}</a>
			@elseif (isset($item['url']))
				<a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
			@else
				{{ $item['label'] }}
			@endif
		</li>
	@endif
@endforeach
