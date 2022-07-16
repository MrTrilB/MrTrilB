
        <!-- Footer -->
		<div class="container footer">
			<div class="container">

				<div class="row">
					<?php
						if ( is_active_sidebar( 'footer_sidebar' ) )
						{
							dynamic_sidebar('footer_sidebar');
						}
					?>
				</div>

			</div>
		</div>

		<!-- Copyright -->
		<div class="container-fluid copyright">
			<?php echo goodgame_kses_widget_html_field(stripslashes(goodgame_gs('copyright'))); ?>
		</div>

		<a href="#" class="back-to-top"><i class="fa fa-angle-double-up"></i></a>

		<!-- END .focus -->
		</div>

    <?php wp_footer();?>

	<!-- END body -->
	</body>

<!-- END html -->
</html>
