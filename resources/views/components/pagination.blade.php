@props(['paginator'])

@if ($paginator->hasPages())
	<div class="card__footer">
		{{ $paginator->links() }}
	</div>
@endif
