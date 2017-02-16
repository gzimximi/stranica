@extends('layout.master')

@section('title', trans('seo.welcome'))

@section('content')

@include('layout.header', ['active' => 'index'])

<main class="content">
	<div class="content-heading" style="background-image:url(../img/bg_index.jpg);">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
					<h1 class="heading">Tere tulemast <u>Skins.ee</u> lehele</h1>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			
		</div>
	</div>
</main>
@endsection