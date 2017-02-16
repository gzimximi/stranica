<div class="col-xs-3">
	<div class="card">
	    <div class="card-main">
	    	<div class="card-header">
	            <div class="card-inner">
	            	<i class="icon">face</i>
	            	User Statistics
	            </div>
	        </div>
	    	<table class="table margin-no">
				<tbody>
					<tr>
						<td>Total users</td>
						<td>{{ $usersCount }}</td>
					</tr>
					<tr>
						<td>New users today</td>
						<td>{{ $usersCountToday }}</td>
					</tr>
					<tr>
						<td>Users have exchanged</td>
						<td>{{ $usersExchanged }}</td>
					</tr>
				</tbody>
			</table>
	    </div>
	</div>
</div>