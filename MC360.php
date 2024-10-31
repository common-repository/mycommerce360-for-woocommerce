<?php
/**
 * Plugin Name: MyCommerce360 - Intelligent Delivery Management System
 * Plugin URI: https://mycommerce360.dk
 * Description: MyCommerce360 integration with WooCommerce. Send packages with Postnord, GLS, Bring, DAO, DHL & UPS.
 * Version: 1.0.47
 * Text Domain: woocommerce-MC360
 * Author:  MyCommerce360
 * Author URI: https://www.mycommerce360.dk
 */
define('MC360_URL', plugins_url('', __FILE__));
define('MC360_DIR', dirname(__FILE__));
if (!function_exists('is_plugin_active_for_network')) {
    require_once (ABSPATH . '/wp-admin/includes/plugin.php');
}
include_once ('helpers/helpers.php');
if (is_admin()) include_once ('configurations/configurations.php');
if (mc360_verify_woocommerce_status()) {
    function mc360_load_translations() {
        $mofile = 'woocommerce-MC360' . '-' . get_locale() . '.mo';
        if (file_exists(dirname(__FILE__) . '/languages/' . $mofile)) {
            load_textdomain('woocommerce-MC360', dirname(__FILE__) . '/languages/' . $mofile);
            load_plugin_textdomain('woocommerce-MC360', false, plugin_dir_path(__FILE__) . '/languages/');
        }
    }
    function mc360_load_shipping_methods_init() {
        $api_key = get_option('apiusername');
        $secretkey = get_option('secretkey');
        if (defined('allagents')) $agents = json_decode(allagents, true);
        $params = array();
		require_once (dirname(__FILE__) . '/classes/mc360_shipping_pickup.php'); 
        if (!empty($api_key) && strlen($api_key) > 3 && !empty($secretkey) && strlen($secretkey) > 3) {
            if (mc360_get_woo_version_number() >= 2.6 && isset($agents) && !empty($agents)) {
                foreach ($agents as $agent) {
                    $agentname = $agent['name'];
                    $mapicon = $agent['logo_pin'];
                    $mapfilter = $agent['logo_filter'];
                    $classname = 'mc360_shipping_' . $agentname;
                    if (!class_exists($classname)) {
                        $var = "agent" . $agentname;
                        $var1 = "filter" . $agentname;
                        $var2 = "name" . $agentname;
                        define($var, $mapicon);
                        define($var1, $mapfilter);
                        define($var2, $agentname);   
                        if (file_exists(dirname(__FILE__) . '/classes/mc360_shipping_' . strtolower($agentname) . '.php')) {
							require_once (dirname(__FILE__) . '/classes/mc360_shipping_droppoint.php');
                            require_once (dirname(__FILE__) . '/classes/mc360_shipping_' . strtolower($agentname) . '.php');
                        }
                    }
                }
            }
        }
    }
    mc360_initialiser();
    function mc360_display_order_data_in_admin() {
        global $woocommerce, $order, $post;
        add_meta_box('mC360_other_fields', __('MC360 details', 'woocommerce'), 'mC360_add_other_fields_for_packaging', 'shop_order', 'side', 'core');
    }
    if (!function_exists('mC360_save_wc_order_other_fields')) {
        function mC360_add_other_fields_for_packaging() {
            global $woocommerce, $order, $post;
            $order_id = $post->ID;
            $trackvalue = get_post_meta($post->ID, 'trackingnumber', true);
            $meta_field_data = (($trackvalue != 0) ? $trackvalue : 0);
            $order = new WC_Order($order_id);
            $methods = $order->get_shipping_methods();
            $droppoint = get_post_meta($order_id, '_shipping_pickuppoint', true);
            $order_items = $order->get_items('shipping');
            if (isset($order_items) && !empty($order_items)) {
                foreach ($order_items as $order_item) {
                    $shipping_method_id = $order_item->get_method_id();
                }
            }
			 $order_items_products = $order->get_items('line_item');
			$product_quantity=0;
			foreach($order_items_products as $order_items_product) {
				 $product_quantity += $order_items_product->get_quantity();
			} 
			 $collis_calc=get_option('collis_calc');
            $default_collis = get_post_meta($order_id, 'default_collis', true);
			if(empty($default_collis)){
				if($collis_calc=="0")
					$default_collis=1;
				elseif($collis_calc=="1")
					$default_collis=count($order->get_items());
				elseif($collis_calc=="2"){
					 $default_collis=$product_quantity;
				}
			}
			elseif(!empty($default_collis)){
				 $default_collis=$default_collis;
			}
			elseif(empty($collis_calc)){
				$default_collis=1;
			}						if($collis_calc=="0")				$colqty=1;			elseif($collis_calc=="1")				$colqty=count($order->get_items());			elseif($collis_calc=="2"){				 $colqty=$product_quantity;			}
            if (defined('allagents')) $agentsis = json_decode(allagents, true);
            $i = 1;
            $bool = false;
            foreach ($agentsis as $agent) {
                $carrier = "carrier_" . $i;
                $mc360_carr = get_option($carrier, true);
                if (isset($shipping_method_id) && !empty($shipping_method_id) && ($shipping_method_id === $mc360_carr)) $bool = true;
                $i++;
            }
            $public_carrier = get_option('public_carrier');
            if (isset($shipping_method_id) && !empty($shipping_method_id) && ($shipping_method_id === $public_carrier)) $bool = true;
            /* if($bool==true){ */
            echo '<div class="table" onload="mc360_sendorder(' . $order_id . ')">';
            $shipping_method = get_post_meta($order_id, '_shipping_carriername', true);
            $shippmentid = get_post_meta($order_id, 'shippment_id', true);
            $shipping_textdata = get_post_meta($order_id, '_shipping_textdata', true);
			$_shipping_carriername_pick = get_post_meta($order_id, '_shipping_carriername_pick', true);
			if(isset($_shipping_carriername_pick) && !empty($_shipping_carriername_pick))
				$shipping_method=$_shipping_carriername_pick;
            if (isset($shippmentid) && !empty($shippmentid)) $_SESSION['errormsg' . $order_id] = '';
         
                echo '<div class="mc360-row">';
                if (isset($_SESSION['errormsg' . $order_id]) && !empty($_SESSION['errormsg' . $order_id])) $message = $_SESSION['errormsg' . $order_id];
                else $message = '';
                if (isset($_SESSION['errormsg' . $order_id]) && !empty($_SESSION['errormsg' . $order_id])) {
                    $display = 'block';
                    $display1 = 'none';
                } else {
                    $display = 'none';
                    $display1 = 'none';
                }
                echo '<div style="display:' . $display . ';" id="mc360-message" class="success ">Order has been imported.</div>';
                echo '<div style="display:' . $display1 . ';"  class="mc360-messageerror alert-danger">' . $message . '</div>';
                echo '<label>Carrier</label><span>' . $shipping_method . '</span></div>';
                echo '<div class="mc360-row"><label>Pickpoint ID</label> <span>' . $droppoint . '</span></div>';
                echo '<div class="mc360-row"><label> MC360 Shipment ID</label> <span id="mc360_shipId">' . $shippmentid . '</span></div>';
					if(!empty($shippmentid)){					if(!empty($default_collis) && $default_collis >1){						$defaultselected='selected="selected"';						$manualselected='';											}					else{						$defaultselected='';						$manualselected='selected="selected"';					}					if($colqty>1){						echo '<div class="mc360-row"><div><label>Collis Value</label></div><p>						<select id="default_collis"  name="default_collis" style="width:250px;"   onchange="changeCollis(' . $post->ID . ');" disabled="disabled">							<option value="1" '.$manualselected.'>1</option>							<option value="' . $colqty . '" '.$defaultselected.'">'.$colqty.'</option>						</select>												</p></div>';					}					else{								echo '<div class="mc360-row"><div><label>Collis Value</label></div><p>						<select id="default_collis"  name="default_collis" style="width:250px;"   onchange="changeCollis(' . $post->ID . ');" disabled="disabled">							<option value="1" '.$manualselected.'>1</option>						</select>												</p></div>';					}				}else{					if(!empty($default_collis) && $default_collis >1){						$defaultselected='selected="selected"';						$manualselected='';											}					else{						$defaultselected='';						$manualselected='selected="selected"';					}					if($colqty>1){						echo '<div class="mc360-row"><div><label>Collis Value</label></div><p><select id="default_collis"  name="default_collis" style="width:250px;"   onchange="changeCollis(' . $post->ID . ');">							<option value="1" '.$manualselected.'>1</option>							<option value="' . $colqty . '" '.$defaultselected.'">'.$colqty.'</option>						</select></p><p id="showmessage" style="display:none;" class="success">Updated Successfully.</p><p id="showmessageerror" style="display:none;" class="mc360-messageerror alert-danger">Manual Collis should be 1.</p></div>';												}else{						echo '<div class="mc360-row"><div><label>Collis Value</label></div><p><select id="default_collis"  name="default_collis" style="width:250px;"   onchange="changeCollis(' . $post->ID . ');">							<option value="1" '.$manualselected.'>1</option>						</select></p><p id="showmessage" style="display:none;" class="success">Updated Successfully.</p><p id="showmessageerror" style="display:none;" class="mc360-messageerror alert-danger">Manual Collis should be 1.</p></div>';					}				}
        if(!empty($shipping_textdata))
				echo '<div class="mc360-row"><label>Carrier text</label><span>'.$shipping_textdata.'</span></div>';
            echo '<div class="mc360-row"><input type="hidden" name="mC360_other_meta_field_nonce" value="' . wp_create_nonce() . '">					<div><label>Track&Trace</label></div>					<p>						<input type="text" readonly="readonly" style="width:250px;"  id="trackingnumber"  name="trackingnumber" placeholder="' . $meta_field_data . '" value="' . $meta_field_data . '"></p>';
			
			
            echo '<p>Track&Trace will show above once the order has been printed on MyCommerce360</p></div></div>';
            echo '<button type="button" id="orderidmc360" orderid="' . $post->ID . '" onclick="mc360_sendorder(' . $post->ID . ');" class="button alt">Import order</button>'; /*   }	 */
        }
    }
    add_action('add_meta_boxes', 'mc360_display_order_data_in_admin');
}
