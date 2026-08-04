@extends('layouts.app')

@section('title', 'Roles — ' . config('app.name'))
@section('page-title', 'Roles')

@section('breadcrumb')
	<x-breadcrumb :items="[['label' => 'Dashboard', 'route' => 'dashboard'], ['label' => 'Roles']]" />
@endsection

@section('page-action')
	<a href="{{ route('admin.roles.create') }}" class="button button--primary">
		<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
			<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M12 5v14m-7-7h14" />
		</svg>
		Add role
	</a>
@endsection

@section('content')
<div class="card max-w-2xl">
	<div class="table-container">
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Users</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@forelse ($roles as $role)
					<tr>
						<td class="font-medium">{{ $role->name }}</td>
						<td class="text-muted-foreground">{{ $role->users_count }}</td>
						<td>
							<div class="flex items-center gap-1 justify-end">
								<a href="{{ route('admin.roles.edit', $role) }}"
									class="button button--ghost button--neutral button--sm">Edit</a>
								<button type="button"
									onclick="confirmDelete('{{ route('admin.roles.destroy', $role) }}', '{{ addslashes($role->name) }}')"
									class="button button--ghost button--danger button--sm">Delete</button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="3" class="text-center text-muted-foreground py-8">No roles found.</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@endsection
