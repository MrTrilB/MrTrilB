<?php

$attachments = new Attachments( 'goodgame_galleries' );
if( $attachments->exist() ) : ?>

	<div class="lightbox lightbox-gallery lightbox-hidden">
		<a href="#" class="btn btn-default btn-dark close"><i class="fa fa-times"></i></a>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 image-wrapper">


					<div class="gallery-title">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

						<div class="legend">
							<a href="<?php echo get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')); ?>" class="time"><?php echo get_the_date(); ?></a>
							<a href="<?php the_permalink(); ?>" class="photos"><?php echo esc_html($attachments->total()); ?></a>
						</div>
					</div>

					<div class="gallery-slideshow"
						data-cycle-swipe="true"
						data-cycle-swipe-fx="fade"
						data-index="1"
						data-cycle-log="false"
						data-cycle-fx="fade"
						data-cycle-timeout="0"
						data-cycle-speed="500"
						data-cycle-pager="#pager-lightbox"
						data-cycle-auto-height="false"
						data-cycle-pager-active-class="active"
						data-cycle-pager-template=""
						data-cycle-slides="> .image"
						data-cycle-prev="#prev-lightbox"
						data-cycle-next="#next-lightbox"
					>
						<?php
						while($attachments->get())
						{
							?>
							<div class="image">



								<?php if($attachments->field( 'caption' ) != "") { ?><p class="caption"><?php echo esc_html($attachments->field( 'caption' )); ?></p><?php } ?>
								<img src="<?php echo esc_url($attachments->src( 'goodgame_post_single_full_screen' )) ?>" alt="<?php echo esc_attr($attachments->field( 'caption' )); ?>" />

							</div>
							<?php
						}
						?>
					</div>


					<div class="single-photo-thumbs">

							<div class="controls" data-total="<?php echo esc_attr($attachments->total()); ?>">

								<a href="#" id="prev-lightbox" class="btn btn-default btn-dark"><i class="fa fa-caret-left"></i></a>
								<s class="btn btn-default btn-dark">1 / <?php echo esc_html($attachments->total()); ?></s>
								<a href="#" id="next-lightbox" class="btn btn-default btn-dark"><i class="fa fa-caret-right"></i></a>
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


						<?php
							if ( is_active_sidebar( 'gallery_sidebar' ) )
							{
								dynamic_sidebar('gallery_sidebar');
							}
						?>
					</div>


				</div>

			</div>
		</div>
	</div>

<?php endif; ?>
