<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Title -->
		<title>Skins.ee - @yield('title')</title>
		
		<!-- Site description -->
		<meta name="description" content="{{ trans('seo.description') }}">
		<!-- Facebook -->
		<meta property="og:image" content="{{ url('/') . '/' . trans('seo.og_image') }}" />
		<meta property="og:image:width" content="640" /> 
		<meta property="og:image:height" content="442" />
		<meta property="og:type" content="website" />
		<meta property="og:description" content="{{ trans('seo.description') }}" />
		<!-- Twitter -->
		<meta name="twitter:title" content="@yield('title')">
		<meta name="twitter:description" content="{{ trans('seo.description') }}">
		<meta name="twitter:image" content="{{ url('/') . '/' . trans('seo.og_image') }}">
		<!-- css -->
		<link rel="stylesheet" href="/css/base.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/node-waves/0.7.5/waves.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/css/tooltipster.min.css">
		<link rel="stylesheet" href="/css/project.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<!-- Heil Favicon -->
		@include('layout.favicon')
	</head>
	<body class="page-brand">
		@if(Auth::check())
			@include('layout.authnav', ['user' => Auth::user()])
		@endif

		@yield('content')

		@include('layout.footer')
		<!-- js -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script src="/js/base.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tooltipster/3.3.0/js/jquery.tooltipster.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/node-waves/0.7.5/waves.min.js"></script>
		<script src="/js/project.js"></script>
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-67791140-1', 'auto');
		ga('send', 'pageview');
		</script>
	</body>
</html>