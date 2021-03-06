( function( $ ) {
	"use strict";
	
	
	var wbcom_memberCarousel = function() {
		
		jQuery( '.member-carousel-container' ).each( function() {			
			//var elementSettings = $(this).parent().parent().data( 'settings' ),
			
			var elementSettings = $(this).data( 'settings' ),
				slidesToShow = +elementSettings.slides_to_show || 3,
				isSingleSlide = 1 === slidesToShow,
				defaultLGDevicesSlidesCount = isSingleSlide ? 1 : slidesToShow,
				elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;
			
			var swiperOptions = {
				slidesPerView: slidesToShow,
				loop: 'yes' === elementSettings.infinite,
				speed: elementSettings.speed,
				handleElementorBreakpoints: true
			};
			swiperOptions.breakpoints = {};
			swiperOptions.breakpoints[0] = {
				slidesPerView: +elementSettings.slides_to_show_mobile || 1,
				slidesPerGroup: +elementSettings.slides_to_scroll_mobile || 1
			};
			
			swiperOptions.breakpoints[elementorBreakpoints.mobile.value] = {
				slidesPerView: +elementSettings.slides_to_show_tablet || 1,
				slidesPerGroup: +elementSettings.slides_to_scroll_tablet || 1
			};
			swiperOptions.breakpoints[elementorBreakpoints.tablet.value] = {
				slidesPerView: +elementSettings.slidesToShow || defaultLGDevicesSlidesCount,
				slidesPerGroup: +elementSettings.slides_to_scroll || 1
			};

			if ('yes' === elementSettings.autoplay) {
				swiperOptions.autoplay = {
					delay: elementSettings.autoplay_speed,
					disableOnInteraction: 'yes' === elementSettings.pause_on_interaction
				};
			}

			if (isSingleSlide) {
				swiperOptions.effect = elementSettings.effect;

			  if ('fade' === elementSettings.effect) {
				swiperOptions.fadeEffect = {
					crossFade: true
				};
			  }
			} else {
				swiperOptions.slidesPerGroup = +elementSettings.slides_to_scroll || 1;
			}

			if (elementSettings.image_spacing_custom) {
				swiperOptions.spaceBetween = elementSettings.image_spacing_custom.size;
			}

			var showArrows = 'arrows' === elementSettings.navigation || 'both' === elementSettings.navigation,
				showDots = 'dots' === elementSettings.navigation || 'both' === elementSettings.navigation;

			if (showArrows) {
				swiperOptions.navigation = {
					prevEl: '.elementor-swiper-button-prev',
					nextEl: '.elementor-swiper-button-next'
				};
			}

			if (showDots) {
				swiperOptions.pagination = {
					el: '.swiper-pagination',
					type: 'bullets',
					clickable: true
				};
			}
			
			/* */			
			var member_swiper =	new Swiper($(this), swiperOptions );
			
			if ( elementSettings.pause_on_hover == 'yes' ) {
				$( this ).mouseenter(function() {
					member_swiper.autoplay.stop();					
				});

				$( this ).mouseleave(function() {
					member_swiper.autoplay.start();					
				});
			}
		});
	}	
	
	jQuery(window).on('elementor/frontend/init', () => {	 
		elementorFrontend.hooks.addAction('frontend/element_ready/wbcom-members-carousel.default', wbcom_memberCarousel);
	});

})( jQuery );