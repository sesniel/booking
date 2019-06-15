@extends('layouts.dashboard')

@section('content')
	@if(session()->has('success'))
		@include('modals.success-modal', [
		'header' => 'JOB POSTED',
		'message' => session('success'),
		'leftBtnLabel' => 'POST ANOTHER JOB',
		'leftBtnUrl' => url('job-posts/create')
		])
	@endif
	<section class="wb-bg-grey" style="padding: 48px 0px">
		<div class="container-fluid">
			<div class="wb-box-job-page">
				@can('edit', $jobPost)
					<a href="{{ url(sprintf('job-posts/%s/edit', $jobPost->id)) }}" class="edit btn wb-btn-primary">Edit</a>
				@endcan
				<div class="left">
					<a href="#">
						<img
							@if ($jobPost->userProfile && $jobPost->userProfile->profile_avatar)
							   src=" {{ $jobPost->userProfile->profile_avatar }}"
							@else
							   src="http://via.placeholder.com/270x270"
							@endif
					   class="profile-img img-square" style="max-width: 100%">
				   </a>
					@if( Auth::user()->account !== 'couple' && !in_array($jobPost->id, $quotedJobs))
						<a href="{{ url(sprintf('job-quotes/create?job_post_id=%s', $jobPost->id)) }}"
							class="btn wb-btn-md wb-btn-orange">
							QUOTE ON THIS JOB
						</a>
					@endif
					<a class="btn wb-btn-xs wb-btn-outline-black">SHARE THIS JOB</a>
					@auth
						<div class="p-t-xs">
							<saved-job job-id="{{ $jobPost->id }}" saved="{{ $isSaved }}"></saved-job>
						</div>
					@endauth
				</div>
				<div class="right">
					<div class="header">
						<h3>
							{{ $jobPost->userProfile->title }} | {{ $jobPost->category->name }} | {!! $jobPost->locations->implode('name', ',&nbsp;') !!}</h3>
						</div>
						<div class="sub-header">
							<ul class="list-inline">
								@if ($jobPost->event_date)
								<li>
									<small><b>Date Required:</b></small> <br />
									<span class="icon"><i class="fa fa-calendar"></i></span>
									{{ $jobPost->event_date }}
								</li>
								@endif
								@if ($jobPost->budget)
								<li>
									<small><b>Max Budget:</b></small> <br />
									<span class="icon"><i class="fa fa-usd"></i></span>
									{{ $jobPost->budget }}
								</li>
								@endif
								<li>
									<small><b>Event Type:</b></small> <br />
									<span class="icon"><i class="fa fa-star"></i></span>
									Engagement Party, booked, Bucks
								</li>
							</ul>
						</div>
						<div class="specification">
							<h1>The Specifics:</h1>
							{!! $jobPost->specifics !!}
						</div>
						@if (($jobPost->category->template === 1
							|| $jobPost->category->template === 2
							|| $jobPost->category->template === 3)
							&& $jobPost->number_of_guests)
							<div class="specification">
								<h1>Approximate Number of Guests:</h1>
								{{ $jobPost->number_of_guests }}
							</div>
						@endif
						@if ($jobPost->category->template === 1 && !$jobPost->propertyTypes->isEmpty())
							<div class="specification">
								<h1>Property Types:</h1>
								<ul>
									@foreach ($jobPost->propertyTypes as $item)
									<li>{{ $item->name }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						@if ($jobPost->category->template === 1 && !$jobPost->otherJobRequirements->isEmpty())
							<div class="specification">
								<h1>Other Requirements:</h1>
								<ul>
									@foreach ($jobPost->otherJobRequirements as $item)
									<li>{{ $item->name }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						@if (($jobPost->category->template === 1
							|| $jobPost->category->template === 2)
							&& $jobPost->timeRequirement)
							<div class="specification">
								<h1>Time Required:</h1>
								{{ $jobPost->timeRequirement->name }}
							</div>
						@endif
						@if ($jobPost->category->template === 2 && $jobPost->required_address)
							<div class="specification">
								<h1>Venue or Address where Supplier is required:</h1>
								{{ $jobPost->required_address }}
							</div>
						@endif
						@if ($jobPost->category->template === 3 && $jobPost->completion_date)
							<div class="specification">
								<h1>Completion Date:</h1>
								{{ $jobPost->completion_date }}
							</div>
						@endif
						@if ($jobPost->category->template === 3 && $jobPost->shipping_address)
							<div class="specification">
								<h1>Shipping Address:</h1>
								<p>Street: {{ $jobPost->shipping_address['street'] }}</p>
								<p>Suburb: {{ $jobPost->shipping_address['suburb'] }}</p>
								<p>State: {{ $jobPost->shipping_address['state'] }}</p>
								<p>Post Code: {{ $jobPost->shipping_address['post_code'] }}</p>
							</div>
						@endif
						@if ($jobPost->specifics)
							<div class="specification">
								<h1>Job Specification:</h1>
								{!! $jobPost->specifics !!}
							</div>
						@endif
						<div class="description"></div>
						@if (!$gallery->isEmpty())
						<div class="wb-gallery">
							<div class="carousel slide media-carousel" id="media">
								<div class="carousel-inner">
									<div class="owl-carousel owl-carousel-gallery owl-theme">
										@foreach($gallery as $item)
										<div class="item" style="width: auto;">
											<img src="{{ $item->getFileUrl() }}" class="img-responsive" style="height: 180px;">
										</div>
										@endforeach
									</div>
								</div>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
	</section>
@endsection

@push('scripts')
<script>
	$(document).ready(function(){
		$('.owl-carousel-gallery').owlCarousel({
			nav: true,
			loop:true,
			margin:30,
			responsiveClass:true,
			dots: false,
			navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
			autoplay: true,
			stagePadding: 0,
			responsive:{
				0:{
					items:1,
					nav:false
				},
				768:{
					items:3,
				},
				1000:{
					items:3,
					nav:true,
				}
			}
		});
	});
</script>
@endpush