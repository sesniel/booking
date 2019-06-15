@extends('layouts.public')

@section('content')
<section id="wb-settings-block">
	<div class="container">
		<div class="text-center">
			<h1 class="wb-title">Your Settings</h1></div>
			<div class="row">
				<div class="text-center">
					<div style="border-bottom: 1px solid rgb(219, 219, 219); margin-bottom: 40px; text-transform: uppercase;">
						<a href="{{ url('/settings') }}" class="wb-btn-link">PROFILE</a> <i class="wb-btn-separator"></i> 
						<a href="{{ url('/settings/card-account') }}" class="wb-btn-link">Payment Methods</a> <i class="wb-btn-separator"></i> 
						<a href="{{ url('/settings/notification') }}" class="wb-btn-link active">NOTIFICATION SETTINGS</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="notification-container wb-box" style="padding: 30px 32px; margin-bottom: 100px;">
					<div class="description-block">
						<h3 class="title-msg "><span class="icon"><i class="fa fa-check-circle-o"></i></span>You're now using our recommended notification settings</h3>
						<p>Our recommend settings will only send you a notificatin when someone wants your attention. For example, if a new job opportunity is added, a couple sends you a direct message, or accepted your quote.</p>
						<p>if you'd like you can <a href="#" class="link">switch back to default setings.</a></p>
					</div>
					<form method="POST" action="https://staging.booking.com/foo/bar" accept-charset="UTF-8">
						<input name="_token" type="hidden" value="iMmopnNhA4EiZWHeuL4qoCZKCxnRfDHrMZMIKkdc">
						<h4 class="title">Email and Mobile SMS Notification</h4>
						<p>Select how you want to receive these notifications</p>
						<div class="checkbox">
							<input id="email" name="email" type="checkbox" value="false">
							<label for="email">Email</label>
						</div>
						<div class="checkbox">
							<input id="sms" name="sms" type="checkbox" value="false">
							<label for="sms">SMS</label>
						</div>
						<div class="checkbox">
							<input id="both_email_sms" name="both" type="checkbox" value="false">
							<label for="both_email_sms">Both (email &amp; sms)</label>
						</div>
						<div class="well">
							<div class="row">
								<div class="col-md-4">
									<div class="checkbox">
										<input id="toggle_untoggle" name="toggle_untoggle" type="checkbox" value="false">
										<label for="toggle_untoggle">Toggle/Untoggle All</label>
									</div>
									<div class="checkbox">
										<input id="sends_a_message" name="sends_a_message" type="checkbox" value="false">
										<label for="sends_a_message">Couple sends me a message</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="checkbox">
										<input id="sends_a_message_2" name="sends_a_message_2" type="checkbox" value="false">
										<label for="sends_a_message_2">Couple sends me a message</label>
									</div>
									<div class="checkbox">
										<input id="negotiates_my_quote" name="negotiates_my_quote" type="checkbox" value="false">
										<label for="negotiates_my_quote">Couple negotiates my quote</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="checkbox">
										<input id="negotiates_my_quote_2" name="negotiates_my_quote_2" type="checkbox" value="false">
										<label for="negotiates_my_quote_2">Couple negotiates my quote</label>
									</div>
									<div class="checkbox">
										<input id="declines_my_quote" name="declines_my_quote" type="checkbox" value="false">
										<label for="declines_my_quote">Couple declines my quote</label>
									</div>
								</div>
							</div>
						</div>
						<div class="notification-block">
							<h4 class="title">Notifications Address</h4>
							<div class="row">
								<div class="col-md-4"><span>Email</span>
									<div class="checkbox">
										<input id="use_profile_email" name="use_profile_email" type="checkbox" value="false">
										<label for="use_profile_email">Use my profile email</label>
									</div>
									<div class="checkbox">
										<input id="use_custom_email" name="use_custom_email" type="checkbox" value="false">
										<label for="use_custom_email">Use custom email</label>
									</div>
								</div>
								<div class="col-md-4"><span>SMS</span>
									<div class="checkbox">
										<input id="use_profile_number" name="use_profile_number" type="checkbox" value="false">
										<label for="use_profile_number">Use my profile phone number</label>
									</div>
									<div class="checkbox">
										<input id="use_custom_number" name="use_custom_number" type="checkbox" value="false">
										<label for="use_custom_number">Use custom phone number</label>
									</div>
								</div>
							</div>
						</div>
						<div class="calendar-notif-block">
							<h4 class="title">Calendar Notifications</h4>
							<div class="row">
								<div class="col-md-4">
									<p>Send me payment notifications for:</p>
									<div class="checkbox">
										<input id="2_days_before" name="2_days_before" type="checkbox" value="false">
										<label for="2_days_before">2 days before job due date</label>
									</div>
									<div class="checkbox">
										<input id="5_days_before" name="5_days_before" type="checkbox" value="false">
										<label for="5_days_before">5 days before job due date</label>
									</div>
								</div>
								<div class="col-md-4"><span>&nbsp;</span>
									<div class="checkbox">
										<input id="bounce_payment" name="bounce_payment" type="checkbox" value="false">
										<label for="bounce_payment">Bounce payment</label>
									</div>
								</div>
							</div>
						</div>
						<div class="action-block">
							<p>Privacy Policy texts Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
							<input type="submit" value="SAVE CHANGES" class="btn wb-btn-orange">
							<button type="button" class="btn wb-btn-orange">DEFAULT SETTINGS</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
@include('partials.get-started')
@endsection