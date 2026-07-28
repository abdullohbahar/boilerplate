<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>@yield('subject', config('app.name'))</title>
	<style>
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
		body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; background: #f3f4f6; color: #111827; -webkit-font-smoothing: antialiased; }
		.wrapper { max-width: 600px; margin: 40px auto; }
		.header { padding: 24px 32px; background: #fff; border-radius: 12px 12px 0 0; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 10px; }
		.header-brand { font-size: 18px; font-weight: 700; color: #111827; text-decoration: none; }
		.body { padding: 32px; background: #fff; }
		.body h1 { font-size: 22px; font-weight: 700; margin-bottom: 12px; }
		.body p { font-size: 15px; line-height: 1.6; color: #374151; margin-bottom: 16px; }
		.btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: #fff !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; }
		.footer { padding: 20px 32px; background: #f9fafb; border-radius: 0 0 12px 12px; border-top: 1px solid #e5e7eb; font-size: 13px; color: #6b7280; text-align: center; }
		.footer a { color: #6b7280; }
		.divider { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
	</style>
</head>
<body>
<div class="wrapper">
	<div class="header">
		<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#2563eb" aria-hidden="true">
			<path d="M12 1.5l3.4 7.1 7.1 3.4-7.1 3.4-3.4 7.1-3.4-7.1L1.5 12l7.1-3.4z" opacity=".45" />
			<path d="M12 1.5l3.4 7.1L12 12 8.6 8.6z" />
		</svg>
		<a href="{{ url('/') }}" class="header-brand">{{ config('app.name') }}</a>
	</div>

	<div class="body">
		@yield('content')
	</div>

	<div class="footer">
		&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
		@hasSection('unsubscribe')
			&nbsp;·&nbsp; @yield('unsubscribe')
		@endif
	</div>
</div>
</body>
</html>
