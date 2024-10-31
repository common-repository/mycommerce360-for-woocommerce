<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Settings for  all shipping methods.
 */
$settings_default = array(
	'hidden_post_field' => array(
		'type' 			=> 'hidden',
		'class'         => 'hidden_post_field',
	),
	'title' => array(
		'title' 		=> __( 'Method Name', 'woocommerce-MC360'),
		'type' 			=> 'text',
		'description' 	=> __( 'This controls the title which customer will be presented for during checkout.', 'woocommerce-MC360' ),
		'default'		=> $this->method_title,
		'desc_tip'		=> true
	),
	'tax_status' => array(
		'title' 		=> __( 'Tax Status', 'woocommerce-MC360' ),
		'type' 			=> 'select',
		'class'         => 'wc-enhanced-select',
		'default' 		=> 'taxable',
		'options'		=> array(
			'taxable' 	=> __( 'Taxable', 'woocommerce-MC360' ),
			'none' 		=> _x( 'None', 'Tax status', 'woocommerce-MC360' )
		)
	),
	'cost' => array(
		'title'         => __( 'Shipping Cost', 'woocommerce-MC360'),
		'type'          => 'text',
		'class'         =>  'cost',
		'default'       => '',
		'desc_tip'       => true
	),
	'min_amount' => array(
		'title'         => __( 'Minimum order value for free shipping', 'woocommerce-MC360'),
		'type'          => 'text',
        'description' 	=> __( 'Enter amount for when free shipping will start. Leave field to 0 if you want to disable the option.' ),
		'class'         =>  'min_amount',
		'default'       => '',
		'desc_tip'       => true
	)
);
return $settings_default;

