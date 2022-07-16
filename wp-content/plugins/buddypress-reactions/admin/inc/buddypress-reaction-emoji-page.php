<?php
global $wpdb;
$table_name     = $wpdb->prefix . 'bp_reactions_emojis';
$where_search   = '';
$customPagHTML  = '';
$total_query    = 'SELECT count(*) FROM ' . $table_name . " {$where_search} ORDER BY ID ASC";
$total          = $wpdb->get_var( $total_query );
$items_per_page = 20;
$page           = ( isset( $_GET['cpage'] ) ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset         = ( $page * $items_per_page ) - $items_per_page;
$query          = 'SELECT * FROM ' . $table_name . " {$where_search} ORDER BY ID ASC LIMIT {$offset}, {$items_per_page}";
$result         = $wpdb->get_results( $query );
$total_page     = ceil( $total / $items_per_page );

?>
<div class="wbcom-tab-content">
	<div class="bpr-settings-container bp-reactions-emoji bp-reactions-emoji-names-listing">
		<h3><?php esc_html_e( 'Emoji Lists', 'buddypress-reactions' ); ?></h3>
		<p class="description bp-reactions-notice"><?php esc_html_e( 'Click to edit icon to update emoji name. After change the name then you have to hit the enter key to update emoji name.', 'buddypress-reactions' ); ?></p>
		<table class="table bp-reactions-table table-light table-responsive table-hover">
			<thead>
				<tr>
					<td><?php esc_html_e( 'Icon', 'buddypress-reactions' ); ?></td>
					<td><?php esc_html_e( 'Name', 'buddypress-reactions' ); ?></td>
					<td><?php esc_html_e( 'Action', 'buddypress-reactions' ); ?></td>
				</tr>
			</thed>
			<tbody>
				<?php
				if ( $result ) :
					foreach ( $result as $res ) :
						?>
					<tr>
						<td class="emoji-image"><img src="<?php echo get_buddypress_reaction_emoji( $res->id, 'svg' ); ?>"  alt="" class="user-status-reaction-image"</td>
						<td class="emoji-content">
							<span class="emoji-name-<?php echo esc_attr( $res->id ); ?>"><?php echo $res->name; ?></span>
							<input type="text" data-emoji-id="<?php echo esc_attr( $res->id ); ?>" id="reactions-emojis-<?php echo esc_attr( $res->id ); ?>" class="reactions_emojis_name regular-text" name="reactions_emojis_name" value="<?php echo esc_attr( $res->name ); ?>" style="display:none;"/>
						</td>
						<td class="emoji-action"><a data-emoji-id="<?php echo esc_attr( $res->id ); ?>" class="bp-reaction-action bpr-edit" href=""><span class="dashicons dashicons-edit"></span></a></td>
					</tr>
						<?php
					endforeach;
				else :
					?>
					<tr >
						<td colspan="5"><?php esc_html_e( 'You have no any Emoji.', 'buddypress-reactions' ); ?></td>
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

</div>
