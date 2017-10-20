jQuery(document).ready(function($){
	$('#load-map-data').click(function(){
		var loader = new varienLoader(true);
		$('#load-map-data').addClass('disabled').attr('disabled', true);
		Element.show('loading-mask');
		var urlDomain = window.location.href;
		var arr = urlDomain.split("/");
		$.post(pathJson,$('#edit_form').serialize(), function(response){
			Element.hide('loading-mask');
			$('#load-map-data').removeClass('disabled').attr('disabled', false);
			
			$('#page_latitude').val(response.lat);
			$('#page_longitude').val(response.long)
		
		},'json');
		
		return false;
	});
});