<div class="col-xs-3">
	<div class="card">
	    <div class="card-main">
	    	<div class="card-header">
	            <div class="card-inner">
	            	<i class="icon">show_chart</i>
	            	Google Analytics
	            </div>
	        </div>
	    	<table class="table margin-no">
				<tbody>
					<tr>
						<td>Users online</td>
						<td>{{-- LaravelAnalytics::getActiveUsers() --}}</td>
					</tr>
					<tr>
						<td>Daily users</td>
						<td>{{-- $dailyUsers --}}</td>
					</tr>
					<tr>
						<td>Monthly users</td>
						<td>{{-- $monthlyUsers --}}</td>
					</tr>
					<tr>
						<td>Daily pageviews</td>
						<td>{{-- $dailyPageViews --}}</td>
					</tr>
				</tbody>
			</table>
	    </div>
	</div>
</div>