=== MyCommerce360 - Intelligent Delivery Management System ===
Contributors: mycommerce360
Tags: mycommerce360, commerce, shipping, freigt, fragt, plugin, droppoint, udleveringssted
Donate link: http://example.com/
Requires at least: 4.1
Tested up to: 5.7
Requires PHP: 5.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use the MyCommerce360 plugin for WooCommerce to offer your customers a pickuppoint selection of droppoints

== Description ==
Use the MyCommerce360 plugin for WooCommerce to offer your customers a pickuppoint selection of droppoints. The plugin will also transfer orders to your MyCommerce360 account where you can print the orders.

== Installation ==
1. Upload "mc360.php" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
Simply install the plugin by either FTP or by the WordPress Plugin installer.


== Frequently Asked Questions ==
= How do I use it? = 

Try our [beginner's guide]

== Screenshots ==
1. Add Api Key and Password provided by MyCommerce360,Add google Api key by google.
2. Select shipping method for each Carrier i.e.(GLS,POSTNORD,UPS etc.).If you want to use all carriers individually.For single carrier with multiple dropoint,choose our custom shipping method i.e. Select Droppoint.
3. Select Shipping Zone
4. Select carrier for each zone.
5. On checkout Select droppoint,button will be shown
6. Infowindow View
7. Pop Up view with listing section
8. Pop Up view with Map section
9. Import order manually from admin,(order will automatically get import if you select order status from MyCommerce360 admin page's first tab)

== Changelog ==

= 1.0.1 =
*Google api related bug fixed
*Css issue fixed
*Api issue fixed that wasn't showing UPS in  search

= 1.0.2 =
*Red marker absent when there is no address filled in field in not logged in state
*Made it compatible with WPML plugins and is loading with highest priority.

= 1.0.3 =
*Adjusted zoom level for map for better view

= 1.0.4 =
*Old orders can be imported now(when carrier name got match with mc360 admin).
*Pickup Number has been removed from address 2.
*While priniting label from MC360, It was giving a space entity.Thats removed.
*If you select pickup point and then after that you select another shipping method after that, then when you finish the order, it still takes the information from the pickuppoint and then it sends pickuppoint data to mc360(resolved).
*If error arises, while importing order to mc360,then It will be shown to user.
*Shipping without pickup is fixed(Home delivery)

= 1.0.5 =
*Mc360 shippment Id appears as soon as order get imported and success/error message appears.
*Order with status change was giving some error, that has been resolved.

= 1.0.6 =

*Mc360 shippment Id appears as soon as order get imported and success/error message appears.
*Order with status change was giving some error, that has been resolved.

= 1.0.7 =
*carriers from Api is organized on popup with respect to Zones.
*WPML String Translation compatiblity to show custom shipping methods name exactly inside admin.(Zone based).
* Fixed a bug like Sverige is having postcodes 12 345 that google returns was throwing error because of non-numeric data.Now its fixed and able to show points on map.

= 1.0.8. =
*minor js issues

= 1.0.9. =
*Resolved a bug, with which shipping form prevented to post when there was disabled attribute added.


= 1.0.10 =
*Resolved a bug, with which Zip code removed to hit Mc360 API.

= 1.0.11 =
*Resolved a bug, with which user can place order without selecting pickup point.
*Resolved a bug, with which data was importing wrong in some case.

= 1.0.12 =
*Resolved a bug, related to import orders.

= 1.0.13 =
*Added a message to ensure client to choose pickup point.
*Added some translations.

= 1.0.14 =
*bug while update status and order import, wrong message was showing.

= 1.0.15 =
*bug while importing order wrong product dimensions were transferring(single units).

= 1.0.16 =
*bug while  order cancellation from payment page, pickup point address was not delivering.

= 1.0.17 =
*bug while  order cancellation from payment page, company name was not delivering.

= 1.0.18 =
*Api request optimizations.

= 1.0.19 =
*issue with tick(another adress selection) has been resolved and map pickup sleect will close popup itself now.

= 1.0.20 =
*added a feature that enabled a user to select pickup points from place order button.

= 1.0.21 =
*removed a bug regarding shipping adress selection and Api executed in optimized way.

= 1.0.22 =
*removed a bug arised because of optimizations.

= 1.0.23 =
*removed a js issue.

= 1.0.24 =
*js code improved.

= 1.0.25 =
*update_checkout function was not triggering with some themes. Fixed done.

= 1.0.26 =
*Carrier mismatching was there on selection of shipping adress field.Resolved.

= 1.0.27 =
*Design issue.

= 1.0.28 =
*site breaks when mc360 api overloads, now its resolved.

= 1.0.29 =
*Fix for ssl.

= 1.0.30 =
*Fix for ssl.

= 1.0.31 =
*Fix for ssl.

= 1.0.32 =
*Added input for minimum order value,above that free shipping will reflect.

= 1.0.33 =
*Restricted code for specific pages

= 1.0.34 =
*address code fixed that triggers google api.

= 1.0.35 =
*fixed bug related to tax calculation and first shipping method usually disappears has been fixed.Wrong carrer name for free shipping was importing when multiple in same zone has ammended in plugin by addig functionality.

= 1.0.36 =
*Made it compatible to new shipping agent for home delivery.

= 1.0.37 =
*Now, it's able to calculate tax on the basis of whole order.WOrking with default functionality of woocommerce.

= 1.0.38 =
*Feature: Added textfield option for flexdelivery*

*Bugfix: Fixed PHP 7.2 compatability bug*

*Optimization: Optimized database and autoload*

= 1.0.39 =
*Feature: Compatible to woocommerce custom status plugin.From now onwards, It is able to import on custom order statuses as well

= 1.0.40 =
*Feature: Compatible to woocommerce custom shipping methods.From now onwards, COnflict with custom shipping plugin removed*
* Tested and compatible to latest versions of wordpress and woocommerce*

= 1.0.41 =
*Bugfix: Fixed an IE11 issue on the map.*
*Bugfix: Hidden Google logo and Terms&conditions on map. Removed CSS to show it again.*

= 1.0.42 =
*Bugfix: Place order used to hide after selection of carrier other than select droppoint(Mc360)

= 1.0.43 =
*Bugfix: Shipping name was overwriting with billing name on button click and is resolved.

= 1.0.44 =
*Bugfix: Resolved jquery not defined  issue in a test case.	

= 1.0.45 =
*Bugfix: Added new option to choose Default label amount(to choose type of colis calculation), order will import with division of colis amount.

= 1.0.46 =
*Bugfix: Changed method of dividing the collis for the feature: “split per SKU” and “split per quantity”.
*Feature: Added dropdown of possible choices of splitting instead of input field	

= 1.0.47 =
*Bugfix: Issue with "Always print 1 label" option has been fixed. The option will now generate 1 colli for the whole order, regardless of amount of products or amount of quantity.


