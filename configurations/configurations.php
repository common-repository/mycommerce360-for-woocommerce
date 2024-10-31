<?php



  defined('ABSPATH') or die('Plugin file cannot be accessed directly.');



  



  add_action('admin_menu', 'mc360_add_admin_menu');



  



  function mc360_add_admin_menu()



  {



      



      add_submenu_page('woocommerce', __('mycommerce360.dk', 'woocommerce-MC360'), __('MyCommerce360', 'woocommerce-MC360'), 'manage_options', 'mc360', 'mc360_options_page');



      



      



  }



  



  function mc360_options_page()



  {



?>



        <?php



      if (isset($_GET['tab'])) {



          $active_tab = $_GET['tab'];



      } else {



          $active_tab = 'integration-options';



      }



?>  



        <div class="wrap mc360">            <?php



      settings_errors();



?> 			



            <h2>MyCommerce360 WooCommerce integration</h2>	



			<img class="imgRight" src="<?php



      echo MC360_URL . '/assets/img/mc-360-logo.jpg';



?>"/>







            <h2 class="nav-tab-wrapper">  



                <a href="?page=mc360&tab=integration-options" class="nav-tab tab1 <?php



      echo $active_tab == 'integration-options' ? 'nav-tab-active' : '';



?>">General Settings</a>  



                <a href="?page=mc360&tab=carrier-options" class="nav-tab tab2  <?php



      echo $active_tab == 'carrier-options' ? 'nav-tab-active' : '';



?>">Carrier selection</a>   



                <a href="?page=mc360&tab=documentation" class="nav-tab tab3  <?php



      echo $active_tab == 'documentation' ? 'nav-tab-active' : '';



?>">Documentation</a>   



                <a href="https://mycommerce360.dk/customer-service" target=



				"_blank" class="nav-tab <?php



      echo $active_tab == 'support' ? 'nav-tab-active' : '';



?>">Support</a>  



            </h2>  







            <form method="post" action="options.php" class="<?php



      if ($active_tab == 'integration-options') {



          echo "tab1";



      }



?>" > 



				<?php



      if ($active_tab == 'integration-options') {



          



          settings_fields('setting-group-1');



          do_settings_sections('mc360-1');



          submit_button();



          



      } else if ($active_tab == 'carrier-options') {



          



          settings_fields('setting-group-2');



          do_settings_sections('mc360-2');



          submit_button();



          



      } else if ($active_tab == 'documentation') {



          



          settings_fields('setting-group-3');



          do_settings_sections('mc360-3');



          



      }



?>







             </form> 







        </div>



        <?php



  }



  



  /* ----------------------------------------------------------------------------- */



  /* Setting Sections And Fields */



  /* ----------------------------------------------------------------------------- */



  



  function mc360_sandbox_initialize_theme_options()



  {

if((isset($_GET['page']) && $_GET['page']=='mc360') || (isset($_POST['option_page']) && $_POST['option_page']=='setting-group-1') || (isset($_POST['option_page']) && $_POST['option_page']=='setting-group-2')){

      add_settings_section('page_1_section', // ID used to identify this section and with which to register options  



          '', // Title to be displayed on the administration page  



          'mc360_display_header_options_content_integration_section', // Callback used to render the description of the section  



          'mc360-1' // Page on which to add this section of options  



          );



      add_settings_section('page_1_2_section', // ID used to identify this section and with which to register options  



          '', // Title to be displayed on the administration page  



          'mc360_display_header_options_content_integration_section_order', // Callback used to render the description of the section  



          'mc360-1' // Page on which to add this section of options  



          );



      



      add_settings_section('page_2_section', // ID used to identify this section and with which to register options  



          '', // Title to be displayed on the administration page  



          'mc360_display_header_options_content_carrier_selection', // Callback used to render the description of the section  



          'mc360-2' // Page on which to add this section of options  



          );



      add_settings_section('page_2_2_section', // ID used to identify this section and with which to register options  



          '', // Title to be displayed on the administration page  



          'mc360_display_header_options_content_carrier_public', // Callback used to render the description of the section  



          'mc360-2' // Page on which to add this section of options  



          );



      add_settings_section('page_3_2_section', // ID used to identify this section and with which to register options  



          '', // Title to be displayed on the administration page  



          'mc360_display_header_options_content_documentation', // Callback used to render the description of the section  



          'mc360-3' // Page on which to add this section of options  



          );



      add_settings_section('page_4_1_section', // ID used to identify this section and with which to register options  



          '', // Title to be displayed on the administration page  



          '', // Callback used to render the description of the section  



          'mc360-4' // Page on which to add this section of options  



          );



      



      /* ----------------------------------------------------------------------------- */



      /* Integration Section 1 */



      /* ----------------------------------------------------------------------------- */



      



      add_settings_field('apiusername', // ID used to identify the field throughout the theme  



          __('API username', 'woocommerce-MC360'), // The label to the left of the option interface element  



          'mc360_display_username_form_element', // The name of the function responsible for rendering the option interface  



          'mc360-1', // The page on which this option will be displayed  



          'page_1_section', // The name of the section to which this field belongs  



          array( // The array of arguments to pass to the callback. In this case, just a description.  



          'This is the description of the option 1'



      ));



      register_setting(



      //~ 'mc360',  



          'setting-group-1', 'apiusername');



      add_settings_field('secretkey', // ID used to identify the field throughout the theme  



          __('API Secret token', 'woocommerce-MC360'), // The label to the left of the option interface element  



          'mc360_display_secret_form_element', // The name of the function responsible for rendering the option interface  



          'mc360-1', // The page on which this option will be displayed  



          'page_1_section', // The name of the section to which this field belongs  



          array( // The array of arguments to pass to the callback. In this case, just a description.  



          'This is the description of the option 1'



      ));



      register_setting(



      //~ 'mc360',  



          'setting-group-1', 'secretkey');



      add_settings_field('importusername', // ID used to identify the field throughout the theme  



          __('Username for data import', 'woocommerce-MC360'), // The label to the left of the option interface element  



          'mc360_display_import_username_form_element', // The name of the function responsible for rendering the option interface  



          'mc360-1', // The page on which this option will be displayed  



          'page_1_section', // The name of the section to which this field belongs  



          array( // The array of arguments to pass to the callback. In this case, just a description.  



          'This is the description of the option 1'



      ));



      register_setting(



      //~ 'mc360',  



          'setting-group-1', 'importusername');



      add_settings_field('google_api_key', // ID used to identify the field throughout the theme  



          __('Google Api Key', 'woocommerce-MC360'), // The label to the left of the option interface element  



          'mc360_display_google_api_key', // The name of the function responsible for rendering the option interface  



          'mc360-1', // The page on which this option will be displayed  



          'page_1_section', // The name of the section to which this field belongs  



          array( // The array of arguments to pass to the callback. In this case, just a description.  



          'This is the description of the option 1'



      ));



      register_setting(



      //~ 'mc360',  



          'setting-group-1', 'google_api_key');
		  
		  add_settings_field('collis_calc', // ID used to identify the field throughout the theme  



          __('Default label amount:', 'woocommerce-MC360'), // The label to the left of the option interface element  



          'mc360_display_collis_calc', // The name of the function responsible for rendering the option interface  



          'mc360-1', // The page on which this option will be displayed  



          'page_1_section', // The name of the section to which this field belongs  



          array( // The array of arguments to pass to the callback. In this case, just a description.  



          'This is the description of the option 1'



      ));



      register_setting(



      //~ 'mc360',  



          'setting-group-1', 'collis_calc');



      /* ----------------------------------------------------------------------------- */



      /* Integration Section 2 */



      /* ----------------------------------------------------------------------------- */



      add_settings_field('order_status_new', // ID used to identify the field throughout the theme  



          __('Select status to trigger import', 'woocommerce-MC360'), // The label to the left of the option interface element  



          'mc360_display_order_status_form_element', // The name of the function responsible for rendering the option interface  



          'mc360-1', // The page on which this option will be displayed  



          'page_1_2_section', // The name of the section to which this field belongs  



          array( // The array of arguments to pass to the callback. In this case, just a description.  



          __('If you want the orders to be imported right away, select the first status that a successful order is assigned to.', 'woocommerce-MC360')



      ));



      register_setting(



      //~ 'mc360',  



          'setting-group-1', 'order_status_new');



      



      /* ----------------------------------------------------------------------------- */



      /* Carrier Section 1 */



      /* ----------------------------------------------------------------------------- */

	  delete_option('_apiagents');

	  delete_option('_totalagents');

	

		  mc360_getAgents();

		  if(defined('allagents')){

            $agents = json_decode(allagents,true);

	  }

	  else{

          if(defined('allagents'))

            $agents = json_decode(allagents,true);

	  }

	 

      $i      = 1;



      if (isset($agents) && !empty($agents)) {



          foreach ($agents as $key => $agent) {



              add_settings_field("carrier_$i", // ID -- ID used to identify the field throughout the theme  



                  __($agent['name'], 'woocommerce-MC360'), // LABEL -- The label to the left of the option interface element  



                  "mc360_display_carrier_form_element_$i", // CALLBACK FUNCTION -- The name of the function responsible for rendering the option interface  



                  'mc360-2', // MENU PAGE SLUG -- The page on which this option will be displayed  



                  'page_2_section', // SECTION ID -- The name of the section to which this field belongs  



                  array( // The array of arguments to pass to the callback. In this case, just a description.  



                  'This is the description of the option 2' // DESCRIPTION -- The description of the field.



              ));



              register_setting('setting-group-2', "carrier_$i");



              $i++;



          }



      }



      /* else



      echo "<h3>Please insert api credentials to get all carriers</h3>"; */



      /* ----------------------------------------------------------------------------- */



      /* Carrier Section 2 */



      /* ----------------------------------------------------------------------------- */



      add_settings_field('public_carrier', // ID used to identify the field throughout the theme  



          __('Select a carrier', 'woocommerce-MC360'), // The label to the left of the option interface element  



          'mc360_display_public_carrier_form_element', // The name of the function responsible for rendering the option interface  



          'mc360-2', // The page on which this option will be displayed  



          'page_2_2_section', // The name of the section to which this field belongs  



          array( // The array of arguments to pass to the callback. In this case, just a description.  



          'If you want the orders to be imported right away,select the first status that a successfull order is assigned to.'



      ));



      register_setting(



      //~ 'mc360',  



          'setting-group-2', 'public_carrier');



      



      add_settings_field('pop_up_status', // ID used to identify the field throughout the theme  



          __('Select display', 'woocommerce-MC360'), // The label to the left of the option interface element  



          'mc360_display_popup_status_form_element', // The name of the function responsible for rendering the option interface  



          'mc360-2', // The page on which this option will be displayed  



          'page_2_2_section', // The name of the section to which this field belongs  



          array( // The array of arguments to pass to the callback. In this case, just a description.  



          'Select whether you want popup or integrated window on front'



      ));



      register_setting(



      //~ 'mc360',  



          'setting-group-2', 'pop_up_status');



      



  }



  } 



  /* function mc360_sandbox_initialize_theme_options */



  add_action('admin_init', 'mc360_sandbox_initialize_theme_options');



  



  function mc360_display_header_options_content_integration_section()



  {



      echo "<h3>" . __('General Settings', 'woocommerce-MC360') . "</h3>";



  }



  function mc360_display_header_options_content_integration_section_order()



  {



      echo "<h3>" . __('Orderimport', 'woocommerce-MC360') . "</h3>";



  }



  function mc360_display_header_options_content_carrier_selection()



  {



      echo "<h3>" . __('Carrier selection for droppoints', 'woocommerce-MC360') . "</h3>";



  }



  function mc360_display_header_options_content_carrier_public()



  {



      echo "<h3>" . __('Public carrier for droppoints', 'woocommerce-MC360') . "</h3><p>" . __('Create a shippingmethod that you call something like "Select droppoint" eg. and Select it here. All your shipping methods will be showing under that one', 'woocommerce-MC360') . "</p>";



  }



  function mc360_display_header_options_content_documentation()



  {



      echo "<h3>" . __('Documentation', 'woocommerce-MC360') . "</h3>";



      mc360_display_documentation_form_element();



  }



  



  



  /* ----------------------------------------------------------------------------- */



  /* Field Callbacks */



  /* ----------------------------------------------------------------------------- */



  function mc360_display_username_form_element($args)



  {



?>



		<input type="text" class="form-control" name="apiusername" id="apiusername" value="<?php



      echo get_option('apiusername');



?>" />



		<small><?php



      echo __('You can find the API username in your MC360 contract.', 'woocommerce-MC360');



?></small>



		



	<?php



  }



  function mc360_display_secret_form_element($args)



  {



?>



		<input type="text" name="secretkey" id="secretkey" value="<?php



      echo get_option('secretkey');



?>" />



		<small><?php



      echo __('You can find the API secret token in your MC360 contract.', 'woocommerce-MC360');



?></small>



		



	<?php



  }



  function mc360_display_import_username_form_element($args)



  {



?>



		<input type="text" name="importusername" id="importusername" value="<?php



      echo get_option('importusername');



?>" />



		<small><?php



      echo __('You can find the API import username when you edit your profile in MC360.', 'woocommerce-MC360');



?></small>



	<?php



  }



  function mc360_display_google_api_key($args)



  {



?>



		<input type="text" name="google_api_key" id="google_api_key" value="<?php



      echo get_option('google_api_key');



?>" />



		<small><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php



      echo __('Click here', 'woocommerce-MC360');



?></a><?php



      echo __('to generate a Google API key. How-to guide is provided in documentation', 'woocommerce-MC360');



?> </small>



	<?php



  }
  function mc360_display_collis_calc($args){


$collis_calc=get_option('collis_calc');
?>

<select id ="order_status_new" name ="collis_calc">
<option value="0" <?php if($collis_calc==0 || empty($collis_calc)){ echo 'selected="selected"';} ?>>Print always 1 label</option>
<option value="1"  <?php if($collis_calc==1){ echo 'selected="selected"';} ?>>Print a label per SKU</option>
<option value="2"  <?php if($collis_calc==2){ echo 'selected="selected"';} ?>>Print a label per quantity</option>
</select>

		

	<?php



  }
  



  function mc360_display_order_status_form_element($args)



  {



      $statuses = wc_get_order_statuses();



?>



	



		<select  id="order_status_new"  name="order_status_new">



		<option value="">None</option>



			<?php



      $selectedstatus = get_option('order_status_new');



      



      foreach ($statuses as $key => $status) {



?>



				<option value="<?php



          echo $key;



?>" <?php



          echo ($selectedstatus == $key ? 'selected="selected"' : '');



?> ><?php



          echo $status;



?></option>



			<?php



      }



?>



		</select>



		 <p class="description apiusername"> <?php



      echo $args[0];



?> </p>



		



	<?php



  }



  function mc360_display_popup_status_form_element($args)



  {



      $selectedstatus = get_option('pop_up_status');



?>



	



		<label><input type="radio" name="pop_up_status" <?php



      echo ($selectedstatus == 'popup' ? 'checked="checked"' : 'checked');



?>  id="pop_up_status1" value="popup" />Pop Up</label>



		<label><input type="radio" name="pop_up_status"  <?php



      echo ($selectedstatus == 'window' ? 'checked="checked"' : '');



?>   id="pop_up_status2" value="window" />Integrated Window</label>



	<?php



  }



  function mc360_display_carrier_form_element_1()



  {



      $shipmethods     = mc360_getShippingMethods();



      $selectedcarrier = get_option('carrier_1');



?>



			<select name="carrier_1">



				<option value="">None</option>



			<?php



      foreach ($shipmethods as $key => $shipmethod) {



?>



				<option value="<?php



          echo $shipmethod->id;



?>" <?php



          echo ($selectedcarrier == $key ? 'selected="selected"' : '');



?>><?php



          echo $shipmethod->method_title;



?></option>



			<?php



      }



?>



			</select>



        <?php



      



  }



  function mc360_display_carrier_form_element_2()



  {



      $shipmethods      = mc360_getShippingMethods();



      $selectedcarrier2 = get_option('carrier_2');



?>



			<select name="carrier_2">



				<option value="">None</option>



			<?php



      foreach ($shipmethods as $key => $shipmethod) {



?>



				<option value="<?php



          echo $shipmethod->id;



?>" <?php



          echo ($selectedcarrier2 == $key ? 'selected="selected"' : '');



?>><?php



          echo $shipmethod->method_title;



?></option>



			<?php



      }



?>



			</select>



        <?php



      



  }



  function mc360_display_carrier_form_element_3()



  {



      $shipmethods      = mc360_getShippingMethods();



      $selectedcarrier3 = get_option('carrier_3');



      



?>



            <select name="carrier_3">



				<option value="">None</option>



			<?php



      foreach ($shipmethods as $key => $shipmethod) {



?>



				<option value="<?php



          echo $shipmethod->id;



?>" <?php



          echo ($selectedcarrier3 == $key ? 'selected="selected"' : '');



?>><?php



          echo $shipmethod->method_title;



?></option>



			<?php



      }



?>



			</select>



        <?php



      



  }



  function mc360_display_carrier_form_element_4()



  {



      $shipmethods      = mc360_getShippingMethods();



      $selectedcarrier4 = get_option('carrier_4');



?>



           <select name="carrier_4">



				<option value="">None</option>



			<?php



      foreach ($shipmethods as $key => $shipmethod) {



?>



				<option value="<?php



          echo $shipmethod->id;



?>"<?php



          echo ($selectedcarrier4 == $key ? 'selected="selected"' : '');



?>><?php



          echo $shipmethod->method_title;



?></option>



			<?php



      }



?>



			</select>



        <?php



      



  }



  function mc360_display_carrier_form_element_5()



  {



      $shipmethods      = mc360_getShippingMethods();



      $selectedcarrier5 = get_option('carrier_5');



?><select name="carrier_5">



				<option value="">None</option>



			<?php



      foreach ($shipmethods as $key => $shipmethod) {



?>



				<option value="<?php



          echo $shipmethod->id;



?>" <?php



          echo ($selectedcarrier5 == $key ? 'selected="selected"' : '');



?>><?php



          echo $shipmethod->method_title;



?></option>



			<?php



      }



?>



			</select>



        <?php



      



  }



  function mc360_display_carrier_form_element_6()



  {



      $shipmethods      = mc360_getShippingMethods();



      $selectedcarrier6 = get_option('carrier_6');



?>



           <select name="carrier_6">



				<option value="">None</option>



			<?php



      foreach ($shipmethods as $key => $shipmethod) {



?>



				<option value="<?php



          echo $shipmethod->id;



?>" <?php



          echo ($selectedcarrier6 == $key ? 'selected="selected"' : '');



?>><?php



          echo $shipmethod->method_title;



?></option>



			<?php



      }



?>



			</select>



        <?php



      



  }



  function mc360_display_public_carrier_form_element()



  {



      $shipmethods            = mc360_getShippingMethods();



      



      $selectedpublic_carrier = get_option('public_carrier');



?>



            <select name="public_carrier">



				<option value="">None</option>



			<?php



      foreach ($shipmethods as $key => $shipmethod) {



?>



				<option value="<?php



          echo $shipmethod->id;



?>"  <?php



          echo ($selectedpublic_carrier == $key ? 'selected="selected"' : '');



?>><?php



          echo $shipmethod->method_title;



?></option>



			<?php



      }



?>



			</select>



        <?php



  }



  function mc360_display_documentation_form_element()



  {



?>



		<table>



		



		<tr><td><a href="<?php



      echo MC360_URL . '/assets/pdf/mc360-dokumentation-da.pdf';



?>"  target="



		_blank">Danish</a></td></tr>



		<table>



		<?php



  }



  



  function mc360_getShippingMethods()



  {



      global $woocommerce;



      $shipmethods = $woocommerce->shipping->load_shipping_methods();



      return $shipmethods;



      



  }



  



  



?>