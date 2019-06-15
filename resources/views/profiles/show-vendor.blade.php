@extends('layouts.public')
@section('content')
<div class="wb-bg-grey">
	<div class="container">
		<div id="wb-dashboard">
			@include('partials.profiles.vendor-header')
			@include('partials.profiles.expertise')
			<div class="wb-main-wrapper">
				@include('partials.profiles.description')
				@include('partials.profiles.gallery')
				<!-- @include('partials.profiles.instagram-gallery') -->
			</div>
		</div>
	</div>
</div>
@endsection