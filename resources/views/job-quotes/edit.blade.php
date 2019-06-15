@extends('layouts.dashboard')

@section('content')
    @if(session()->has('success'))
        @include('modals.success-modal', [
        'header' => 'JOB QUOTE UPDATED',
        'message' => 'Your job quote was updated successfully.',
        ])
    @endif
<div class="wb-bg-grey wb-cc-quotation" id="wb-cc-quotation">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-7">
				<form action="{{ url(sprintf('/job-quotes/%s', $jobQuote->id)) }}"
					method="POST"
					enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					<div class="wb-box wb-tab-send-quote" style="margin-top: 43px; padding: 35px;">
                        @include('partials.alert-messages')
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab_step_1" onClick="resetStepTo(1)" id="cc-quote-tab-1" data-toggle="tab">Step 1</a>
							</li>
							<li>
								<a href="#tab_step_2" onClick="resetStepTo(2)" id="cc-quote-tab-2" data-toggle="tab">Step 2</a>
							</li>
							<li>
								<a href="#tab_step_3" onClick="resetStepTo(3)" id="cc-quote-tab-3" data-toggle="tab">Step 3</a>
							</li>
						</ul>
						<div class="tab-content">
							@include('partials.job-quotes.step-1')
							@include('partials.job-quotes.step-2')
							@include('partials.job-quotes.step-3')
						</div>
						<div class="wb-send-quote" style="text-align: right;">
							<div>
								<div class="action-buttons" style="inline">
									<button id="draft-quote"
									type="submit"
									name="status"
									value="draft"
									style="font-weight: 300;"
									class="btn wb-btn-outline-default">
									SAVE AS DRAFT
								</button>
								<button id="submit-quote"
								type="submit"
								name="status"
								value="pending response"
								style="font-weight: 300;"
								class="hidden btn wb-btn-orange">
								UPDATE QUOTE
							</button>

							<button id="next-step"
							value="1"
							style="font-weight: 300;"
							class=" btn wb-btn-orange">
							NEXT STEP
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@include('partials.job-quotes.job-details')
</div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
	$('#next-step').on('click', function(e){
		e.preventDefault();
		var step = parseInt($(this).val());
		step += 1;
		$(this).val(step);
		$('#cc-quote-tab-'+step).trigger('click');
		toggleBtn(step);
	});

    $('#submit-quote').on('click', function(){
        $('#message').val($('#message-html').html());
    })

	function resetStepTo(step) {
		$('#next-step').val(step);
		toggleBtn(step);
	}

	function toggleBtn(step) {
		$('#next-step').val(step);
		if (step >= 3) {
			$('#next-step').hide();
			$('#submit-quote').removeClass('hidden');
		} else {
			$('#next-step').show();
			$('#submit-quote').addClass('hidden');
		}
	}
</script>
@endpush

