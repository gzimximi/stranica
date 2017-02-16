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
					@include('admin.index.ga')
					@include('admin.index.users')
					@include('admin.index.exchanges')
					@include('admin.index.links')
				</div>
			</div>
		</div>
	</div>
</main>
@endsection