@extends('layouts.dashboard')

@section('content')
	<section class="wb-bg-grey section-postJob" style="padding: 40px 0px">
		@if(session()->has('job-creation-success'))
			@include('partials.success-modal')
		@endif
		<div class="container-fluid">
			<div class="wb-tab-job">
				<form action="{{ url(sprintf('/job-posts/%s', $jobPost->id)) }}"
					method="POST"
					enctype="multipart/form-data">
					{{ csrf_field() }}
					<input name="_method" type="hidden" value="PATCH">
					@include('partials.alert-messages')
					@include('partials.job-posts.steps-label')
					<div class="tab-content">
						@include('partials.job-posts.step-1-form')
						@include('partials.job-posts.step-2-form')
						@include('partials.job-posts.step-3-form')
						@include('partials.job-posts.step-4-form', array('btnLbl' => 'Update Job Post'))
					</div>
				</form>
			</div>
		</div>
	</section>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('assets/vendor/bootstrap-wysiwyg/external/jquery.hotkeys.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/bootstrap-wysiwyg/bootstrap-wysiwyg.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/js/tagsinput/jquery.tagsinput.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
	$('.template').hide();

	var category = $('#job_category_id').children('option:selected').data('template');

	$('#job_category_id').change(function(){
		$('.template').hide();
		category = $(this).children('option:selected').data('template');
		getCategorySpecifics();
		if (category == 1) {
			$('.template-1-title').show();
			$('#location-input').show();
			$('#property-type-input').show();
			$('#number-of-guests-input').show();
			$('#other-requirements-input').show();
			$('#time-required-input').show();
			$('#no-selected-job-category').hide();
		} else if (category == 2) {
			$('.template-2-title').show();
			$('#location-input').show();
			$('#required-address-input').show();
			$('#number-of-guests-input').show();
			$('#time-required-input').show();
			$('#no-selected-job-category').hide();
		} else if (category == 3) {
			$('.template-2-title').show();
			$('#number-of-guests-input').show();
			$('#completion-date-input').show();
			$('#shipping-address-input').show();
			$('#no-selected-job-category').hide();
		} else {
			$('.no-selected-job-category').hide();
		}
	});

	if (category) {
		$('#job_category_id').trigger('change');
	} else {
		$('.no-selected-job-category').show();
	}

	function moveToStep(step) {
		$('#'+step).trigger('click');
	}

	function getCategorySpecifics()
	{
		NProgress.start();
		$.ajax( {
			type: "GET",
			url: '/app-settings?metaType=job_category_specifics&metaKey='+ $('#job_category_id').val(),
			success: function( response ) {
				if (response[0]) {
					$('#job-specifics').html(response[0].meta_value);
				}
				NProgress.done();
			}
		});
	}

    $('#job_desc_display2').wysiwyg();
	$('#submit-job-post').on('click', function(){
		$('#job-specifics').val($('#job-specifics-html').html());
	})

	var imagesPreview = function(input, placeToInsertImagePreview) {

		if (input.files) {
			var filesAmount = input.files.length;
			for (i = 0; i < filesAmount; i++) {
				var reader = new FileReader();
				reader.onload = function(event) {
					$(placeToInsertImagePreview).append(
						'<div class="item">' +
						'<img src="'+event.target.result+'" class="wb-icon" height="86" width="143" />' +
						'</div>'
					);
				}
				reader.readAsDataURL(input.files[i]);
			}
		}
	};

	function validateFileSize(input) {
		var max = 10000000;
		var valid = true;
		var filesAmount = input.files.length;

		for (i = 0; i < filesAmount; i++) {
			if (input.files[i].size > max) {
				valid = false;
				break;
			}
		}

		return valid;
	}

	function validateFileType(input) {
		var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
		var valid = true;
		var filesAmount = input.files.length;

		for (i = 0; i < filesAmount; i++) {
			if (!validTypes.includes(input.files[i].type)) {
				valid = false;
				break;
			}
		}

		return valid;
	}

	$('#add-photos').on('change', function() {
		if (! validateFileType(this)) {
			alert('The Image must be a file of type: jpeg, png.');
			$(this).val('');
			return false;
		}

		if (! validateFileSize(this)) {
			alert('Image size may not be greater than 10MB.');
			$(this).val('');
			return false;
		}

		imagesPreview(this, 'div.gallery');
	});
	</script>
@endpush
