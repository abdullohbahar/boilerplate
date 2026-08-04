@extends('layouts.app')

@section('title', 'Users — ' . config('app.name'))
@section('page-title', 'Users')

@section('breadcrumb')
	<x-breadcrumb :items="[['label' => 'Dashboard', 'route' => 'dashboard'], ['label' => 'Users']]" />
@endsection

@section('page-action')
	<a href="{{ route('admin.users.create') }}" class="button button--primary">
		<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
			<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M12 5v14m-7-7h14" />
		</svg>
		Add user
	</a>
@endsection

@section('content')
<div class="card">
	<div class="card__header">
		<x-search-bar :action="route('admin.users.index')" :value="request('search')" placeholder="Search name or email…" />
	</div>

	<div class="table-container">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<x-sortable-th column="name" label="Name" />
					<x-sortable-th column="email" label="Email" />
					<th>Role</th>
					<x-sortable-th column="created_at" label="Joined" />
					<th></th>
				</tr>
			</thead>
			<tbody>
				@forelse ($users as $user)
					<tr>
						<td class="text-muted-foreground text-sm">{{ $users->firstItem() + $loop->index }}</td>
						<td class="font-medium">{{ $user->name }}</td>
						<td class="text-muted-foreground">{{ $user->email }}</td>
						<td>
							@foreach ($user->roles as $role)
								<span class="badge badge--{{ $role->name === 'admin' ? 'primary' : 'neutral' }}">
									{{ $role->name }}
								</span>
							@endforeach
						</td>
						<td class="text-muted-foreground text-sm">{{ $user->created_at->format('d M Y') }}</td>
						<td>
							<div class="flex items-center gap-1 justify-end">
								@if (! $user->hasRole('admin') && $user->id !== auth()->id() && ! session('impersonator_id'))
									<form method="POST" action="{{ route('admin.impersonate.start', $user) }}"
										onsubmit="return confirm('Login as {{ addslashes($user->name) }}?')">
										@csrf
										<button type="submit" class="button button--ghost button--neutral button--sm">Login as</button>
									</form>
								@endif
								<a href="{{ route('admin.users.edit', $user) }}"
									class="button button--ghost button--neutral button--sm">Edit</a>
								<button type="button"
									onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}', '{{ addslashes($user->name) }}')"
									class="button button--ghost button--danger button--sm">Delete</button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="6" class="text-center text-muted-foreground py-8">No users found.</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<x-pagination :paginator="$users" />
</div>
@endsection
