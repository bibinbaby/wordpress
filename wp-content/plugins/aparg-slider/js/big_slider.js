function resizeCapions(slider) {
	var sliderWidth = jQuery('.'+slider.bigSliderWrapper+'#'+slider.sliderId).outerWidth(true);
	var maxSliderWidthToResize = 800;
	var margin_bottom = 10;
	var font_size = 16;
	var padding = 12; 
	var line_height = 20;
	var captionFactor = (sliderWidth < maxSliderWidthToResize)?sliderWidth / maxSliderWidthToResize:1;
	jQuery('.'+slider.bigSliderWrapper + '#'+slider.sliderId+' .flexslider .flex-caption').each(function(i) {
		jQuery(this).css('margin-bottom', margin_bottom * captionFactor + 'px');
		jQuery(this).css('font-size', font_size * captionFactor + 'px');
		jQuery(this).css('padding', padding * captionFactor + 'px');	
		jQuery(this).css('line-height', line_height * captionFactor + 'px');
		jQuery(this).css('text-transform', 'uppercase');
		jQuery('.'+slider.bigSliderWrapper+'#'+slider.sliderId+' .captionWrapper').css('line-height', line_height * captionFactor + 'px');

	});
}

function sliderStart(slider){
	
	jQuery('.'+this.bigSliderWrapper + '#'+this.sliderId+' .flexslider .slides li').css('margin', (this.itemMargin/2)+'px');
	jQuery('.'+this.bigSliderWrapper + '#'+this.sliderId+' .flexslider .flex-caption').css('background-color', this.descBgColor);
	jQuery('.'+this.bigSliderWrapper + '#'+this.sliderId+' .flexslider .flex-caption').css('color', this.descTextColor);
	jQuery('.'+this.bigSliderWrapper+'#'+this.sliderId+' .flexslider .captionWrapper .flex-caption').css('visibility','visible');
	jQuery('.'+this.bigSliderWrapper+'#'+this.sliderId+' .flexslider .slides li').not('.flex-active-slide').find('.captionWrapper .flex-caption').hide();
	this.captionStyle = new Object();
	resizeCapions(this);
	var _that = this;
	jQuery(window).resize(function() {
		resizeCapions(_that);
	});	
	if(_that.smoothHeight===false) jQuery('.'+this.bigSliderWrapper + '#'+this.sliderId+' .flexslider, .'+this.bigSliderWrapper + '#'+this.sliderId+' .flex-viewport').addClass('wholesized');	
}
function sliderBefore(slider){
	var delay = 0;
	var _that = this;
	
	var sliderWidth = jQuery('.'+this.bigSliderWrapper+'#'+this.sliderId).outerWidth(true);
		jQuery('.'+this.bigSliderWrapper+'#'+this.sliderId+' .flex-active-slide .captionWrapper .flex-caption').each(function(index) {

			offset = jQuery('.'+_that.bigSliderWrapper+'#'+_that.sliderId+' .flex-active-slide .captionWrapper').position().left;
			animateTo = sliderWidth - offset;
			
			jQuery(this).css('left', '0px').show();
			
			

			var that = this;
			setTimeout(function() {
				jQuery(that).animate({
					'left': animateTo
				}, _that.bigSliderDuration
						);
										
			}, index * _that.bigSliderDuration);
			delay = (index + 1) * _that.bigSliderDuration;
		});
		
		return delay;
}
function sliderAfter(slider){
	var _that = this;
	
	jQuery('.'+this.bigSliderWrapper+'#'+this.sliderId+' .flex-active-slide .captionWrapper .flex-caption').each(function(index) {

			offset = jQuery('.'+_that.bigSliderWrapper+'#'+_that.sliderId+' .flex-active-slide .captionWrapper').position().left + jQuery(this).outerWidth();
			animateTo = 0;
			jQuery(this).css('left', '-' + offset + 'px').show();
			var that = this;
			setTimeout(function() {
				jQuery(that).animate({
					'left': animateTo
				}, _that.bigSliderDuration
						);

			}, index * _that.bigSliderDuration);
	});	
	var img_h = (jQuery('.flexslider').find('.slides > li').eq(slider.currentSlide).find('img').innerHeight())?jQuery('.flexslider').find('.slides > li').eq(slider.currentSlide).find('img').innerHeight():"280";
	var box = jQuery('.flexslider').find('.slides > li').eq(slider.currentSlide);		
}