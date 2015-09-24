<div class='wrap'>
	<h2>APARG Slider Plugin</h2>
	 <!-- **** 
	 
	 deleted
	 --> 
	<div id="submit_error_msg" class="submit_error_msg" title="Submit Notifications" style="display:none">
		<p>You must upload at least 2 images, before submiting!</p>
	</div>
	
	<div id="add_slider_msg" class="submit_error_msg" title="Add Slider Notifications" style="display:none">
		<p>You should add slider by clicking on '+' tab.</p>
	</div>
	
	<div id="delete_slide_msg" class="delete_slide_msg" title="Delete Slide Notifications" style="display:none">
		<p>Are you sure to delete this slide?</p>
	</div>
	
	<div id="delete_description_msg" class="delete_description_msg" title="Delete Description Notifications" style="display:none">
		<p>Are you sure to delete this slide description?</p>
	</div>
	
	<div id="descriptions_limit_msg" class="descriptions_limit_msg" title="Limit Description Count" style="display:none">
		<p>Sorry, but you pass descriptions limit(4 descriptions of each slide).</p>
	</div>
	
	
		
	<div class="nav-tabs-nav" id="aparg_slider_tabs">
		<div class="nav-tabs-wrapper">
			<div class="nav-tabs" >
				<?php 
					$slider_tabs = get_all_sliders();
					foreach($slider_tabs as $id => $slider)
					{
						if($id==0){ $first_slider_id = $slider->slider_id; }
						$last_tab_id = $slider->slider_id;
						if($slider->slider_id == count($slider_tabs)){ $active_tab = "nav-tab-active"; } else { $active_tab = ""; } 					
						$tabs.= '<a href="admin.php?page=apargslider&id='.$slider->slider_id.'" id="'.$slider->slider_id.'" class="tabs"><span class="nav-tab '.$active_tab.'" id="slide_N_'.$slider->slider_id.'">'.$slider->slider_name.'</span></a>';
					}
					echo $tabs;
					if($_GET['id'] && count($slider_tabs)>0) $page_id = $_GET['id'];
					else if(!isset($_GET['id']) && count($slider_tabs)>0) $page_id = $first_slider_id;
					else $page_id = 0;
				?>
				<a href="admin.php?page=apargslider&slider=new&id=<?php echo (count($slider_tabs)>0)? ($last_tab_id+1): 1; ?>" class="nav-tab menu-add-new" id="add-new-slider">
					<abbr title="Add slide">+</abbr>
				</a>
			</div>
		</div>
	</div>
	<script>
		jQuery(document).ready(function(){
			jQuery('.nav-tab-active').removeClass('nav-tab-active');
			id = "<?php echo $page_id; ?>";
			jQuery('#slide_N_'+id).addClass('nav-tab-active');
			jQuery("#submit_error_msg,#add_slider_msg,#descriptions_limit_msg").dialog({
				autoOpen: false, 
				buttons : {
					"Ok" : function() {
					  jQuery(this).dialog("close");
					}
				}
			});
			
			var param = window.location.search.substr(1);
			var params_array = param.split ("&");
			var params = {};

			for ( var i = 0; i < params_array.length; i++) {
				var temp_array = params_array[i].split("=");
				params[temp_array[0]] = temp_array[1];
			}
			var sliders_id = params.id;
			
			if(sliders_id==="undefined" || typeof sliders_id === "undefined")
			{
				slider_id = 0;
				jQuery('#AddImage').live('click',function(e){
					jQuery('#add_slider_msg').dialog('open');
				});
			}
			else { slider_id = sliders_id;}
			jQuery('#submit_'+slider_id).click(function(e){
				if(slider_id==0)
				{
					jQuery('#add_slider_msg').dialog('open');
					e.preventDefault();
				}
				else
				{
					if(jQuery('.row').size()<2)
					{
						jQuery('#submit_error_msg').dialog('open');
						e.preventDefault();
					}
				}
			});
			
			
			
						
		});
	</script>
	<?php if($page_id==0): ?>
	<input type="button" onClick="location.href='admin.php?page=apargslider&slider=new&id=1'" class="button" id="add-first-slider" value="Add My First Slider">
	<?php endif; ?>
	<form class="slider_form" method="post" name="slider_form_<?php echo $page_id; ?>" id="frm_<?php echo $page_id; ?>">
	<?php if($page_id!=0): ?>
		<div class="slider_container">
			<div class="left">
				<table class="widefat sortable" id="slide_img_container">
					<thead>
						<tr>
							<th>
								Slides
								<button id="AddImage" class="button alignright add-slide" data-editor="content"  title="Add Image">
								<span></span>Add Image</button> 
								<div style="display:none;" class="close_url"><?php echo plugins_url('images/close_delete.png',__FILE__ ); ?></div>		
							</th>
						</tr>
					</thead>
					<tbody id="img_cont" class="ui-sortable"></tbody>
				</table>
			</div>
		</div>
		<?php endif; ?>
		<div class="slider_settings" id="slider_settings_<?php echo $page_id; ?>">
		<?php if($page_id!=0): ?>
				<table class="widefat">
					<thead>
						<tr style="width: 100%;">
							<th  colspan="2">
								Settings
								<input class="button button-primary" type="submit" name="save_slider_<?php echo $page_id; ?>" id="submit_<?php echo $page_id; ?>" class="saveslider"  value="Save">	
							</th>	
						</tr>
					</thead>
					<tbody>
						<tr>
							<td width="67%" class="slider_w">
								<label for="carousel">Slider Width: </label>
								<p>(Format: in px or %)</p>
								<p>(If checked set default width)</p>
							</td>
							<td width="33%">
								<div class="slider_size">
									<input type="text" id="slider_width" name="slide_options[slider_width]" value="" disabled />
									<p>
										<input type="checkbox" id="slider_width_def" name="slide_options[slider_width_def]" value="1" checked="checked">	
										<span>100%</span>										
									</p>	
								</div>
							</td>
						</tr>
						<tr>
							<td width="67%" class="slider_h">
								<label for="carousel">Slider Height: </label>
								<p>(Format: in px or %)</p>	
								<p>(If checked set default height)</p>	
							</td>
							<td width="33%">
								<div class="slider_size">
									<input type="text" id="slider_height" name="slide_options[slider_height]" value="" disabled />
									<p>
										<input type="checkbox" id="slider_height_def" name="slide_options[slider_height_def]" value="1" checked="checked">
										<span>auto</span>
									</p>	
								</div>	
							</td>
						</tr>
						
						
						<tr>
							<td width="67%"><label for="slideshowSpeed">Slide Show Speed:</label></td>
							<td width="33%"><input type="text" id="slideshowSpeed" name="slide_options[slideshowSpeed]" value="4000"></td>
						</tr>
						<tr>
							<td width="67%"><label for="desc_duration">Description Speed:</label></td>
							<td width="33%"><input type="text" id="desc_duration" name="slide_options[desc_duration]" value="200"></td>	
						</tr>
						<tr>
							<td width="67%"><label>Description Background Color:</label></td>
							<td width="33%" ><div class="choose_color"><input type="text" id="desc_bg_color" name="slide_options[desc_bg_color]" autocomplete="off" value="#DB3256"><div id="current_bg_color"   data-color="#db3256"></div></div></td><!-- **** -->
							
						</tr>
						<tr>
							<td width="67%"><label>Description Text Color:</label></td>
							<td width="33%" ><div class="choose_color"><input type="text" id="desc_text_color" name="slide_options[desc_text_color]" autocomplete="off" value="#ffffff" ><div id="current_text_color" data-color="#ffffff"></div></div></td> <!-- **** --> 
						</tr>
						<tr>
							<td width="67%"><label for="animation">Animation:</label></td>
							<td width="33%">
								<select type="text" id="animation" name="slide_options[animation]">
									<option value="fade">Fade</option>
									<option value="slide" selected="selected">Slide</option>
								</select>
							</td>
						</tr>
						<tr>
							<td width="67%"><label for="animationSpeed">Animation Speed:</label></td>
							<td width="33%"><input type="text" id="animationSpeed" name="slide_options[animationSpeed]" value="1000"></td>
						</tr>
						<!-- ----------------------- -->
						<tr>
							<td width="67%"><label for="carousel">Carousel: </label></td>
							<td width="33%">
								<input type="checkbox" id="carousel" value="1" name="slide_options[carousel]" checked="checked">
							<!--	<input type="hidden" id="carouselHidden"  value="1" /> -->
							</td>
						</tr>
						
						<tr>
							<td width="67%"><label for="carousel_item_width">Carousel Item Width: </label></td>
							<td width="33%"><input type="text" id="carousel_item_width" name="slide_options[carousel_item_width]" value="210" /></td>
						</tr>
						<!-- ----------------------- -->
						
						<tr>
							<td width="67%"><label for="randomize">Randomize:</label></td>
							<td width="33%">
								<input type="checkbox" id="randomize" name="slide_options[randomize]" value="0">
							<!--	<input type="hidden" id="randomizeHidden" name="slide_options[randomize]" value="0"> -->
							</td>
						</tr>
						<tr>
							<td width="67%"><label for="controlNav">Paging Navigation:</label></td>
							<td width="33%">
								<input type="checkbox" id="controlNav" name="slide_options[controlNav]" value="1" checked="checked">
								<!-- <input type="hidden" id="controlNavHidden"  value="1"> -->
							</td>
						</tr>
						
						<tr>
							<td width="67%"><label for="directionNav">Direction Navigation:</label></td>
							<td width="33%">
								<input type="checkbox" id="directionNav" value="1" name="slide_options[directionNav]" checked="checked">
								<!-- <input type="hidden" id="directionNavHidden"  value="1"> -->
							</td>
						</tr>
						<tr>
							<td width="67%"><label for="pauseOnHover">Pause On Hover:</label></td>
							<td width="33%">
								<input type="checkbox" id="pauseOnHover" value="1" name="slide_options[pauseOnHover]" checked="checked">
							<!--	<input type="hidden" id="pauseOnHoverHidden"  value="1"> -->
							</td>
						</tr>
						<tr>
							<td><input type="button" onClick="location.href='admin.php?page=apargslider&slider=delete&id=<?php echo $page_id; ?>'" class="button" id="delete-slide" value="Delete This Slider"></td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
				<table class="widefat" id="aparg_slider_usage">
					<thead>
						<tr>
							<th>Usage</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="highlight">Shortcode</td>
						</tr>
						<tr>
							<td>[aparg_slider id=<?php echo $page_id; ?>]</td>
						</tr>
						<tr>
							<td class="highlight">Template Include</td>
						</tr>
						<tr>
							<td>do_shortcode(&quot;[aparg_slider id=<?php echo $page_id; ?>]&quot;)</td>
						</tr>
					</tbody>
				</table>
				<?php endif; ?>
				<table class="widefat" id="aparg_slider_info">
					<thead>
						<tr>
							<th>Slider Info</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>This plugin let users to create multiple sliders with descriptions for each slide.</td>
						</tr>
						<tr>
							<td class="highlight"><a href="http://www.aparg.com" target="_blank">http://www.aparg.com</a></td>
						</tr>
					</tbody>
				</table>
		</div>
	</form>	
</div>