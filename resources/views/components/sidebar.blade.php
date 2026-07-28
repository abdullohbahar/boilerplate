<aside class="sidebar sidebar--lg sidebar--app" data-stisla-sidebar>
	<header class="sidebar__header">
		<a class="sidebar__brand" href="{{ url('/') }}">
			<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
				<path d="M12 1.5l3.4 7.1 7.1 3.4-7.1 3.4-3.4 7.1-3.4-7.1L1.5 12l7.1-3.4z" opacity=".45" />
				<path d="M12 1.5l3.4 7.1L12 12 8.6 8.6z" />
			</svg>
			<span>{{ config('app.name') }}</span>
		</a>
	</header>

	<div class="sidebar__content">
		<nav class="sidebar__menu">
			<div class="sidebar__group">
				<span class="sidebar__group-title">Menu</span>
				<ul class="sidebar__list">
					<li class="sidebar__item">
						<a class="sidebar__button {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
							<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
								<path fill="currentColor" d="M2 6.5c0-2.121 0-3.182.659-3.841S4.379 2 6.5 2s3.182 0 3.841.659S11 4.379 11 6.5s0 3.182-.659 3.841S8.621 11 6.5 11s-3.182 0-3.841-.659S2 8.621 2 6.5m11 11c0-2.121 0-3.182.659-3.841S15.379 13 17.5 13s3.182 0 3.841.659S22 15.379 22 17.5s0 3.182-.659 3.841S19.621 22 17.5 22s-3.182 0-3.841-.659S13 19.621 13 17.5" opacity=".5" />
								<path fill="currentColor" d="M2 17.5c0-2.121 0-3.182.659-3.841S4.379 13 6.5 13s3.182 0 3.841.659S11 15.379 11 17.5s0 3.182-.659 3.841S8.621 22 6.5 22s-3.182 0-3.841-.659S2 19.621 2 17.5m11-11c0-2.121 0-3.182.659-3.841S15.379 2 17.5 2s3.182 0 3.841.659S22 4.379 22 6.5s0 3.182-.659 3.841S19.621 11 17.5 11s-3.182 0-3.841-.659S13 8.621 13 6.5" />
							</svg>
							<span>Dashboard</span>
						</a>
					</li>
					<li class="sidebar__item">
						<a class="sidebar__button {{ request()->routeIs('profile', 'profile.edit') ? 'active' : '' }}" href="{{ route('profile') }}">
							<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
								<g fill="none" stroke="currentColor" stroke-width="1.5">
									<circle cx="12" cy="6" r="4" />
									<path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" opacity=".5" />
								</g>
							</svg>
							<span>Profile</span>
						</a>
					</li>
				</ul>
			</div>
		</nav>
	</div>

	<footer class="sidebar__footer">
		<ul class="sidebar__list">
			<li class="sidebar__item">
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<button type="submit" class="sidebar__button w-full text-start">
						<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
							<path fill="currentColor" d="M16 2h-1c-2.829 0-4.242 0-5.121.879S9 5.172 9 8v8c0 2.829 0 4.243.879 5.122c.878.878 2.292.878 5.119.878H16c2.828 0 4.242 0 5.121-.879C22 20.243 22 18.828 22 16V8c0-2.828 0-4.243-.879-5.121S18.828 2 16 2" opacity=".5" />
							<path fill="currentColor" fill-rule="evenodd" d="M15.75 12a.75.75 0 0 0-.75-.75H4.027l1.961-1.68a.75.75 0 1 0-.976-1.14l-3.5 3a.75.75 0 0 0 0 1.14l3.5 3a.75.75 0 1 0 .976-1.14l-1.96-1.68H15a.75.75 0 0 0 .75-.75" clip-rule="evenodd" />
						</svg>
						<span>Log out</span>
					</button>
				</form>
			</li>
		</ul>
	</footer>
</aside>
