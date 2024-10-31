 var ajax_url = mc360Params.ajax_url;
 var plugin_root = mc360Params.plugin_root;
 var is_user_logged_in = mc360Params.is_user_logged_in;
 var select_droppoint = mc360Params.select_droppoint;
 var selected = mc360Params.selected;
 var you_have_Selected = mc360Params.you_have_Selected;
 var pricetrans = mc360Params.pricetrans;
 var pop_up_status = mc360Params.pop_up_status;
 var google_api_key = mc360Params.google_api_key;
 var choose_pickup_point_btn = mc360Params.choose_pickup_point_btn;
 var place_order_btn = mc360Params.place_order_btn;
 var mc360Company = mc360Params.mc360Company;
 var allagents = mc360Params.allagents;
 var overviewMarkers = [];
 var markerGroups = [];
 var custmarker = '';
 var map = '';
 var bounds = '';
 var marker = '';
 var showmappp = 0;
 var $j = jQuery;

 function loadimage() {
 	$ = jQuery;
 	$("#map_canvas").html('<img src="' + plugin_root + '/assets/img/loading.gif" style="margin: 45%;width:auto; "/>');
 	$j("#droppoints ul").html('<img class="loader" src="' + plugin_root + '/assets/img/loading.gif" style="margin: 45%; "/>');
 }

 function getdroppoints(agentname, filterIcon, mapicon, ship_type, postcode, search) {
 	overviewMarkers = [];
 	custmarker = '';
 	/* $("#selectedHtml").html(''); 



 	fillinput();*/
 	$ = jQuery;
 	if (pop_up_status == 'popup') $('body').addClass("removescroll");
 	$("#postagain").val('');
 	var checkposttype = $("#billing_postcode").val();
 	var checkposttbool = $.isNumeric(checkposttype);
 	if ($j(window).width() < 768) {
 		$j("#droppoints ul").html('<img  class="loader"  src="' + plugin_root + '/assets/img/loading.gif" style="margin: 55% auto;width:auto; "/>');
 	} else loadimage();
 	$("#map_wrapper").show();
 	var ship_types = '';
 	$(".mc360-overlay").css("visibility", "visible");
 	$(".mc360-overlay").css("opacity", "1");
 	if (checkposttbool == true) {
 		$("#Dao365").html('');
 		$("#Bring").html('');
 		$("#PostNord").html('');
 		$("#GLS").html('');
 		$("#UPS").html('');
 		$("#DHL").html('');
 	}
 	var postagain = $("#billing_postcode").val();
 	/* if (jQuery('#ship-to-different-address-checkbox:checked').length > 0) { */
 	/* shipping adress */
 	/* country = jQuery('#shipping_country').val();



 	       state = jQuery('#shipping_state').val();



 	       address_1 = jQuery('#shipping_address_1').val();



 	       city = jQuery('#shipping_city').val();







 	   } else { */
 	/* billing country */
 	country = jQuery('#billing_country').val();
 	state = jQuery('#billing_state').val();
 	address_1 = jQuery('#billing_address_1').val();
 	city = jQuery('#billing_city').val();
 	/*  } */
 	if (state == '' && address_1 == '' && city == '' && country.length > 0 && postagain.length < 1) {
 		var myLatlng = new google.maps.LatLng(56.0132805, 9.9106988);
 		var mapOptions = {
 			zoom: 14,
 			center: myLatlng,
 		}
 		var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
 		setOverview(map);
 		htmlall = '';
 	}
 	/* var allagents = []; */
 	var html = '';
 	var htmlall = '';
 	var str = '';
 	var temp = 0;
 	/* var allagents = $("#allagents").html(); */
 	if (typeof(allagents) != "undefined" && allagents != '' && allagents !== null) {
 		var parseagents = JSON.parse(allagents);
 		var agentid = ship_type;
 		$(parseagents).each(function(index, agent) {
 			if (agentid == agent.id) {
 				var ship_type = agent.id;
 				var agentnameall = agent.name;
 				var filterIcon = agent.logo_filter;
 				var mapicon = agent.logo_pin;
 				agentname = agentnameall;
 				var str = "checked=checked";
 				var price = $("#" + agentname + "price").text();
 				var priceis = parseFloat(price).toFixed(2);
 				var curr = $("#currencysymbol").text();
 				if (isNaN(priceis)) priceis = 0;
 				html += "<li><div class='chk_bx'><input id=" + agentname + ship_type + " value=" + ship_type + " map=" + mapicon + " ship_type=" + ship_type + " agentname=" + agentname + " ship_type=" + ship_type + " " + str + " type='checkbox' onchange='callagent();' class='agentspop' name='agentspop'/> <label for=" + agentname + ship_type + "><span></span><div class='imgg'><img src='" + filterIcon + "'/></div><div class='agentdata'><h3>" + agentname + "</h3><p>(" + priceis + "  " + curr + ")</p></div></label></div></li>";
 				temp = 1;
 			}
 		});
 	}
 	if (temp == 1) $(".check_bx_list ul").html(html);
 	if (typeof(search) == 'undefined' && checkposttbool == false) {
 		var defaultid = $("#mc360_postcode").val();
 		$("#billing_postcode").val(defaultid);
 		checkposttype = $("#billing_postcode").val();
 		checkposttbool = $.isNumeric(checkposttype);
 		if (checkposttbool == true) {
 			initialize(agentname, filterIcon, mapicon, ship_type, postcode, search);
 		} else if (checkposttype.length < 1 || checkposttype == null) emptyMap();
 		else if (search == 'search') callagent();
 	} else {
 		checkposttbool = $.isNumeric(checkposttype);
 		if (checkposttbool == true) {
 			initialize(agentname, filterIcon, mapicon, ship_type, postcode, search);
 		} else if (checkposttype.length < 1 || checkposttype == null) emptyMap();
 		else if (search == 'search') callagent();
 	}
 	mobileview();
 }

 function initialize(agentname, filterIcon, mapicon, ship_type, postcode, search) {
 	/* if (jQuery('#ship-to-different-address-checkbox:checked').length > 0) { */
 	/* shipping adress */
 	/*  country = jQuery('#shipping_country').val();



 	       state = jQuery('#shipping_state').val();



 	       address_1 = jQuery('#shipping_address_1').val();



 	       city = jQuery('#shipping_city').val();



 	       cust_zipcode = jQuery('#shipping_postcode').val();



 	       var fname = jQuery('#shipping_first_name').val();







 	   } else { */
 	/* billing country */
 	country = jQuery('#billing_country').val();
 	state = jQuery('#billing_state').val();
 	address = jQuery('#billing_address_1').val();
 	city = jQuery('#billing_city').val();
 	cust_zipcode = jQuery('#billing_postcode').val();
 	var fname = jQuery('#billing_first_name').val();
 	/*  } */
 	var listing = '';
 	var points = [];
 	/* var allagents = []; */
 	var mapicon_arry = [];
 	var agentname_arry = [];
 	var ship_type_arry = [];
 	/* allagents = $("#allagents").html(); */
 	var parseagents = JSON.parse(allagents);
 	$(parseagents).each(function(index, agent) {
 		var agentname = agent.name;
 		var filterIcon = agent.logo_filter;
 		var mapicon = agent.logo_pin;
 		var ship_type = agent.id;
 		ship_type_arry.push(ship_type);
 		mapicon_arry.push(mapicon);
 		agentname_arry.push(agentname);
 	});	var addressplit=address.split(',');		if(addressplit.length>0){			address_1=addressplit[0];		}		else{			address_1=address;		}
 	/* customer address */
 	if ((agentname == '' || agentname == null) || (filterIcon == '' || filterIcon == null) || (mapicon == '' || mapicon == null) || (ship_type == '' || ship_type == null) && postcode != null && postcode != '') {
 		var temp = 0;				/* console.log("iff");		console.log(addressplit); */
 		var data = {
 			'action': 'mc360_get_shop_list',
 			'agent': ship_type_arry.toString(),
 			'zipcode': postcode,
 			'country': country,
 			'state': state,
 			'address_1': address_1,
 			'city': city,
 		};
 		jQuery.post(ajax_url, data, function(response) {
 			var returned = JSON.parse(response);
 			var alldroppoints = returned.droppoints;
 			for (var carriers in alldroppoints) {
 				var carrierdroppoints = alldroppoints[carriers];
 				var carrierid = carrierdroppoints.id;
 				var carriername = carrierdroppoints.name;
 				var len = ship_type_arry.length;
 				if (jQuery.inArray(carrierid, ship_type_arry) != '1') {
 					var carriersid = carrierid;
 					var mapiconis = 'https://mycommerce360.dk/img/map' + carriersid + '.png';
 				} else {
 					var carriersid = '';
 					var mapiconis = '';
 				}
 				if (jQuery.inArray(carriername, agentname_arry) != '-1') {
 					var carriersname = carriername;
 				} else var carriersname = '';
 				var droppoints = carrierdroppoints.droppoints;
 				/* getPrice of carrier */
 				var price = $("#" + carriersname + "price").text();
 				var priceis = parseFloat(price).toFixed(2);
 				var curr = $("#currencysymbol").text();
 				if (isNaN(priceis)) priceis = 0;
 				/* getPrice of carrier */
 				for (var i in droppoints) {
 					var points1 = [];
 					var address = droppoints[i].address;
 					var city = droppoints[i].city;
 					var country_code = droppoints[i].country_code;
 					var latitude = droppoints[i].latitude;
 					var longitude = droppoints[i].longitude;
 					var company_name = droppoints[i].company_name;
 					var number = droppoints[i].number;
 					var zip_code = droppoints[i].zip_code;
 					var finalship = "mc360_shipping_" + carriersname.toLowerCase();
 					/* 	0	 */
 					points1.push(city);
 					/* 	1	 */
 					points1.push(country_code);
 					/* 	2	 */
 					points1.push(latitude);
 					/* 	3	 */
 					points1.push(longitude);
 					/* 	4	 */
 					points1.push('Overview');
 					/* 	5	 */
 					points1.push(1);
 					/* 	6	 */
 					points1.push(company_name);
 					/* 	7	 */
 					points1.push(address);
 					/* 	8	 */
 					points1.push(number);
 					/* 	9	 */
 					points1.push(carriersname);
 					/* 	10	 */
 					points1.push(carriersid);
 					/* 	11	 */
 					points1.push(mapiconis);
 					/* 	12	 */
 					points1.push(zip_code);
 					/* 	13	 */
 					points1.push(finalship);
 					/* 	14	 */
 					points1.push(priceis + ' ' + curr);
 					points.push(points1);
 					var datatostore = JSON.stringify(points1);
 					if (carriersname == 'Dao365') $("#Dao365").append("<span>" + datatostore + "</span>");
 					if (carriersname == 'Bring') $("#Bring").append("<span>" + datatostore + "</span>");
 					if (carriersname == 'PostNord') $("#PostNord").append("<span>" + datatostore + "</span>");
 					if (carriersname == 'GLS') $("#GLS").append("<span>" + datatostore + "</span>");
 					if (carriersname == 'UPS') $("#UPS").append("<span>" + datatostore + "</span>");
 					if (carriersname == 'DHL') $("#DHL").append("<span>" + datatostore + "</span>");
 				}
 				temp++;
 			}
 			if (len == temp) {
 				callagent();
 			}
 		});
 	} else {
 		/* console.log("else"); */
 		var data = {
 			'action': 'mc360_get_shop_list',
 			'agent': ship_type,
 			'zipcode': postcode,
 			'country': country,
 			'state': state,
 			'address_1': address_1,
 			'city': city,
 		};
 		jQuery.post(ajax_url, data, function(response) {
 			var points = [];
 			var returned = JSON.parse(response);
 			var alldroppoints = returned.droppoints;
 			for (var carriers in alldroppoints) {
 				var carrierdroppoints = alldroppoints[carriers];
 				var carrierid = carrierdroppoints.id;
 				var carriername = carrierdroppoints.name;
 				if (jQuery.inArray(carrierid, ship_type_arry) != '1') {
 					var carriersid = carrierid;
 					var mapiconis = 'https://mycommerce360.dk/img/map' + carriersid + '.png';
 				} else {
 					var carriersid = '';
 					var mapiconis = '';
 				}
 				if (jQuery.inArray(carriername, agentname_arry) != '-1') {
 					var carriersname = carriername;
 				} else var carriersname = '';
 				var droppoints = carrierdroppoints.droppoints;
 				/* getPrice of carrier */
 				var price = $("#" + carriersname + "price").text();
 				var priceis = parseFloat(price).toFixed(2);
 				var curr = $("#currencysymbol").text();
 				if (isNaN(priceis)) priceis = 0;
 				/* getPrice of carrier */
 				for (var i in droppoints) {
 					var points1 = [];
 					var address = droppoints[i].address;
 					var city = droppoints[i].city;
 					var country_code = droppoints[i].country_code;
 					var latitude = droppoints[i].latitude;
 					var longitude = droppoints[i].longitude;
 					var company_name = droppoints[i].company_name;
 					var number = droppoints[i].number;
 					var zip_code = droppoints[i].zip_code;
 					var finalship = "mc360_shipping_" + carriersname.toLowerCase();
 					/* 	0	 */
 					points1.push(city);
 					/* 	1	 */
 					points1.push(country_code);
 					/* 	2	 */
 					points1.push(latitude);
 					/* 	3	 */
 					points1.push(longitude);
 					/* 	4	 */
 					points1.push('Overview');
 					/* 	5	 */
 					points1.push(1);
 					/* 	6	 */
 					points1.push(company_name);
 					/* 	7	 */
 					points1.push(address);
 					/* 	8	 */
 					points1.push(number);
 					/* 	9	 */
 					points1.push(carriersname);
 					/* 	10	 */
 					points1.push(carriersid);
 					/* 	11	 */
 					points1.push(mapiconis);
 					/* 	12	 */
 					points1.push(zip_code);
 					/* 	13	 */
 					points1.push(finalship);
 					/* 	14	 */
 					points1.push(priceis + ' ' + curr);
 					points.push(points1);
 					var datatostore = JSON.stringify(points1);
 					$("#" + carriersname).append("<span>" + datatostore + "</span>");
 				}
 				setMarker(points, ship_type_arry, mapicon_arry, agentname);
 			}
 		});
 	}
 }
 $ = jQuery;

 function toggleGroup(type) {
 	for (var i = 0; i < overviewMarkers.length; i++) {
 		var overtype = overviewMarkers[i].type;
 		if (typeof(overtype) != "undefined" && overtype == type) {
 			var marker = overviewMarkers[i];
 			if (!marker.getVisible()) {
 				marker.setVisible(true);
 				$(".maplist" + type).css("display", "table");
 			} else {
 				marker.setVisible(false);
 				$(".maplist" + type).css("display", "none");
 			}
 		}
 	}
 }

 function setMarker(points, ship_type, mapicon, agentname) {
 	/* console.log("fgfgf"); */
 	bounds = new google.maps.LatLngBounds();
 	state = '';
 	address_1 = '';
 	city = '';
 	cust_zipcode = '';
 	if (jQuery(window).width() < 768) var postagain = $("#postagainis").val();
 	else var postagain = $("#postagain").val();
 	var adresschanged = 0;
 	if (postagain.length > 1) adresschanged=1;
 	/* customer address */
 	/* billing country */
 	var country = jQuery('#billing_country').val();
 	var state = jQuery('#billing_state').val();
 	var address = jQuery('#billing_address_1').val();
 	var city = jQuery('#billing_city').val();
 	var cust_zipcode = jQuery('#billing_postcode').val();
 	var fname = jQuery('#billing_first_name').val();	var addressplit=address.split(',');		if(addressplit.length>0){			address_1=addressplit[0];		}		else{			address_1=address;		}
 	if ((is_user_logged_in == 1 && adresschanged == 0) || (address_1.length > 0 && cust_zipcode.length > 0 && adresschanged == 0)) {
 		var custaddress = address_1 + ',' + city + ',' + state + ',' + cust_zipcode + ',' + country;
 		$.ajax({
 			url: 'https://maps.googleapis.com/maps/api/geocode/json?key=' + google_api_key + '&address=' + encodeURIComponent(custaddress) + '&sensor=false',
 			type: "get",
 			success: function(data, textStatus) {
 				if (data.status === "OK") {
 					var myylocation = data.results[0].geometry.location;
 					lat = myylocation.lat;
 					lng = myylocation.lng;
 					var latlng = new google.maps.LatLng(lat, lng);
 					custmarker = new google.maps.Marker({
 						position: latlng,
 						icon: '',
 						zIndex: 1,
 						title: fname
 					});
 					if (overviewMarkers.push(custmarker)) {
 						var myLatlng = new google.maps.LatLng(56.0132805, 9.9106988);
 						var mapOptions = {
 							center: myLatlng,
 							zoom: 14,
 						}
 						map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
 						/* Show markers on map */
 						setOverview(map);
 						/* Show markers on map */
 					}
 				}
 			}
 		});
 	}
 	if ((points.length < 1 && overviewMarkers.length > 1 && is_user_logged_in == 1) || (points.length < 1 && overviewMarkers.length < 1 && is_user_logged_in != 1)) {
 		emptyMap();
 		return;
 	}
 	var infowindow = new google.maps.InfoWindow();
 	var originPoint = [];
 	listing = '';
 	for (var i in points) {
 		if (points[i]['visibility'] == false) var displaylist = "style='display:none'";
 		else var displaylist = "style='display:table'";
 		var toselected = '<div class="list_right_new" style="display:none;"><h2>' + points[i][6] + '</h2><div class="list_right_new_info"><span>' + points[i][7] + '</span><span>' + points[i][12] + ',&nbsp;' + points[i][0] + '</span> </div><h4>' + pricetrans + ': <strong>' + points[i][14] + '</strong></h4></div>';
 		var shippingfield = '<div class="shipping_field"><input type="hidden" class="selectedcountry" value="' + points[i][1] + '"><input type="hidden" class="selectedcity" value="' + points[i][0] + '"><input type="hidden" class="selectedadress" value="' + points[i][7] + '"><input type="hidden" class="selectedcompany" value="' + points[i][6] + '"><input type="hidden" class="selectedpostcode" value="' + points[i][12] + '"></div>';
 		/* Embedding in list */
 		var listing = "<li class='maplist" + points[i][9] + "' onclick='checkradio(this);' " + displaylist + " ><input type='radio'  mc360-agentId=" + points[i][10] + " mc360-latt=" + points[i][2] + " mc360-lng=" + points[i][3] + "	class='click'  name='loc_name' value=" + points[i][8] + "><input type='hidden' class='finalship' name='finalship' value=" + points[i][13] + ">" + '<input type="hidden" id="mc360-agentId"  name="mc360-agentId" class="mc360-agentIds" value="' + points[i][10] + '"><input type="hidden" id="mc360-latt"  class="mc360-latts"   name="mc360-latt" value="' + points[i][2] + '"><input type="hidden" id="mc360-lng" name="mc360-lng"  class="mc360-lngs"  value="' + points[i][3] + '"><div class="location_icn"><img src="' + points[i][11] + '" ></div><div class="lcation_desc" >' + "<h2>" + points[i][6] + "</h2><span>" + points[i][7] + "</span>,&nbsp;<span>" + points[i][12] + ",&nbsp;" + points[i][0] + "</span><h4>" + pricetrans + ": " + points[i][14] + "</h4></div>" + toselected + shippingfield + "</li>";
 		$(".loader").hide();
 		$("#droppoints ul").append(listing);
 		/* Embedding in list */
 		var p = points[i];
 		var latlng = new google.maps.LatLng(p[2], p[3]);
 		bounds.extend(latlng);
 		var marker = new google.maps.Marker({
 			position: latlng,
 			icon: p[11],
 			zIndex: p[5],
 			title: p[0],
 			type: p[9]
 		});
 		if (p['visibility'] == false) marker.setVisible(p['visibility']);
 		overviewMarkers.push(marker);
 		google.maps.event.addListener(marker, 'click', (function(marker, i) {
 			return function() {
 				var toselected1 = '<div class="list_right_new" style="display:none;"><h2>' + points[i][6] + '</h2><div class="list_right_new_info"><span>' + points[i][7] + '</span><span>' + points[i][12] + ',&nbsp;' + points[i][0] + '</span> </div><h4>' + pricetrans + ': ' + ' <strong>' + points[i][14] + '</strong></h4></div>';
 				var shippingfield1 = '<div class="shipping_field"><input type="hidden" class="selectedcountry" value="' + points[i][1] + '"><input type="hidden" class="selectedcity" value="' + points[i][0] + '"><input type="hidden" class="selectedadress" value="' + points[i][7] + '"><input type="hidden" class="selectedcompany" value="' + points[i][6] + '"><input type="hidden" class="selectedpostcode" value="' + points[i][12] + '"></div>';
 				var fields = '<input type="hidden" id="mc360-agentId" class="mc360-agentIds"  name="mc360-agentId"  value="' + points[i][10] + '"><input type="hidden" id="mc360-latt"  class="mc360-latts"  name="mc360-latt" value="' + points[i][2] + '"><input type="hidden" id="mc360-lng"  class="mc360-lngs"  name="mc360-lng" value="' + points[i][3] + '">';
 				var infocontent = "<div class='infodata'><ul><li   onclick='checkradio(this);'><input type='radio' style='display:none;'  mc360-agentId=" + points[i][10] + " mc360-latt=" + points[i][2] + " mc360-lng=" + points[i][3] + "	class='click'  name='loc_name' value=" + points[i][8] + "><input type='hidden' class='finalship' name='finalship' value=" + points[i][13] + ">" + fields + "<div class='location_icn' ><img src=" + points[i][11] + " ></div><div class='lcation_desc' style='display:block;'><h2>" + points[i][6] + "</h2><span>" + points[i][7] + ",&nbsp;</span><span>" + points[i][12] + ",&nbsp;" + points[i][0] + " </span><h4>" + pricetrans + ": " + points[i][14] + "</h4></div><div class='chooseitbtn'><a class='chooseit' onclick='chooseit(this);' href='javascript:;'>" + select_droppoint + "</a></div>" + toselected1 + shippingfield1 + "</li></ul></div>";
 				infowindow.setContent(infocontent);
 				infowindow.open(map, marker)
 				originPoint.push(marker, i);
 			}
 		})(marker, i));
 	}
 	if ((is_user_logged_in != 1 && adresschanged == 1) || (is_user_logged_in == 1 && adresschanged == 1) || (is_user_logged_in != 1 && adresschanged == 0)) {
 		if (adresschanged == 0) {
 			var custaddress = address_1 + ',' + city + ',' + state + cust_zipcode + ',' + country;
 			$.ajax({
 				url: 'https://maps.googleapis.com/maps/api/geocode/json?key=' + google_api_key + '&address=' + encodeURIComponent(custaddress) + '&sensor=false',
 				type: "get",
 				success: function(data, textStatus) {
 					if (data.status === "OK") {
 						var myylocation = data.results[0].geometry.location;
 						lat = myylocation.lat;
 						lng = myylocation.lng;
 						var latlng = new google.maps.LatLng(lat, lng);
 						custmarker = new google.maps.Marker({
 							position: latlng,
 							icon: '',
 							zIndex: 1,
 							title: fname
 						});
 						if (overviewMarkers.push(custmarker)) {
 							var myLatlng = new google.maps.LatLng(56.0132805, 9.9106988);
 							var mapOptions = {
 								zoom: 14,
 								center: myLatlng,
 							}
 							map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
 							/* Show markers on map */
 							setOverview(map);
 							/* Show markers on map */
 						}
 					}
 				}
 			});
 		} else {
 			var myLatlng = new google.maps.LatLng(56.0132805, 9.9106988);
 			var mapOptions = {
 				zoom: 14,
 				center: myLatlng,
 			}
 			map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
 			/* Show markers on map */
 			setOverview(map);
 			/* Show markers on map */
 		}
 	}
 }

 function setOverview(map) {
 	/* console.log(overviewMarkers); */
 	for (var i = 0; i < overviewMarkers.length; i++) {
 		if (custmarker == '') {
 			bounds.extend(this.overviewMarkers[i].getPosition());
 			map.setCenter(this.overviewMarkers[i].getPosition())
 			map.fitBounds(bounds);
 			map.setZoom(14);
 		} else {
 			bounds.extend(this.overviewMarkers[i].getPosition());
 			map.setCenter(this.overviewMarkers[i].getPosition())
 			map.fitBounds(bounds);
 			map.setZoom(14);
 		}
 		overviewMarkers[i].setMap(map);
 	}
 	if ($(".maplist #showlisting").hasClass("activee")) {
 		showlisting($(".maplist #showlisting"))
 	}
 	if ($(".maplist #showmap").hasClass("activee")) {
 		showmap($(".maplist #showmap"));
 	}
 }

 function showmap(thiss) {
 	$(".top_btn a").removeClass("activee");
 	$(thiss).addClass("activee");
 	$("#droppoints").hide();
 	$("#map_canvas").show();
 	if (custmarker != '') {
 		bounds.extend(custmarker.getPosition());
 		map.fitBounds(bounds);
 		map.setCenter(custmarker.getPosition());
 		map.setZoom(14);
 		custmarker.setMap(map);
 		if (showmappp == 0) {
 			showmappp = 1;
 			setTimeout(function() {
 				$("#showmap").trigger('click');
 			}, 1000);
 		}
 	}
 	/* else if(bounds.length>=1){



 	        map.fitBounds(bounds);



 	        map.panToBounds(bounds); 



 	    }  */
 }

 function showlisting(thiss) {
 	$(".top_btn a").removeClass("activee");
 	$(thiss).addClass("activee");
 	$("#map_canvas").hide();
 	$("#droppoints").show();
 }

 function checkradio($this) {
 	/* console.log($this);
 	console.log($($this).children());
 	console.log($($this).children(".chooseitbtn")); */
 	var parentis = $($this).parent().parent();
 	if (parentis.hasClass("infodata")) $(".chooseit").text(selected);
 	$(".click").prop("checked", false);
 	$("#droppoints ul li,#map_canvas ul li").removeClass("selected");
 	$($this).addClass("selected");
 	var radiobtn = $("li.selected .click");
 	$(radiobtn).prop("checked", true);
 	$(radiobtn).prop("checked", "checked");
 	var agentid = $(radiobtn).attr("mc360-agentId");
 	var mc360latt = $(radiobtn).attr("mc360-latt");
 	var mc360lng = $(radiobtn).attr("mc360-lng");
 	$(".pickupselection #mc360-agentId").val(agentid);
 	$(".pickupselection #mc360-latt").val(mc360latt);
 	$(".pickupselection #mc360-lng").val(mc360lng);
 	var html = $($this).html();
 	$(".radio_list .datashow").html(html);
 	$(".selectedloc").show();
 	/* Selected droppoints values insert into default shipping fields in case of droppoints selection */
 	$("#ship-to-different-address-checkbox").prop("checked", true);
 	$("#ship-to-different-address-checkbox").prop("checked", "checked");
 	var inputtype = "<input type='hidden' id='checktest' value='1' name='checktest'>";
	if($("#checktest").length<1)
		$(inputtype).insertAfter("#billing_email");
	else
		$("#checktest").val(1);
 	var billing_first_name = $("#billing_first_name").val();
 	var billing_last_name = $("#billing_last_name").val();
 	var selectedcountryis = $(".datashow .shipping_field .selectedcountry").val();
 	var selectedcityis = $(".datashow .shipping_field .selectedcity").val();
 	var selectedadressis = $(".datashow .shipping_field .selectedadress").val();
 	var selectedcompanyis = $(".datashow .shipping_field .selectedcompany").val();
 	var selectedpostcodeis = $(".datashow .shipping_field .selectedpostcode").val();
	if($("#shipping_first_name").val().length < 1){
		$("#shipping_first_name").val(billing_first_name);
	}
	if($("#shipping_last_name").val().length < 1){
		$("#shipping_last_name").val(billing_last_name);
	}
 	$("#shipping_country").val(selectedcountryis);
 	$("#shipping_city").val(selectedcityis);
 	$("#shipping_address_1").val(selectedadressis);
 	$("#shipping_company").val(selectedcompanyis);
 	$("#shipping_postcode").val(selectedpostcodeis);
 	/* Selected droppoints values insert into default shipping fields in case of droppoints selection */
 	/* Selected droppoints ID insert into default adress field two in case of droppoints selection */
 	/*  var selectedpickup = $(".datashow .click").val();



 	 $("#billing_address_2").val(selectedpickup);



 	 $("#shipping_address_2").val(selectedpickup); */
 	/* Selected droppoints ID insert into default adress field two in case of droppoints selection */
 	if ($(radiobtn).prop('checked')) {
 		$("#continue").show();
 		$(".radio_list").show();
 		$(".removee").show();
 		$(".radio_list").show();
 		$(".radio_list h6").show();
 	} else {
 		$("#continue").hide();
 		$(".radio_list").hide();
 		$(".removee").hide();
 		$(".radio_list h6").hide();
 	}
 	var chooseitbtn = $($this).children(".chooseitbtn");
 	if (chooseitbtn.length > 0) selectdata();
	trigger=0;
 }

 function closeit() {
 	$("#map_wrapper").hide();
 	$(".mc360-overlay").css("visibility", "hidden");
 	$(".mc360-overlay").css("opacity", "0");
 	if (pop_up_status == 'popup') $('body').removeClass("removescroll");
 }

 function removeit() {
 	$(".datashow").html('');
 	$(".removee").hide();
 	$(".radio_list").hide();
 	$(".radio_list h6").hide();
 }

  var returnis = 0;
   var trigger = 0;
 function selectdata() {
	if($("#ship-to-different-address-checkbox").removeAttr("disabled") ){
			if($("#ship-to-different-address-checkbox").is(":checked")){
				$("#ship-to-different-address-checkbox").removeAttr("checked");
			}
	}
 	$(".pickupselection ul").remove();
 	var selectedcompanyis = $(".datashow .shipping_field .selectedcompany").val();
 	var html = '<ul><li>' + you_have_Selected + $(".datashow .lcation_desc").html() + '</li></ul>';
 	var locationis = $(".datashow .click").val();
 	var carrieridis = $(".datashow .finalship").val();
 	var mc360agentIds = $(".datashow .mc360-agentIds").val();
 	var mc360latts = $(".datashow .mc360-latts").val();
 	var mc360lngs = $(".datashow .mc360-lngs").val();
 	$(html).insertAfter(".pickupselection button");
 	var data = {
 		'action': 'mc360_update_cost',
 		'carrieridis': carrieridis,
 		'mc360-agentId': mc360agentIds,
 		'mc360-latt': mc360latts,
 		'mc360-lng': mc360lngs,
 		'mc360-droppoint': locationis,
 		'mc360-selectedcompanyis': selectedcompanyis,
 		'html': html,
 	};
 	$.post(ajax_url, data, function(response) {
 		$(document.body).trigger("update_checkout");
		$("#ship-to-different-address-checkbox")[0].click();
			
 	});
 	closeit();
 }
var reload=0;	
$(document).ajaxComplete(function(event, xhr, settings) {    var shiplength = $("#mc360-shipping").length;    var selectedHtml = $("#selectedHtml").html();    if (shiplength > 0 && (selectedHtml == '' || selectedHtml == null || $.trim(selectedHtml).length < 1)) {        /* console.log("iff"); */        $("#place_order").prop("disabled", true);        $("#place_order").attr("disabled", "disabled");        $("#place_order").val(choose_pickup_point_btn);        var clonebtn = $("#place_order").clone();        clonebtn.attr("id", "place_order_new");        clonebtn.attr("disabled", "");        clonebtn.attr("onclick", "callpopup();");        clonebtn.prop("disabled", false);        clonebtn.val(choose_pickup_point_btn);        var newbtn = clonebtn.prop("type", "button");        if ($("#place_order_new").length < 1) newbtn.insertAfter("#place_order");        $("#place_order").hide();        $(".shipping_address").hide();        $("#ship-to-different-address-checkbox").attr("disabled", "disabled");        $("#ship-to-different-address-checkbox").prop("disabled", true);        $("#checktesting").val(0);    } else if ($.trim(selectedHtml).length > 1) {        /* console.log("elseif"); */        $("#place_order").prop("disabled", false);        $("#place_order").removeAttr("disabled");        $("#place_order").show();        $("#place_order_new").hide();        $("#place_order_new").remove();        $(".shipping_address").hide();        $("#ship-to-different-address-checkbox").attr("disabled", "disabled");        $("#ship-to-different-address-checkbox").prop("disabled", true);    } else {        var inputtype = "<input type='hidden' id='checktesting' value='0' name='checktesting'>";        if ($("#checktesting").length < 1) $(inputtype).insertAfter("#billing_email");        /* console.log("else"); */        if (shiplength < 1 && $.trim(selectedHtml).length < 1 && ($("#ship-to-different-address-checkbox").attr("disabled"))) {             /* console.log("elseeee");$("#ship-to-different-address-checkbox").prop("disabled", false); */            $("#ship-to-different-address-checkbox").removeAttr("disabled");            $("#ship-to-different-address-checkbox").prop("checked", false);            $("#ship-to-different-address-checkbox").removeAttr("checked");            $("#shipping_first_name").val('');            $("#shipping_last_name").val(''); /*$("#shipping_country").val('');	*/            $("#shipping_postcode").val('');            $("#shipping_company").val('');            $("#shipping_address_1").val('');            $("#shipping_address_2").val('');            $("#shipping_city").val('');            $("#order_comments").val('');        } else if (jQuery('#ship-to-different-address-checkbox:checked').length > 0) {            /* console.log("elsefi"); */            if ($("#checktesting").val() == 0) {                var selectedCountry = $("#billing_country").val(); /*$('#shipping_country').val(selectedCountry).trigger('change');*/                $("#checktesting").val(1);            }        }    }});



function callpopup(){
	/* console.log("test"); */
	$(".pickupselection button").trigger("click");
}
 
 jQuery(document).ready(function($) {
	 if($("#selectedHtml ul li").length>0){
		 var inputtype = "<input type='hidden' id='checktest' value='1' name='checktest'>";
			if($("#checktest").length<1)
				$(inputtype).insertAfter("#billing_email");
		 
	 }
 	jQuery("form.woocommerce-checkout").on('submit', function() {
		if($("#shipping_first_name").val().length < 1){
			var billing_first_name = $("#billing_first_name").val();
			$("#shipping_first_name").val(billing_first_name);
		}
		if($("#shipping_last_name").val().length < 1){
			var billing_last_name = $("#billing_last_name").val();
			$("#shipping_last_name").val(billing_last_name);
		}
 		var shipping_company = $("#shipping_company").val();
 		if ($("#ship-to-different-address-checkbox").is(':checked')) {
 			if (shipping_company.length < 1) $("#shipping_company").val(mc360Company);
 			$("#ship-to-different-address-checkbox").removeAttr("disabled");
 			$("#ship-to-different-address-checkbox").prop("disabled", false);
 		}
 	});
 });

 function callagent() {
 	var points = [];
 	var ship_type_arry = [];
 	var mapicon_arry = [];
 	var agentname = '';
 	var newoverview = [];
 	var temps = 0;
 	var postdatas = $("#postagain").val();
 	if (overviewMarkers.length >= 1 && postdatas.length > 1) {
 		newoverview = overviewMarkers;
 		overviewMarkers = newoverview.slice(0, 1);
 	} else if (postdatas.length == 0) {
 		overviewMarkers = [];
 	}
 	$("#droppoints ul").html('');
 	$(".agentspop").each(function(i, v) {
 		var agentname = $(this).attr("agentname");
 		var ship_type = $(this).attr('ship_type');
 		var mapicon = $(this).attr('map');
 		var agentname = $(this).attr('agentname');
 		var checked = $(this).is(':checked');
 		$("#" + agentname + " span").each(function(i, v) {
 			var data = $(this).html();
 			var datas = $.trim(data);
 			pointsdata = JSON.parse(datas);
 			if (checked) pointsdata.visibility = true;
 			else pointsdata.visibility = false;
 			points.push(pointsdata)
 			ship_type_arry.push(ship_type)
 			mapicon_arry.push(mapicon)
 		});
 		$("#selectedagent").append("<span>" + $(this).val() + "</span>");
 	});
 	setMarker(points, ship_type_arry, mapicon_arry, agentname);
 }

 function deleteMarkers() {
 	clearMarkers();
 	overviewMarkers = [];
 }

 function clearMarkers() {
 	setMapOnAll(null);
 }

 function emptyMap() {
 	var latlng = new google.maps.LatLng(56.0132805, 9.9106988);
 	var mapOptions = {
 		zoom: 8,
 		center: latlng,
 	}
 	var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
 }

 function setMapOnAll(map) {
 	for (var i = 0; i < overviewMarkers.length; i++) {
 		overviewMarkers[i].setMap(null);
 		overviewMarkers[i].setVisible(false);
 	}
 	overviewMarkers = [];
 }

 function checkfieldmobile($this) {
 	$ = jQuery;
 	var mobileval = $($this).val();
 	$("#postagain").val(mobileval);
 	checkfield($this);
 }

 function checkfield($this) {
 	$ = jQuery;
 	if ($($this).length > 0) {
 		var postcode = $($this).val();
 		var postcodefield = $($this);
 	} else {
 		var postcode = $("#postagain").val();
 		var postcodefield = $("#postagain");
 	}
 	if (typeof(postcode) == 'undefined') postcode = $("#postagain").val();
 	if (postcode == '') {
 		postcodefield.css({
 			"color": "#d2241d",
 			"background": "#fdf3f2",
 			"border-color": "#f1cbca"
 		});
 		$("#mc360-shipping .map_right_sec .srch_icn_input i.fa-map-marker").css({
 			"border": "1px solid #f1cbca",
 			"background": "#fdf3f2"
 		});
 	} else {
 		postcodefield.css({
 			"color": "#000",
 			"background": "#fff",
 			"border-color": "#ccc"
 		});
 		$("#mc360-shipping .map_right_sec .srch_icn_input i.fa-map-marker").css({
 			"border": "1px solid #ccc",
 			"background": "#fff"
 		});
 	}
 }

 function fillinput() {
 	$ = jQuery;
 	var countryiso = $("#billing_country").val();
 	var windoWidth = $(window).width();
 	if (windoWidth > 767) var input = document.getElementById('postagain');
 	else var input = document.getElementById('postagainis');
 	var options = {
 		types: ['geocode'],
 		componentRestrictions: {
 			country: countryiso
 		}
 	};
 	var autocomplete = new google.maps.places.Autocomplete(input, options);
 	/* droppoints section */
 	var listing = '';
 	var points = [];
 	/* var allagents = []; */
 	var mapicon_arry = [];
 	var agentname_arry = [];
 	var ship_type_arry = [];
 	/* allagents = $("#allagents").html(); */
 	if (allagents.length > 0) {
 		var parseagents = JSON.parse(allagents);
 		$(parseagents).each(function(index, agent) {
 			var agentname = agent.name;
 			var filterIcon = agent.logo_filter;
 			var mapicon = agent.logo_pin;
 			var ship_type = agent.id;
 			ship_type_arry.push(ship_type);
 			mapicon_arry.push(mapicon);
 			agentname_arry.push(agentname);
 		});
 	}
 	/* droppoints section */
 	autocomplete.addListener('place_changed', function() {
 		loadimage();
 		var tempcall = 0;
 		var place = autocomplete.getPlace();
 		var postcode = place.address_components;
 		if (typeof(postcode) != "undefined") {
 			$(postcode).each(function(index, findcode) {
 				$(findcode.types).each(function(indexis, codeis) {
 					if (codeis == 'postal_code') {
 						var postcode = findcode.long_name;
 					}
 				});
 			});
 		}
 		var numeric = 0;
 		if ($.isNumeric(postcode)) {
 			numeric = 1;
 		}
 		if (windoWidth > 767) var selected = $("#postagain").val();
 		else var selected = $("#postagainis").val();
 		var arr = selected.split(',');
 		var countryis = countryiso;
 		if (!place.place_id) {
 			return;
 		}
 		var geocoder = new google.maps.Geocoder();
 		geocoder.geocode({
 			'placeId': place.place_id
 		}, function(results, status) {
 			if (status == 'OK') {
 				$("#Dao365").html('');
 				$("#Bring").html('');
 				$("#PostNord").html('');
 				$("#GLS").html('');
 				$("#UPS").html('');
 				$("#DHL").html('');
 				var locationis = results[0].geometry.location;
 				var lat = locationis.lat().toString().substr(0, 12);
 				var lng = locationis.lng().toString().substr(0, 12);
 				overviewMarkers = [];
 				var latlng = new google.maps.LatLng(lat, lng);
 				custmarker = new google.maps.Marker({
 					position: latlng,
 					icon: '',
 					zIndex: 1,
 					title: $("#billing_first_name").val()
 				});
 				overviewMarkers.push(custmarker);
 				if (numeric === 0) {
 					$.get("https://maps.googleapis.com/maps/api/geocode/json?key=" + google_api_key + "&latlng=" + lat + "," + lng + "&sensor=true", function(data, status) {
 						var postdata = data.results[0].address_components;
 						if (typeof(postdata) != "undefined") {
 							$(postdata).each(function(index, findcode) {
 								$(findcode.types).each(function(indexis, codeis) {
 									if (codeis == 'postal_code') {
 										var postcode = findcode.long_name;
 										if (typeof(postcode) != "undefined") {
 											var data = {
 												'action': 'mc360_get_shop_list',
 												'agent': ship_type_arry.toString(),
 												'zipcode': postcode,
 												'country': countryis,
 												'lat': lat,
 												'lng': lng,
 											};
 											jQuery.post(ajax_url, data, function(response) {
 												/* embedd dropoints */
 												var returned = JSON.parse(response);
 												var alldroppoints = returned.droppoints;
 												for (var carriers in alldroppoints) {
 													var carrierdroppoints = alldroppoints[carriers];
 													var carrierid = carrierdroppoints.id;
 													var carriername = carrierdroppoints.name;
 													var len = ship_type_arry.length;
 													if (jQuery.inArray(carrierid, ship_type_arry) != '1') {
 														var carriersid = carrierid;
 														var mapiconis = 'https://mycommerce360.dk/img/map' + carriersid + '.png';
 													} else {
 														var carriersid = '';
 														var mapiconis = '';
 													}
 													if (jQuery.inArray(carriername, agentname_arry) != '-1') {
 														var carriersname = carriername;
 													} else var carriersname = '';
 													var droppoints = carrierdroppoints.droppoints;
 													/* getPrice of carrier */
 													var price = $("#" + carriersname + "price").text();
 													var priceis = parseFloat(price).toFixed(2);
 													var curr = $("#currencysymbol").text();
 													if (isNaN(priceis)) priceis = 0;
 													/* getPrice of carrier */
 													for (var i in droppoints) {
 														var points1 = [];
 														var address = droppoints[i].address;
 														var city = droppoints[i].city;
 														var country_code = droppoints[i].country_code;
 														var latitude = droppoints[i].latitude;
 														var longitude = droppoints[i].longitude;
 														var company_name = droppoints[i].company_name;
 														var number = droppoints[i].number;
 														var zip_code = droppoints[i].zip_code;
 														var finalship = "mc360_shipping_" + carriersname.toLowerCase();
 														/* 	0	 */
 														points1.push(city);
 														/* 	1	 */
 														points1.push(country_code);
 														/* 	2	 */
 														points1.push(latitude);
 														/* 	3	 */
 														points1.push(longitude);
 														/* 	4	 */
 														points1.push('Overview');
 														/* 	5	 */
 														points1.push(1);
 														/* 	6	 */
 														points1.push(company_name);
 														/* 	7	 */
 														points1.push(address);
 														/* 	8	 */
 														points1.push(number);
 														/* 	9	 */
 														points1.push(carriersname);
 														/* 	10	 */
 														points1.push(carriersid);
 														/* 	11	 */
 														points1.push(mapiconis);
 														/* 	12	 */
 														points1.push(zip_code);
 														/* 	13	 */
 														points1.push(finalship);
 														/* 	14	 */
 														points1.push(priceis + ' ' + curr);
 														points.push(points1);
 														var datatostore = JSON.stringify(points1);
 														if (carriersname == 'Dao365') $("#Dao365").append("<span>" + datatostore + "</span>");
 														if (carriersname == 'Bring') $("#Bring").append("<span>" + datatostore + "</span>");
 														if (carriersname == 'PostNord') $("#PostNord").append("<span>" + datatostore + "</span>");
 														if (carriersname == 'GLS') $("#GLS").append("<span>" + datatostore + "</span>");
 														if (carriersname == 'UPS') $("#UPS").append("<span>" + datatostore + "</span>");
 														if (carriersname == 'DHL') $("#DHL").append("<span>" + datatostore + "</span>");
 													}
 													tempcall++;
 												}
 												if (len == tempcall) {
 													callagent();
 												}
 												/* embedd dropoints */
 											});
 										}
 									}
 								});
 							});
 						}
 					});
 				}
 				if (numeric == 1) {
 					var data = {
 						'action': 'mc360_get_shop_list',
 						'agent': ship_type_arry.toString(),
 						'zipcode': postcode,
 						'country': countryis,
 						'lat': lat,
 						'lng': lng,
 					};
 					jQuery.post(ajax_url, data, function(response) {
 						/* embedd dropoints */
 						var returned = JSON.parse(response);
 						var alldroppoints = returned.droppoints;
 						for (var carriers in alldroppoints) {
 							var carrierdroppoints = alldroppoints[carriers];
 							var carrierid = carrierdroppoints.id;
 							var carriername = carrierdroppoints.name;
 							var len = ship_type_arry.length;
 							if (jQuery.inArray(carrierid, ship_type_arry) != '1') {
 								var carriersid = carrierid;
 								var mapiconis = 'https://mycommerce360.dk/img/map' + carriersid + '.png';
 							} else {
 								var carriersid = '';
 								var mapiconis = '';
 							}
 							if (jQuery.inArray(carriername, agentname_arry) != '-1') {
 								var carriersname = carriername;
 							} else var carriersname = '';
 							var droppoints = carrierdroppoints.droppoints;
 							/* getPrice of carrier */
 							var price = $("#" + carriersname + "price").text();
 							var priceis = parseFloat(price).toFixed(2);
 							var curr = $("#currencysymbol").text();
 							if (isNaN(priceis)) priceis = 0;
 							/* getPrice of carrier */
 							for (var i in droppoints) {
 								var points1 = [];
 								var address = droppoints[i].address;
 								var city = droppoints[i].city;
 								var country_code = droppoints[i].country_code;
 								var latitude = droppoints[i].latitude;
 								var longitude = droppoints[i].longitude;
 								var company_name = droppoints[i].company_name;
 								var number = droppoints[i].number;
 								var zip_code = droppoints[i].zip_code;
 								var finalship = "mc360_shipping_" + carriersname.toLowerCase();
 								/* 	0	 */
 								points1.push(city);
 								/* 	1	 */
 								points1.push(country_code);
 								/* 	2	 */
 								points1.push(latitude);
 								/* 	3	 */
 								points1.push(longitude);
 								/* 	4	 */
 								points1.push('Overview');
 								/* 	5	 */
 								points1.push(1);
 								/* 	6	 */
 								points1.push(company_name);
 								/* 	7	 */
 								points1.push(address);
 								/* 	8	 */
 								points1.push(number);
 								/* 	9	 */
 								points1.push(carriersname);
 								/* 	10	 */
 								points1.push(carriersid);
 								/* 	11	 */
 								points1.push(mapiconis);
 								/* 	12	 */
 								points1.push(zip_code);
 								/* 	13	 */
 								points1.push(finalship);
 								/* 	14	 */
 								points1.push(priceis + ' ' + curr);
 								points.push(points1);
 								var datatostore = JSON.stringify(points1);
 								if (carriersname == 'Dao365') $("#Dao365").append("<span>" + datatostore + "</span>");
 								if (carriersname == 'Bring') $("#Bring").append("<span>" + datatostore + "</span>");
 								if (carriersname == 'PostNord') $("#PostNord").append("<span>" + datatostore + "</span>");
 								if (carriersname == 'GLS') $("#GLS").append("<span>" + datatostore + "</span>");
 								if (carriersname == 'UPS') $("#UPS").append("<span>" + datatostore + "</span>");
 								if (carriersname == 'DHL') $("#DHL").append("<span>" + datatostore + "</span>");
 							}
 							tempcall++;
 						}
 						if (len == tempcall) {
 							callagent();
 						}
 						/* embedd dropoints */
 					});
 				}
 			}
 		});
 	});
 }

 function showagents(thiss) {
 	$(".check_bx_list").show();
 	$(".serch_bx").show();
 }

 function closethis() {
 	$(".check_bx_list").hide();
 	$(".serch_bx").hide();
 }

 function mobileview() {
 	if ($j(window).width() < 768) {
 		$j(".serch_bx.mobile").show();
 		$j(".serch_bx").hide();
 		fillinput();
 		if ($j(".maplist #showlisting").hasClass("activee")) {
 			showlisting($j(".maplist #showlisting"))
 		}
 		if ($j(".maplist #showmap").hasClass("activee")) {
 			showmap($j(".maplist #showmap"));
 		}
 		$j(".check_bx_list").hide();
 		var height = $j(window).height();
 		$j("#mc360-shipping #intwindow .location_list ul").css("height", height - 60);
 		$j("#mc360-shipping #popup1 .location_list ul").css("height", height - 60);
 	} else {
 		fillinput();
 		$j(".serch_bx.mobile").hide();
 		$j(".serch_bx").show();
 		if ($j(".maplist #showlisting").hasClass("activee")) {
 			$j("#map_canvas").hide();
 			$j("#droppoints").show();
 		}
 		if ($j(".maplist #showmap").hasClass("activee")) {
 			$j("#map_canvas").show();
 			$j("#droppoints").hide();
 		}
 		$j(".loader").hide();
 	}
 }

 function chooseit(thiss) {
 	$(thiss).parent();
 }

 
 
 $(window).load(function(){
	 
			var inputtype = "<input type='hidden' id='checktest' value='1' name='checktest'>";
			if($("#checktest").length<1)
				$(inputtype).insertAfter("#billing_email");
 }); 
 var currentRequest=null;
 function addField(that){
	 var data = { 	
		 'action': 'mc360_update_field', 
		 'mc360_shipping_pickup_adress': jQuery(that).val() 
	 };	
	 
	 currentRequest = jQuery.ajax({ 
		type: 'POST',     
		data: data,           
		url: ajax_url,        
		beforeSend : function()    {  
			if(currentRequest != null) {    
				currentRequest.abort();       
			}          
		},         
		success: function(data) {    
		}	
	}); 
 }
 function updateCarrier(carriername){
	
	var data = { 	
		'action': 'mc360_update_carriername', 
		'mc360_shipping': carriername 
	};	
	 
	
	jQuery.post(ajax_url, data, function(response) {	
		});
 }
 function selectship(that){
	 if(jQuery("#place_order_new").is(":visible")){
        $("#place_order").prop("disabled", false);
        $("#place_order").removeAttr("disabled");
        $("#place_order").show();
        $("#place_order_new").hide();
        $("#place_order_new").remove();
	 }
	 if(jQuery(that).val()==="flex"){
		 jQuery(".mc360_shipping_pickup_adress").show();
		 
	 }
	 else{
		jQuery(".mc360_shipping_pickup_adress").hide();
		jQuery(".mc360_shipping_pickup_adress").val('');
		
	 }
	  var data = { 	
		 'action': 'mc360_get_shipping', 
		 'ship':jQuery(that).val(), 
		};
		jQuery.post(ajax_url, data, function(response) {	
		}); 
		 
 }