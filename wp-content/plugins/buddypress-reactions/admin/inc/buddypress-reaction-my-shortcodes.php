<?php
global $wpdb;
$table_name     = $wpdb->prefix . 'bp_reactions_shortcodes ';
$where_search   = '';
$customPagHTML  = '';
$total_query    = 'SELECT count(*) FROM ' . $table_name . " {$where_search} ORDER BY ID DESC";
$total          = $wpdb->get_var( $total_query );
$items_per_page = 20;
$page           = ( isset( $_GET['cpage'] ) ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset         = ( $page * $items_per_page ) - $items_per_page;
$query          = 'SELECT * FROM ' . $table_name . " {$where_search} ORDER BY ID DESC LIMIT {$offset}, {$items_per_page}";
$result         = $wpdb->get_results( $query );
$total_page     = ceil( $total / $items_per_page );

?>
<div class="wbcom-tab-content bp-reactions bp-reactions-shortcode-list">
	<h3><?php esc_html_e( 'Shortcodes Lists', 'buddypress-reactions' ); ?></h3>
	<table class="table bp-reactions-table table-light table-responsive table-hover">
		<thead>
			<tr>
				<td><?php esc_html_e( 'ID', 'buddypress-reactions' ); ?></td>
				<td><?php esc_html_e( 'Name', 'buddypress-reactions' ); ?></td>
				<!--td><?php esc_html_e( 'Post Type', 'buddypress-reactions' ); ?></td-->
				<td><?php esc_html_e( 'Shortcode', 'buddypress-reactions' ); ?></td>
				<td><?php esc_html_e( 'Actions', 'buddypress-reactions' ); ?></td>
			</tr>
		</thed>
		<tbody>
			<?php
			if ( $result ) :
				foreach ( $result as $res ) :
					?>
				<tr>
					<td><?php echo $res->id; ?></td>
					<td><?php echo $res->name; ?></td>
					<!--td><?php // echo $res->post_type; ?></td-->
					<td>
						<code>
						[bp_reactions id="<?php echo $res->id; ?>"]
						</code>
					</td>
					<td>
						<a class="bp-reaction-action bpr-edit" href="<?php echo admin_url( '/admin.php?page=buddypress-reactions&tab=shortcode-generator&bpr_id=' . $res->id ); ?>"><span class="dashicons dashicons-edit"></span></a>
						<!--a class="bp-reaction-action bpr-copy" href="<?php // echo admin_url( '/admin.php?page=buddypress-reactions&tab=shortcode-generator&bpr_clone_id=' . $res->id ); ?>"><span class="dashicons dashicons-admin-page"></span></a>
						<a class="bp-reaction-action bpr-remove" href="#" data-id="<?php // echo esc_attr( $res->id ); ?>"><span class="dashicons dashicons-trash"></span></a-->
					</td>
				</tr>
					<?php
				endforeach;
			else :
				?>
				<tr >
					<td colspan="5"><?php esc_html_e( 'You have no any shortcode. Go to Shortcode Generator and make!', 'buddypress-reactions' ); ?></td>
				</tr>
			<?php endif; ?>
		</body>		
	</table>
	<?php if ( $total_page > 1 ) { ?>
		<div class="bp-reaction-pagination">		
			<?php
			$big = 999999999; // need an unlikely integer.
			echo paginate_links(
				array(
					'base'    => add_query_arg( 'cpage', '%#%' ),
					'format'  => '',
					'current' => $page,
					'total'   => $total_page,
				)
			);
			?>
		</div>
	<?php } ?>
</div>
