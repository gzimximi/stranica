@extends('layout.master')

@section('title', 'Admin')

@section('content')

@include('layout.header', ['active' => 'admin'])

<main class="content">
	<div class="content-heading repeat-heading margin-bottom-no" style="background-image:url(/img/diagmonds.png);">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1 class="heading">Admin panel</h1>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					@include('admin.nav')
					<div class="col-xs-12">
						<h2 class="margin-bottom-no">Trade no #{{ $trade->id }}
							<span class="label">{{ ($trade->his_value >= $trade->our_value) ? 'Trade looks profitable' : 'Looks like we lost some value..' }}</span>
						</h2>
						<p>
							Made by <a href="https://steamcommunity.com/profiles/{{ $trade->user->steamid }}" target="_blank">{{ $trade->user->username }}</a>
							<a href="{{ url('admin/user/' . $trade->user->id) }}"><b>(User details)</b></a>
						</p>
					</div>
					<div class="col-xs-6">
						<h4 class="margin-no">Our items (${{ $trade->our_value }})</h4>
						@if(isset($trade->items_array['bot']))
						<table class="table">
							<thead>
								<tr>
									<th>Our item</th>
									<th>Count</th>
									<th>Inventory ($)</th>
									<th>Market ($)</th>
									<th>Profit ($)</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($trade->items_array['bot'] as $key => $count)
								<tr>
									<td>{{ $key }}</td>
									<td>{{ $count }}</td>
									<td>
										${{ $item->getPrices($key)['bot'] * $count }}
									</td>
									<td>
										${{ $item->getPrices($key)['market'] * $count }}
									</td>
									<td>
										${{ round(($item->getPrices($key)['market_sell'] * $count) - ($item->getPrices($key)['bot'] * $count), 3) }}
									</td>
									<td>
										<a href="https://steamcommunity.com/market/listings/730/{{ $key }}" target="_blank">Market</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@endif
					</div>
					<div class="col-xs-6">
						<h4 class="margin-no">His items (${{ $trade->his_value }})</h4>
						<table class="table">
							<thead>
								<tr>
									<th>His item</th>
									<th>Count</th>
									<th>Inventory ($)</th>
									<th>Market ($)</th>
									<th>Profit ($)</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($trade->items_array['user'] as $key => $count)
								<tr>
									<td>{{ $key }}</td>
									<td>{{ $count }}</td>
									<td>
										${{ $item->getPrices($key)['user'] * $count }}
									</td>
									<td>
										${{ $item->getPrices($key)['market'] * $count }}
									</td>
									<td>
										${{ round(($item->getPrices($key)['market_sell'] * $count) - ($item->getPrices($key)['user'] * $count), 3) }}
									</td>
									<td>
										<a href="https://steamcommunity.com/market/listings/730/{{ $key }}" target="_blank">Market</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection