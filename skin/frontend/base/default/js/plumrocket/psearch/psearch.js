/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please 
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_Search
 * @copyright   Copyright (c) 2015 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */
 

function Plumrocket_Search(config)
{
	var $this = this;
	var $form = pjQuery_1_10_2('#pas-mini-form');
	var $tooltip = pjQuery_1_10_2('#pas-tooltip');

	this.config = {
		path: 				'',
		delay: 				500,
		queryLenghtMin: 	2
	}
	pjQuery_1_10_2.extend(this.config, config);

	this.timeout = null;

	this.run = function()
	{
		// Left.
		$form.on('change', '.pas-nav-left .pas-search-dropdown', function() {
			var text = pjQuery_1_10_2.trim(pjQuery_1_10_2(this).find('option:selected').text());
			$form.find('.pas-nav-left .pas-search-label').text(text);
		});
		
		// Center.
		$form.find('.pas-nav-center .pas-input-text').on('keyup', function() {
			var queryText = this.value;
			var categoryId = $form.find('.pas-nav-left .pas-search-dropdown').val();
			if(this.timeout) {
				clearTimeout(this.timeout);
			}

			if(queryText.length >= $this.config.queryLenghtMin) {
				this.timeout = setTimeout(function() {
					$this._find(queryText, categoryId);
				}, $this.config.delay);
			}else{
				$this._hide();
			}
		})
		.on('blur', function() {
			setTimeout(function() {
				$this._hide();
			}, 500);
		});

	}

	this._find = function(queryText, categoryId) {
		$form.find('.pas-loader').css('visibility', 'visible');
		pjQuery_1_10_2.get($this.config.path, {'q': queryText, 'cat': categoryId}, function(data) {
			data = JSON.parse(pjQuery_1_10_2.trim(data));
			if(data.success && data.content) {
				$tooltip.html(data.content);
				$this._show();
			}else{
				$this._hide();
			}
		})
		.always(function() {
			$form.find('.pas-loader').css('visibility', 'hidden');
		})
		.fail(function() {});
	}

	this._show = function()
	{
		$form.addClass('pas-active');
	}

	this._hide = function()
	{
		$form.removeClass('pas-active');
	}

}