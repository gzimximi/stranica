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
						<table class="table">
							<thead>
								<tr>
									<th>User</th>
									<th>His value</th>
									<th>Our value</th>
									<th>Items</th>
									<th>State</th>
									<th>Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($trades as $trade)
									<tr>
										<td>
											<a href="{{ url('admin/user/' . $trade->user->id) }}" target="_blank">
												{{ $trade->user->username }}
											</a>
										</td>
										<td>{{ $trade->his_value }}</td>
										<td>{{ $trade->our_value }}</td>
										<td>{{ $trade->item_count }}</td>
										<td>
											@if($trade->state == "Accepted")
											<b style="color:green;">Accepted</b>
											@else
											{{ $trade->state }}
											@endif
										</td>
										<td>{{ $trade->created_at }}</td>
										<td>
											<a href="{{ url('admin/exchange/' . $trade->id) }}">Details</a>
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