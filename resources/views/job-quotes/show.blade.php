@extends('layouts.dashboard')

@section('content')
	@if(session()->has('success'))
		@include('modals.success-modal', [
		'header' => 'JOB QUOTE SENT',
		'message' => 'Your job quote has now been sent to the couple.',
		'leftBtnLabel' => 'Find More Work',
		'leftBtnUrl' => url('dashboard/job-posts/live')
		])
	@endif

	<div class="wb-bg-grey">
		<div class="container-fluid">
			<div id="wb-single-quote" class="wb-box">
				<div class="wb-cover-quote" style="position: relative;">
					@can('edit', $jobQuote)
						@if ($jobQuote->locked === 0)
							<div class="div-block">
								<a href="{{ url(sprintf('/job-quotes/%s/edit', $jobQuote->id)) }}" class="btn wb-btn-primary pull-right mini2x pos-abs pos-top-right">Edit</a>
							</div>
						@endif
					@endcan
					@include('partials.alert-messages')
					<div class="profile-img">
						@if ($jobQuote->user->vendorProfile->profile_avatar)
						<img src="{{ $jobQuote->user->vendorProfile->profile_avatar }}" alt="no image">
						@else
						<img src="http://via.placeholder.com/180x130" alt="no image">
						@endif
					</div>
					<a style="display: block; color: #353554;" href="#" class="name" style="color: #000;">{{ $jobQuote->user->vendorProfile->business_name }}</a>
					@couple
					<a href="{{ url(sprintf('/dashboard/messages?recipient_user_id=%s', $jobQuote->user_id)) }}"
						class="btn wb-btn-primary"
						style="margin-top: 20px;">

						MESSAGE SUPPLIER
					</a>
					@endcouple
					@vendor
					<a href="{{ url(sprintf('/dashboard/messages?recipient_user_id=%s', $jobQuote->jobPost->user_id)) }}"
						class="btn wb-btn-primary"
						style="margin-top: 20px;">
						MESSAGE COUPLE
					</a>
					@endvendor
					<div class="location" style="color: #000;">
						{{ $jobQuote->user->vendorProfile->location->name }}
					</div>
				</div>
				<div class="wb-wrapper">
					<div class="row">
						<div class="col-lg-7 col-xl-8">
							<div class="details-block">
								<div class="message-block">
									<div class="sub-header" style="margin: 0 0 20px;">
										{{ $jobQuote->jobPost->userProfile->title }} |
										{{ $jobQuote->jobPost->category->name }} |
										{!! $jobQuote->jobPost->locations->implode('name', ',&nbsp;') !!}
									</div>
								</div>
								<ul class="list-unstyled">
									<li>
										<span class="icon"><i class="fa fa-calendar"></i></span>
										<label>Date Required: </label> {{ $jobQuote->jobPost->event_date }}
									</li>
									<li>
										<span class="icon"><i class="fa fa-star"></i></span>
										<label>Event Type: </label> {{ $jobQuote->jobPost->event->name }}
									</li>
									<li>
										<span class="icon"><i class="fa fa-users"></i></span>
										<label>Approx number of guests: </label> 45
									</li>
									<li>
										<span class="icon"><i class="fa fa-map-marker"></i></span>
										<label>Venue or Address: </label> {{ $jobQuote->jobPost->locations->implode('name', ',') }}
									</li>
									<li>
										<span class="icon"><i class="fa fa-usd"></i></span>
										<label>Max Budget: </label> {{ $jobQuote->jobPost->budget }}
									</li>
									<li>
										<span class="icon"><i class="fa fa-clock-o"></i></span>
										<label>Time Required: </label>
										<span class="value">
											{{ $jobQuote->jobPost->timeRequirement ? $jobQuote->jobPost->timeRequirement->name : ''  }}
										</span>
									</li>
								</ul>
							</div>
							@include('partials.job-quotes.quote-details')
						</div>
						<div class="col-lg-5 col-xl-4">
							@if ($jobQuote->status === 1)
								@couple
									@include('partials.job-quotes.quote-response')
								@endcouple
								@vendor
									<label class="wb-btn-orange-block" style="text-align: center;">
										Awaiting couple’s response
									</label>
								@endvendor
							@elseif ($jobQuote->status === 5)
								@couple
									<a href="{{ url(sprintf('payments/create?invoice_id=%s', $jobQuote->invoice->id)) }}"
										class="wb-btn-orange-block" style="text-align: center;">
										VIEW & PAY INVOICE
									</a>
								@endcouple
								@vendor
									<label class="wb-btn-orange-block" style="text-align: center;">
										Invoice sent, awaiting payment
									</label>
								@endvendor
							@elseif ($jobQuote->status === 3)
								@couple
									@include('partials.job-quotes.quote-response')
								@endcouple
								@vendor
									<label class="wb-btn-orange-block" style="text-align: center;">
										Couple requested changes
									</label>
								@endvendor
							@elseif ($jobQuote->status === 8)
								<label class="wb-btn-orange-block" style="text-align: center;">
									@vendor
										Your quote was declined.
									@endvendor
									@couple
										You declined this quote.
									@endcouple
								</label>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection