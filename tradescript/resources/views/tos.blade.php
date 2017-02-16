@extends('layout.master')

@section('title', trans('seo.terms'))

@section('content')

@include('layout.header', ['active' => 'terms'])

<main class="content">
	<div class="content-heading repeat-heading" style="background-image:url(/img/diagmonds.png);">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1">
					<h1 class="heading">{{ trans('common.terms') }}</h1>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">
				<section class="content-inner margin-top-no">
					<div class="card">
						<div class="card-main">
							<div class="card-inner">
								<p>{{ trans('common.terms_sub_info') }}</p>
								<h4 class="margin-top-no">{{ trans('common.terms_exchanging') }}</h4>
								<p>{{ trans('common.terms_p_one') }}</p>
								<p>{{ trans('common.terms_p_two') }}</p>
							</div>
						</div>
					</div>
					
					
				</section>
			</div>
		</div>
	</div>
</main>
@endsection