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

pjQuery_1_10_2(document).ready(function() {	
	
	// Sortable Attributes.
	pjQuery_1_10_2('ul#sortable-attributes, ul#sortable-searchable').sortable({
		connectWith: "ul",
		receive: function(event, ui) {
			var id = (pjQuery_1_10_2(this).data('list') +'-'+ pjQuery_1_10_2(ui.item).data('id'));
			ui.item.attr('id', id);
		},
		update: function(event, ui) {
			
			if(event.target.id == 'sortable-searchable') {
				pjQuery_1_10_2('#sortable-searchable li').each(function(i) {
					var $this = pjQuery_1_10_2(this);
					$this.attr('id', $this.parent().data('list') +'-'+ $this.data('id'));
					$this.text( (i + 1) +' | '+ $this.data('name') );
				});
			}else if(event.target.id == 'sortable-attributes') {
				pjQuery_1_10_2('#sortable-attributes li').each(function() {
					var $this = pjQuery_1_10_2(this);
					$this.attr('id', $this.parent().data('list') +'-'+ $this.data('id'));
					$this.text( $this.data('name') );
				});
			}
			

			var sortable = [
				pjQuery_1_10_2('#sortable-attributes').sortable('serialize'),
				pjQuery_1_10_2('#sortable-searchable').sortable('serialize')
			];

			pjQuery_1_10_2('#psearch-attributes-change').val( sortable.join('&') );
		}
    })
    .disableSelection();

});