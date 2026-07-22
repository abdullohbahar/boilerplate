@if (session('success'))
	<div class="alert alert--success mb-4" role="alert">
		{{ session('success') }}
	</div>
@endif

@if (session('error'))
	<div class="alert alert--danger mb-4" role="alert">
		{{ session('error') }}
	</div>
@endif

@if (session('warning'))
	<div class="alert alert--warning mb-4" role="alert">
		{{ session('warning') }}
	</div>
@endif

@if ($errors->any())
	<div class="alert alert--danger mb-4" role="alert">
		<ul class="list-disc list-inside">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
