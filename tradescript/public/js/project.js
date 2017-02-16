$(function() {

	$doc  = $(document);
	$('.tooltip').tooltipster({
		delay: 0
	});


	Data = {
		'page': $doc.find('[data-page]').data('page'),
		'inventory': {
			'tradable': false,
			'itemtpl': '#item-tpl',
			'bot': {
				'selector': '[data-inventory="bot"]',
				'url': '/steam/bot-inventory'
			},
			'user': {
				'selector': '[data-inventory="user"]',
				'url': '/steam/user-inventory'
			}
		},
		'offer': {
			'send': '/steam/send-offer'
		}
	};

	function loadInventory(who, callback) {
		$.get(Data.inventory[who].url, function(data) {
			if(typeof callback === "function") callback(null, data);
		}).fail(function(err) {
			if(typeof callback === "function") callback(err);
		});
	}
	function setInventory(who, err, data, callback) {
		data.user = (who == 'user') ? true : false;

		var source   = $(Data.inventory.itemtpl).html();
		var template = Handlebars.compile(source);

		var html = 'Error retrieving the inventory';
		if( ! err) {
			var html = template(data);
		}
		
		
		$(Data.inventory[who].selector).html(html);

		$(Data.inventory[who].selector).find('div').sort(function(a, b) {
			var aS = $(a).find('.price').html();
			var bS = $(b).find('.price').html();

			return +bS - +aS;
		}).appendTo($(Data.inventory[who].selector));

		$('.tooltip').tooltipster({
			delay: 0
		});
		Waves.attach('.app-item');

		if(typeof callback === "function") callback();
	}
	function setInventoryBindings() {
		$doc.find('[data-inventory] .app-item').unbind('click').bind('click', function(e) {
			if($(this).hasClass('disabled')) return false;

			var who  	 = $(this).parents('[data-inventory]').eq(0).data('inventory');
			var name     = $(this).data('title');
			var selected = $doc.find('[data-selected="' + who + '"]');

			if(selected.find('[data-title="' + name + '"]').size() > 0) {
				var count = selected.find('[data-title="' + name + '"] .count');
				count.html(parseInt(count.html()) + 1);
			} else {
				$clone = $(this).parent().clone();
				$clone.find('.count').html('1');
				$clone.find('.app-item').removeClass('waves-effect');
				//console.log($clone.find('.app-item').attr('class'));
				$doc.find('[data-selected="' + who + '"]').prepend($clone);
			}

			var count = $(this).find('.count');
			count.html(parseInt(count.html()) - 1);

			if(count.html() == 0) {
				$(this).addClass('disabled');
			}

			setSelectedValue(who, +$(this).find('.price').html());
			setSelectedBindings();
			e.preventDefault();
		});
	}
	function setSelectedBindings() {
		$doc.find('[data-selected] .app-item').unbind('click').bind('click', function(e) {
			var who = $(this).parents('[data-selected]').eq(0).data('selected');
			var name = $(this).data('title');
			var inventory = $doc.find('[data-inventory="' + who + '"]');

			var count = $(this).find('.count');
			var setCount = parseInt(count.html()) - 1;
			count.html(setCount);

			var invItem = inventory.find('.app-item[data-title="' + name + '"]');
			var invCount = invItem.find('.count');
			if(invItem.hasClass('disabled')) invItem.removeClass('disabled');
			invCount.html(parseInt(invCount.html()) + 1);

			setSelectedValue(who, -Math.abs(+$(this).find('.price').html()));

			if(setCount == 0) {
				$(this).parent().remove();
			}

			e.preventDefault();
		});
	}
	function setSelectedValue(who, value) {
		var valueDom = $doc.find('.' + who + '-value');
		var newValue = (+valueDom.html() + value).toFixed(3);
		valueDom.html(newValue);

		var userValue = (+$doc.find('.user-value').html()).toFixed(3);
		var botValue  = (+$doc.find('.bot-value').html()).toFixed(3);

		$doc.find('[data-inventory="bot"] .app-item').each(function() {
			var item = $(this).parent();
			var price = +item.find('.price').html();
			var count = +item.find('.count').html();

			if((userValue - botValue) >= price && count != 0) $(this).removeClass('disabled');
			else $(this).addClass('disabled');
		});

		//console.log(userValue, botValue);
		if(userValue >= botValue && userValue != 0) {
			Data.inventory.tradable = true;
		} else {
			Data.inventory.tradable = false;
		}
		$doc.trigger('tradable');
	}
	function reloadInventories() {
		$doc.find('.botloader, .userloader').fadeIn();
		$(Data.inventory['bot'].selector).html('');
		$(Data.inventory['user'].selector).html('');
		$doc.find('[data-selected]').html('');
		$doc.find('.user-value, .bot-value').html('0.000');
		Data.inventory.tradable = false;
		$doc.trigger('tradable');
		loadInventory('bot', function(err, data) {
			if(err) {
				var error = '<div class="card card-alert card-red" style="margin-left:15px;"><div class="card-main"><div class="card-inner">Something went wrong. Try reloading the inventories.</div></div></div><div class="card card-alert card-red" style="margin-left:15px;"><div class="card-main"><div class="card-inner">' + err + '</div></div></div>';
				$(Data.inventory.bot.selector).html(error);
				return;
			} else if(data.success == false) {
				var error = '<div class="card card-alert card-red" style="margin-left:15px;"><div class="card-main"><div class="card-inner">' + data.message + '</div></div></div>';
				$(Data.inventory.bot.selector).html(error);
				return;
			}
			setInventory('bot', null, data, function() {
				setInventoryBindings();
				$doc.find('.botloader').fadeOut();
			});
		});
		loadInventory('user', function(err, data) {
			if(err) {
				var error = '<div class="card card-alert card-red" style="margin-left:15px;"><div class="card-main"><div class="card-inner">Something went wrong. Try reloading the inventories.</div></div></div><div class="card card-alert card-red" style="margin-left:15px;"><div class="card-main"><div class="card-inner">' + err + '</div></div></div>';
				$(Data.inventory.user.selector).html(error);
				return;
			} else if(data.success == false) {
				var error = '<div class="card card-alert card-red" style="margin-left:15px;"><div class="card-main"><div class="card-inner">' + data.message + '</div></div></div>';
				$(Data.inventory.user.selector).html(error);
				return;
			}
			setInventory('user', null, data, function() {
				setInventoryBindings();
				$doc.find('.userloader').fadeOut();
				$doc.find('[data-inventory="bot"] .app-item').addClass('disabled');
			});
		});
	}

	if(Data.page == "exchange") {
		reloadInventories();

		$doc.find('[data-reloadinventories]').bind('click', reloadInventories);

		$doc.on('tradable', function() {
			var btn = $('[data-maketrade]');
			if(Data.inventory.tradable) btn.removeClass('disabled');
			else btn.addClass('disabled');
		});

		$doc.find('[data-maketrade]').bind('click', function(e) {
			if($(this).hasClass('disabled')) return false;
			var tradeItems = {
				"user": {},
				"bot": {}
			}
			var userSelected = $doc.find('[data-selected="user"] .app-item');
			var botSelected  = $doc.find('[data-selected="bot"] .app-item');

			userSelected.each(function() {
				tradeItems.user[$(this).data('title')] = +$(this).find('.count').html();
			});
			botSelected.each(function() {
				tradeItems.bot[$(this).data('title')]  = +$(this).find('.count').html();
			});

			$('#exchangeModal').modal('show');
			$('#exchangeModal [data-loaded]').hide();
			$('#exchangeModal [data-error]').hide();
			$('#exchangeModal [data-loading]').show();

			tradeItems._token = zeToken;

			$.post(Data.offer.send, tradeItems, function(data) {
				$('#exchangeModal [data-loading]').hide();

				if(typeof data.error != "undefined") {
					$('#exchangeModal [data-error]').html(data.error);
					$('#exchangeModal [data-error]').fadeIn();
				} else {
					$('#exchangeModal [data-loaded]').fadeIn();
					$('#exchangeModal [data-offerlink]').attr('href', 'https://steamcommunity.com/tradeoffer/' + data.offer);
				}
			});
			e.preventDefault();
		});

		$doc.find('.order').bind('change', function() {
			var value = $(this).val();
			var who = $(this).data('who');

			$(Data.inventory[who].selector).find('div').sort(function(a, b) {
				var aS = $(a).find('.price').html();
				var bS = $(b).find('.price').html();

				if(value == 'expensive') return +bS - +aS;
				return +aS - +bS;
			}).appendTo($(Data.inventory[who].selector));
		});
	}

});