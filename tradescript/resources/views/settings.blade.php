@extends('layout.master')

@section('title', trans('seo.settings'))

@section('content')

@include('layout.header', ['active' => 'settings'])

<main class="content">
	<div class="content-heading repeat-heading" style="background-image:url(/img/diagmonds.png);">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1">
					<h1 class="heading">{{ trans('common.settings') }}</h1>
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
								{{ trans('common.settings_extra') }}. <a href="http://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url" target="_blank"><b>{{ trans('common.trade_link') }}</b></a>.
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-main">
							<div class="card-inner">
								@if (count($errors) > 0)
									<div class="card card-red card-alert">
										<div class="card-main">
											<div class="card-inner">
												<div><b>{{ trans('common.something_wrong') }}</b></div>
												<ul class="list">
													@foreach ($errors->all() as $error)
										                <li>{{ $error }}</li>
										            @endforeach
												</ul>
											</div>
										</div>
									</div>
								@endif
								<div class="form-group form-group-label">
									<label class="floating-label" for="referral">Referral link</label>
									<input class="form-control" id="referral" type="text" value="{{ url('trader') }}/{{ Auth::user()->id }}">
								</div>
								<form method="post">
									<div class="form-group form-group-label">
									    <label class="floating-label" for="tradeurl">{{ trans('common.trade_link') }}</label>
									    <input class="form-control" name="trade_url" id="tradeurl" type="text" value="{{ Auth::user()->tradeurl }}">
									</div>
									<div class="checkbox checkbox-adv" style="margin-bottom:16px;">
									    <label for="tos">
									        <input class="access-hide" id="tos" name="terms_of_service" type="checkbox" checked> {{ trans('common.accept_terms') }}
									        <span class="checkbox-circle"></span><span class="checkbox-circle-check"></span><span class="checkbox-circle-icon icon">done</span>
									    </label>
									</div>
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<button type="submit" class="btn btn-brand">{{ trans('common.save_changes') }}</button>
								</form>
							</div>
						</div>
					</div>
					
					
				</section>
			</div>
		</div>
	</div>
</main>
@endsection