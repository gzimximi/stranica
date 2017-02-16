<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="hidden-xs col-sm-3">
				<h4 class="">{{ trans('common.friends') }}</h4>
				<ul class="list-unstyled">
					<li>
						<a href="#">-</a>
					</li>
				</ul>
			</div>
			<div class="hidden-xs col-sm-3">
				<h4 class="">{{ trans('common.social_media') }}</h4>
				<ul class="list-unstyled">
					<li>
						<a href="#" target="_blank">Steam</a>
					</li>
					</li>
				</ul>
			</div>
			<div class="hidden-xs col-sm-6">
				<h4 class="">{{ trans('common.sponsors') }}</h4>
				<p>{!! trans('common.sponsor_description') !!}</p>
			</div>
		</div>
		<hr style="margin:12px 0;">
		<div class="row">
			<div class="col-sm-6">
				<ul class="list-inline">
					<li>
						© Skins.ee 2016
					</li>
				</ul>
			</div>
			<div class="col-sm-6">
				<ul class="list-inline">
					<li>
						<a href="{{ url('tos') }}">{{ trans('common.terms') }}</a>
					</li>
					<li>
						<a href="{{ url('contact') }}">{{ trans('common.contact') }}</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</footer>