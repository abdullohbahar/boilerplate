@extends('layouts.app')

@section('title', 'Menu Access — ' . config('app.name'))
@section('page-title', 'Menu Access')

@section('breadcrumb')
	<x-breadcrumb :items="[['label' => 'Dashboard', 'route' => 'dashboard'], ['label' => 'Menu Access']]" />
@endsection

@section('content')
<div class="card">
	<div class="card__header">
		<span class="card__title">Role — Menu Assignment</span>
		<p class="text-muted-foreground text-sm mt-1">{{ __('Control which roles can see each sidebar menu item.') }}</p>
	</div>
	<div class="card__body">
		@if ($menus->isEmpty())
			<div class="text-muted-foreground text-center py-8">
				No menus found. Run <code class="font-mono">php artisan menu:sync</code> to populate from config.
			</div>
		@else
			<form method="POST" action="{{ route('admin.menus.update') }}">
				@csrf
				@method('PUT')

				<div class="table-container">
					<table class="table">
						<thead>
							<tr>
								<th>Menu</th>
								<th>Route</th>
								@foreach ($roles as $role)
									<th class="text-center">{{ ucfirst($role->name) }}</th>
								@endforeach
							</tr>
						</thead>
						<tbody>
							@foreach ($menus as $menu)
								@php $assignedRoles = $menu->roles->pluck('name')->toArray(); @endphp
								<tr>
									<td class="font-medium">
										{{ $menu->label }}
										@if ($menu->parent_key)
											<span class="text-muted-foreground text-xs ml-1">↳ {{ $menu->parent_key }}</span>
										@endif
									</td>
									<td class="font-mono text-xs text-muted-foreground">{{ $menu->route ?? '—' }}</td>
									@foreach ($roles as $role)
										<td class="text-center">
											<input
												type="checkbox"
												name="menu_roles[{{ $menu->id }}][]"
												value="{{ $role->name }}"
												{{ in_array($role->name, $assignedRoles) ? 'checked' : '' }}
											/>
										</td>
									@endforeach
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>

				<div class="mt-4">
					<button type="submit" class="button button--primary">{{ __('Save Changes') }}</button>
				</div>
			</form>
		@endif
	</div>
</div>
@endsection
