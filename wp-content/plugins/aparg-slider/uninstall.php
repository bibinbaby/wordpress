<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

	global $wpdb;

	$table_name = $wpdb->prefix . "aparg_flexslider";
	$options_table_name = $wpdb->prefix . "aparg_flexslider_options";
	$sliders_table_name = $wpdb->prefix . "aparg_flexslider_sliders";
	
	$sql_query = "DROP TABLE `".$table_name."`, ";
	$sql_query.= "`".$options_table_name."`, ";
	$sql_query.= "`".$sliders_table_name."`";
	$wpdb->query($sql_query);

?>