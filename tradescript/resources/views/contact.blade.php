@extends('layout.master')

@section('title', trans('seo.contact'))

@section('content')

@include('layout.header', ['active' => 'contact'])

<main class="content">
	<div class="content-heading repeat-heading" style="background-image:url(/img/diagmonds.png);">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1">
					<h1 class="heading">{{ trans('common.contact') }}</h1>
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
								<h4 class="margin-top-no">{{ trans('common.contact_extra') }}</h4>
								<p>{!! trans('common.contact_notice') !!}</p>
								<ul>
									<li>PEPZ - /id/wishuponablackstar
										<ul>
											<li><a href="mailto:kristjan@skins.ee">kristjan@skins.ee</a></li>
										</ul>
									</li>
									<li>Crackdorium - /id/crackdorium
										<ul>
											<li><a href="mailto:silver@skins.ee">silver@skins.ee</a></li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>
					
					
				</section>
			</div>
		</div>
	</div>
</main>
@endsection