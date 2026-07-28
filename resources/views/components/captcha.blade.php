@if (config('captcha.enabled'))
	@php
		$provider = config('captcha.provider');
		$siteKey = $provider === 'cloudflare'
			? config('captcha.cloudflare.site_key')
			: config('captcha.google.site_key');
	@endphp

	@if ($provider === 'cloudflare')
		@pushOnce('head')
			<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
		@endPushOnce
		<div class="cf-turnstile" data-sitekey="{{ $siteKey }}"></div>
	@else
		@pushOnce('head')
			<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		@endPushOnce
		<div class="g-recaptcha" data-sitekey="{{ $siteKey }}"></div>
	@endif

	@error('captcha')
		<p class="field__error">{{ $message }}</p>
	@enderror
@endif
