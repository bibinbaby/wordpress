<?php
function get_all_sliders()
{
	global $wpdb;
	$sSQL = "SELECT * FROM " . $wpdb->prefix."aparg_flexslider_sliders";
	$get_sliders = $wpdb->get_results($sSQL);
	return $get_sliders;
}

function get_slider_data($slide_id)
{
	global $wpdb;
	$sSQL = "SELECT * FROM " . $wpdb->prefix."aparg_flexslider WHERE slide_id=".$slide_id;
	$get_slider_data = $wpdb->get_results($sSQL);
	return $get_slider_data;
}

function addnewslider($slider_id)
{
	global $wpdb;
	global $check_db;
	$tblname = $wpdb->prefix."aparg_flexslider_sliders";
	$check_table = get_all_sliders();
	foreach($check_table as $slide_id=>$v):
		if($slide_id==$slider_id)
		{
			$check_db = false;
			$wpdb->query("DELETE FROM `".$tblname."` WHERE slider_id=".$slider_id);
			break;
		}
		else
		{
			$check_db = true;
		}
	endforeach;
	$field = "`slider_id`, `slider_name` ";
	$values = $slider_id.", 'Slider ".$slider_id."'";
	$sSQL = "INSERT INTO " . $tblname . " ($field) VALUES ($values)";
	$wpdb->query($sSQL);
}

function add_slides($tblname, $meminfo,$slide_id)
	{
	
		global $wpdb;
		global $check_db;
		$count = sizeof($meminfo);
		if($count>0)
		{
			$id = 0;
			$field = "slide_id";
			$values = "".$slide_id."";
			$check_table = get_slider_data($slide_id);
				if(empty($check_table))
				{
					$check_db= false;
				}
				else
				{
					$wpdb->query("DELETE FROM `".$tblname."` WHERE slide_id=".$slide_id);
					$check_db = true;
				}
					foreach($meminfo as $k =>$val):
						foreach($val as $key=> $v):
						if($field == "")
						{
							$field = "`" . $key . "`";
							$values = "'" . $v . "'";
						}
						else
						{
							$field = $field.",`" . $key . "`";
							$values = $values.",'" . $v . "'";
						}
						endforeach;
						if($check_db!=true)
						{
							$sSQL = "INSERT INTO " . $tblname . " ($field) VALUES ($values)";
						}
						else
						{
							$sSQL = "INSERT INTO " . $tblname . " ($field) VALUES ($values)";
						}
						$field = "slide_id";
						$values = "".$slide_id."";
						$wpdb->query($sSQL);
					endforeach;	
			return true;
		}
	}
	
function get_slider_settings($slider_id)
{
	global $wpdb;
	$sSQL = "SELECT slider_option_name, slider_option FROM " . $wpdb->prefix."aparg_flexslider_options WHERE slider_id=".$slider_id;
	$get_slider_setings =  $wpdb->get_results($sSQL);
	$settings = array();
	foreach($get_slider_setings as $key => $opt):
			$settings['slider_options'][$opt->slider_option_name] = $opt->slider_option;
	endforeach;
	return $settings;
	
}
function add_slider_options($tblname, $meminfo,$slider_id)
{
	global $wpdb;
	global $check_db;
	$check_table = get_slider_data($slider_id);
	$field = "slider_id";
	$values = "".$slider_id."";
		if(empty($check_table))
		{
			$check_db = false;
		}
		else
		{
			$wpdb->query("DELETE FROM `".$tblname."` WHERE slider_id=".$slider_id);
			$check_db = true;
		}
		foreach($meminfo as $key=> $val):
			foreach($val as $k=> $v):
			if($field == "")
				{
					$field= "`" . $k . "`";
					$values= "'" . $v . "'";
				}
				else
				{
					$field= $field.",`" . $k . "`";
					$values= $values.",'" . $v . "'";
				}
			endforeach;	
			if($check_db!=true)
			{
				$sSQL = "INSERT INTO " . $tblname . " ($field) VALUES ($values)";
			}
			else
			{
				$sSQL = "INSERT INTO " . $tblname . " ($field) VALUES ($values)";
			}
	
			$field = "slider_id";
			$values = "".$slider_id."";
			$wpdb->query($sSQL);
		endforeach;
}

function delete_slider($slider_id)
{
	global $wpdb;
	$slide_table_name = $wpdb->prefix . "aparg_flexslider";
	$sliders_table_name = $wpdb->prefix . "aparg_flexslider_sliders";
	$options_table_name = $wpdb->prefix . "aparg_flexslider_options";
	
	$wpdb->query("DELETE FROM `".$slide_table_name."` WHERE slide_id=".$slider_id);
	$wpdb->query("DELETE FROM `".$options_table_name."` WHERE slider_id=".$slider_id);	
	$wpdb->query("DELETE FROM `".$sliders_table_name."` WHERE slider_id=".$slider_id);
}

?>