// Make disable and take away clickablity from slider's description options 
var switch_opt;
function switchopt(){
	if(jQuery(".desc").size()==0){
		switch_opt ="Off";
		jQuery("#desc_bg_color,#desc_text_color").attr("disabled",true);
		jQuery("#desc_bg_color,#desc_text_color").css("opacity","0.5");
		jQuery("#current_bg_color,#current_text_color").css("opacity","0.5");
	}
	else {
		jQuery(".desc").each(function(){
			if(jQuery(this).val()!=""){
				switch_opt ="On";
				jQuery("#desc_bg_color,#desc_text_color").attr("disabled",false);
				jQuery("#desc_bg_color,#desc_text_color").css("opacity","1");
				jQuery("#current_bg_color,#current_text_color").css("opacity","1");		
				return false;
			}
			else {
				switch_opt ="Off";
				jQuery("#desc_bg_color,#desc_text_color").attr("disabled",true);
				jQuery("#desc_bg_color,#desc_text_color").css("opacity","0.5");
				jQuery("#current_bg_color,#current_text_color").css("opacity","0.5");
			}
		});	
	}
}

function default_sizes(elem,el){
	if(jQuery(elem).is(':checked')){
		jQuery(elem).val(1);
		jQuery(elem).attr("checked",true);	
		jQuery('#'+el).attr("disabled",true);
		jQuery('#'+el).val('');
	}
	else{
		jQuery(elem).val(0);
		jQuery(elem).attr("checked",false);	
		jQuery('#'+el).attr("disabled",false);
	}		
}


// Make disable and take away clickablity from slider's carousel item width options 
function switch_carousel(elem){
		if(jQuery(elem).is(":checked")){
			//jQuery("#carouselHidden").val(1);
			jQuery(elem).val(1);
			jQuery(elem).attr("checked",true);	
			jQuery('#carousel_item_width').attr("disabled",false);
			jQuery("#animation option[value='slide']").attr('selected',true);
		}
		else{
		//	jQuery("#carouselHidden").val(0);
			jQuery(elem).val(0);
			jQuery(elem).attr("checked",false);
			jQuery('#carousel_item_width').attr("disabled",true);
		
		}
}
/****************************************/

function sorting_slides(){
	if(jQuery("#img_cont").size() > 0  && jQuery("#img_cont").children().size()>=2)
	{
		var sortable_container = jQuery("#img_cont").height(); 
		
		jQuery("#slide_img_container tbody").sortable({
			items: "tr.sortable-row",
			cursor:"move",
			start: function(e, ui){
				ui.placeholder.height(ui.item.height());
				jQuery(this).children("tr.sortable-row").height(ui.item.height());
			},
			helper: function(e, ui) {
				ui.children().each(function() {
					jQuery(this).width(jQuery(this).width());
					
				});
				ui.height(ui.height());
				
				return ui;
			},
			stop: function(event,ui){
				jQuery(this).children("tr.sortable-row").height("auto");
			},
			update: function(e,ui)
			{
				jQuery(".row").each(function(row_id){
					jQuery(this).attr("id","row_"+row_id);
					
					jQuery(this).find("table").attr("id","table_"+row_id);
					jQuery(this).find(".hidden_img").attr("name","img["+row_id+"]");
					jQuery(this).find(".hidden_title").attr("name","title["+row_id+"]");
					jQuery(this).find(".addinput").attr("id", row_id);
					jQuery(this).find(".desc").each(function(i)
					{
						jQuery(this).attr("id","desc_"+row_id+""+i);
						jQuery(this).attr("name","desc["+row_id+"]["+i+"]");	
					});
					jQuery(this).find(".delete_img").attr("deleted_row_id",row_id);
				});
				warn_on_unload = "Leaving this page will cause any unsaved data to be lost.";
			}
		});	
	}		
}

/* **** */

jQuery(document).ready(function(){ 
	var check_changes = false; 
	var warn_on_unload="";
	
	/* **** */
		var addimg_uploader;
		
		jQuery('#AddImage').click(function(e) {
			
			e.preventDefault();
			//If the uploader object has already been created, reopen the dialog
			if (addimg_uploader) {
				addimg_uploader.open();
				return;
			}
			//Extend the wp.media object
			addimg_uploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Image',
				button: {
					text: 'Choose Image'
				},
				multiple: false
			});
	 
			//When a file is selected, grab the URL
			addimg_uploader.on('select', function() {
				attachment = addimg_uploader.state().get('selection').first().toJSON();
				var rows = jQuery('.row').size();
				var url = jQuery('.close_url').text(); 
				var addContent = jQuery('#img_cont');
				var section_id = jQuery('.addinput').attr('id');
				var str = '<tr class="row sortable-row" id="row_'+rows+'" width="100%"><td width="99%" height="99%"><table id="table_'+rows+'" width="100%">';
				str+='<tr width="100%"><td width="25%"><a href="#" style="background-image:url('+attachment.url+')" class="current_img" alt="'+attachment.alt+'"><span>Click to change image</span></a>';
				str+='<input type="hidden" class="hidden_img" name="img['+rows+']" value="'+attachment.url+'"><input type="hidden" class="hidden_title" name="title['+rows+']" value="'+attachment.alt+'"></td>';
				str+= '<td width="68%" class="addinput" id="'+rows+'"><button class="button addDescription" name="addDescription" ><span></span>Add Description</button>&nbsp;&nbsp;';
				str+='<button class="button empty_desc" name="empty_desc" id="delete_desc_row_'+rows+'"><span></span>Delete Descriptions</button>';
				str+= '<p id="curr_desc_0" class="current_description"><input type="text" class="desc" id="desc_'+rows+'0" name="desc['+rows+'][0]" value="" placeholder="Type a description" />';
				str+='<a href="#" class="delete_desc" remove_desc="0" style="background-image:url('+url.replace("close_delete.png","")+'trash_can_delete.png);"></a></p>';
				str+= '</td><td width="5%"><a href="#" deleted_row_id="'+rows+'" class="delete_img" ><img src="'+url+'"></a></td></tr></table></td></tr>';
				jQuery(str).appendTo(addContent);
					
					section_id++;
					rows++;	
						
				
					/* **** */
					if(jQuery("#img_cont .row.sortable-row").size()>=1){
						switchopt();
						jQuery(".desc").on("blur",function(){
							switchopt();
						});
					}	
					
					sorting_slides()
					/* **** */
					
					return false; 
					
			});
			addimg_uploader.on('close', function() {
				jQuery('#add_slider_msg').dialog('close');
			});
			//Open the uploader dialog
			addimg_uploader.open();
			warn_on_unload = "Leaving this page will cause any unsaved data to be lost.";
	});	
	
	jQuery('#img_cont').on('click','.addDescription', function(){
		var parent = jQuery(this).parent();
		var url = jQuery('.close_url').text(); 
		var i = jQuery(this).parent('.addinput').children("p").size();	
		var current_section_id = parent.attr('id');
		var curent_section =  jQuery(this).parent('#'+current_section_id);
		if(i>=4)
		{			
			jQuery('#descriptions_limit_msg').dialog('open');
		}
		else
		{
			jQuery('<p id="current_desc_'+i+'" class="current_description"><input type="text" class="desc" id="desc_' +current_section_id +'' +i +'" name="desc[' +current_section_id +'][' +i +']" value="" placeholder="Type a description"  /><a href="#" class="delete_desc" remove_desc="'+i+'" style="background-image:url('+url.replace("close_delete.png","")+'trash_can_delete.png)"></a></p>').appendTo(curent_section);
		}
		i++;
		
		if(i==1)
		{			
			jQuery('.empty_desc#delete_desc_row_'+current_section_id).show();
		}
		
		/* **** */
		switchopt();
		jQuery(".desc").on("blur",function(){
			switchopt();
		});
		/* **** */
 		
		warn_on_unload = "Leaving this page will cause any unsaved data to be lost.";
		return false;
		
	});	
	
	jQuery('#img_cont').on('click','.empty_desc',function(e){
		e.preventDefault();
		jQuery(this).parent().find('p').remove();
		jQuery(this).hide();
		
		switchopt();
		warn_on_unload = "Leaving this page will cause any unsaved data to be lost.";
	});
	jQuery('#desc_bg_color, #desc_text_color').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			jQuery(el).val('#'+hex);
			if(jQuery(el).attr('id')=="desc_bg_color")
			{
				jQuery('#current_bg_color').css('background-color','#'+hex);
			}
			else
			{
				jQuery('#current_text_color').css('background-color','#'+hex);
			
			} 
			jQuery(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			jQuery(this).ColorPickerSetColor(this.value);
		}
	}).bind('keyup', function(){
		jQuery(this).ColorPickerSetColor(this.value);
	});
	
// **** //
	// use ColorPicker plugin to choose color
		jQuery('#current_bg_color').ColorPicker({
			color: jQuery('#current_bg_color').attr('data-color'),
			onShow: function (colpkr,el) {
				if(switch_opt=="On"){
					jQuery(colpkr).fadeIn(500);
				}
				return false;
			},
			onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb,el) {
				jQuery(el).val('#'+hex);
				jQuery(el).ColorPickerHide();
			},
			
			onSubmit: function(hsb, hex, rgb, el) {
				jQuery(el).css('background-color','#'+hex);
				jQuery('#current_bg_color').attr('data-color','#'+hex);
				jQuery('#desc_bg_color').val('#'+hex);
				jQuery(el).ColorPickerHide();
			}
		});

		jQuery('#current_text_color').ColorPicker({
			color: jQuery('#current_text_color').attr('data-color'),
			onShow: function (colpkr) {
				if(switch_opt=="On"){
					jQuery(colpkr).fadeIn(500);
				}	
				return false;
			},
			onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb,el) {
				jQuery(el).val('#'+hex);
				jQuery(el).ColorPickerHide();
			},
			onSubmit: function(hsb, hex, rgb, el) {
				jQuery(el).css('background-color','#'+hex);
				jQuery('#current_text_color').attr('data-color','#'+hex);
				jQuery('#desc_text_color').val('#'+hex);
				jQuery(el).ColorPickerHide();
			}
		});
		
// **** //	
	
	jQuery('#randomize, #controlNav, #pauseOnHover, #directionNav').click(function(){
		if(jQuery(this).attr('checked')=="checked")
		{
			jQuery(this).attr('checked',true);
			jQuery(this).val(1);
			//jQuery('#'+this.id+'Hidden').val(1);
		}
		else 
		{
			jQuery(this).attr('checked',false);
			jQuery(this).val(0);
			//jQuery('#'+this.id+'Hidden').val(0);
		}
		warn_on_unload = "Leaving this page will cause any unsaved data to be lost.";
	});	

	jQuery('#img_cont').on('click','.delete_img',function(){
		deleted_id = jQuery(this).attr('deleted_row_id');
		jQuery("#delete_slide_msg").dialog({
			  autoOpen: true, 
			  buttons : {
							"Yes" : function() {
							jQuery('.row').each(function(){
								row_id = jQuery(this).attr('id').replace("row_","");
								if(row_id > deleted_id)
								{
									row_id--;
									jQuery(this).attr('id','row_'+row_id);
									
									jQuery(this).find('table').attr('id','table_'+row_id);
									jQuery(this).find('.hidden_img').attr('name','img['+row_id+']');
									jQuery(this).find('.hidden_title').attr('name','title['+row_id+']');
									jQuery(this).find('.addinput').attr('id', row_id);
									jQuery(this).find('.desc').each(function(i){
										jQuery(this).attr('id','desc_'+row_id+''+i);
										jQuery(this).attr('name','desc['+row_id+']['+i+']');	
									});
									jQuery(this).find('.delete_img').attr('deleted_row_id',row_id);
								}
							});
								jQuery('#row_'+deleted_id).remove();
								jQuery(this).dialog("close");
								
								/* **** */
									switchopt();
								/* **** */
							},
							"No" : function() {
								jQuery(this).dialog("close");
							}
					    },
		});
		
		 warn_on_unload = "Leaving this page will cause any unsaved data to be lost.";
	});
		
			
	
	jQuery('#img_cont').on('click','.delete_desc',function(){
		deleted_id = jQuery(this).attr('remove_desc');
		deleted_cont = jQuery(this).parent();
		deleted_item = jQuery(this);
		row_id = jQuery(this).parent().parent().attr('id');
		jQuery("#delete_description_msg").dialog({
			  autoOpen: true, 
			  buttons : {
							"Yes" : function() {
								deleted_item.parent().parent().find('.desc').each(function(i){
									el = jQuery(this); 
									if(i == deleted_id)
									{
										deleted_cont.remove();
									}
									else if(i > deleted_id)
									{
										i--;
										el.attr('id','desc_'+row_id+''+i);
										el.attr('name','desc['+row_id+']['+i+']');
										el.next().attr('remove_desc',i);
										el.parent().attr('id','current_desc_'+i);
									}
									if(jQuery('.addinput#'+row_id+' p').size()==0)
									{
										jQuery('.empty_desc#delete_desc_row_'+row_id).hide();
									}
									/* **** */
										switchopt();
									/* **** */
								});
								jQuery(this).dialog("close");
							},
							"No" : function() {
								jQuery(this).dialog("close");
							}
					    },
		});
		
		 warn_on_unload = "Leaving this page will cause any unsaved data to be lost.";
	});	
	var custom_uploader;
	jQuery('#img_cont').on('click','.current_img',function(e) {
		current_img = jQuery(this);
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
			current_img.css('background-image','url(' + attachment.url + ')');
			current_img.attr('alt',attachment.alt);
			current_img.next().val(attachment.url);
			current_img.parent().find('.hidden_title').val(attachment.alt);
			 warn_on_unload = "Leaving this page will cause any unsaved data to be lost.";
        });
		custom_uploader.on('close', function() {
			jQuery('#add_slider_msg').dialog('close');
		});
        //Open the uploader dialog
        custom_uploader.open();

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
				slider_id = 1;
			}
			else { slider_id = sliders_id;}
    jQuery('input:text,input:checkbox,select').on('change', function() 
    {
		warn_on_unload = "Leaving this page will cause any unsaved data to be lost.";
    });
	
	jQuery('#submit_'+slider_id).click(function(){
		warn_on_unload = "";
	});
	
	window.onbeforeunload = function() { 
		if(warn_on_unload != '')
		{
			return warn_on_unload;
		}   
	}
	
	jQuery('#carousel').on('click',function(){
		switch_carousel(jQuery(this));
	});
	
	jQuery('#slider_width_def').on('click',function(){
		default_sizes(jQuery(this),'slider_width');
	});
	
	jQuery('#slider_height_def').on('click',function(){
		default_sizes(jQuery(this),'slider_height');
	});
	
	jQuery('#animation').on('change',function(){
		if(jQuery(this).val()=="fade"){
			//jQuery("#carouselHidden").val(0);
			jQuery('#carousel').val(0);
			jQuery('#carousel').attr("checked",false);	
			jQuery('#carousel_item_width').attr("disabled",true);
		}
		else{
		//	jQuery("#carouselHidden").val(1);
			jQuery('#carousel').val(1);
			jQuery('#carousel').attr("checked",true);	
			jQuery('#carousel_item_width').attr("disabled",false);
		}
	});
});

/* **** */
jQuery(window).load(function (){ 
	
	sorting_slides();
	switch_carousel(jQuery('#carousel'));	
	switchopt();
	
	jQuery(".desc").on("blur",function(){
		switchopt();
	});
		
	
})
/* **** */