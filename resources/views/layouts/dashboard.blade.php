<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{{ ucfirst(Auth::user()->account) }} Dashboard</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="pusher-key" content="{{ env('PUSHER_APP_KEY') }}">
	<meta name="pusher-cluster" content="{{ env('PUSHER_APP_CLUSTER') }}">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/skins/_all-skins.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet" >
	<!-- Full Calendar -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.1/fullcalendar.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.1/fullcalendar.print.min.css" media="print">

	{{-- Default website image for social sharing --}}
	<meta property="og:image" content="{{ asset('/apple-touch-icon-200x200.png') }}">
	<meta property="og:image:type" content="image/png">
	<meta property="og:image:width" content="200">
	<meta property="og:image:height" content="200">
	<meta property="og:type" content="website" />

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
	{{-- end fav icons --}}

	@yield('css')
	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/css/nprogress.css') }}" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue"></script>
</head>
<body class="wb-frontend-dashboard sidebar-mini sidebar-collapse">
	<div id="app">
		<div class="wrapper">
			<!-- Main Header -->
			@include('partials.dashboard.header')
			<!-- Left side column. contains the logo` and sidebar -->
			@include(sprintf('partials.dashboard.sidebar-menu.%s', Auth::user()->account))
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				@yield('content')
			</div>
			<!-- Main Footer -->
			@include('partials.dashboard.footer')
		</div>
	</div>
	@include('modals.dashboard-search-modal')
	<!-- jQuery 3.1.1 -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="{{ asset('assets/vendor/bootstrap-wysiwyg/external/jquery.hotkeys.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/vendor/bootstrap-wysiwyg/bootstrap-wysiwyg.js') }}"></script>
	<!-- AdminLTE App -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/js/app.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.1/fullcalendar.min.js"></script>
	<script type="text/javascript" src="{{ asset('assets/js/nprogress.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/stupidtable.min.js') }}"></script>
	<script src="{{ asset('/js/app.js') }}"></script>
	@yield('scripts')
	@stack('scripts')
</body>
</html>
