$j = jQuery;
var storelocatori = {
		currentIndex : null,
		latitude : null, 
		longitude : null,
		current: false,
		height: 230,
		scrollWheel:true,
		mapTypeControl:true,
		scaleControl:true,
		firstload:null,
		page:1,
		heightWindow: null,
		stopCheck: false,
		originalpath:null,
		prevScroll: null,
		stopLoad: false,
		showPosisiton: function(position){
			storelocatori.latitude = position.coords.latitude
        	storelocatori.longitude = position.coords.longitude
		},
		
		showError: function(error){
			switch(error.code) {
				case error.PERMISSION_DENIED:
		    		console.log("User denied the request for Geolocation.");
		    	break;
				case error.POSITION_UNAVAILABLE:
					console.log("Location information is unavailable.");
				break;
				case error.TIMEOUT:
					console.log("The request to get user location timed out.");
				break;
				case error.UNKNOWN_ERROR:
					console.log("An unknown error occurred.");
				break;
		    }
		}, 
		
		decorate: function(){
			
			setTimeout(function(){
				$j('.search-result .item').removeAttr('style');
				$j('.search-result .item').each(function(){
					var height = $j(this).outerHeight(true);
					
					if (storelocatori.height < height){
						storelocatori.height = height;
					}
				});
				
				$j('.search-result .item').each(function(){
					$j(this).css('height', storelocatori.height+30);				
				});
			}, 500)
		},
		
		loadPage: function(){
			
			$j(window).scroll(function(){
				storelocatori.heightWindow = $j(window).height();
				if (storelocatori.stopLoad==true){
					return;
				}
				
				if (storelocatori.stopCheck==true){
					return;
				}
				if ($j('.back-to-top').length){
					var position = $j('.back-to-top').offset().top - storelocatori.heightWindow;
					var scroll = $j(window).scrollTop();
					if (scroll<=storelocatori.prevScroll){
						return;
					}
					storelocatori.prevScroll = scroll;
					
					var currentPosition = position-scroll;
					if (currentPosition<=300){						
						storelocatori.stopCheck=true;
						$j('.item-pagination .pagination-loader .loader').show();
						$j('#storelocatori-search').submit();
					}
					
				}
				
			});
		}, 
		reset: function (){
			var mapOptions = {
					mapTypeId : google.maps.MapTypeId.ROADMAP,
					zoom: 1,
					scrollwheel: storelocatori.scrollWheel,
					scaleControl: storelocatori.scaleControl,
					mapTypeControl:storelocatori.mapTypeControl
				};

			
			$j('#map-canvas').addClass('map-container');
			map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
			bounds = new google.maps.LatLngBounds();
			
			storelocatori.stopCheck = false;
			storelocatori.originalpath = null;
			storelocatori.prevScroll = null;
			storelocatori.stopLoad = false;
		}
};

var options = {
		 // enableHighAccuracy: true,
		  timeout: 5000,
		  maximumAge: Infinity
};

$j(document).ready(function(){
	storelocatori.loadPage();
	$j(".storelocatori select").chosen({disable_search_threshold: 10});
	

	navigator.geolocation.getCurrentPosition(storelocatori.showPosisiton,storelocatori.showError, options);	
	if ("geolocation" in navigator) {
	
	}else{
		$j('.btn-current-location').remove();
	}
	

	$j('.btn-current-location').click(function(e){
		e.preventDefault();
		navigator.geolocation.getCurrentPosition(function(position) {
			storelocatori.latitude  = position.coords.latitude;
			storelocatori.longitude = position.coords.longitude;			
		});
		
		storelocatori.current = true;
		$j('#storelocatori-search').submit();
	});
	
	$j('#storelocatori-search').submit(function(e){		
			e.preventDefault();
			$j('.loader-ajax').removeClass('hidden');
			var path= $j(this).serialize();
			
			if (storelocatori.current==true){
				path += '&latitude=' + storelocatori.latitude + '&longitude='+storelocatori.longitude + '&current=true';
			}else{
				path += '&latitude=' + storelocatori.latitude + '&longitude='+storelocatori.longitude;
			}
			if (storelocatori.stopCheck==true){
				path = storelocatori.originalpath + '&page='+storelocatori.page;
			}else{
				storelocatori.reset();	
				storelocatori.originalpath = path;
			}
			 
			$j.post(urlSearch, path, function(response){
				storelocatori.stopCheck=false;
				$j('.item-pagination .pagination-loader .loader').hide();
				$j('.loader-ajax').addClass('hidden');
				if (response.error==false){
					
					if (response.action=="viewresult"){
						storelocatori.stopLoad = response.stopLoad;
						if (response.pagination==true){						
							$j('.item-pagination').replaceWith(response.result)
		
						}else{
							$j('#search-result').html(response.result);
						}
						if (response.maps.totalRecords==0){
							var mapOptions = {
									center : new google.maps.LatLng(0, 0),
									zoom : 1,
									mapTypeId : google.maps.MapTypeId.ROADMAP
							};
							map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
						}else{
								
							initGMap(response.maps);
						}
					}
			
				};
			},'json');
		
		return false;
	});
		
	if (storelocatori.firstload==true){
		$j('#map-canvas').addClass('map-container');
		var mapOptions = {
				mapTypeId : google.maps.MapTypeId.ROADMAP,
				zoom: 1,
				scrollwheel: storelocatori.scrollWheel,
				scaleControl: storelocatori.scaleControl,
				mapTypeControl:storelocatori.mapTypeControl
			};
	
		map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
		bounds = new google.maps.LatLngBounds();
	}
});



infoWindow = new Array();


var map = null;
var bounds = null;
function initGMap(mapsJson) {
	
	
	if (mapsJson.totalRecords==0){
		return;
	}
	
	
	
	
	if (mapsJson.totalRecords==1){
		
		var myLatlng = new google.maps.LatLng(mapsJson.items[0].latitude,mapsJson.items[0].longitude);
		bounds.extend(myLatlng);
		
		var marker = new google.maps.Marker({
		      position: myLatlng,
		      map: map,
		      title: mapsJson.items[0].title,
		      icon: image,
		      
		  });

		infoWindow[0] = new InfoBox({
	         content: mapsJson.items[0].content,
	         disableAutoPan: false,
	         maxWidth: 279,
	         pixelOffset: new google.maps.Size(-139, -286),
	         zIndex: null,
	         boxStyle: {
	            background: "none",
	            opacity: 1,
	            width: "279px",
	            top:"-10px"
	        },
	        closeBoxMargin: "0 0 0 0",
	        closeBoxURL: closeButton,
	        infoBoxClearance: new google.maps.Size(1, 1)
	    });
		
		google.maps.event.addListener(marker, 'click', function() {
			infoWindow[0].open(map, this);			   
		});
		 
		map.fitBounds(bounds);
	    map.panToBounds(bounds); 
	    
	  
	  
	  
		return;
	}else{
		
		var zoomChangeBoundsListener =
		    google.maps.event.addListener(map, 'bounds_changed', function(event) {
		        google.maps.event.removeListener(zoomChangeBoundsListener);
		        map.setZoom( Math.min( zoomData, map.getZoom() ) );
		    });
	
		$j.each(mapsJson.items, function(index) {
			if (this.latitude!='0' && this.longitude!='0'){
				
				var myLatlng = new google.maps.LatLng(this.latitude,this.longitude);
				bounds.extend(myLatlng);
				
				var marker = new google.maps.Marker({
				      position: myLatlng,
				      map: map,
				      title: this.title,
				      icon: image
				 });
			    

		     infoWindow[index] = new InfoBox({
			         content: this.content,
			         disableAutoPan: false,
			         maxWidth: 279,
			         pixelOffset: new google.maps.Size(-139, -286),
			         zIndex: null,
			         boxStyle: {
			            background: "none",
			            opacity: 1,
			            width: "279px",
			            top:"-10px"
			        },
			        closeBoxMargin: "0 0 0 0",
			        closeBoxURL: closeButton,
			        infoBoxClearance: new google.maps.Size(1, 1)
			    });
				
				google.maps.event.addListener(marker, 'click', function() {
					if (storelocatori.currentIndex!=null){
						infoWindow[storelocatori.currentIndex].close();	
					}
					infoWindow[index].open(map, this);			   
					storelocatori.currentIndex = index;
				});
			
				map.fitBounds(bounds);
			    map.panToBounds(bounds); 
			}
			
		});
	}

}