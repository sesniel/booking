@extends('layouts.public')
@section('top-content')
@include('partials.edit-alert')
@endsection
@section('content')
<div class="wb-bg-grey">
	<div class="container">
		@include('partials.alert-messages')
		<div id="wb-dashboard">
			<form id="edit-user-form"
			action="{{ url(sprintf('/%ss/%s', Auth::user()->account, $profile->id)) }}"
			method="POST"
			enctype="multipart/form-data">
			{{ csrf_field() }}
			<input name="_method" type="hidden" value="PATCH">
			@include('partials.profiles.vendor-header')
			@include('partials.profiles.expertise')
			<div class="wb-main-wrapper">
				@include('partials.profiles.description')
				@include('partials.profiles.gallery')
				<!-- @include('partials.profiles.instagram-gallery') -->
			</div>
		</form>
		@include('partials.profiles.gallery-upload-modal')
	</div>
</div>
</div>
@endsection
