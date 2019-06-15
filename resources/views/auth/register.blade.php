@extends('layouts.public')
@section('content')
<div class="wb-small-banner wb-join-us">
	<div class="caption">Photo: Elin Bandmann</div><!-- /.caption -->
</div>
<section  class="login-register">
	<div class="container">
		@include('partials.auth.actions')
		@include('partials.auth.register-form')
	</div>
</section>
@endsection
@push('scripts')
<script type="text/javascript">
	$('.login-register .wb-form-group').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$('label span', this).hide();
		$('label', this).addClass('active');
		$('input', this).focus();
	});

	$('.login-register .wb-form-group input').on('focus', function(event) {
		event.preventDefault();
		/* Act on the event */
		labelSpan = $(this).closest('.wb-form-group').find('label span');
		label = $(this).closest('.wb-form-group').find('label');
		labelSpan.hide();
		label.addClass('active');
	});

	$('.login-register .wb-form-group input').on('blur', function(event) {
		var inputValue = $(this).val();
		if ( inputValue == '' ) {

			labelSpan = $(this).closest('.wb-form-group').find('label span');
			label = $(this).closest('.wb-form-group').find('label');
			labelSpan.show();
			label.removeClass('active');

		}
	});
</script>
@endpush