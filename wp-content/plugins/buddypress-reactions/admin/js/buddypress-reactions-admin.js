(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
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

        let picked_emojis = get_bp_emojis_picked();

        $('.emoji-picker .emoji-pick').hover(
            function() {
                $(this).find('.emoji-lottie-holder').show();
                $(this).find('.emoji-svg-holder').hide();
                let emoji_id = $(this).data('emoji_id');
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
            },
            function() {
                if (typeof $(this).data('animation') != 'undefined') {
                    $(this).data('animation').pause();
                }
                $(this).find('.emoji-lottie-holder').hide();
                $(this).find('.emoji-svg-holder').show();
            }
        );


        $('.emoji-picker-scrollbar').mCustomScrollbar({
            theme: '3d-thick-dark',
        });

        $('.picked-emoji.emoji-lottie-holder.lottie-element').each(function() {
            let $elem = $(this);
            $(this).html('');
            let element_container = $elem.get(0);
            let emoji_id = $elem.data('emoji_id');

            let animation = bodymovin.loadAnimation({
                container: element_container,
                path: bpreactions.emojis_path + 'json/' + emoji_id + '.json?v=' + bpreactions.version,
                renderer: 'svg',
                loop: true,
                autoplay: true,
                name: emoji_id
            });

        });

        /* Emoji remove*/
        $(document).on('click', '.wbcom-picked-emojis .remove-picked-emoji', function() {
            let emoji_id = $(this).parent().data('emoji_id');
            picked_emojis.splice(picked_emojis.indexOf(emoji_id), 1);
            $(this).parent().remove();
            $( '#bp_reactions_emoji_id_' + emoji_id).remove();
            $('.emoji-pick[data-emoji_id="' + emoji_id + '"]').removeClass('active');

            if ($('.wbcom-picked-emojis').children().length == 0) {
                $('.wbcom-picker-empty').show();
            }
        });

        /* Emoji Picker */
        $(document).on('click', '.emoji-picker-wrapper .emoji-picker .emoji-pick', function() {
            let $pick = $(this);
            let emoji_id = $pick.data('emoji_id');
            if ($pick.hasClass('active')) {
                $pick.removeClass('active');
                picked_emojis.splice(picked_emojis.indexOf(emoji_id), 1);
                $('.picked-emoji.emoji-lottie-holder[data-emoji_id=' + emoji_id + ']').remove();
                $('.wbcom-picked-emojis').children().length == 0 && $('.wbcom-picker-empty').show();
            } else {

                if (bpreactions.max_emojis <= picked_emojis.length) {
                    $(".bp-reactions-messages-container").addClass('active');
                    setTimeout(function() { $(".bp-reactions-messages-container").removeClass('active'); }, 3000);
                } else {
                    $pick.addClass('active');
                    picked_emojis.push(emoji_id);
                    $('.wbcom-picked-emojis').append('<div class="picked-emoji emoji-lottie-holder" data-emoji_id="' + emoji_id + '"><input type="hidden" name="bp_reactions[emojis][]" id="bp_reactions_emoji_id_'+ emoji_id +'" value="' + emoji_id + '" /></div>');
                    bodymovin.loadAnimation({
                        container: $('.wbcom-picked-emojis .picked-emoji').last().get(0),
                        path: bpreactions.emojis_path + 'json/' + emoji_id + '.json?v=' + bpreactions.version,
                        renderer: 'svg',
                        loop: true,
                        autoplay: true,
                        name: emoji_id
                    });
                }
                $('.wbcom-picker-empty').hide();
            }
            set_bp_emojis_dynamic_blocks();
        });
        $('.bp-reactions-color-picker').wpColorPicker();
        $('.wbcom-picked-emojis').sortable();
        $(".wbcom-picked-emojis").disableSelection();

        $(document).on('mouseenter', '.wbcom-picked-emojis .picked-emoji', function() {
            $(this).append('<span class="remove-picked-emoji">&times;</span>');
        });

        $(document).on('mouseleave', '.wbcom-picked-emojis .picked-emoji', function() {
            $(this).find('.remove-picked-emoji').remove();
        });


        $(document).on('click', '.bp-reaction-action.bpr-remove', function(e) {

            e.preventDefault();
            if (!confirm(bpreactions.bp_reactions_shortcode_delete)) {
                return;
            }
            var bpr_id = $(this).data('id');

            $.ajax({
                url: bpreactions.ajaxUrl,
                dataType: 'JSON',
                type: 'post',
                data: {
                    action: 'bpr_delete_shortcode',
                    bpr_id: bpr_id
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $(this).parent().parent().remove();
                        location.reload();
                    }
                    WPRA_Utils.showMessage(data.message, data.status);
                }
            });
        });

		 $(document).on('click', '.bp-reactions-emoji .bp-reaction-action.bpr-edit', function(e) {
			e.preventDefault();
			var emoji_id = $(this).data( 'emoji-id' );
			$( '.emoji-name-' + emoji_id ).hide();
			$( '#reactions-emojis-' + emoji_id ).show();
		 });

		 $(document).on('keypress', '.bp-reactions-emoji .reactions_emojis_name', function(e) {
			var emoji_id = $(this).data( 'emoji-id' );
			console.log(event.keyCode);
			if (e.which == '13' ) {
				var emoji_name = $( '#reactions-emojis-' + emoji_id ).val();
				$( '.emoji-name-' + emoji_id ).text( emoji_name );


				$.ajax({
					url: bpreactions.ajaxUrl,
					dataType: 'JSON',
					type: 'post',
					data: {
						action: 'bpr_update_emoji_name',
						emoji_name: emoji_name,
						emoji_id: emoji_id,
					},
					success: function(data) {
						$( '.emoji-name-' + emoji_id ).show();
						$( '#reactions-emojis-' + emoji_id ).hide();
					}
				});
			}
		 });

        function get_bp_emojis_picked() {
            let picked = [];
            $('.wbcom-picked-emojis .picked-emoji').each(function() {
                picked.push($(this).data('emoji_id'));
            });
            return picked;
        }

        function set_bp_emojis_dynamic_blocks() {
            $('.emoji-depended-block').each(function() {
                const option_name = $(this).data('option_name');
                const def_val = $(this).data('def_val');
                bp_emoji_depended_block($(this), option_name, def_val);
            });
        }

        function bp_emoji_depended_block($elem, option_name, def_val = '') {
            let options = get_options();
            let picked_emojis = options['emojis'];
            if (picked_emojis.length == 0) return;
            let $item_clone = $elem.children().first().clone();
            $elem.html('');

            $.each(picked_emojis, function(key, emoji_id) {
                let $item = $item_clone.clone();
                let val = options[option_name + '-' + emoji_id] ? options[option_name + '-' + emoji_id] : def_val;
                $item.find('input').data('emoji_id', emoji_id);
                $item.find('input').attr('id', option_name + '-' + emoji_id);
                $item.find('input').val(val);
                $item.find('.emoji-svg-holder').css({
                    'background-image': 'url(' + bpreactions.emojis_path + 'svg/' + emoji_id + '.svg?v=' + bpreactions.version + ')'
                });
                $elem.append($item);
            });
        }


    });


})(jQuery);