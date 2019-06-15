@extends('layouts.public')
@section('content')
	<div class="wb-bg-grey">
		<div class="container">
			<div id="wb-dashboard">
				@include('partials.profiles.couple-header')
				@include('partials.profiles.active-jobs')
				<div class="wb-main-wrapper">
					@include('partials.profiles.description')
					@include('partials.profiles.gallery')
					<!-- @include('partials.profiles.pinterest-gallery') -->
					{{-- @include('partials.couple-favourite-suppliers') --}}
				</div>
			</div>
		</div>
	</div>
@endsection
