<?php
/*
Plugin Name: APARG Slider
Plugin URI: http://aparg.com
Description: This plugin let users to create multiple sliders with descriptions for each slide.
Version: 1.7
Author: APARG
Author URI: http://aparg.com
*/

ini_set('upload_max_size','100M');
ini_set('post_max_size','15M');
ini_set('max_execution_time',300);


if ( ! defined( 'WP_ADMIN_URL' ) )
    define( 'WP_ADMIN_URL', ABSPATH . '/wp-admin' );
if ( ! defined( 'WP_INCLUDES_URL' ) )
    define( 'WP_INCLUDES_URL', ABSPATH . '/wp-includes' );
	
/* Creating tables for slider images and options in DB */
function aparg_addmyplugin() {
	global $wpdb;
	$table_name = $wpdb->prefix . "aparg_flexslider";
	$options_table_name = $wpdb->prefix . "aparg_flexslider_options";
	$sliders_table_name = $wpdb->prefix . "aparg_flexslider_sliders";
	
	$MSQL = "show tables like '".$table_name;
	$mSQL = "show tables like '".$options_table_name;
	$msql = "show tables like '".$sliders_table_name;
	require_once(ABSPATH . "wp-admin/includes/upgrade.php");
	if($wpdb->get_var($MSQL) != $table_name)
	{
	   
	   $sql = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
			  `id` int(3) NOT NULL AUTO_INCREMENT,
			  `slide_id` int(3) NOT NULL,
			  `slide_url` varchar(255) NOT NULL,
			  `slide_title` varchar(255) NOT NULL,
			  `description` text NOT NULL,
			  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		/* Including dbDelta function for working with DB*/
		
		dbDelta($sql);
	}
	if($wpdb->get_var($mSQL) != $options_table_name)
	{
		$Sql = "CREATE TABLE IF NOT EXISTS `".$options_table_name."` (
			   `id` int(9) NOT NULL AUTO_INCREMENT,
				`slider_id` int(3) NOT NULL,
				`slider_option_name` varchar(30) NOT NULL,
				`slider_option` varchar(30) NOT NULL,
				PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		/* Including dbDelta function for working with DB*/
		dbDelta($Sql);
	}
	
	if($wpdb->get_var($msql) != $sliders_table_name)
	{
		$SQl = "CREATE TABLE IF NOT EXISTS `".$sliders_table_name."` (	
				`slider_id` int(3) NOT NULL,
				`slider_name` varchar(255) NOT NULL,
				PRIMARY KEY (`slider_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		/* Including dbDelta function for working with DB
		 
		*/
		dbDelta($SQl);
	}
}
register_activation_hook(__FILE__, 'aparg_addmyplugin');

/* Creating Menus */
function aparg_slider_menu()
{
	/* Adding menus */
	add_menu_page('APARG Slider', 'APARG Slider', 8, 'apargslider', 'aparg_flex_slider', plugins_url('aparg-slider/images/logo.png'));
}

add_action( 'admin_init', 'aparg_my_plugin_scripts' );
add_action('admin_menu', 'aparg_slider_menu');

// **** //
function apargslider_contextual_help( $contextual_help) {
    global $current_screen;
	$cont_help = "<p>Hi, this is a APARG Slider help.</p>".
		"<p>To use our slider plugin at first you should add a slider by clicking on  the '+' tab. </p>".
		"<p>Then you'll see default slider settings on right side and blank area on the left side where you can add slides(click 'Add Images') with their descriptions('Add Description').</p>".
		"<p>After that save current slide information</p>".
		"<b>Our slider plugin advantages</b>".
		"<ol>".
			"<li>Add 4 descriptions to each slide.</li>".
			"<li>Change slide images by clicking on them</li>".
			"<li>Delete descriptions, slides, entire sliders</li>".
		"</ol>".
		"<p><b>Note:  </b> If all descriptions are empty their options(background and text color) are inactive.</p>"; 
    switch( $current_screen->id ) {
        case 'toplevel_page_apargslider' :
     
            get_current_screen()->add_help_tab( array(
				'id'        => 'apargslider-help-tab',
				'title'     => __( 'APARG Slider Help' ),
				'content'   => __( $cont_help)
            ));
            
        break;
    }
    return $contextual_help;
}
add_filter('contextual_help', 'apargslider_contextual_help');
// **** //


function aparg_my_plugin_scripts() 
{  
	if (is_admin() && isset($_GET['page']) && $_GET['page'] == 'apargslider')
	{
		global $wp_scripts;
		wp_enqueue_script('jquery');
	   
        wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-dialog');  
		
		$ui = $wp_scripts->query('jquery-ui-core');
		wp_enqueue_style('jquery-dialog-style', plugins_url("css/jquery-ui/jquery-ui-$ui->ver.css", __FILE__ ));
		
		wp_enqueue_media();
		wp_register_script('cpicker_scripts', plugins_url('colorpicker/js/colorpicker.js', __FILE__ ));
        wp_enqueue_script('cpicker_scripts');
		wp_register_style('cpicker_styles', plugins_url('colorpicker/css/colorpicker.css', __FILE__ ));
		wp_enqueue_style( 'cpicker_styles');
		wp_register_style('custom_styles', plugins_url('css/plugin_styles.css', __FILE__ ),false,false,'all');
		wp_enqueue_style( 'custom_styles');
		wp_register_script('custom_scripts', plugins_url('js/main.js', __FILE__ ),array( 'jquery','jquery-ui-core'));
        wp_enqueue_script('custom_scripts');
		
		//Add script to use alternatively media query in IE8
		wp_register_script('respond_scripts', plugins_url('js/respond.min.js', __FILE__ ));
        wp_enqueue_script('respond_scripts');
	}	
} 
  
function aparg_make_slider_content($slider_id)
{
	$uploadfiles = $_POST['img'];
	$slides_titles = $_POST['title'];
	$desc_array = array();
	
	$settings = $_POST['slide_options'];
	$settings['slider_width'] = (isset($_POST['slide_options']['slider_width_def']))?"":trim($_POST['slide_options']['slider_width']);
	$settings['slider_height'] = (isset($_POST['slide_options']['slider_height_def']))?"":trim($_POST['slide_options']['slider_height']);
	
	$settings['slider_width_def'] = (isset($_POST['slide_options']['slider_width_def']))?1:0;
	$settings['slider_height_def'] = (isset($_POST['slide_options']['slider_height_def']))?1:0;
	$settings['carousel'] = (isset($_POST['slide_options']['carousel']))?1:0;
	
	$settings['animation'] = (isset($_POST['slide_options']['carousel']))?$_POST['slide_options']['animation']: "slide";
	$settings['animation'] = (!isset($_POST['slide_options']['carousel']) && $_POST['slide_options']['animation']=='fade')?$_POST['slide_options']['animation']: "slide";
	$settings['randomize'] = (isset($_POST['slide_options']['randomize']))?1:0;
	$settings['controlNav'] = (isset($_POST['slide_options']['controlNav']))?1:0;
	$settings['directionNav'] = (isset($_POST['slide_options']['directionNav']))?1:0;
	$settings['pauseOnHover'] = (isset($_POST['slide_options']['pauseOnHover']))?1:0;
	
	if(isset($_POST['desc']) && !empty($_POST['desc']))
	{
		foreach($_POST['desc'] as $key => $desc)
		{
			$desc_array[$key] = implode('%APARG%',$desc);
		}
		
		
	
		$slider_data = array();
		global $check; 
		global $wpdb;
		foreach($uploadfiles as $key => $slide_images):
			$slider_data[$key]["slide_url"] =  str_replace(get_site_url(),"",$slide_images);
			$slider_data[$key]["slide_title"] =  $slides_titles[$key];
			$slider_data[$key]["description"] = (array_key_exists($key,$desc_array))?$desc_array[$key]:"NULL";	
		endforeach;	
		$j = 0;
		foreach($settings as $key => $slider_option):
				$slider_options[$j]["slider_option_name"] =  $key;
				$slider_options[$j]["slider_option"] =  $slider_option;
				$j++;
		endforeach;
		$get_content = get_slider_data($slider_id);
		
		add_slides($table_name = $wpdb->prefix . "aparg_flexslider", $slider_data,$slider_id);
		add_slider_options($tablename = $wpdb->prefix . "aparg_flexslider_options", $slider_options,$slider_id);
			
		$slider_content = aparg_show_saved_slider($slider_id);
		$slider_settings = get_slider_settings($slider_id);
	}	
	if(isset($slider_settings) && !empty($slider_settings) && isset($slider_content) && !empty($slider_content))
	{		
		require_once("slider_form.php");
		echo "<script>".
				
				"jQuery(document).ready(function(){";
				echo "jQuery('#img_cont').append('".$slider_content."');";
					foreach($slider_settings['slider_options'] as $key => $value):					
						$def_size = (($key=="slider_width_def" || $key=="slider_height_def") && $value=="1")?"def":"";
						$def_size = (($key=="slider_width" || $key=="slider_height") && $value=="")?"def":"";
					
						echo ($def_size=="def")?"":"jQuery('#". $key."').val('".$value."');";			
						if($key=="desc_bg_color")
						{	
							echo "jQuery('#current_bg_color').css('background-color','".$value."');";
							// **** //
							echo "jQuery('#current_bg_color').attr('data-color','".$value."');";
						}
						if($key=="desc_text_color")
						{	
							echo "jQuery('#current_text_color').css('background-color','".$value."');";
							// **** //
							echo "jQuery('#current_text_color').attr('data-color','".$value."');";
							
						}		
						if($key=="randomize" || $key=="controlNav" || $key=="directionNav" || $key=="pauseOnHover" || $key=="carousel"  || $key=="slider_height_def" || $key=="slider_width_def")
						{
							if($value == "1")
							{
								echo "jQuery('#". $key."').attr('checked', true);";	
								if($key=="slider_width_def"){
									echo "jQuery('#slider_width').attr('disabled',true);";
								}	
								if($key=="slider_height_def"){
									echo "jQuery('#slider_height').attr('disabled',true);";						
								}
								
							}
							else 
							{
								echo "jQuery('#". $key."').attr('checked', false);";
								if($key=="slider_width_def"){
									echo "jQuery('#slider_width').attr('disabled',false);";
								}
								if($key=="slider_height_def"){
									echo "jQuery('#slider_height').attr('disabled',false);";						
								}
								
							}
						}		
						
					endforeach;

						
				
		echo	"});
			</script>";	
	}
	else
	{
		require_once("slider_form.php");	
	}
	
}

function aparg_show_saved_slider($slider_id)
{
	$get_content = get_slider_data($slider_id);
	$slider_content = "";
	if(isset($get_content) && !empty($get_content))
	{
		foreach($get_content as $key => $value)
		{	
			$description =  explode('%APARG%',$value->description);
			$slider_content.=  '<tr class="row sortable-row" id="row_'.$key.'" width="100%"><td width="99%" height="99%"><table class="table_'.$key.'"  width="100%">';
			// **** //
			$slider_content.=  '<tr width="100%"><td width="22%"><a href="#" style="background-image:url('.get_option('siteurl').$value->slide_url.')" class="current_img" alt="'.$value->slide_title.'"><span>Click to change image</span></a>';
			$slider_content.=  '<input type="hidden" name="img['.$key.']" value="'.$value->slide_url.'" class="hidden_img">';
			$slider_content.=  '<input type="hidden" name="title['.$key.']" value="'.$value->slide_title.'" class="hidden_title"></td>';
			$slider_content.=  '<td class="addinput" width="73%" id="'.$key.'" ><button class="button addDescription" name="addDescription" ><span></span>Add Description</button>&nbsp;&nbsp;';
			$button_style = ($description[0]==="NULL")?'style="display:none;"':'';
			$slider_content.=  '<button class="button empty_desc" name="empty_desc" '.$button_style.' id="delete_desc_row_'.$key.'"><span></span>Delete Descriptions</button><br>';
			foreach($description as $k => $val):
				if($val!="NULL")
				{
					$slider_content.= 	'<p id="current_desc_'.$k.'" class="current_description" width="100%"><input type="text" class="desc" id="desc_'.$key."".$k.'" name="desc['.$key.']['.$k.']" value="'.htmlentities($val).'" placeholder="Type a description"  width="80%"/>';
					$slider_content.= 	'<a href="#" class="delete_desc" remove_desc="'.$k.'" style="background-image:url('.plugins_url('images/trash_can_delete.png',__FILE__ ).'); display: inline;"></a></p>';
				}	
			endforeach;
			$slider_content.= 	'<td width="5%"><a href="#" deleted_row_id="'.$key.'" class="delete_img" ><img src="'.plugins_url('images/close_delete.png',__FILE__ ).'"></a></td>';
			$slider_content.= 	'</td></tr></table></td></tr>';
		}		
		return $slider_content;
	}
}

function aparg_load_custom_files()
{	
	if (!is_admin()) 
	{
		// **** //
		wp_enqueue_scripts('jquery');
		
		// **** //
		wp_register_script('flexslider_scripts', plugins_url('js/jquery.flexslider.js', __FILE__ ),array( 'jquery','jquery-ui-core',));
        wp_enqueue_script('flexslider_scripts');
		wp_register_style('flexslider_style',plugins_url('css/flexslider.css', __FILE__ ));
		wp_enqueue_style('flexslider_style');
		wp_register_style('flexslider_custom_style',plugins_url('css/flexsliderstyles.css', __FILE__ ));
		wp_enqueue_style('flexslider_custom_style');
	}
}
// **** //
add_action('get_header', 'aparg_load_custom_files');	
// **** //

function aparg_flex_slider()
{
	require_once "dbase.php";
	$slider_tabs = get_all_sliders();
	foreach($slider_tabs as $id => $slider)
	{
		if($id==0){ $first_slider_id = $slider->slider_id; }
		$last_tab_id = $slider->slider_id;
	}
	if($_GET['id'] && count($slider_tabs)>0) { $slider_id = $_GET['id']; }
	else if(!isset($_GET['id']) && count($slider_tabs)>0) { $slider_id = $first_slider_id; }
	else if(count($slider_tabs)==0 && $_GET['slider']=="new") { $slider_id = 1; }
	else { $slider_id = 0; }
	if(isset($_POST["save_slider_".$slider_id.""]))
	{
		aparg_make_slider_content($slider_id);	
	}
	else
	{
		$slider_content = aparg_show_saved_slider($slider_id);
		$slider_settings = get_slider_settings($slider_id);
		if(isset($slider_settings) && !empty($slider_settings) && isset($slider_content) && !empty($slider_content))
		{
			require_once("slider_form.php");
			echo "<script>";
				
				echo "jQuery(document).ready(function(){".
					"jQuery('#img_cont').append('".$slider_content."');";
					foreach($slider_settings['slider_options'] as $key => $value):
						$def_size = (($key=="slider_width_def" || $key=="slider_height_def") && $value=="1")?"def":"";
						$def_size = (($key=="slider_width" || $key=="slider_height") && $value=="")?"def":"";
					
						echo ($def_size=="def")?"":"jQuery('#". $key."').val('".$value."');";
						
						if($key=="desc_bg_color")
						{	
							echo "jQuery('#current_bg_color').css('background-color','".$value."');";
							// **** //
							echo "jQuery('#current_bg_color').attr('data-color','".$value."');";
						}
						if($key=="desc_text_color")
						{	
							echo "jQuery('#current_text_color').css('background-color','".$value."');";
							// **** //
							echo "jQuery('#current_text_color').css('data-color','".$value."');";
							
						}
						if($key=="randomize" || $key=="controlNav" || $key=="directionNav" || $key=="pauseOnHover" || $key=="carousel"  || $key=="slider_height_def" || $key=="slider_width_def")
						{
							if($value == "1")
							{
								echo "jQuery('#". $key."').attr('checked', true);";	
								if($key=="slider_width_def"){
									echo "jQuery('#slider_width').attr('disabled',true);";
								}	
								if($key=="slider_height_def"){
									echo "jQuery('#slider_height').attr('disabled',true);";						
								}
								
							}
							else 
							{
								echo "jQuery('#". $key."').attr('checked', false);";
								if($key=="slider_width_def"){
									echo "jQuery('#slider_width').attr('disabled',false);";
								}
								if($key=="slider_height_def"){
									echo "jQuery('#slider_height').attr('disabled',false);";						
								}
								
							}
						}		
					endforeach;
					
				
						
					
				echo "});
			</script>";
			
			if($_GET['slider']=="delete")
			{
				foreach($slider_tabs as $id => $slider)
				{
					if($id==0){ $first_slider_id = $slider->slider_id; }
					$last_tab_id = $slider->slider_id;
				}
				delete_slider($slider_id);
				if($slider_id == $last_tab_id && count($tabs)!=1) { $slide_id = '&id='.$first_slider_id;}
				else if(($slider_id+1) != $last_tab_id && count($tabs)!=1) { $slide_id = '&id='.$last_tab_id;}
				else if($slider_id==$last_tab_id && count($tabs)==1)  { $slide_id = "";}
				else { $slide_id = '&id='.($slider_id+1);}
				echo '<script>
							window.location.href="admin.php?page=apargslider'.$slide_id.'"
					</script>';
			}
		}
		else
		{
			
			if($_GET['slider']=="new")
			{
				echo "<script>
						window.location.href='admin.php?page=apargslider&id=".$slider_id."'
					</script>";
				addnewslider($slider_id);
			}
			else if($_GET['slider']=="delete")
			{
					
				$tabs = get_all_sliders();
				foreach($tabs as $id => $slider)
				{
					if($id==0){ $first_slider_id = $slider->slider_id; }
					$last_tab_id = $slider->slider_id;
				}
				if($slider_id == $last_tab_id && count($tabs)!=1) { $slide_id = '&id='.$first_slider_id;}
				else if(($slider_id+1) != $last_tab_id && count($tabs)!=1) { $slide_id = '&id='.$last_tab_id;}
				else if($slider_id==$last_tab_id && count($tabs)==1)  { $slide_id = "";}
				else { $slide_id = '&id='.($slider_id+1);}
				echo '<script>
						window.location.href="admin.php?page=apargslider'.$slide_id.'"
					</script>';
				delete_slider($slider_id);
			}
			else
			{
				include("slider_form.php");
			}
			
		}	
	}
	
}

function aparg_slider_func($atts){
		add_action('wp_head', 'load_custom_files');	
		require_once "dbase.php";
		$sliders = get_all_sliders();
		$get_slides_content = get_slider_data($atts['id']);
		$slide_option = get_slider_settings($atts['id']); 
		
		$img='';
		$dsc='';
		$img_titles = array();
		if(!empty($slide_option) && !empty($get_slides_content))
		{
			foreach($get_slides_content as $key => $value)
			{
				if($key != count($get_slides_content)-1)
				{
					$img.= $value->slide_url.'*';
					$dsc.= $value->description.'*';
				}
				else
				{
					$img.= $value->slide_url;
					$dsc.= $value->description;
				}
				$img_titles[$key] =  $value->slide_title;
			}
			foreach($slide_option['slider_options'] as $key => $value):
					$str.= ''.$key.'='.$value.' ';	
				endforeach;
				
			extract( shortcode_atts( array(
				'wrapper_class'=>'apargSlider',
				'images' => ''.$img.'',
				'desc' => ''.$dsc,
				'options'=>''.rtrim($str).'',
			), $atts ));
			$images = explode('*',$images);			
			$descs = explode('*',$desc);
			$opt = explode(' ',$options);
			$slider_settings=array();
			foreach($opt as $val)
			{
				$s = explode('=',$val);
				$slider_settings[$s[0]] = $s[1];
			}
			
			$output.="<script>jQuery(window).load(function() {  ";	
			
			$pauseOnHover = ($slider_settings['pauseOnHover']=="0")?"false":"true";
			$controlNav = ($slider_settings['controlNav']=="0")?"false":"true";
			$directionNav = ($slider_settings['directionNav']=="0")?"false":"true";
			$randomize = ($slider_settings['randomize']=="0")?"false":"true";
			
			$itemWidth = (($slider_settings['carousel_item_width']!="")? 'itemWidth:'.$slider_settings['carousel_item_width'].',':'');
			$item_margin = 'itemMargin:'.(($slider_settings['carousel']=="1")? 10:0);
			
			$slider_width = (isset($slider_settings['slider_width']) && $slider_settings['slider_width']!="")?$slider_settings['slider_width']:"100%";
			$slider_height = (isset($slider_settings['slider_height']) && $slider_settings['slider_height']!="")?$slider_settings['slider_height']:"auto";
			
			$smoothHeight = ((preg_match('/(px)/',$slider_height,$result))>0)?'false': 'true';
			
			$output.="jQuery('.".$wrapper_class."#".$atts['id']." .flexslider').flexslider({
						animation: '".$slider_settings['animation']."',
						
						animationLoop: ".(($slider_settings['carousel']=="1")?"false":"true").",
						".$itemWidth."
						".$item_margin.",
						controlNav: ".$controlNav.", 
						touch: false,	
						keyboard: true,
						
						smoothHeight: ".$smoothHeight.", 
						randomize: ".$randomize.",
						bigSliderDuration: ".$slider_settings['desc_duration'].",	
						pauseOnHover: ".$pauseOnHover.",
						directionNav:".$directionNav.", 
						slideshowSpeed: ".$slider_settings['slideshowSpeed'].",
						animationSpeed: ".$slider_settings['animationSpeed'].",
						descBgColor: '".$slider_settings['desc_bg_color']."',
						descTextColor: '".$slider_settings['desc_text_color']."',
						bigSliderWrapper:'".$wrapper_class."',
						sliderId:".$atts['id'].",
						start: sliderStart,
						before: sliderBefore,
						after: sliderAfter
						});
					});";
			$output.="</script>";
			$output.="<script type='text/javascript' src='".plugins_url('js/big_slider.js', __FILE__ )."'></script>";
			
			$output.= '<div class="apargSlider" id="'.$atts['id'].'" style="width:'.$slider_width.'; '.(($slider_settings['animation']=="fade" && $slider_height=="auto")?'':'height:'.$slider_height.';').' '.(($slider_settings['animation']=="fade")?'overflow:hidden;':'').'"><div class="flexslider"><ul class="slides">';
			
			foreach($images as $key=>$image){

				$output.= '<li><img src="'.get_option('siteurl')."".$image.'" alt="'.$img_titles[$key].'"><div class="captionWrapper" >';
				$tempDescs=explode('%APARG%',$descs[$key]);
				if($tempDescs[0]!=="NULL")
				{
					foreach($tempDescs as $tempDesc){				
						if($tempDesc!="")
						{
							$output.='<p class="flex-caption">'. apply_filters( 'the_title', $tempDesc ).'</p>';
						}
					}
				}	
				$output.= '</div></li>';
			}
			$output.= '</ul></div></div>';	
			
			
		}		
		
		if($slider_settings['animation']=="fade"){
			$output.="<script>jQuery(window).load(function(){";
			$output.="jQuery('.apargSlider .flexslider .slides li').addClass('fixHeight');";
			$output.="});</script>";
		}
		
		return $output;			
}
add_shortcode( 'aparg_slider', 'aparg_slider_func' );
?>