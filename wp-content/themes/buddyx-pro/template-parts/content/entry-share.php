<?php
/**
 * Template part for social share a post
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro;

$post_social = get_theme_mod( 'single_post_social_box', buddyx_defaults( 'single-post-social-box' ) );

if ( $post_social == '') {
	return;
}

$post_social_link = get_theme_mod( 'single_post_social_link', buddyx_defaults( 'single-post-social-link' ) );
if ( !empty( $post_social_link )) {?>
	<ul class="buddyx-social-share">
	<?php foreach( $post_social_link as $social ) {
		
		
		switch ( $social ) {
			
			case 'facebook':
					?>
					<li>		
						<a class="btn btn-link btn-icon--left social-facebook"
						   href="http://www.facebook.com/sharer.php?u=<?php echo get_the_permalink(); ?>"
						   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600');return false;"		   
						   target="_blank" >
							<i class="fab fa-facebook-f"></i>
						</a>
					</li>
					<?php
					break;
				case 'twitter':
					?>
					<li>
						<a class="btn btn-link btn-icon--left social-twitter"
						   href="https://twitter.com/share?url=<?php echo get_the_permalink(); ?>"
						   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600');return false;"		   
						   target="_blank" >
							<i class="fab fa-twitter"></i>
						</a>	
					</li>
					<?php					
					break;
				case 'pinterest':
					$params = array(
								'media=' . ( function_exists( 'the_post_thumbnail' ) ? wp_get_attachment_url( get_post_thumbnail_id() ) : '' ),
								'description=' . strip_tags( get_the_title() ),
							);
				
					?>
					<li>
						<a class="btn btn-link btn-icon--left social-pinterest"
						   href="http://pinterest.com/pin/create/button/?url=<?php echo get_the_permalink() . '&' . implode( '&', $params ); ?>"		   
						   target="_blank" data-pin-custom="true" data-pin-do="buttonBookmark">
							<i class="fab fa-pinterest-p"></i>
						</a>	
					</li>
					<?php					

					break;
				case 'linkedin':
					?>
					<li>
						<a class="btn btn-link btn-icon--left social-linkedin"
						   href="https://www.linkedin.com/shareArticle?url=<?php echo get_the_permalink(); ?>"
						   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"		   
						   target="_blank" >
							<i class="fab fa-linkedin-in"></i>
						</a>	
					</li>
					<?php 					
					break;
				case 'whatsapp':
					$whatsapp_url = 'https://web.whatsapp.com/send';
					if ( wp_is_mobile() ) {
						$whatsapp_url = 'https://api.whatsapp.com/send';
					}

					?>
					<li>
						<a class="btn btn-link btn-icon--left social-whatsapp"
						   href="<?php echo $whatsapp_url . '?text=' . urlencode( get_the_title() . ' ' . get_the_permalink() ); ?>"		   
						   target="_blank" >
							<i class="fab fa-whatsapp"></i>
						</a>	
					</li>
					<?php					
					break;
				default:
					return;			

		}
	}

	?>
	</ul>
	<?php
}