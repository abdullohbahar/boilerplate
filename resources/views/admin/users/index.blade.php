@extends('layouts.app')

@section('title', 'Users — ' . config('app.name'))
@section('page-title', 'Users')

@section('breadcrumb')
	<li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
	<li class="breadcrumb__item" aria-current="page">Users</li>
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
		<form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2 ms-auto">
			<div class="input-group">
				<input type="search" name="search" class="input" placeholder="Search name or email…"
					value="{{ request('search') }}" />
			</div>
			@if (request('search'))
				<a href="{{ route('admin.users.index') }}" class="button button--ghost button--neutral">Clear</a>
			@endif
		</form>
	</div>

	<div class="table-container">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th>Role</th>
					<th>Joined</th>
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
								<a href="{{ route('admin.users.edit', $user) }}"
									class="button button--ghost button--neutral button--sm">Edit</a>
								<form method="POST" action="{{ route('admin.users.destroy', $user) }}"
									onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
									@csrf
									@method('DELETE')
									<button type="submit" class="button button--ghost button--danger button--sm">Delete</button>
								</form>
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

	@if ($users->hasPages())
		<div class="card__footer">
			{{ $users->links() }}
		</div>
	@endif
</div>
@endsection
