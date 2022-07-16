<?php
    if(class_exists('Attachments')) {
        $attachments = new Attachments( 'goodgame_galleries' );
    }
?>
<div class="post gallery-block">

    <?php if(class_exists('Attachments')) : ?>

        <?php get_template_part('theme/templates/post-dropdown-platforms-categories'); ?>

        <div class="thumbs">
			  <a href="<?php the_permalink(); ?>" class="btn-circle btn-photo"></a>
            <div class="row">

                <?php
                    if($attachments->exist())
                    {
                        for( $i = 1; $i <= 4; $i++ )
                        {
                            $attachment = $attachments->get();
                            if($attachment)
                            {
                                ?>
                                <div class="thumb">
                                    <a href="<?php the_permalink(); ?>" style="background-image: url(<?php echo esc_url($attachments->src('goodgame_gallery_item_large')); ?>);"></a>
                                </div>

                                <?php
                            }
                        }
                    }
                ?>
            </div>
        </div>

    <?php endif; ?>

	<div class="title">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

		<?php get_template_part('theme/templates/title-legend'); ?>
	</div>
</div>
