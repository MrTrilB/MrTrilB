<?php
/**
 * BuddyxPro\BuddyxPro\Welcome\Component class
 *
 * @package buddyxpro
 */

namespace BuddyxPro\BuddyxPro\Welcome;

use BuddyxPro\BuddyxPro\Component_Interface;
use function add_action;


/**
 * Class Component
 *
 * @package BuddyxPro\BuddyxPro\Welcome
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'welcome';

	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );
	}

	/**
	 *  Add welcome page at admin
	 */
	public function add_admin_menu_page() {
		add_submenu_page(
			'themes.php',
			__( 'Getting Started', 'buddyxpro' ),
			__( 'Getting Started', 'buddyxpro' ),
			'manage_options',
			'buddyxpro-welcome',
			array( &$this, 'submenu_page_callback' )
		);
	}

	public function submenu_page_callback() { ?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Getting started with BuddyX Pro', 'buddyxpro' ); ?></h1>
				
			<div class="buddyx-dashboard-tabs">

				<section class="content">
					<div class="tabs">
					<div role="tablist" aria-label="<?php esc_attr_e('Programming Languages', 'buddyxpro'); ?>">
						<button role="tab" aria-selected="true" id="tab1"><?php esc_html_e('Home', 'buddyxpro'); ?></button>
						<button role="tab" aria-selected="false" id="tab2"><?php esc_html_e('Premium add-ons', 'buddyxpro'); ?></button>
					</div>
					<div role="tabpanel" aria-labelledby="<?php esc_attr_e( 'tab1', 'buddyxpro' ); ?>">
					<div class="buddyx-home-banner">
							<img src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/2020/05/thanks-theme-min.jpg' ); ?>" class="size-medium_large" alt="<?php esc_attr_e( 'Thank You', 'buddyxpro' ); ?>" />
						</div>

						<div class="buddyx-home-body">

							<div class="buddyx-row knowledge-base">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-welcome-learn-more"></span></div>
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'Knowledge Base', 'buddyxpro' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'We have created full-proof documentation for you. It will help you to understand how our plugin works.', 'buddyxpro' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs' ); ?>"><?php esc_html_e( 'Take Me to The Knowledge Page', 'buddyxpro' ); ?></a>
								</div>

								<div class="buddyx-col">
									<img width="1414" height="1072" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/2020/05/knowledge-base-min.png' ); ?>" alt="<?php esc_attr_e( 'Knowledge Base', 'buddyxpro' ); ?>">
								</div>
							</div><!-- .knowledge-base -->

							<div class="buddyx-divider"></div><!-- .buddyx-divider -->

							<div class="buddyx-row video-tutorial">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-video-alt3"></span></div>
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'Video Tutorial', 'buddyxpro' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'How to use Floating Effects and manage CSS Transform?', 'buddyxpro' ); ?></p>
								</div>
							</div><!-- .video-tutorial -->

							<div class="buddyx-row">
								<div class="buddyx-col">
									<a href="<?php echo esc_url(' https://www.youtube.com/watch?v=Ztogq3dx4-E&feature=youtu.be' ); ?>" class="buddyx-feature-sub-title-a" target="_blank">
										<img class="ha-img-fluid ha-rounded" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/2020/05/theme-install-video-v-min.jpg' ); ?>" alt="<?php esc_attr_e( 'BuddyX Demo installation', 'buddyxpro' ); ?>">
										<h4 class="buddyx-feature-sub-title"><?php esc_html_e( 'BuddyX Demo installation', 'buddyxpro' ); ?></h4>
									</a>
								</div>
								<div class="buddyx-col">
									<a href="<?php echo esc_url( 'https://www.youtube.com/watch?v=i51CikDsbeg&feature=youtu.be' )?>" class="buddyx-feature-sub-title-a" target="_blank">
										<img class="ha-img-fluid ha-rounded" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/2020/05/theme-options-v-min.jpg' ); ?>" alt="<?php esc_attr_e( 'BuddyX Customizer Option', 'buddyxpro' ); ?>">
										<h4 class="buddyx-feature-sub-title"><?php esc_html_e( 'BuddyX Customizer Option','buddyxpro' );?></h4>
									</a>
								</div>
							</div><!-- .video-tutorial columns -->

							<div class="buddyx-text-center">
								<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://buddyxtheme.com/docs' ); ?>"><?php esc_html_e( 'View More Videos','buddyxpro' );?></a>
							</div><!-- .video-tutorial button-->

							<div class="buddyx-divider"></div><!-- .buddyx-divider -->

							<div class="buddyx-row faq">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-format-chat"></span></div>
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'FAQ', 'buddyxpro' ); ?></h3>
									<p class="buddyx-col-content"><?php esc_html_e( 'Frequently Asked Questions', 'buddyxpro' ); ?></p>
								</div>
							</div><!-- faq -->

							<div class="buddyx-row">
								<div class="buddyx-col">
									<h4 class="buddyx-faq-title"><?php esc_html_e( 'Can I use these addons in my client project?', 'buddyxpro' ); ?></h4>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Yes, absolutely, no holds barred. Use it to bring colorful moments to your customers. And don’t forget to check out our premium features.', 'buddyxpro' ); ?></p>
								</div>
								<div class="buddyx-col">
									<h4 class="buddyx-faq-title"><?php esc_html_e( 'Is there any support policy available for the free users?', 'buddyxpro' ); ?></h4>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Free or pro version, both comes with excellent support from us. However, pro users will get priority support.', 'buddyxpro' ); ?></p>
								</div>
							</div><!-- .faq columns -->

							<div class="buddyx-divider"></div><!-- .buddyx-divider -->

							<div class="buddyx-row support">
								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-star-filled"></span></div>
									<h3 class="buddyx-feature-title"><?php esc_html_e( 'Rate Us', 'buddyxpro' ); ?></h3>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Never underestimate your rate! Your 5 star rate will encourage us so much!', 'buddyxpro' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wordpress.org/support/theme/buddyx/reviews/?rate=5#new-post' ); ?>"><?php esc_html_e( 'Rate Us ★★★★★', 'buddyxpro' ); ?></a>
								</div>

								<div class="buddyx-col">
									<div class="buddyx-title-icon"><span class="dashicons dashicons-phone"></span></div>
									<h4 class="buddyx-feature-title"><?php esc_html_e( 'Help Desk Hours', 'buddyxpro' ); ?></h3>
									<p class="buddyx-faq-content"><?php esc_html_e( 'Days: Monday-Friday', 'buddyxpro' ); ?></br>
									<?php esc_html_e( 'Time: 10AM – 7PM IST', 'buddyxpro' ); ?></br>
									<?php esc_html_e( 'Inquiries received after the working hours or on weekends will be addressed on the next working day.', 'buddyxpro' ); ?></p>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://support.wbcomdesigns.com/portal/home' ) ?>"><?php esc_html_e( 'Start Chat', 'buddyxpro' ); ?></a>
									<a class="buddyx-btn buddyx-btn-primary" target="_blank" rel="noopener" href="<?php echo esc_url( 'https://wbcomdesigns.com/support' ); ?>"><?php esc_html_e( 'Create Ticket', 'buddyxpro' ); ?></a>
								</div>
							</div><!-- .support -->
							
						</div><!-- .buddyx-home-body -->

					</div><!-- .tab1 -->
					<div role="tabpanel" aria-labelledby="tab2" hidden>
						<div class="buddyx-text-center">
							<h2 class="buddyx-feature-title"><?php esc_html_e( 'Premium add-ons', 'buddyxpro' ); ?></h2>
							<p class="buddyx-col-content"><?php esc_html_e( 'Extend your social community website with our premium add-ons for BuddyPress.', 'buddyxpro' ); ?></p>
						</div><!-- .video-tutorial button-->

						<div class="buddy-plugin-row">
							
							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-polls/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2018/12/polls-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Polls', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Polls', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>

							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-moderation-pro/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2018/12/bp-moderationg-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Moderation Pro', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Moderation Pro', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>

							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-profanity/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2018/12/bp-profanity-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Profanity', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Profanity', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>


							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-quotes/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2018/12/polls-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Quotes', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Quotes', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>


							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-status/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2018/12/polls-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Status', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Status', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>

							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-hashtags/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2019/02/buddypress-hashtag-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Hashtags', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Hashtags', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>


							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-newsfeed/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2019/04/bp-newsfeed-1-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Newsfeed', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Newsfeed', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>

							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-profile-pro/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2018/12/profile-pro-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Profile Pro', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Profile Pro', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>


							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-sticky-post/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2019/10/BuddyPress-Pin-Post-Activity-Updates-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Sticky Post', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Sticky Post', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>

							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-private-community-pro/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2018/12/private-profile-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Private Community Pro', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Private Community Pro', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>

							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-auto-friends/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2019/04/bp-auto-friends-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Auto Friends', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Auto Friends', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>

							<div class="addon-section">
								<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/buddypress-resume-manager/' ) ?>" target="_blank" rel="noopener">
								<div class="addon-img">
									<img width="768" height="768" src="<?php echo esc_url( 'https://wbcomdesigns.com/wp-content/uploads/edd/2018/12/resume-manager-768x768.jpg' ) ?>" class="size-medium_large" alt="<?php esc_attr_e( 'BuddyPress Resume Manager', 'buddyxpro' ); ?>" />
								</div>
								<div class="addon-content">
									<h3 class="addon-title"><?php esc_html_e( 'BuddyPress Resume Manager', 'buddyxpro' ); ?></h3>
								</div>
								</a>
							</div>

						</div>

					</div><!-- .tab2 -->
				</section>
					
			</div><!-- .buddyx-dashboard-tabs -->
		</div><!-- .wrap -->
		
		<script>

			var tabs = document.querySelector('.buddyx-dashboard-tabs .tabs');
			var tabButtons = tabs.querySelectorAll('.buddyx-dashboard-tabs [role="tab"]');
			var tabPanels = Array.from(tabs.querySelectorAll('.buddyx-dashboard-tabs [role="tabpanel"]'));

			function handleTabClick(event) {
			// Hide tab panels
			tabPanels.forEach(panel => panel.hidden = true);

			// Mark all tabs as unselected
			tabButtons.forEach(tab => tab.setAttribute("aria-selected", false));

			// Mark the clicked tab as selected
			event.currentTarget.setAttribute("aria-selected", true);

			// Find the associated tabPanel and show it
			var { id } = event.currentTarget;

			// Find in the array of tabPanels
			var tabPanel = tabPanels.find(
				panel => panel.getAttribute('aria-labelledby') === id
			);
			tabPanel.hidden = false;
			}

			tabButtons.forEach(button => button.addEventListener('click', handleTabClick));

		</script>
	<?php }
}
