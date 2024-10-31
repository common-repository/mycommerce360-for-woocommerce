<?php
ob_start();
if (!defined('ABSPATH')) {
	exit;
	/* Exit if accessed directly */
}

/*************/
/* Functions */
/*************/

function mc360_verify_woocommerce_status()
{
	$mc360getAgents = mc360_getAgents();
	return (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || is_plugin_active('woocommerce/woocommerce.php') || is_plugin_active_for_network('woocommerce/woocommerce.php') || is_plugin_active('__woocommerce/woocommerce.php') || is_plugin_active_for_network('__woocommerce/woocommerce.php'));
}

function mc360_initialiser()
{
	mc360_init_addAction();
	mc360_init_addFilter();
}

function mc360_init_addAction()
{
	add_action('init', 'mc360_load_shipping_methods_init', 1);
	add_action('init', 'mc360_load_translations', 1);
	add_action('wp_enqueue_scripts', 'mc360_enqueueScripts', 99);
	add_action('wp_enqueue_scripts', 'mc360_enqueueStyles');
	add_action('admin_notices', 'mc360_no_frontend_key_admin_notice');
	add_action('admin_notices', 'mc360_no_google_api_key_admin_notice');
	add_action('admin_enqueue_scripts', 'mc360__selectively_enqueue_admin_script');
	add_action('wp_enqueue_scripts', 'mc360_add_inline_scripts');
}

function mc360__selectively_enqueue_admin_script($hook)
{
	$params = array(
		'ajax_url' => admin_url('admin-ajax.php') ,
		'plugin_root' => MC360_URL,
		'selected_shop_header' => __('Currently choosen pickup point:', 'woocommerce-mc360') ,
		'error_message_zipcode' => __('The zipcode must be 4 numbers long, and numeric - please try again', 'woocommerce-mc360') ,
		'error_no_cords_found' => __('* Couldnt mark this pickup point on the map', 'woocommerce-mc360') ,
		'isadmin' => is_admin()
	);
	wp_enqueue_style('my-admin-theme', MC360_URL . '/assets/css/mc360-admin.css', array() , '1.0');
	wp_enqueue_script('my_custom_script', MC360_URL . '/assets/js/admin.js', array() , '1.0');
	wp_localize_script('my_custom_script', 'mc360Params', $params);
}

function mc360_init_addFilter()
{
}

function mc360_checkout_process_shipping()
{
	global $woocommerce;
	$choosen_shipping_method1 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods[0]);
	$choosen_shipping_method2 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods);
	if ($choosen_shipping_method1 == "mc360_shipping_gls" || $choosen_shipping_method2 == "mc360_shipping_gls" || $choosen_shipping_method1 == "mc360_shipping_postnord" || $choosen_shipping_method2 == "mc360_shipping_postnord" || $choosen_shipping_method1 == "mc360_shipping_dao" || $choosen_shipping_method2 == "mc360_shipping_dao" || $choosen_shipping_method2 == "mc360_shipping_bring" || $choosen_shipping_method2 == "mc360_shipping_bring" || $choosen_shipping_method == "mc360_shipping_dhl") {
		if (empty($_POST["mc360"])) {
			wc_add_notice(__('Please select a pickup point before placing your order.', 'woocommerce-mc360') , 'error');
		}
	}
}

function mc360_no_frontend_key_admin_notice()
{
	$html = '';
	$apiusername = get_option('apiusername', true);
	$secretkey = get_option('secretkey', true);
	$importusername = get_option('importusername', true);
	if ((empty($apiusername) || strlen($apiusername) < 1) || (empty($secretkey) || strlen($secretkey) < 1) || (empty($importusername) || strlen($importusername) < 1)) {
		$html.= '<div class="notice notice-error is-dismissible"><p>';
		$mc360_admin_url = '<a href="' . admin_url() . '/admin.php?page=mc360">mc360.dk Settings</a>';
		$html.= printf(esc_html__('Please go to the WooCommerce -> %s, and set a valid frontend key', 'woocommerce-mc360') , $mc360_admin_url);
		$html.= '</p></div>';
	}
}

function mc360_no_google_api_key_admin_notice()
{
	$html = '';
	$google_api_key = get_option('google_api_key', true);
	if (empty($google_api_key) || strlen($google_api_key) < 5) {
		$html.= '<div class="notice notice-error is-dismissible"><p>';
		$mc360_admin_url = '<a href="' . admin_url() . '/admin.php?page=mc360">mc360.dk Settings</a>';
		$html.= printf(esc_html__('Please go to the WooCommerce -> %s, and set a valid Google Map API key', 'woocommerce-mc360') , $mc360_admin_url);
		$html.= '</p></div>';
	}
}

function mc360_enqueueScripts()
{
	if (is_checkout() || is_cart()) {
		$pop_up_status = get_option('pop_up_status');
		$mc360Company = WC()->session->get('mc360-company');
		if (defined('allagents')) {
			$params = array(
				'ajax_url' => admin_url('admin-ajax.php') ,
				'plugin_root' => MC360_URL,
				'selected_shop_header' => __('Currently choosen pickup point:', 'woocommerce-MC360') ,
				'error_message_zipcode' => __('The zipcode must be 4 numbers long, and numeric - please try again', 'woocommerce-MC360') ,
				'error_no_cords_found' => __('* Couldnt mark this pickup point on the map', 'woocommerce-MC360') ,
				'is_user_logged_in' => is_user_logged_in() ,
				'select_droppoint' => __('Select droppoint', 'woocommerce-MC360') ,
				'you_have_Selected' => __('You have selected', 'woocommerce-MC360') ,
				'pricetrans' => __('Price', 'woocommerce-MC360') ,
				'selected' => __('Selected', 'woocommerce-MC360') ,
				'pop_up_status' => $pop_up_status,
				'google_api_key' => get_option('google_api_key', true) ,
				'mc360Company' => "$mc360Company",
				'allagents' => allagents,
				'choose_pickup_point_btn' => __('Choose pickup point', 'woocommerce-MC360') ,
				'place_order_btn' => __('Place Order', 'woocommerce-MC360') ,
			);
			wp_enqueue_script('mc360_script', MC360_URL . '/assets/js/main.js', array(
				'jquery'
			) , filemtime(MC360_DIR . '/assets/js/main.js'));
			wp_enqueue_script('gmaps', 'https://maps.googleapis.com/maps/api/js?key=' . get_option('google_api_key', true) . '&libraries=places', array(
				'jquery'
			) , null, false);
			wp_localize_script('mc360_script', 'mc360Params', $params);
		}
	}
}

/**
 * Filters just the chat-script
 */
add_filter('script_loader_tag', 'mc360_regal_tag', 10, 3);

function mc360_regal_tag($tag, $handle, $src)
{
	if ($handle !== 'gmaps') {
		return $tag;
	}

	return "<script src='$src' async defer></script>";
}

function mc360_enqueueStyles()
{
	if (is_checkout() || is_cart()) {
		wp_enqueue_style('woocommerce-mc360', MC360_URL . '/assets/css/map.css', array() , filemtime(MC360_DIR . '/assets/css/map.css'));
		wp_enqueue_style('woocommerce-mc360-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	}
}

function mc360_get_woo_version_number()
{
	if (!function_exists('get_plugins')) {
		require_once (ABSPATH . 'wp-admin/includes/plugin.php');

	}

	$plugin_folder = get_plugins('/' . 'woocommerce');
	$plugin_file = 'woocommerce.php';
	if (isset($plugin_folder[$plugin_file]['Version'])) {
		return $plugin_folder[$plugin_file]['Version'];
	}
	else {
		return NULL;
	}
}

function mc360_getAgents()
{
	$totalagents = get_option('_totalagents');
	$apiagents = get_option('_apiagents');
	if ((!empty($totalagents)) && (!is_admin())) {
		if (!defined('allagents')) define('allagents', json_encode($totalagents));
	}
	else
	if ((!empty($apiagents)) && (is_admin())) {
		if (!defined('allagents')) define('allagents', json_encode($apiagents));
	}
	else {
		$api_key = get_option('apiusername');
		$secretkey = get_option('secretkey');
		$result = wp_remote_get("https://mycommerce360.dk/api/shipping-agent/get", array(
			'timeout' => 10,
			'sslverify' => false,
			'headers' => array(
				'MyCommerce360-API-Username' => $api_key,
				'MyCommerce360-API-Secret' => $secretkey
			)
		));
		if (!is_wp_error($result) && isset($result['body']) && !empty($result['body'])) {
			$array = json_decode($result['body'], true);
			$apiagents = $agents = $array['data']['agents'];
			if (!is_admin()) {
				$totalagents = [];
				$i = 1;
				foreach($apiagents as $key => $apiagent) {
					$mc360_agent = 'carrier_' . $i;
					$selectedcarrier = get_option($mc360_agent);
					if (!empty($selectedcarrier)) $totalagents[] = $apiagent;
					$i++;
				}

				update_option('_totalagents', $totalagents);
			}
			else {
				update_option('_apiagents', $apiagents);
			}

			$totalagents = get_option('_totalagents', true);
			$apiagents = get_option('_apiagents', true);
			if ($totalagents && (!is_admin())) {
				if (!defined('allagents')) define('allagents', json_encode($totalagents));
			}
			else
			if (($apiagents) && (is_admin())) {
				if (!defined('allagents')) define('allagents', json_encode($apiagents));
			}
		}
		else return '';
	}
}

function mc360_createShippment($data_string = '')
{
	$api_key = get_option('apiusername');
	$secretkey = get_option('secretkey');
	$url = "https://mycommerce360.dk/api/shipment/create";
	$response = wp_remote_post($url, array(
		'method' => 'POST',
		'timeout' => 45,
		'sslverify' => false,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(
			'MyCommerce360-API-Username' => $api_key,
			'MyCommerce360-API-Secret' => $secretkey
		) ,
		'body' => $data_string,
		'cookies' => array()
	));
	$array = json_decode($response['body'], true);
	return $array;
}

function mc360_gettrackingnumber($data_string = '', $orderid)
{
	$api_key = get_option('apiusername');
	$secretkey = get_option('secretkey');
	$url = "https://mycommerce360.dk/api/shipment/get";
	$response = wp_remote_post($url, array(
		'method' => 'POST',
		'timeout' => 45,
		'sslverify' => false,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(
			'MyCommerce360-API-Username' => $api_key,
			'MyCommerce360-API-Secret' => $secretkey
		) ,
		'body' => $data_string,
		'cookies' => array()
	));
	$array = json_decode($response['body'], true);
	$status = $array['data']['status'];
	if ($status == "PRINTED") {
		$track_number = $array['data']['shipment_number'];
		update_post_meta($orderid, 'trackingnumber', $track_number);
		if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {
			if (!empty($track_number)) echo $track_number;
			die;
		}
	}
	else {
		if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {
			echo "hide";
			die;
		}
	}

	return true;
}

function mc360_gettrackingnumberError()
{
?>



		<div class="error notice">



			<p><?php
	_e('Order has been already imported', 'woocommerce-MC360');
?></p>



		</div>



	<?php
}

function mc360_order_status_change($order_id){	$resultAjax = array();	if (empty($order_id)) $order_id = sanitize_text_field($_POST['order']);	$shippmentid = get_post_meta($order_id, 'shippment_id', true);	$importusername = get_option('importusername', true);	if (!empty($shippmentid)) {		$trackingdata = '{			"id":' . $shippmentid . ',			"api_username": "' . $importusername . '"		}';		/* $imported=mc360_gettrackingnumber($trackingdata, $order_id);  */		if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {			$resultAjax['warning'] = "order has been already imported.";			echo json_encode($resultAjax);			wp_die();		}	}	else {		$order = new WC_Order($order_id);		$order_status = get_option('order_status_new', true);		$current_status = $order->get_status();		if (($order_status == "wc-" . $current_status) || (isset($_POST['order']) && !empty($_POST['order']))) {			$order_items = $order->get_items('shipping');			$shipping = $order->get_address('shipping');			if (!empty($shipping)) {				$shipping_country = $shipping['country'];				$shipping_company = $shipping['company'];				$shipping_first_name = $shipping['first_name'];				$shipping_last_name = $shipping['last_name'];				$shipping_address_1 = $shipping['address_1'];				$shipping_address_2 = '';				$shipping_address_2 = $shipping['address_2'];				$shipping_state = $shipping['state'];				$shipping_postcode = $shipping['postcode'];				$shipping_city = $shipping['city'];			}			else {				$shipping_company = $order->get_billing_company();				$shipping_first_name = $order->get_billing_first_name();				$shipping_last_name = $order->get_billing_last_name();				$shipping_address_1 = $order->get_billing_address_1();  				$shipping_address_2 = '';				$shipping_address_2  = $order->get_billing_address_2();/* */				$shipping_state = $order->get_billing_state();				$shipping_postcode = $order->get_billing_postcode();				$shipping_city = $order->get_billing_city();				$billing_phone = $order->get_billing_phone();				$shipping_country = $order->get_billing_country();			}			/* Bliing Variables */			$billing_company = $order->get_billing_company();			$billing_first_name = $order->get_billing_first_name();			$billing_last_name = $order->get_billing_last_name();			$billing_address_1 = $order->get_billing_address_1();			$billing_address_2 = '';			$billing_address_2  = $order->get_billing_address_2(); 			$billing_state = $order->get_billing_state();			$billing_postcode = $order->get_billing_postcode();			$billing_city = $order->get_billing_city();			$billing_phone = $order->get_billing_phone();			$billing_email = $order->get_billing_email();			$billing_country = $order->get_billing_country();			/* Bliing Variables */			/* Shipping Variables */			$order_items_products = $order->get_items('line_item');			$height = 0;			$weight = 0;			$length = 0;			$width = 0;			$totalcollis=0;			$product_quantityis=0;			foreach($order_items_products as $order_items_product) {				$product_id = $order_items_product->get_product_id();				$product_quantity = $order_items_product->get_quantity();				$product_quantityis +=$qty[]= $order_items_product->get_quantity();				/*  */				$_product = wc_get_product($product_id);				$weigh[]=$weightis = $_product->get_weight() * $product_quantity;				$weight+= $weightis;				$lengthh[]=$lengthis = $_product->get_length() * $product_quantity;				$length+= $lengthis;				$widthh[]=$widthis = $_product->get_width() * $product_quantity;				$width+= $widthis;				$heighh[]=$heightis = $_product->get_height() * $product_quantity;				$height+= $heightis;				$totalcollis++;			}			$weight_unit = get_option('woocommerce_weight_unit', true);			/* Shipping Variables */			/* Carrier Details */			global $wpdb;			$query = 'select `order_item_name` from ' . $wpdb->prefix . 'woocommerce_order_items where order_id=' . $order_id . ' and order_item_type="shipping"';			$shipping_methods = $wpdb->get_row($query, OBJECT);			$shipping_method = get_post_meta($order_id, '_shipping_carriername', true);			if (empty($shipping_method)) {				foreach($order_items as $order_item) {					$shipping_method = $order_item->get_name();				}			}				$shipping_textdata = get_post_meta($order_id, '_shipping_textdata', true);					$details='';				if(isset($shipping_textdata) && !empty($shipping_textdata)){					$details='"active_services_json":{									"PreferredLocation":{"details":"'.$shipping_textdata.'"}							},';				}				$_shipping_carriername_pick = get_post_meta($order_id, '_shipping_carriername_pick', true);				if(isset($_shipping_carriername_pick) && !empty($_shipping_carriername_pick)){					$shipping_method=$_shipping_carriername_pick;				}			/* Carrier Details */			$droppoint = get_post_meta($order_id, '_shipping_pickuppoint', true);			/* API Call */			 $json_format = '{    			"customer":{       			"company_name":"' . $billing_company . '",   			"name":"' . $billing_first_name . '  ' . $billing_last_name . '",   			"address_1":"' . $billing_address_1 . '",				"address_2":"' . $billing_address_2 . '",				"state":"' . $billing_state . '",				"post_code":"' . $billing_postcode . '",				"city":"' . $billing_city . '",					"country_code":"' . $billing_country . '",				"phone":"' . $billing_phone . '",				"email":"' . $billing_email . '"    },  			"delivery":{   			"company_name":"' . $shipping_company . '",    			"name":"' . $shipping_first_name . '  ' . $shipping_last_name . '", 			"address_1":"' . $shipping_address_1 . '",				"address_2":"' . $shipping_address_2 . '",				"state":"' . $shipping_state . '",				"post_code":"' . $shipping_postcode . '",				"city":"' . $shipping_city . '",				"country_code":"' . $shipping_country . '",					"phone":"' . $billing_phone . '",					"email":"' . $billing_email . '"    },   			"sender":{       			"name":"",      			"address_1":"",        			"address_2":"",   			"state":"",    			"post_code":"", 			"city":"",      			"country_code":"", 			"phone":"",      			"email":""    },  			"return_to":{   			"company_name":"",    			"name":"",    			"address_1":"",  			"address_2":"",      			"state":"",  			"post_code":"", 			"city":"",     			"country_code":"",   			"phone":"",      			"email":""    },  			"shipping_method":"' . $shipping_method . '",  			"reference":"' . $order_id . '",  			"droppoint_no": "' . $droppoint . '", 			"api_username": "' . $importusername . '",    			"weight_type": "' . $weight_unit . '",			"insurance_amount": "",   			"insurance_content": "",  			'.$details.'"colli":[ ';			$colli = get_post_meta($order_id, 'default_collis', true);			$collis_calc=get_option('collis_calc');			if(empty($colli)){				if($collis_calc=="0")					$colli=1;				elseif($collis_calc=="1")					$colli=$totalcollis;				elseif($collis_calc=="2")					$colli=$product_quantityis;			}			elseif(!empty($colli)){				 $colli=$colli;			}			elseif(empty($collis_calc)){				$colli=1;			}			$k=0;		if($collis_calc=="2"){				for($j=0;$j<count($qty);$j++){					for($i=0;$i<$qty[$j];$i++){						if(array_sum($qty)-1==$k){							$json_format .='						   { 							"weight":"' . $weigh[$j] . '",   							"height":"' . $heighh[$j] . '",    							"length":"' . $lengthh[$j] . '",  							"width":"' . $widthh[$j] . '" 							}   						';						}else{							$json_format .='							   { 								"weight":"' . $weigh[$j] . '",   								"height":"' . $heighh[$j] . '",    								"length":"' . $lengthh[$j] . '",  								"width":"' . $widthh[$j] . '" 								},   							';												}						$k++;					}				}							} 			
	
            if ($collis_calc == "0")
            {
				 $json_format .= '			

				 { 					
				 "weight":"' . array_sum($weigh) . '", 
				 "height":"' . array_sum($heighh) . '",    
				 "length":"' . array_sum($lengthh) . '",  		
				 "width":"' . array_sum($widthh) . '" 			
				 }   					';
                
            }
if($collis_calc=="1"){				for($i=0;$i<count($weigh);$i++){					if(count($weigh)-1==$i){						$json_format .='					   { 						"weight":"' . $weigh[$i] . '",   						"height":"' . $heighh[$i] . '",    						"length":"' . $lengthh[$i] . '",  						"width":"' . $widthh[$i] . '" 						}   					';					}else{						$json_format .='						   { 							"weight":"' . $weigh[$i] . '",   							"height":"' . $heighh[$i] . '",    							"length":"' . $lengthh[$i] . '",  							"width":"' . $widthh[$i] . '" 							},   						';										}				}							}			/* if($collis_calc=="2"){				for($i=0;$i<$product_quantityis;$i++){					if($product_quantityis==$i){						$json_format .='					   { 						"weight":"' . $weigh[$i] . '",   						"height":"' . $heighh[$i] . '",    						"length":"' . $lengthh[$i] . '",  						"width":"' . $widthh[$i] . '" 						}   					';					}else{						$json_format .='						   { 							"weight":"' . $weigh[$i] . '",   							"height":"' . $heighh[$i] . '",    							"length":"' . $lengthh[$i] . '",  							"width":"' . $widthh[$i] . '" 							},   						';										}				}							}else{				for($i=0;$i<$colli;$i++){					if($colli-1==$i){						$json_format .='					   { 						"weight":"' . $weight/$colli . '",   						"height":"' . $height/$colli . '",    						"length":"' . $length/$colli . '",  						"width":"' . $width/$colli . '" 						}   					';					}else{					$json_format .='					   { 						"weight":"' . $weight/$colli . '",   						"height":"' . $height/$colli . '",    						"length":"' . $length/$colli . '",  						"width":"' . $width/$colli . '" 						},   					';					}				}			} */			$json_format .='			]}';			/*echo $json_format;die; */ 			 			$response = mc360_createShippment($json_format);						if (!$response['error']) {				$shipid = $response['additional']['shipment']['id'];				$updated = update_post_meta($order_id, 'shippment_id', $shipid);				if ($updated) {					$_SESSION['message' . $order_id] = 'Order has been imported';				}				$resultAjax["mc360_shipId"] = $shipid;				unset($_SESSION['errormsg' . $order_id]);				/* if (isset($_POST['ajax']) && !empty($_POST['ajax']))				wp_die(); */			}			else {				$resultAjax["error"] = $response['message'];				$_SESSION['errormsg' . $order_id] = $response['message'];			}			if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {				echo json_encode($resultAjax);				wp_die();			}			/* if ($response) {			$shipid  = $response['additional']['shipment']['id'];			$updated = update_post_meta($order_id, 'shippment_id', $shipid);			if ($updated) {			$_SESSION['message' . $order_id] = 'Order has been imported';			echo true;			}			if (isset($_POST['ajax']) && !empty($_POST['ajax']))			die;			} */			return;			/* API Call */		}	} /*   } */	return;}

function mc360_checkintervaltrackingnumber()
{
	$orderid = sanitize_text_field($_POST['order']);
	$importusername = get_option('importusername', true);
	$shipid = get_post_meta($orderid, 'shippment_id', true);
	$trackingnumber = get_post_meta($orderid, 'trackingnumber', true);
	if (empty($trackingnumber) && !empty($shipid)) {
		$trackingdata = '{



			"id":' . $shipid . ',



			"api_username": "' . $importusername . '"



		}';
		$data = mc360_gettrackingnumber($trackingdata, $orderid);
	}
	else {
		unset($_SESSION['errormsg' . $order_id]);
		echo $trackingnumber;
		return;
	}

	return;
}

function mc360_register_my_session()
{ 
	if (!session_id()) {
		session_start();
	}
}

add_action('init', 'mc360_register_my_session');
/* add_action('woocommerce_order_status_pending', 'mc360_order_status_change');
add_action('woocommerce_order_status_failed', 'mc360_order_status_change');
add_action('woocommerce_order_status_on-hold', 'mc360_order_status_change');
add_action('woocommerce_order_status_processing', 'mc360_order_status_change');
add_action('woocommerce_order_status_completed', 'mc360_order_status_change');
add_action('woocommerce_order_status_refunded', 'mc360_order_status_change');
add_action('woocommerce_order_status_cancelled', 'mc360_order_status_change'); */
add_action('wp_ajax_mc360_sendorder', 'mc360_order_status_change');
add_action('wp_ajax_nopriv_mc360_sendorder', 'mc360_order_status_change');
add_action('wp_ajax_mc360_checktracking', 'mc360_checkintervaltrackingnumber');
add_action('wp_ajax_nopriv_mc360_checktracking', 'mc360_checkintervaltrackingnumber');
add_action('woocommerce_order_status_changed','mc360_order_status_change', 10, 3);

function mc360_add_inline_scripts()
{
	/* Script to load some required map data on load */
	if (is_checkout()) {
		wp_add_inline_script('jquery-migrate', '$j=jQuery;$j=jQuery.noConflict();



																	$j(document).ready(function(){



																		var bill_postcode=$j("#billing_postcode").val();



																		var mc360_postcode=$j("#mc360_postcode").val(bill_postcode);



																	});



																	$j(window).resize(function(){



																		mobileview();



																	});



																	');
	}

	/* Script to load some required map data on load */
}

function mc360_add_inline_scripts_for_popup()
{
	/* Script to load some required map data on load when popup is there */
	wp_add_inline_script('jquery-migrate', '$j=jQuery.noConflict(); $j(document).mouseup(function (e){



							  var container = $j(".popup");



							  if (!container.is(e.target) 



							   && container.has(e.target).length === 0) 



							  {



							   $j("#map_wrapper").hide();



							  }



							 });');
	/* Script to load some required map data on load when popup is there*/
}

function mc360PopUpDisplay()
{
	add_action('wp_enqueue_scripts', 'mc360_add_inline_scripts_for_popup');
}

function mc360_changeCollis(){
	$orderid=$_POST['order'];
	$default_collis=$_POST['default_collis'];
	if(update_post_meta($orderid, 'default_collis', $default_collis))
		echo true;
	wp_die();
}
add_action('wp_ajax_mc360_changeCollis', 'mc360_changeCollis');