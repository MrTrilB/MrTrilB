<?php get_header(); ?>

<div class="container-fluid page-title">
	<div class="container">
		<div class="title-wrapper">
			<h2>
				<span><s><?php esc_html_e('Photo galleries', 'planetshine-goodgame' ); ?></s></span>

				<a href="<?php echo get_post_type_archive_link('gallery'); ?>" class="btn btn-default btn-dark"><?php esc_html_e('Back to gallery list', 'planetshine-goodgame' ); ?></a>

			</h2>
		</div>
	</div>
</div>

<div class="container main-content-wrapper main-content-wrapper-fullwidth sidebar-disabled abcabc">

	<div class="main-content">

		<?php $attachments = new Attachments( 'goodgame_galleries' ); ?>

			<div class="row">
				<div class="post-block post-gallery gallery-single">
					<div class="row">
						<div class="col-md-12">
							<div class="galleries">
								<div class="gallery-title">
									<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

									<div class="legend">
										<a href="<?php echo get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')); ?>" class="time"><?php echo get_the_date(); ?></a>
										<?php if( $attachments->exist() ) : ?>
                                            <a href="<?php the_permalink(); ?>" class="photos"><?php echo esc_html($attachments->total()); ?></a>
                                        <?php endif; ?>
									</div>

								</div>

                                <?php if( $attachments->exist() ) : ?>

                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="gallery-slideshow"
                                                data-cycle-swipe="true"
                                                data-cycle-swipe-fx="fade"
                                                data-index="1"
                                                data-cycle-log="false"
                                                data-cycle-fx="fade"
                                                data-cycle-timeout="0"
                                                data-cycle-speed="500"
                                                data-cycle-pager=""
                                                data-cycle-auto-height="false"
                                                data-cycle-pager-active-class="active"
                                                data-cycle-pager-template=""
                                                data-cycle-slides="> .single-photo-active"
                                                data-cycle-prev="#prev"
                                                data-cycle-next="#next"
                                            >
                                                <?php
                                                while($attachments->get())
                                                {
                                                    ?>
                                                    <div class="single-photo-active">
                                                        <a href="#" class="btn-default btn-dark btn-maximize"></a>
                                                        <?php if($attachments->field( 'caption' ) != "") { ?><p class="caption"><?php echo esc_html($attachments->field( 'caption' )); ?></p><?php } ?>
                                                        <img src="<?php echo esc_url($attachments->src( 'goodgame_gallery_item_large' )) ?>" alt="<?php echo esc_attr($attachments->field( 'caption' )); ?>" />
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>


											<div class="single-photo-thumbs">
												<div class="controls" data-total="<?php echo esc_attr($attachments->total()); ?>">
													<a href="#" id="prev" class="btn btn-default"><i class="fa fa-caret-left"></i></a>
													<s class="btn btn-default">1 / <?php echo esc_html($attachments->total()); ?></s>
													<a href="#" id="next" class="btn btn-default"><i class="fa fa-caret-right"></i></a>
												</div>

                                                <div class="thumbs" id="pager">
                                                    <?php
                                                    $c = 0;
                                                    $attachments->rewind();

                                                    while($attachments->get())
                                                    {
                                                        $c++;

                                                        $class = '';
                                                        if($c == 1)
                                                        {
                                                            $class = 'active';
                                                        }

                                                        echo '<div class=" thumb ' . $class . ' ">';
                                                        echo	'<a href="#">' . $attachments->image( 'goodgame_gallery_item_small' ) . '</a>';
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>


											<div class="gallery-description">
                                                <div class="the-content-container"><?php the_content(); ?></div>
                                            </div>
                                        </div>
                                    </div>

									<div class="row">
										<div class="col-md-12 col-sm-12"></div>
									</div>

								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

	</div>

</div>

<?php echo $banner = goodgame_get_banner_by_location('single_gallery_ad', 'banner'); ?>

<?php if(goodgame_gs('show_gallery_single_latest') == 'on') : ?>
	<div class="container galleries-recent"><?php echo do_shortcode('[photo_galleries count="4" columns="4"]'); ?></div>
<?php endif; ?>

<?php get_footer(); ?>
