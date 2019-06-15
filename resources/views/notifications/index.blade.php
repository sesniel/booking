@extends('layouts.dashboard')

@section('content')
<section id="wb-notification-block">
	<div class="container-fluid">
		@if (count($notifications) < 1)
		<div class="alert wb-alert-info">
			No notifications.
		</div>
		@endif
		<ul class="wb-timeline">
			<div>
				<li>
					<div class="wb-timeline-panel">
						<div class="{{-- table-responsive --}}">
							<table class="table no-margin table-notification">
								<tbody>
									@foreach($notifications as $notification)
											@if ($notification->type === 'App\\Notifications\\JobQuoteReceived')
												@include('partials.notifications.jobquote-received', ['notification' => $notification])
											@endif
											@if ($notification->type === 'App\\Notifications\\JobQuoteResponse')
												@include('partials.notifications.jobquote-response', ['notification' => $notification])
											@endif
											@if ($notification->type === 'App\\Notifications\\InvoiceReceived')
												@include('partials.notifications.invoice-received', ['notification' => $notification])
											@endif
											@if ($notification->type === 'App\\Notifications\\NewJobPosted')
												@include('partials.notifications.new-job-posted', ['notification' => $notification])
											@endif
											@if ($notification->type === 'App\\Notifications\\PaymentReceived')
												@include('partials.notifications.payment-received', ['notification' => $notification])
											@endif
										@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</li>
			</div>
		</ul>
		<div class="wb-pagination-block">
			{{ $notifications->links() }}
		</div>
	</div>
</section>
@endsection