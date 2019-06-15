@extends('layouts.public')

@section('content')
<div class="wb-small-banner wd-how-it-works">
	<div class="caption">Photo: Andreas Holm</div><!-- /.caption -->
</div>
<section class="section-howItWorks" style="padding: 40px 0 0">
	<div class="text-center">

		<div class="">

			<div class="">

				<h1 class="wb-title">How booking Works</h1>

				<div class="wb-action-buttons" style="margin-bottom: 90px">
					<a href="#couple" class="couple btn btn-lg wb-btn-outline-purple active" data-toggle="tab">FOR COUPLES</a>
					<a href="#vendor" class="vendor btn btn-lg wb-btn-outline-purple" data-toggle="tab">FOR BUSINESSES</a>
				</div>

				<div class="tab-content">
					<div class="tab-pane active" id="couple">
						<div class="container">
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<ul class="list-unstyled wb-numlist">
										<li>
											<div class="wb-numlist-lead">
												<span class="num">1</span>
												Tell Us What You Need
											</div>
											<div class="wb-numlist-info">
												<img src="{{ asset('assets/images/how/couples/Step-1.png') }}" class="thumb" />
												<p>
													Whether you're looking for a florist, a venue or something else entirely, simply post a job detailing the service you require, and let our professional community of booked businesses pitch for your work. Using our quick and easy templates, you can specify what you need so that the most suited businesses can send you a quote.
												</p>
											</div>
										</li>

										<li>
											<div class="wb-numlist-lead">
												<span class="num">2</span>
												Let The Quotes Roll In
											</div>
											<div class="wb-numlist-info">
												<img src="{{ asset('assets/images/how/couples/Step-2.png') }}" class="thumb" />
												<p>
													We'll automatically notify you when you receive quotes, so you can review & compare them in your planning dashboard. Our integrated review system will allow you to choose the suppliers &amp; venues that are most suited to your budget, style and preference.
												</p>
											</div>
										</li>

										<li>
											<div class="wb-numlist-lead">
												<span class="num">3</span>
												Confirm & Pay Securely
											</div>
											<div class="wb-numlist-info">
												<img src="{{ asset('assets/images/how/couples/Step-3.png') }}" class="thumb" />
												<p>
													Once you're ready to confirm your bookings, check out safely with our secure payment system. Each booking will be added to your payment tracker so you can easily manage your budget &amp; be reminded when your final payments are due.
												</p>
											</div>
										</li>

										<li>
											<div class="wb-numlist-lead">
												<span class="num">4</span>
												Tell Us What You Think
											</div>
											<div class="wb-numlist-info">
												<img src="{{ asset('assets/images/how/couples/Step-4.png') }}" class="thumb" />
												<p>
													After your big day, you'll have the chance to review any suppliers and venues you booked through booking. We value our couple's experience highly, as this helps us to maintain a network of reliable and trusted booked businesses.
												</p>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>

						@if (!Auth::check())
						<section class="wb-block danger-bg" style="margin-top: 40px">
							<h1 class="text" style="margin-bottom: 30px">Start Booking Your booked</h1>
							<a href="{{ url('/register') }}" class="btn btn-lg btn-danger">Sign Up Now</a>
						</section>
						@endif
					</div>

					<div class="tab-pane" id="vendor">
						<div class="container">
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<ul class="list-unstyled wb-numlist">
										<li>
											<div class="wb-numlist-lead">
												<span class="num">1</span>
												Sign Up
											</div>
											<div class="wb-numlist-info">
												<img src="{{ asset('assets/images/how/vendors/Step-1.png') }}" class="thumb" />
												<p>
													Join booking, create your business profile and start advertising your services for free. Let us know what you specialise in, and which locations you're looking for work in, so we can automatically notify you of jobs that are suited to your business.
												</p>
											</div>
										</li>

										<li>
											<div class="wb-numlist-lead">
												<span class="num">2</span>
												Get Notified Of Work
											</div>
											<div class="wb-numlist-info">
												<img src="{{ asset('assets/images/how/vendors/Step-2.png') }}" class="thumb" />
												<p>
													For jobs that suit your availablity, you can quickly and easily submit a quote to the couple using our templates. You can customise your quotes to include your business logo, terms &amp; conditions, and any other important details. Using our business planner, you can track the status of any jobs you've quoted on, to see if they've been accepted.
												</p>
											</div>
										</li>

										<li>
											<div class="wb-numlist-lead">
												<span class="num">3</span>
												Bring In The Revenue
											</div>
											<div class="wb-numlist-info">
												<img src="{{ asset('assets/images/how/vendors/Step-3.png') }}" class="thumb" />
												<p>
													When a couple accepts your quote, they'll make payment on your invoice into your nominated bank account, using our secure payment gateway. If there is a remaining balance to be paid, we'll send them a reminder according to your payment terms, so that you don't need to be chasing invoices.
												</p>
											</div>
										</li>

										<li>
											<div class="wb-numlist-lead">
												<span class="num">4</span>
												Grow Your Star Rating
											</div>
											<div class="wb-numlist-info">
												<img src="{{ asset('assets/images/how/vendors/Step-4.png') }}" class="thumb" />
												<p>
													We want you to be rewarded for the amazing work you do. Our rating system allows couples who have booked your services to leave honest reviews after their big day so future couples can be assured of your skills. By growing your booking star rating, you can secure more work and grow your business.
												</p>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>

						@guest
						<section class="wb-block danger-bg" style="margin-top: 40px">
							<h1 class="text" style="margin-bottom: 30px">Start Earning With booking</h1>
							<a href="{{ url('/register') }}" class="btn btn-lg btn-danger">Sign Up Now</a>
						</section>
						@endguest

					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection