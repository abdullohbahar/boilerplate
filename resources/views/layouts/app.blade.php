<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<script>
		(function () {
			var t = localStorage.getItem('stisla-theme');
			if (t === 'dark' || t === 'light') document.documentElement.dataset.theme = t;
		})();
	</script>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
	<title>@yield('title', config('app.name'))</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell" data-stisla-app-shell data-stisla-app-shell-auto-collapse="true">

	<x-sidebar />

	<main class="app-shell__main">
		@if (session('impersonator_id'))
			<div class="alert alert--warning flex items-center justify-between gap-4 rounded-none border-x-0 border-t-0" role="alert" style="position:sticky;top:0;z-index:100;">
				<span>
					<strong>Impersonation mode</strong> — you are currently viewing the app as <strong>{{ auth()->user()->name }}</strong>.
				</span>
				<form method="POST" action="{{ route('admin.impersonate.stop') }}">
					@csrf
					<button type="submit" class="button button--warning button--sm">Return to my account</button>
				</form>
			</div>
		@endif
		<header class="navbar">
			<button type="button"
				class="button button--ghost button--neutral button--icon-only button--flush-start"
				data-stisla-app-shell-toggle="auto"
				aria-label="Toggle sidebar">
				<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
					<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M20 7H4m16 5H4m16 5H4" />
				</svg>
			</button>

			<div class="ms-auto flex gap-1">
				<div class="menu">
					<button type="button"
						class="button button--ghost button--neutral button--sm"
						data-stisla-menu-trigger="langSwitch"
						aria-haspopup="menu"
						aria-expanded="false"
						aria-controls="langSwitch">
						{{ strtoupper(app()->getLocale()) }}
					</button>
					<div class="menu__popup" id="langSwitch" data-stisla-menu role="menu" data-state="closed">
						@foreach (config('app.supported_locales', ['en', 'id']) as $locale)
							<form method="POST" action="{{ route('locale.switch', $locale) }}">
								@csrf
								<button type="submit"
									class="menu__item w-full text-start {{ app()->getLocale() === $locale ? 'font-semibold' : '' }}"
									role="menuitem">
									{{ strtoupper($locale) }}
								</button>
							</form>
						@endforeach
					</div>
				</div>

				<button type="button"
					class="button button--ghost button--neutral button--icon-only"
					data-theme-toggle
					aria-label="Toggle theme">
					<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
						<path fill="currentColor" d="m21.067 11.857l-.642-.388zm-8.924-8.924l-.388-.642zM21.25 12A9.25 9.25 0 0 1 12 21.25v1.5c5.937 0 10.75-4.813 10.75-10.75zM12 21.25A9.25 9.25 0 0 1 2.75 12h-1.5c0 5.937 4.813 10.75 10.75 10.75zM2.75 12A9.25 9.25 0 0 1 12 2.75v-1.5C6.063 1.25 1.25 6.063 1.25 12zm12.75 2.25A5.75 5.75 0 0 1 9.75 8.5h-1.5a7.25 7.25 0 0 0 7.25 7.25zm4.925-2.781A5.75 5.75 0 0 1 15.5 14.25v1.5a7.25 7.25 0 0 0 6.21-3.505zM9.75 8.5a5.75 5.75 0 0 1 2.781-4.925l-.776-1.284A7.25 7.25 0 0 0 8.25 8.5zM12 2.75a.38.38 0 0 1-.268-.118a.3.3 0 0 1-.082-.155c-.004-.031-.002-.121.105-.186l.776 1.284c.503-.304.665-.861.606-1.299c-.062-.455-.42-1.026-1.137-1.026zm9.71 9.495c-.066.107-.156.109-.187.105a.3.3 0 0 1-.155-.082a.38.38 0 0 1-.118-.268h1.5c0-.717-.571-1.075-1.026-1.137c-.438-.059-.995.103-1.299.606z" />
					</svg>
				</button>

				<div class="menu">
					<button type="button"
						class="button button--ghost button--neutral flex items-center gap-2"
						data-stisla-menu-trigger="topbarUser"
						aria-haspopup="menu"
						aria-expanded="false"
						aria-controls="topbarUser">
						<span class="hidden sm:inline font-medium">{{ auth()->user()->name }}</span>
						<span class="avatar avatar--sm avatar--circle" data-stisla-avatar>
							@if (auth()->user()->fileUrl('avatar'))
								<img src="{{ auth()->user()->fileUrl('avatar') }}" alt="{{ auth()->user()->name }}" class="avatar__image" />
							@else
								<span class="avatar__fallback">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
							@endif
						</span>
						<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
							<path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9l-7 6l-7-6" />
						</svg>
					</button>
					<div class="menu__popup w-48" id="topbarUser" data-stisla-menu role="menu" data-state="closed">
						<div class="menu__group" role="group">
							<h3 class="menu__group-label">{{ auth()->user()->name }}</h3>
							<a href="{{ route('profile') }}" class="menu__item" role="menuitem">
								<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
									<g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="6" r="4" /><path d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5Z" /></g>
								</svg>
								Profile
							</a>
						</div>
						<hr class="menu__separator" role="separator" />
						<form method="POST" action="{{ route('logout') }}">
							@csrf
							<button type="submit" class="menu__item w-full text-start" role="menuitem">
								<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true">
									<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5">
										<path d="M9.002 7c.012-2.175.109-3.353.877-4.121C10.758 2 12.172 2 15 2h1c2.829 0 4.243 0 5.122.879C22 3.757 22 5.172 22 8v8c0 2.828 0 4.243-.878 5.121C20.242 22 18.829 22 16 22h-1c-2.828 0-4.242 0-5.121-.879c-.768-.768-.865-1.946-.877-4.121" />
										<path stroke-linejoin="round" d="M15 12H2m0 0l3.5-3M2 12l3.5 3" />
									</g>
								</svg>
								Log out
							</button>
						</form>
					</div>
				</div>
			</div>
		</header>

		<div class="page content">
			<div class="content__container">
				<header class="page__header">
					<div class="page__headline">
						@hasSection('breadcrumb')
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb">
									@yield('breadcrumb')
								</ol>
							</nav>
						@endif
						<h1 class="page__title">@yield('page-title')</h1>
						@hasSection('page-description')
							<p class="page__description">@yield('page-description')</p>
						@endif
					</div>
					@hasSection('page-action')
						<div class="page__action">
							@yield('page-action')
						</div>
					@endif
				</header>

				<div class="page__body">
					<x-flash-message />
					@yield('content')
				</div>
			</div>
		</div>
	</main>
</div>

@stack('scripts')
</body>
</html>
