<?php
class CommonFunctions

{
	public function __construct()

	{
	}	
	public function mc360_HTML_Text(){
		if (is_checkout()) {
			?>		
			<script>
			jQuery(document).ready(function(){
				console.log(jQuery("input[name='choose_carrier']:checked"));
				selectship(jQuery("input[name='choose_carrier']:checked"));
			});
			</script>
			<div id="mc360-shipping_text" class="mc360_checkout_wrapper">	
			<div class="pickupselection">
				<div class="mc360_radio">
					<label>
						<input type="radio" <?php if (!WC()->session->__isset('ship') || empty(WC()->session->__isset('ship')) || WC()->session->get('ship',0) =="normal") echo "checked='checked'";?>   name="choose_carrier"   onchange="selectship(this);" value="normal"/>
						<?php echo __('Choose normal delivery', 'woocommerce-MC360'); ?>
					</label>
					<label>
						<input type="radio" name="choose_carrier" <?php if (WC()->session->__isset('ship') && WC()->session->get('ship',0) =="flex") echo "checked='checked'";?>  onchange="selectship(this);"  value="flex"/>
						<?php echo __('Choose flex delivery', 'woocommerce-MC360'); ?>
					</label>
				</div>
				<input type="text" class="mc360_shipping_pickup_adress preety" required="required" <?php if (WC()->session->__isset('ship') && WC()->session->get('ship',0)=="flex") echo "style='display:block;'";?> onblur="addField(this);" placeholder="<?php echo __('Where can the carrier leave the package?', 'woocommerce-MC360'); ?>" name="mc360_shipping_pickup_adress" value="<?php if (WC()->session->__isset('ship') && WC()->session->get('ship',0)=="flex"  && WC()->session->__isset('mc360_shipping_pickup_adress') ) echo  WC()->session->get('mc360_shipping_pickup_adress', 0) ;  ?>"/>		
			</div>		
			</div>	
		<?php	}			
		else{			
			echo '<br/><div class="shipping_pickup_cart">' . __('Pickup point can be added during checkout', 'woocommerce-MC360') . '</div>';		
		}	
	}
	public function mc360_HTML_codeAndFindlocation($shipping_type, $mapicon, $filterIcon, $agentname, $price){
		ob_start();
		if (is_checkout()) {
?>
<div id="mc360-shipping" class="mc360_checkout_wrapper">
  <div class="pickupselection">
    <input type="hidden" name="mc360_postcode" id="mc360_postcode" value=""/>
    <button type="button"  class="button alt" onclick="getdroppoints('<?php
			echo $agentname;
?>', '<?php
			echo $filterIcon;
?>', '<?php
			echo $mapicon;
?>', '<?php
			echo $shipping_type;
?>', jQuery('#billing_postcode').val());">
      <span>
        <i class="fa fa-map-marker" aria-hidden="true">
        </i>
        <?php
			echo __('Select pickup point', 'woocommerce-MC360');
?>
      </span>
    </button>
    <?php
			$agents = json_decode(allagents, true);
			$public_carrier = get_option('public_carrier');
			$retrive_data = WC()->session->get( '_store_carr' );			
			$allagents = [];
			
			if (isset($public_carrier) && !empty($public_carrier) && $public_carrier == 'mc360_shipping_droppoint') {
				foreach($agents as $agent) {
					$agentname = $agent['name'];
					if(in_array( strtolower("mc360_shipping_".$agentname),$retrive_data)){
						$allagents[] = $agent;
					}
				}
			}
			else
				$allagents=$agents;
			
?>
    <div id="selectedHtml">
      <?php
			if (WC()->session->__isset('html')) echo WC()->session->get('html', 0);
?>
    </div>
   <div id="allagents" style="display:none;">	
      <?php
			echo json_encode($allagents);
?>
    </div>
    <div id="DHLprice" style="display:none;">
      <?php
			echo $price = mc360_getPrice('mc360_shipping_dhl');
?>
    </div>
    <div id="Bringprice" style="display:none;">
      <?php
			echo $price = mc360_getPrice('mc360_shipping_bring');
?>
    </div>
    <div id="GLSprice" style="display:none;">
      <?php
			echo $price = mc360_getPrice('mc360_shipping_gls');
?>
    </div>
    <div id="PostNordprice" style="display:none;">
      <?php
			echo $price = mc360_getPrice('mc360_shipping_postnord');
?>
    </div>
    <div id="Dao365price" style="display:none;">
      <?php
			echo $price = mc360_getPrice('mc360_shipping_dao365');
?>
    </div>
    <div id="UPSprice" style="display:none;">
      <?php
			echo $price = mc360_getPrice('mc360_shipping_ups');
?>
    </div>
    <div id="currencysymbol" style="display:none;">
      <?php
			echo get_woocommerce_currency_symbol();
?>
    </div>
  </div>
  <div id="map_wrapper" style="display:none;">
    <?php
			$display = get_option("pop_up_status");
?>	
    <div id="<?php
			echo ($display == 'popup') ? 'popup1' : 'intwindow';
?>" class="<?php
			echo ($display == 'popup') ? 'mc360-overlay' : 'windowdata';
?>">
      <div class="popup">
        <div class="map_sec">
          <h2>
            <?php
			echo __('Droppoint location', 'woocommerce-MC360');
?>
          </h2>
          <a  class="closepopup" href="javascript:;" onclick="closeit();">&times;
          </a>
          <div id="selectedagent" style="display:none;">
          </div>
          <div class="main_map_sec">
            <div class="map_bx">
              <div id="map_canvas" class="mapping" 
                   <?php
			echo ($display == 'popup') ? 'style="width:100%;height:505px;"' : 'style="width:100%;height:270px;"';
?>>
              <img src="<?php
			echo MC360_URL ?>/assets/img/loading.gif" style="margin: 45% auto;"/>
            </div>
            <div id="droppoints" class="location_list" style="display:none;">
              <ul>
              </ul>
            </div>
          </div>
          <div class="map_right_sec">
            <div class="top_btn">
              <div class="agentlisttt">
                <a onclick="showagents(this)" id="showagents"  	
                   <?php
			echo ($display == 'popup') ? 'style="display:none;"' : 'style="display:block;"';
?> href="javascript:;">
                <?php
			echo __('EDIT', 'woocommerce-MC360');
?>
                </a>
            </div>
            <div class="maplist">
              <a onclick="showmap(this)" id="showmap" class="activee" href="javascript:;">
                <?php
			echo __('MAP', 'woocommerce-MC360');
?>
              </a>
              <a onclick="showlisting(this)"   id="showlisting"  href="javascript:;">
                <?php
			echo __('LIST', 'woocommerce-MC360');
?>
              </a>
            </div>
          </div>
          <div class="serch_bx">
            <div class="srch_icn_input">
              <i class="fa fa-map-marker" aria-hidden="true">
              </i>
              <input type="text" id="postagain"  value="" name="postagain"  placeholder="<?php
			echo __('Enter a location', 'woocommerce-MC360');
?>"/>
              <a href="javascript:;" class="srch_icn" onclick="getdroppoints('<?php
			echo $agentname;
?>', '<?php
			echo $filterIcon;
?>', '<?php
			echo $mapicon;
?>', '<?php
			echo $shipping_type;
?>', jQuery('#postagain').val(),'search');">
                <i class="fa fa-search" aria-hidden="true">
                </i>
              </a>
            </div>
          </div>
          <div class="check_bx_list" 
               <?php
			echo ($display == 'popup') ? 'style="display:block;"' : 'style="display:none;"';
?>>
          <div id="Dao365" style="display:none;">
          </div>
          <div id="Bring" style="display:none;">
          </div>
          <div id="PostNord" style="display:none;">
          </div>
          <div id="GLS" style="display:none;">
          </div>
          <div id="UPS" style="display:none;">
          </div>
          <div id="DHL" style="display:none;">
          </div>
          <div class="tilpas" 
               <?php
			echo ($display == 'popup') ? 'style="display:block;"' : 'style="display:none;"';
?>>
          <div class="serch_bx mobile">
            <a  id="removetilpas" class="removetilpas" href="javascript:;" onclick="closethis();" 
               <?php
			echo ($display == 'popup') ? 'style="display:none;"' : 'style="display:block;"';
?>>&times;
            </a>
          <div class="srch_icn_input">
            <i class="fa fa-map-marker" aria-hidden="true">
            </i>
            <input type="text" id="postagainis"  value="" name="postagain" placeholder="<?php
			echo __('Enter a location', 'woocommerce-MC360');
?>"/>
            <a href="javascript:;" id="searchit" class="srch_icn" onclick="getdroppoints('<?php
			echo $agentname;
?>', '<?php
			echo $filterIcon;
?>', '<?php
			echo $mapicon;
?>', '<?php
			echo $shipping_type;
?>', jQuery('#postagain').val(),search');">
              <i class="fa fa-search" aria-hidden="true">
              </i>
            </a>
          </div>
        </div>
        <ul>
          <?php
			foreach($allagents as $agent) {
				$agentnameis = $agent['name'];
				$ship_type = $agent['id'];
				$mapicon = $agent['logo_pin'];
				$filterIcon = $agent['logo_filter'];
?>
          <li>
            <div class='chk_bx'>
              <input id="<?php
				echo $agentnameis . $ship_type;
?>" value="<?php
				echo $ship_type;
?>" map="<?php
				echo $mapicon;
?>" ship_type="<?php
				echo $ship_type;
?>" agentname="<?php
				echo $agentnameis;
?>"   type='checkbox' onchange="toggleGroup('<?php
				echo $agentnameis; ?>')" checked="checked" class='agentspop' name='agentspop'/> 
              <label for="<?php
				echo $agentnameis . $ship_type;
?>">
                <span>
                </span>
                <div class='imgg'>
                  <img src='<?php
				echo $filterIcon;
?>'/>
                </div>
                <div class='agentdata'>
                  <h3>
                    <?php
				echo $agentnameis;
?>
                  </h3>
                  <p>(
                    <?php
				echo (!empty(mc360_getPrice('mc360_shipping_' . strtolower($agentnameis))) ? mc360_getPrice('mc360_shipping_' . strtolower($agentnameis)) : 0) . ' ' . get_woocommerce_currency_symbol();
?>)
                  </p>
                </div>
              </label>
            </div>
          </li>
          <?php
			}
?>
        </ul>
      </div>
    </div>
    <div class="radio_list" style="display:none;">
      <h6>
        <?php
			echo __('CHOOSEN PICKUP POINT', 'woocommerce-MC360');
?>
      </h6>
      <div class="selectedloc" style="display:none;">
        <div class="removee">
          <a  class="removedata" href="javascript:;" onclick="removeit();">&times;
          </a>
        </div>
        <div class="datashow">
        </div>
      </div>
      <a href="javascript:;" style="display:none;" onclick="selectdata();" id="continue" class="button alt">
        <?php
			echo __('CONTINUE', 'woocommerce-MC360');
?>
      </a>
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>
<?php
			if ($display == 'popup') mc360PopUpDisplay();
		}
		else {
			echo '<br/><div class="shipping_pickup_cart">' . __('Pickup point can be selected during checkout', 'woocommerce-MC360') . '</div>';
		}
		$content = ob_get_clean();
		echo $content;
	}
}
function mc360_get_shop_list_callback()
{
	$method = 'GET';
	$url = 'https://mycommerce360.dk/api/drop-points';
	$api_key = get_option('apiusername', true);
	$secret_key = get_option('secretkey', true);
	$country = (!empty($_POST['country']) ? sanitize_text_field($_POST['country']) : 'DK');
	$state = (!empty($_POST['state']) ? sanitize_text_field($_POST['state']) : '');
	$address1 = (!empty($_POST['address_1']) ? sanitize_text_field($_POST['address_1']) : '');
	$city = (!empty($_POST['city']) ? sanitize_text_field($_POST['city']) : '');
	$zipcode = sanitize_text_field($_POST['zipcode']);
	$agent = sanitize_text_field($_POST['agent']);
	if (isset($_POST['lat']) && !empty($_POST['lat'])) $latitude = sanitize_text_field($_POST['lat']);
	if (isset($_POST['lng']) && !empty($_POST['lng'])) $longitude = sanitize_text_field($_POST['lng']);
	if (!isset($latitude) && !isset($longitude)) {
		if (empty($address1) || empty($city)) {
			$address = $zipcode . ',' . $country;
		}
		else $address = $address1 . ',' . $city . ',' . $state . '&nbsp:' . $zipcode;
		/*Get JSON results from this request*/
		$geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=' . get_option('google_api_key', true) . '&address=' . urlencode($address) . '&sensor=false');
		/* Convert the JSON to an array*/
		$geo = json_decode($geo, true);
		if ($geo['status'] == 'OK') {
			/* Get Lat & Long */
			$latitude = $geo['results'][0]['geometry']['location']['lat'];
			$longitude = $geo['results'][0]['geometry']['location']['lng'];
		}
	}
	$data = array(
		'agent_id' => $agent,
		'country_code' => $country,
		'zip_code' => '',
		'latitude' => $latitude,
		'longitude' => $longitude
	);
	if (!empty($zipcode) && !empty($api_key) && !empty($secret_key)) {
		/*Call to MyCommerce360-API */
		$tempShopList = (array)Callmc360API($method, $url, $data, $api_key, $secret_key);
		if (empty($tempShopList['message']) || $tempShopList['message'] == null) {
			$data = extract($tempShopList);
			$response['droppoints'] = $lists = $tempShopList['data'];
		}
	}
	else {
		$response['status'] = false;
		$response['error'] = __('The zipcode must be 4 numbers long, and numeric - please try again', 'woocommerce-mc360');
	}
	echo json_encode($response);
	wp_die();
}
function Callmc360API($method, $url, $data = false, $api_key, $secret_key)
{
	$url = sprintf("%s?%s", $url, http_build_query($data));
	$result = wp_remote_get($url, array(
		'timeout' => 10,
		'sslverify' => false,
		'headers' => array(
			'MyCommerce360-API-Username' => $api_key,
			'MyCommerce360-API-Secret' => $secret_key
		)
	));
	$result = json_decode($result['body'], true);
	return $result;
}
function mc360_add_droppoint_data($order_id)
{
	$chosen = WC()->session->get('chosen_shipping_methods');
	$shipps=explode(":",$chosen[0]);
	if ($shipps[0]=="mc360_shipping_pickup") {
		update_post_meta($order_id, '_shipping_textdata', WC()->session->get('mc360_shipping_pickup_adress'));
		update_post_meta($order_id, '_shipping_carriername_pick', WC()->session->get('droppoint_random_title_test'));
	}
	update_post_meta($order_id, '_carrier', WC()->session->get('mc360-agentId'));
	update_post_meta($order_id, '_shipping_latt', WC()->session->get('mc360-latt'));
	update_post_meta($order_id, '_shipping_lng', WC()->session->get('mc360-lng'));
	update_post_meta($order_id, '_shipping_pickuppoint', WC()->session->get('mc360-droppoint'));
	update_post_meta($order_id, '_shipping_carriername', WC()->session->get('droppoint_random_title'));		
	

		
}
function mc360_filter_woocommerce_package_rates($shippingmethods)
{
	 /**/
	 
	global $wpdb;
	$public_carrier = get_option('public_carrier');
	$ispickup=false;
	$isother=false;
	foreach($shippingmethods as $k=>$shippingmethod){
		
		if (strpos($k, 'mc360_shipping_pickup') !== false) {
			$ispickup=true;
		}
		elseif ($shippingmethod != 'mc360_shipping_droppoint' ) {
			$isother=true;
		}
	}
	$option_data=array();
		$count = 0;
		if (isset($public_carrier) && !empty($public_carrier) && $public_carrier == 'mc360_shipping_droppoint' ) {
			
			foreach($shippingmethods as $key => $shippingmethod) {
				
				$keyparts = explode(':', $key);
				
				/* if ($key != 'mc360_shipping_droppoint'  && $keyparts[0] != 'flat_rate' && $keyparts[0] != 'free_shipping' && $keyparts[0] != 'local_pickup' && $keyparts[0] != 'mc360_shipping_pickup') { */
                if ($key != 'mc360_shipping_droppoint'  && ($keyparts[0] == 'mc360_shipping_bring' ||  $keyparts[0] == 'mc360_shipping_dao365' ||  $keyparts[0] == 'mc360_shipping_dhl' || $keyparts[0] == 'mc360_shipping_gls' || $keyparts[0] == 'mc360_shipping_postnord' || $keyparts[0] == 'mc360_shipping_ups')) {
					
					/* delete_option(session_id() . '_store_carr_' . $shippingmethods[$key]->id); */
				
					$option_data[]=strtolower($shippingmethods[$key]->id);
					/* if (update_option(session_id() . '_store_carr_' . $shippingmethods[$key]->id, $shippingmethods[$key]->id)); */
						unset($shippingmethods[$key]);
					$count++;
				}
			}
			WC()->session->set( '_store_carr'  ,$option_data);
		}
		elseif($isother){		
			$agents = json_decode(allagents, true);
			$i = 1;
			foreach($agents as $key => $agent) {
				$carrier = "carrier_$i";
				$selectedcarrier = get_option($carrier);
				$shuldbe = "mc360_shipping_" . strtolower($agent['name']);
				if ($shuldbe != $selectedcarrier) {
					unset($shippingmethods[$shuldbe]);
				}
				if (isset($shippingmethods['mc360_shipping_droppoint'])) {
					unset($shippingmethods['mc360_shipping_droppoint']);
				}
				$i++;
			}
		}
		/* if (isset($public_carrier) && !empty($public_carrier) && $public_carrier == 'mc360_shipping_droppoint') 
			update_option(session_id() . '_countForZoneIs', $count); */
		
		
	

	return $shippingmethods;
}
function mc360_update_cost()
{
	global $wpdb;
	$carrieridis = sanitize_text_field($_POST['carrieridis']);
	$selectedcompanyis = sanitize_text_field($_POST['mc360-selectedcompanyis']);
	$mc360agentId = sanitize_text_field($_POST['mc360-agentId']);
	$countryid = WC()->customer->get_shipping_country();
	$selctzonequery = "SELECT `zone_id` FROM `" . $wpdb->prefix . "woocommerce_shipping_zone_locations` WHERE `location_code`  = '$countryid' order by location_id desc limit 1 ";
	$zoneid = $wpdb->get_var($selctzonequery);
	$getinstanceid = "SELECT `instance_id`  FROM `" . $wpdb->prefix . "woocommerce_shipping_zone_methods` WHERE `zone_id`  = " . $zoneid . " and method_id='$carrieridis'";
	$instance_idresult = $wpdb->get_row($getinstanceid);
	$instance_id = $instance_idresult->instance_id;
	$query = "SELECT `option_name` FROM `" . $wpdb->prefix . "options` WHERE `option_name`  LIKE '%woocommerce_" . $carrieridis . '_' . $instance_id . "_settings%'  ";
	$shipping_option = $wpdb->get_var($query);
	$shipping_cost = get_option($shipping_option, true);
	$total = WC()->cart->cart_contents_total;
	if (isset($shipping_cost['min_amount']) && !empty($shipping_cost['min_amount']) && ($total >= $shipping_cost['min_amount'])) $cost = 0;
	else $cost = $shipping_cost['cost'];
	if (wc_tax_enabled() && 'taxable' === $shipping_cost['tax_status'] && !WC()->customer->get_is_vat_exempt()) {
		$tax = array_sum(WC_Tax::calc_shipping_tax($cost, WC_Tax::get_shipping_tax_rates()));
		$cost = $cost + $tax;
	}
	WC()->session->set('droppoint_random_cost', $cost);
	WC()->session->set('droppoint_random_title', $shipping_cost['title']);
	WC()->session->set('mc360-agentId', $mc360agentId);
	WC()->session->set('mc360-latt', sanitize_text_field($_POST['mc360-latt']));
	WC()->session->set('mc360-lng', sanitize_text_field($_POST['mc360-lng']));
	WC()->session->set('mc360-droppoint', sanitize_text_field($_POST['mc360-droppoint']));
	WC()->session->set('html', $_POST['html']);
	WC()->session->set('mc360-company', $selectedcompanyis);
	die;
}
function mc360_update_field(){
	if($_POST['mc360_shipping_pickup_adress']){
		WC()->session->set('mc360_shipping_pickup_adress', $_POST['mc360_shipping_pickup_adress']);die(1);
	}
	else
		die(0);
}
function mc360_get_shipping(){
	
	if(isset($_POST['ship']) && !empty($_POST['ship']) && $_POST['ship']=="flex"){
		WC()->session->set('ship', '');
		$ship="flex_delivery";
		$shipsession="flex";
	}
	else{
		$ship="normal_delivery";
		$shipsession="normal";
		WC()->session->set('ship', '');
		WC()->session->set('mc360_shipping_pickup_adress', '');
	}
	WC()->session->set('ship', $shipsession);
	global $wpdb;
	$chosen = WC()->session->get('chosen_shipping_methods');
	$shipps=explode(":",$chosen[0]);
	$shipping_method="mc360_shipping_pickup";
	$countryid = WC()->customer->get_shipping_country();
    $query = "SELECT `option_name` FROM `" . $wpdb->prefix . "options` WHERE `option_name`  LIKE '%woocommerce_" . $shipping_method. '_' . $shipps[1] . "_settings%'  ";
	$shipping_option = $wpdb->get_var($query);
	$shipping_result = get_option($shipping_option, true);
	echo $shipping_result[$ship];
	mc360_update_carriername($shipping_result[$ship]);
	die;
}
function mc360_update_carriername($carrier){
	if($_POST['mc360_shipping'])
		$carrier=$_POST['mc360_shipping'];
	
	WC()->session->set('droppoint_random_title_test', '');
	WC()->session->set('droppoint_random_title_test',$carrier);die;
}
function mc360_getPrice($shipping_method)
{
	global $wpdb;
	$cost=0;
	$countryid = WC()->customer->get_shipping_country();
	$selctzonequery = "SELECT `zone_id` FROM `" . $wpdb->prefix . "woocommerce_shipping_zone_locations` WHERE `location_code`  = '$countryid' order by location_id desc limit 1 ";
	$zoneid = $wpdb->get_var($selctzonequery);
	$getinstanceid = "SELECT `instance_id`  FROM `" . $wpdb->prefix . "woocommerce_shipping_zone_methods` WHERE `zone_id`  = " . $zoneid . " and method_id='$shipping_method'";
	$instance_idresult = $wpdb->get_row($getinstanceid);
	if(isset($instance_idresult) && !empty($instance_idresult)){
			$instance_id = $instance_idresult->instance_id;
		$query = "SELECT `option_name` FROM `" . $wpdb->prefix . "options` WHERE `option_name`  LIKE '%woocommerce_" . $shipping_method . '_' . $instance_id . "_settings%'  ";
		$shipping_option = $wpdb->get_var($query);
		$shipping_cost = get_option($shipping_option, true);
		$total = WC()->cart->cart_contents_total;
		if (isset($shipping_cost['min_amount']) && !empty($shipping_cost['min_amount']) && ($total >= $shipping_cost['min_amount'])) $cost = 0;
		else if (isset($shipping_cost['cost']) && !empty($shipping_cost['cost'])) $cost = $shipping_cost['cost'];
		else $cost = 0;
		if (wc_tax_enabled() && 'taxable' === $shipping_cost['tax_status'] && !WC()->customer->get_is_vat_exempt()) {
			$tax = array_sum(WC_Tax::calc_shipping_tax($cost, WC_Tax::get_shipping_tax_rates()));
			$cost = $cost + $tax;
		}
	}
	return $cost;
}
function mc360_getInfo()
{
	$chosen = WC()->session->get('chosen_shipping_methods');
	$shiping_name = $chosen[0];
	$sub = explode(':', $shiping_name);
	if (count($sub) == 2) $shiping_name = $sub[0] . '_' . $sub[1];
	$agentsis = json_decode(allagents, true);
	$i = 1;
	$bool = 0;
	foreach($agentsis as $agent) {
		$carrier = "carrier_" . $i;
		$mc360_carr = get_option($carrier, true);
		if (($shiping_name === $mc360_carr)) $bool = 1;
		$i++;
	}
	$public_carrier = get_option('public_carrier');
	if ($shiping_name === $public_carrier) $bool = 1;
	echo $bool;
	wp_die();
}
function mc360_woocommerce_checkout_update_order_review($post_data)
{
	global $wpdb;
	$chosen = (isset($_POST['shipping_method']) && !empty($_POST['shipping_method'])) ? $_POST['shipping_method'] : WC()->session->get('chosen_shipping_methods');
	$shiping_name = $chosen[0];
	if ($shiping_name != "mc360_shipping_droppoint") {
		$sub = explode(':', $shiping_name);
		if (count($sub) == 2) {
			$shiping_name = $sub[0];
			$instance_id = $sub[1];
		}
		$countryid = WC()->customer->get_shipping_country();
		$selctzonequery = "SELECT `zone_id` FROM `" . $wpdb->prefix . "woocommerce_shipping_zone_locations` WHERE `location_code`  = '$countryid' order by location_id desc limit 1 ";
		$zoneid = $wpdb->get_var($selctzonequery);
		if (!isset($instance_id)) {
			$getinstanceid = "SELECT `instance_id`,`method_id`  FROM `" . $wpdb->prefix . "woocommerce_shipping_zone_methods` WHERE `zone_id`  = " . $zoneid . " and method_id='$shiping_name'";
			$instance_idresult = $wpdb->get_row($getinstanceid);
			if (isset($instance_idresult->instance_id) && !empty($instance_idresult->instance_id)) $instance_id = $instance_idresult->instance_id;
			else $instance_id = '';
		}
		$query = "SELECT `option_name` FROM `" . $wpdb->prefix . "options` WHERE `option_name`  LIKE '%woocommerce_" . $shiping_name . '_' . $instance_id . "_settings%'  ";
		$shipping_option = $wpdb->get_var($query);
		$shipping_data = get_option($shipping_option, true);
		$bool = false;
		if (isset($shipping_data['title']) && !empty($shipping_data['title'])) WC()->session->set('droppoint_random_title', $shipping_data['title']);		
	
		if (isset($_POST['shipping_method']) && !empty($_POST['shipping_method'])) {
			$shipping_methods = $_POST['shipping_method'];
			$shipping_method = $shipping_methods[0];
			$agentsis = json_decode(allagents, true);
			$i = 1;
			foreach($agentsis as $agent) {
				$carrier = "carrier_" . $i;
				$mc360_carr = get_option($carrier, true);
				if (($shipping_method === $mc360_carr)) $bool = true;
				$i++;
			}
			$public_carrier = get_option('public_carrier');
			if ($shipping_method === $public_carrier) $bool = true;
		}
		if ($bool == false) {
			WC()->session->set('droppoint_random_cost', '');
			WC()->session->set('droppoint_random_title', '');
			WC()->session->set('mc360-agentId', '');
			WC()->session->set('mc360-latt', '');
			WC()->session->set('mc360-lng', '');
			WC()->session->set('mc360-droppoint', '');			/* WC()->session->set('shipping_pickup', 0); */
			WC()->session->set('html', '');
		}
	}
	$shipping_cost = 30;
	foreach(WC()->cart->get_shipping_packages() as $package_key => $package) {
		/* this is needed for us to remove the session set for the shipping cost. Without this, we can't set it on the checkout page.*/
		WC()->session->set('shipping_for_package_' . $package_key, false);
		if ($shiping_name != "mc360_shipping_droppoint") WC()->session->set('droppoint_random_title', $shipping_data['title']);
	}
}
function mc360_adjust_shipping_rate($rates)
{
	foreach($rates as $key => $rate) {
		if ($key == 'mc360_shipping_droppoint') {
			$cost = $rate->cost;
			$rate->cost = WC()->session->get('droppoint_random_cost');
		}
	}
	return $rates;
}
add_filter('woocommerce_package_rates', 'mc360_adjust_shipping_rate', 50);
add_action('woocommerce_checkout_update_order_review', 'mc360_woocommerce_checkout_update_order_review');
add_action('wp_ajax_mc360_update_cost', 'mc360_update_cost');
add_action('wp_ajax_nopriv_mc360_update_cost', 'mc360_update_cost');
add_action('wp_ajax_mc360_update_field', 'mc360_update_field');
add_action('wp_ajax_nopriv_mc360_update_field', 'mc360_update_field');
add_action('wp_ajax_mc360_get_shipping', 'mc360_get_shipping');
add_action('wp_ajax_nopriv_mc360_get_shipping', 'mc360_get_shipping');
add_action('wp_ajax_mc360_update_carriername', 'mc360_update_carriername');
add_action('wp_ajax_nopriv_mc360_update_carriername', 'mc360_update_carriername');
add_action('wp_ajax_mc360_getInfo', 'mc360_getInfo');
add_action('wp_ajax_nopriv_mc360_getInfo', 'mc360_getInfo');
add_action('woocommerce_checkout_update_order_meta', 'mc360_add_droppoint_data');
add_action('wp_ajax_mc360_get_shop_list', 'mc360_get_shop_list_callback');
add_action('wp_ajax_nopriv_mc360_get_shop_list', 'mc360_get_shop_list_callback');
add_filter('woocommerce_package_rates', 'mc360_filter_woocommerce_package_rates', 10, 2);
/* add_filter('woocommerce_ship_to_different_address_checked', '__return_true'); */
?>
