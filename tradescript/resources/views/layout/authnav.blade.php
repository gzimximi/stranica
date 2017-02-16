<?php
$token = (Auth::user()->tradeurl !== '') ? true : false;
?>
<nav aria-hidden="true" class="menu menu-right" id="doc_menu_profile" tabindex="-1">
	<div class="menu-scroll">
		<div class="menu-top">
			<div class="menu-top-img">
				<img alt="{{ $user->username }}" src="/img/diagmonds.png">
			</div>
			<div class="menu-top-info">
				<a class="menu-top-user" href="javascript:void(0)">
					<span class="avatar pull-left" style="margin-right:15px;">
						<img alt="{{ $user->username }}" src="{{ $user->avatar_url }}">
					</span>
					{{ $user->username }}
				</a>
			</div>
			<div class="menu-top-info-sub">
				<div>
					Steam tradelink <span class="label label-{{ ($token) ? 'green' : 'red' }} pull-right">{{ ($token) ? trans('common.added') : trans('common.missing') }}</span>
				</div>
			</div>
		</div>
		<div class="menu-content">
			<ul class="nav">
				@role('admin')
				<li>
					<a class="waves-attach" href="{{ url('admin') }}">Admin</a>
				</li>
				@endrole
				<li>
					<a class="waves-attach" href="{{ url('settings') }}">{{ trans('common.settings') }}</a>
				</li>
				<li>
					<a class="waves-attach" href="{{ url('auth/logout') }}">{{ trans('common.logout') }}</a>
				</li>
			</ul>
		</div>
	</div>
</nav>