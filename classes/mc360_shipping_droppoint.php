<?php
require_once (dirname(__FILE__) . '/Commonfunctions.php');

/**
 * Check if WooCommerce is active
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	function mc360_shipping_droppoint()
	{
		if (!class_exists('MC360_SHIPPING_DROPPOINT')) {
			class MC360_SHIPPING_DROPPOINT extends WC_Shipping_Method

			{
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct($instance_id = 0)

				{
					$this->id = 'mc360_shipping_droppoint'; /* Id for your shipping method. Should be uunique.*/
					$this->instance_id = absint($instance_id);
					$this->method_title = (!empty($this->method_title) ? $this->method_title : __('Select droppoint', 'woocommerce-mc360'));
					$this->method_description = __('Adds the option to ship with the  pickup point to the checkout'); /* Description shown in admin*/
					$this->enabled = "yes"; /* This can be added as an setting but for this example its forced enabled*/
					$this->supports = array(
						'shipping-zones',
						'instance-settings',
						'instance-settings-modal'
					);
					$this->init();
				}
				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init()
				{
					$this->instance_form_fields = include ('method_settings/settings-default.php');
					$this->init_settings(); /* This is part of the settings API. Loads settings you previously init.*/
					$this->title = $this->get_option('title'); /* This can be added as an setting but for this example its forced.*/
					$this->cost = $this->get_option('cost');
					$this->min_amount = $this->get_option('min_amount', 0);
					$this->requires = $this->get_option('requires');
					$this->tax_status = $this->get_option('tax_status'); /*Save settings in admin if you have any defined*/
					add_action('woocommerce_update_options_shipping_' . $this->id, array(
						$this,
						'process_admin_options'
					));
				}
				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping($package = array())
				{
					@session_start();
					if (isset($_SESSION['droppoint_random_cost']) && !empty($_SESSION['droppoint_random_cost'])) $rateis = $_SESSION['droppoint_random_cost'];
					else $rateis = 0;
					if ($this->is_taxable()) {
						$tax = array_sum(WC_Tax::calc_shipping_tax($rateis, WC_Tax::get_shipping_tax_rates()));
						$rateis = $rateis + $tax;
					}
					$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						'cost' => $rateis,
						'calc_tax' => 'per_order'
					);
					/*Register the rate*/
					$this->add_rate($rate);
				}
				public function is_taxable()

				{
					return wc_tax_enabled() && 'taxable' === $this->tax_status && !WC()->customer->get_is_vat_exempt();
				}
				public function get_instance_form_fields()

				{
					if (is_admin()) {
						wc_enqueue_js("				jQuery( function( $ ) {					function wcFreeShippingShowHideMinAmountField( el ) {						var form = $( el ).closest( 'form' );						var minAmountField = $( '#woocommerce_mc360_shipping_droppoint_min_amount', form ).closest( 'tr' );						if ( 'coupon' === $( el ).val() || '' === $( el ).val() ) {							minAmountField.hide();						} else {							minAmountField.show();						}					}					$( document.body ).on( 'change', '#woocommerce_mc360_shipping_droppoint_enable_free_shipping', function() {						wcFreeShippingShowHideMinAmountField( this );					});										$( '#woocommerce_mc360_shipping_droppoint_enable_free_shipping' ).change();					$( document.body ).on( 'wc_backbone_modal_loaded', function( evt, target ) {						if ( 'wc-modal-shipping-method-settings' === target ) {							wcFreeShippingShowHideMinAmountField( $( '#wc-backbone-modal-dialog #woocommerce_mc360_shipping_droppoint_enable_free_shipping', evt.currentTarget ) );						}					} );				});			");
					}
					return parent::get_instance_form_fields();
				}
			}
		}
	}
	add_action('woocommerce_shipping_init', 'mc360_shipping_droppoint');
	function add_droppoint_shipping_method($methods)
	{
		global $wpdb;
		$selectdatas = "SELECT * FROM information_schema.TABLES WHERE (TABLE_SCHEMA = '" . DB_NAME . "') AND (TABLE_NAME = '" . $wpdb->prefix . "icl_strings')";
		$data = $wpdb->get_row($selectdatas);
		if ($data) {
			$wpdb->query('DELETE  FROM ' . $wpdb->prefix . 'icl_strings
							   WHERE name = "mc360_shipping_droppoint_shipping_method_title"');
		}
		$methods['mc360_shipping_droppoint'] = 'mc360_shipping_droppoint';
		return $methods;
	}
	add_filter('woocommerce_shipping_methods', 'add_droppoint_shipping_method');
	function check_choosen_shipping_method_droppoint($rate)
	{
		$chosen_methods = WC()->session->get('chosen_shipping_methods');
		$chosen_shipping = preg_replace('/[0-9]+/', '', $chosen_methods[0]);;
		if ($chosen_shipping === 'mc_shipping_droppoint') {
			if ($rate->method_id == 'mc360_shipping_droppoint') {
				$common = new CommonFunctions();
				$price = mc360_getPrice('mc360_shipping_droppoint');
				$common->mc360_HTML_codeAndFindlocation('', '', '', '', $price);
			}
		}
	}
	add_action('woocommerce_after_shipping_rate', 'check_choosen_shipping_method_droppoint');
}