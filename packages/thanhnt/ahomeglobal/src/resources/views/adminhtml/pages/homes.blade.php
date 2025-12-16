<div class="">
	the content of homes list adminhtml
	@foreach ($homes as $home)
		<p>
			{{ $home->name }}
		</p>
	@endforeach
</div>