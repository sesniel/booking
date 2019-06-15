<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="pusher-key" content="{{ env('PUSHER_APP_KEY') }}">
	<meta name="pusher-cluster" content="{{ env('PUSHER_APP_CLUSTER') }}">

	{{-- Default website image for social sharing --}}
	<meta property="og:image" content="{{ asset('/apple-touch-icon-200x200.png') }}" />
	<meta property="og:image:type" content="image/png" />
	<meta property="og:image:width" content="200" />
	<meta property="og:image:height" content="200" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="{{ url('/') }}" />

	{{-- <meta property="og:image" content="{{ asset('/apple-touch-icon-180x180.png') }}" /> --}}
	<title>{{ config('app.name', 'booking') }}</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
	<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css" rel="stylesheet" >
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet" >

	{{-- fav icons --}}
	<link rel="shortcut icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon" />
	<link rel="apple-touch-icon" href="{{ asset('/apple-touch-icon.png') }}" />
	<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/apple-touch-icon-57x57.png') }}" />
	<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/apple-touch-icon-72x72.png') }}" />
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/apple-touch-icon-76x76.png') }}" />
	<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/apple-touch-icon-114x114.png') }}" />
	<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/apple-touch-icon-120x120.png') }}" />
	<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/apple-touch-icon-144x144.png') }}" />
	<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/apple-touch-icon-152x152.png') }}" />
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-touch-icon-180x180.png') }}" />
	<link rel="image_src" sizes="180x180" href="{{ asset('/apple-touch-icon-200x200.png') }}" />
	{{-- end fav icons --}}

	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/vendor/css/tagsinput/jquery.tagsinput.min.css') }}" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue"></script>
	<link href="{{ asset('assets/css/dropzone.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/css/nprogress.css') }}" rel="stylesheet" />
	@stack('css')
</head>
<body>
	<div id="app">
		<div class="body">
			@yield('top-content')
			@include('partials.header')
			@yield('content')
			@include('partials.about')
			@include('partials.bottom-social-icons')
			@include('partials.footer')
		</div>
	</div>
	<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="{{ asset('assets/vendor/bootstrap-wysiwyg/external/jquery.hotkeys.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/vendor/bootstrap-wysiwyg/bootstrap-wysiwyg.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="{{ asset('assets/js/nprogress.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/vendor/js/clicktoggle.js') }}"></script>
	<script src="{{ asset('/js/app.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/dropzone.min.js') }}"></script>
	@stack('scripts')
	@stack('styles')
</body>
</html>
