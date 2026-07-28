@extends('layouts.app')

@section('title', 'Profile — ' . config('app.name'))
@section('page-title', $user->name)

@section('breadcrumb')
	<li class="breadcrumb__item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
	<li class="breadcrumb__item" aria-current="page">Profile</li>
@endsection

@section('page-action')
	<a href="{{ route('profile.edit') }}" class="button button--neutral">
		<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
			<path fill="none" stroke="currentColor" stroke-width="1.5" d="m14.36 4.079l.927-.927a3.932 3.932 0 0 1 5.561 5.561l-.927.927m-5.56-5.561s.115 1.97 1.853 3.707C17.952 9.524 19.92 9.64 19.92 9.64m-5.56-5.561l-8.522 8.52c-.577.578-.866.867-1.114 1.185a6.6 6.6 0 0 0-.749 1.211c-.173.364-.302.752-.56 1.526l-1.094 3.281m17.6-10.162L11.4 18.16c-.577.577-.866.866-1.184 1.114a6.6 6.6 0 0 1-1.211.749c-.364.173-.751.302-1.526.56l-3.281 1.094m0 0l-.802.268a1.06 1.06 0 0 1-1.342-1.342l.268-.802m1.876 1.876l-1.876-1.876" />
		</svg>
		Edit profile
	</a>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-4">

	{{-- Left: details --}}
	<div class="col-span-12 lg:col-span-4 flex flex-col gap-4">
		<div class="card">
			<div class="card__body flex flex-col items-center text-center gap-3">
				<span class="avatar avatar--circle" data-stisla-avatar style="--avatar-size: 5rem;">
					<span class="avatar__fallback text-2xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
				</span>
				<div>
					<h2 class="text-lg font-semibold">{{ $user->name }}</h2>
					<div class="text-muted-foreground text-sm">{{ $user->getRoleNames()->join(', ') }}</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card__header"><span class="card__title">Details</span></div>
			<ul class="list-group">
				<li class="list-group__item">
					<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
						<g fill="none" stroke="currentColor" stroke-width="1.5">
							<path d="M2 12c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12s0 5.657-1.172 6.828S17.771 20 14 20h-4c-3.771 0-5.657 0-6.828-1.172S2 15.771 2 12Z" />
							<path stroke-linecap="round" d="m6 8l2.159 1.8c1.837 1.53 2.755 2.295 3.841 2.295s2.005-.765 3.841-2.296L18 8" />
						</g>
					</svg>
					<span>Email</span>
					<span class="ms-auto text-muted-foreground text-sm">{{ $user->email }}</span>
				</li>
				<li class="list-group__item">
					<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
						<g fill="none">
							<path stroke="currentColor" stroke-width="1.5" d="M2 12c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12v2c0 3.771 0 5.657-1.172 6.828S17.771 22 14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14z" />
							<path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M7 4V2.5M17 4V2.5M2.5 9h19" />
							<path fill="currentColor" d="M18 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0" />
						</g>
					</svg>
					<span>Joined</span>
					<span class="ms-auto text-muted-foreground text-sm">{{ $user->created_at->format('M Y') }}</span>
				</li>
			</ul>
		</div>
	</div>

	{{-- Right: activity log --}}
	<div class="col-span-12 lg:col-span-8">
		<div class="card h-full">
			<div class="card__header"><span class="card__title">Recent activity</span></div>
			<div class="card__body">
				@if ($activities->isEmpty())
					<p class="text-muted-foreground text-sm">No activity yet.</p>
				@else
					<ol class="timeline">
						@foreach ($activities as $activity)
							<li class="timeline__item">
								<span class="timeline__marker">
									<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
										<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l2 2m6-2a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />
									</svg>
								</span>
								<div class="timeline__body">
									<div class="timeline__title">{{ $activity->description }}</div>
									<div class="timeline__time">{{ $activity->created_at->diffForHumans() }}</div>
								</div>
							</li>
						@endforeach
					</ol>
				@endif
			</div>
		</div>
	</div>

</div>
@endsection
