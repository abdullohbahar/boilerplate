<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
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
	@vite(['resources/css/app.css'])
</head>
<body>
<div class="flex flex-col items-center justify-center min-h-screen text-center px-4">
	<a href="{{ url('/') }}" class="flex items-center gap-2 mb-8 text-muted-foreground hover:text-foreground transition-colors">
		<svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
			<path d="M12 1.5l3.4 7.1 7.1 3.4-7.1 3.4-3.4 7.1-3.4-7.1L1.5 12l7.1-3.4z" opacity=".45" />
			<path d="M12 1.5l3.4 7.1L12 12 8.6 8.6z" />
		</svg>
		<span class="font-semibold">{{ config('app.name') }}</span>
	</a>

	<p class="text-8xl font-bold text-muted-foreground/30 leading-none">@yield('code')</p>
	<h1 class="text-2xl font-semibold mt-4">@yield('heading')</h1>
	<p class="text-muted-foreground mt-2 max-w-sm">@yield('message')</p>

	<div class="flex items-center gap-3 mt-8">
		@yield('actions')
	</div>
</div>
</body>
</html>
