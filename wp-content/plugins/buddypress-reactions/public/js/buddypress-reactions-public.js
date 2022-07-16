(function($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).on('ready', function() {

		/* Set Amination By Default */
		$('.bp-reactions-wrap.bp-reactions-animation-true .emoji-pick').each( function(){
			$(this).find('.emoji-lottie-holder').show();
            $(this).find('.emoji-svg-holder').hide();
            let emoji_id = $(this).data('emoji-id');

            if (typeof $(this).data('animation') == 'undefined') {
                let animation = bodymovin.loadAnimation({
                    container: $(this).find('.emoji-lottie-holder').get(0),
                    path: bpreactions.emojis_path + 'json/' + emoji_id + '.json?v=' + bpreactions.version,
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    name: emoji_id
                });
                $(this).data('animation', animation);
            } else {
                $(this).data('animation').play();
            }
		});

		/* Set Amination By Default for activity */
		$(document).on('mouseenter', '.bp-activity-react-button-wrapper .bp-activity-react-btn', function(e) {
			var emoji_pick = $(this).parent().find('.bp-reactions-animation-true');
			emoji_pick.find('.emoji-pick').each( function(){
				$(this).find('.emoji-lottie-holder').show();
				$(this).find('.emoji-svg-holder').hide();
				let emoji_id = $(this).data('emoji-id');

				if (typeof $(this).data('animation') == 'undefined') {
					let animation = bodymovin.loadAnimation({
						container: $(this).find('.emoji-lottie-holder').get(0),
						path: bpreactions.emojis_path + 'json/' + emoji_id + '.json?v=' + bpreactions.version,
						renderer: 'svg',
						loop: true,
						autoplay: true,
						name: emoji_id
					});
					$(this).data('animation', animation);
				} else {
					$(this).data('animation').play();
				}
			});
		});

		/* Set Amination on Single Hover */
        $(document).on('mouseenter', '.bp-activity-reactions.bp-reactions-animation-on_hover .emoji-pick, .bp-reactions-wrap.bp-reactions-animation-on_hover .emoji-pick', function(e) {

            $(this).find('.emoji-lottie-holder').show();
            $(this).find('.emoji-svg-holder').hide();
            let emoji_id = $(this).data('emoji-id');

            if (typeof $(this).data('animation') == 'undefined') {
                let animation = bodymovin.loadAnimation({
                    container: $(this).find('.emoji-lottie-holder').get(0),
                    path: bpreactions.emojis_path + 'json/' + emoji_id + '.json?v=' + bpreactions.version,
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    name: emoji_id
                });
                $(this).data('animation', animation);
            } else {
                $(this).data('animation').play();
            }

        });
        $(document).on('mouseleave', '.bp-activity-reactions.bp-reactions-animation-on_hover .emoji-pick, .bp-reactions-wrap.bp-reactions-animation-on_hover .emoji-pick', function(e) {
            if (typeof $(this).data('animation') != 'undefined') {
                $(this).data('animation').pause();
            }
            $(this).find('.emoji-lottie-holder').hide();
            $(this).find('.emoji-svg-holder').show();

        });

		/* Set Amination on all on hover  */
		$(document).on('mouseenter', '.bp-activity-reactions.bp-reactions-animation-on_hover_all .emoji-pick, .bp-reactions-wrap.bp-reactions-animation-on_hover_all .emoji-pick', function(e) {
			$('.bp-activity-reactions.bp-reactions-animation-on_hover_all .emoji-pick, .bp-reactions-wrap.bp-reactions-animation-on_hover_all .emoji-pick').each( function(){
				$(this).find('.emoji-lottie-holder').show();
				$(this).find('.emoji-svg-holder').hide();
				let emoji_id = $(this).data('emoji-id');

				if (typeof $(this).data('animation') == 'undefined') {
					let animation = bodymovin.loadAnimation({
						container: $(this).find('.emoji-lottie-holder').get(0),
						path: bpreactions.emojis_path + 'json/' + emoji_id + '.json?v=' + bpreactions.version,
						renderer: 'svg',
						loop: true,
						autoplay: true,
						name: emoji_id
					});
					$(this).data('animation', animation);
				} else {
					$(this).data('animation').play();
				}
			})

        });
        $(document).on('mouseleave', '.bp-activity-reactions.bp-reactions-animation-on_hover_all .emoji-pick, .bp-reactions-wrap.bp-reactions-animation-on_hover_all .emoji-pick', function(e) {
			$('.bp-activity-reactions.bp-reactions-animation-on_hover_all .emoji-pick,.bp-reactions-wrap.bp-reactions-animation-on_hover_all .emoji-pick').each( function(){
				if (typeof $(this).data('animation') != 'undefined') {
					$(this).data('animation').pause();
				}
				$(this).find('.emoji-lottie-holder').hide();
				$(this).find('.emoji-svg-holder').show();
			});

        });

        setInterval(function() {
            $('ul.activity-list li').each(function() {
                var activity_id = $(this).attr('id');
                var reacted_container = $('#' + activity_id + " > .reacted-count.content-actions").clone();
                $('#' + activity_id + " > .reacted-count.content-actions").remove();
                $('#' + activity_id + " .activity-content .activity-inner").after(reacted_container);
            });

        }, 500);


        $(document).on('click', '.reaction-options.emoji-picker .emoji-pick', function() {
            var emoji_id = $(this).data('emoji-id');
            var post_id = $(this).data('post-id');
            var post_type = $(this).data('type');
            var bprs_id = $(this).data('bprs-id');

            var emoji_url = bpreactions.emojis_path + 'svg/' + emoji_id + '.svg?v=' + bpreactions.version;


			var clicked_reaction_count = parseInt($(this).find( ".bp-rmoji-count-number" ).attr('data-count'));
			var active_reaction_count = parseInt($(this).parent().find('.active').find( ".bp-rmoji-count-number" ).attr('data-count'));
			if (isNaN(clicked_reaction_count)) clicked_reaction_count = 0;
			$(this).find( ".bp-rmoji-count-number" ).attr('data-count',  clicked_reaction_count + 1);
			$(this).find( ".bp-rmoji-count-number" ).text( bp_reactions_emoji_count_format(clicked_reaction_count + 1));


			var revert_count = active_reaction_count;
			if ( active_reaction_count > 0) {
				revert_count -= 1;
				$(this).parent().find('.active').find( ".bp-rmoji-count-number" ).attr('data-count', revert_count);
				$(this).parent().find('.active').find( ".bp-rmoji-count-number" ).text(bp_reactions_emoji_count_format(revert_count));
			}

			$('.reaction-options.emoji-picker .emoji-pick').removeClass('active');
			$(this).addClass('active');

            $.ajax({
                url: bpreactions.ajaxUrl,
                dataType: 'json',
                type: 'post',
                data: {
                    action: 'bpr_create_user_react_emoji_ajax',
                    emoji_id: emoji_id,
                    post_id: post_id,
                    post_type: post_type,
                    bprs_id: bprs_id,
                    ajax_nonce: bpreactions.ajax_nonce,
                },
                success: function(data) {
                    if (post_type == 'activity') {
                        $("#activity-" + post_id + " div#bp-reactions-post-"+ post_id + ".reacted-count.content-actions").replaceWith(data.container);
						$('#activity-' + post_id + ' .bp-post-react-icon').html('<img class="post-option-image" src="' + emoji_url + '" alt="">');					
                    } else {
						$("#bp-reactions-post-" + post_id + ".reacted-count.content-actions").replaceWith(data.container);
						$('#post-reactions-' + post_id + ' .bp-post-react-icon').html('<img class="post-option-image" src="' + emoji_url + '" alt="">');
					}
                }
            });

            

        });

        $(document).on('click', '.wbreacted-emoji-container, .total-reaction-counts', function() {

            var emoji_id = $(this).data('emoji_id');
            if (emoji_id == 'all') {
                var post_id = $(this).parent().parent().data('post-id');
                var post_type = $(this).parent().parent().data('post-type');
				var bprs_id = $(this).parent().parent().data('bprs-id');
            } else {
                var post_id = $(this).parent().parent().parent().data('post-id');
                var post_type = $(this).parent().parent().parent().data('post-type');
				var bprs_id = $(this).parent().parent().parent().data('bprs-id');
            }
			
            $.ajax({
                url: bpreactions.ajaxUrl,
                dataType: 'json',
                type: 'post',
                data: {
                    action: 'bpr_display_user_react_emoji_ajax',
                    emoji_id: emoji_id,
                    post_id: post_id,
                    post_type: post_type,
                    bprs_id: bprs_id,
                    ajax_nonce: bpreactions.ajax_nonce,
                },
                success: function(data) {
                    $('body').append('<div class="br-modal"><div class="bp-reachtions-overlay"><div class="bp-reaction-pop-container animate-slide-down"><div class="reaction-popup-close-button"><i class="br-icon br-icon-x"></i></div>' + data + '</div></div></div>');

                    if (emoji_id != 'all') {
                        $('.reaction-box-item .user-status').hide();
                        $('.reaction-box-item .user-status.bp-reacted-emoji-' + emoji_id).show();
                    }
                }
            });

        })

        $(document).on('click', '.reaction-popup-close-button, .bp-reachtions-overlay', function(event) {
            var className = $(event.target).attr('class');
			console.log(className);
            if (className == 'bp-reachtions-overlay' || className == 'br-icon br-icon-x' || className == 'reaction-popup-close-button') {
                $('.br-modal').remove();
            }

        });

        $(document).on('click', '.reaction-box .reaction-box-options ul .reaction-box-option', function() {
            $('ul .reaction-box-option').removeClass('active');
            $(this).addClass('active');
            var emoji_id = $(this).data('id');
            if (emoji_id != 'all') {
                $('.reaction-box-item .user-status').hide();
                $('.reaction-box-item .user-status.bp-reacted-emoji-' + emoji_id).show();
            } else {
                $('.reaction-box-item .user-status').show();
            }
        });

		function bp_reactions_emoji_count_format( count ) {

			var format = count;
			if ( count >= 1000000 ) {
				format = round( ( count / 1000000 ), 1 ) + 'M';
			} else if ( count >= 1000 ) {
				format = round( ( count / 1000 ), 1 ) + 'K';
			}

			return format;
		}
		
		
		$('.widget_bp_reactions_statistics_widget .wp-reaction-monthly-stats').each(function() {
			var data = $(this).data('chart-info');
			var text = $(this).data('text');
			
			var data_label = [] ;
			var data_value = [];
			$.each( data, function( key, value ) {
				data_label.push(key);
				data_value.push(value);			  
			});
			console.log(data);
			console.log(data_label);
			console.log(data_value);
			const  id = $(this).attr( 'id' )
			const ctx = document.getElementById(id)
			const myChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: data_label,
					datasets: [{
						label: bpreactions.reactions_count,
						data: data_value,
						backgroundColor: [							
							'rgba(54, 162, 235, 0.2)',							
						],
						borderColor: [							
							'rgba(54, 162, 235, 1)',							
						],
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
			
		});
		
		
		 $(document).on('click', '.bp-activity-react-button', function() {
            
            var post_id = $(this).data('post-id');
            var post_type = $(this).data('type');
            var bprs_id = $(this).data('bprs-id');

			
			/*
			var clicked_reaction_count = parseInt($(this).find( ".bp-rmoji-count-number" ).attr('data-count'));
			var active_reaction_count = parseInt($(this).parent().find('.active').find( ".bp-rmoji-count-number" ).attr('data-count'));
			if (isNaN(clicked_reaction_count)) clicked_reaction_count = 0;
			$(this).find( ".bp-rmoji-count-number" ).attr('data-count',  clicked_reaction_count + 1);
			$(this).find( ".bp-rmoji-count-number" ).text( bp_reactions_emoji_count_format(clicked_reaction_count + 1));


			var revert_count = active_reaction_count;
			if ( active_reaction_count > 0) {
				revert_count -= 1;
				$(this).parent().find('.active').find( ".bp-rmoji-count-number" ).attr('data-count', revert_count);
				$(this).parent().find('.active').find( ".bp-rmoji-count-number" ).text(bp_reactions_emoji_count_format(revert_count));
			}
			*/			

            $.ajax({
                url: bpreactions.ajaxUrl,
                dataType: 'json',
                type: 'post',
                data: {
                    action: 'bpr_remove_user_react_emoji_ajax',                    
                    post_id: post_id,
                    post_type: post_type,
                    bprs_id: bprs_id,
                    ajax_nonce: bpreactions.ajax_nonce,
                },
                success: function(data) {
                    if (post_type == 'activity') {
                        $("#activity-" + post_id + " div#bp-reactions-post-"+ post_id + ".reacted-count.content-actions").replaceWith(data.container);
						$('#activity-' + post_id + ' .bp-post-react-icon').html('<div class="icon-thumbs-up"><i class="br-icon br-icon-smile"></i></div>');
                    } else {
						$("#bp-reactions-post-" + post_id + ".reacted-count.content-actions").replaceWith(data.container);
						$('#post-reactions-' + post_id + ' .bp-post-react-icon').html('<div class="icon-thumbs-up"><i class="br-icon br-icon-smile"></i></div>');
					}
                }
            });

        });

    });

})(jQuery);