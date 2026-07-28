@php
$canSee = fn (string $key) => $sidebarMenus->contains('key', $key);
$hasAdminMenus = $sidebarMenus->contains(fn ($m) => str_starts_with($m->key, 'admin.'));
@endphp

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
					@if ($canSee('dashboard'))
						<li class="sidebar__item">
							<a class="sidebar__button {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
									<path fill="currentColor" d="M2 6.5c0-2.121 0-3.182.659-3.841S4.379 2 6.5 2s3.182 0 3.841.659S11 4.379 11 6.5s0 3.182-.659 3.841S8.621 11 6.5 11s-3.182 0-3.841-.659S2 8.621 2 6.5m11 11c0-2.121 0-3.182.659-3.841S15.379 13 17.5 13s3.182 0 3.841.659S22 15.379 22 17.5s0 3.182-.659 3.841S19.621 22 17.5 22s-3.182 0-3.841-.659S13 19.621 13 17.5" opacity=".5" />
									<path fill="currentColor" d="M2 17.5c0-2.121 0-3.182.659-3.841S4.379 13 6.5 13s3.182 0 3.841.659S11 15.379 11 17.5s0 3.182-.659 3.841S8.621 22 6.5 22s-3.182 0-3.841-.659S2 19.621 2 17.5m11-11c0-2.121 0-3.182.659-3.841S15.379 2 17.5 2s3.182 0 3.841.659S22 4.379 22 6.5s0 3.182-.659 3.841S19.621 11 17.5 11s-3.182 0-3.841-.659S13 8.621 13 6.5" />
								</svg>
								<span>{{ __('app.dashboard') }}</span>
							</a>
						</li>
					@endif
					@if ($canSee('profile'))
						<li class="sidebar__item">
							<a class="sidebar__button {{ request()->routeIs('profile', 'profile.edit') ? 'active' : '' }}" href="{{ route('profile') }}">
								<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
									<g fill="none" stroke="currentColor" stroke-width="1.5">
										<circle cx="12" cy="6" r="4" />
										<path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" opacity=".5" />
									</g>
								</svg>
								<span>{{ __('app.profile') }}</span>
							</a>
						</li>
					@endif
				</ul>
			</div>
		</nav>
	</div>

	@if ($hasAdminMenus)
		<div class="sidebar__content">
			<nav class="sidebar__menu">
				<div class="sidebar__group">
					<span class="sidebar__group-title">Admin</span>
					<ul class="sidebar__list">
						@if ($canSee('admin.users'))
							<li class="sidebar__item">
								<a class="sidebar__button {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
									<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
										<g fill="none" stroke="currentColor" stroke-width="1.5">
											<circle cx="9" cy="6" r="4" />
											<path d="M15 9a3 3 0 1 0 0-6" opacity=".5" />
											<ellipse cx="9" cy="17" rx="7" ry="4" />
											<path d="M22 17c0 2.21-3.134 4-7 4" opacity=".5" />
										</g>
									</svg>
									<span>{{ __('app.users') }}</span>
								</a>
							</li>
						@endif
						@if ($canSee('admin.roles'))
							<li class="sidebar__item">
								<a class="sidebar__button {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
									<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
										<g fill="none" stroke="currentColor" stroke-width="1.5">
											<path d="M12 2C9.239 2 7 4.239 7 7s2.239 5 5 5 5-2.239 5-5-2.239-5-5-5Z" opacity=".5" />
											<path d="M17 14H7a5 5 0 0 0-5 5v1a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-1a5 5 0 0 0-5-5Z" />
											<path d="M17 10.5 18.5 12l3-3" stroke-linecap="round" stroke-linejoin="round" />
										</g>
									</svg>
									<span>{{ __('app.roles') }}</span>
								</a>
							</li>
						@endif
						@if ($canSee('admin.activity'))
							<li class="sidebar__item">
								<a class="sidebar__button {{ request()->routeIs('admin.activity.*') ? 'active' : '' }}" href="{{ route('admin.activity.index') }}">
									<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
										<g fill="none" stroke="currentColor" stroke-width="1.5">
											<path d="M3 12h4l3-9 4 18 3-9h4" stroke-linecap="round" stroke-linejoin="round" />
										</g>
									</svg>
									<span>{{ __('app.activity_log') }}</span>
								</a>
							</li>
						@endif
						@if ($canSee('admin.menus'))
							<li class="sidebar__item">
								<a class="sidebar__button {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" href="{{ route('admin.menus.index') }}">
									<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
										<g fill="none" stroke="currentColor" stroke-width="1.5">
											<path d="M20 7H4" stroke-linecap="round" />
											<path d="M20 12H4" stroke-linecap="round" opacity=".5" />
											<path d="M20 17H4" stroke-linecap="round" />
										</g>
									</svg>
									<span>{{ __('app.menus') }}</span>
								</a>
							</li>
						@endif
						@if ($canSee('admin.settings'))
							<li class="sidebar__item">
								<a class="sidebar__button {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
									<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
										<g fill="none" stroke="currentColor" stroke-width="1.5">
											<path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" /><path d="M19.622 10.395l-1.097-2.65L20 6l-2-2-1.735 1.483-2.707-1.113L12.935 2h-1.954l-.632 2.401-2.645 1.115L6 4 4 6l1.453 1.789-1.08 2.657L2 11v2l2.401.655L5.516 16.3 4 18l2 2 1.791-1.46 2.606 1.072L11 22h2l.604-2.387 2.651-1.098C16.697 19.51 18 20 18 20l2-2-1.484-1.75 1.098-2.652L22 13v-2z" />
										</g>
									</svg>
									<span>{{ __('app.settings') }}</span>
								</a>
							</li>
						@endif
					</ul>
				</div>
			</nav>
		</div>
	@endif

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
						<span>{{ __('app.sign_out') }}</span>
					</button>
				</form>
			</li>
		</ul>
	</footer>
</aside>
