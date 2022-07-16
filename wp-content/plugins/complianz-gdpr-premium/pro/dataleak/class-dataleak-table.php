<?php
/**
 * dataleak Table Class
 *
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load WP_List_Table if not loaded
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class cmplz_Dataleak_Table extends WP_List_Table {

	/**
	 * Number of items per page
	 *
	 * @var int
	 * @since 1.5
	 */
	public $per_page = 30;

	/**
	 * Number found
	 *
	 * @var int
	 * @since 1.7
	 */
	public $count = 0;

	/**
	 * Total customers
	 *
	 * @var int
	 * @since 1.95
	 */
	public $total = 0;

	/**
	 * The arguments for the data set
	 *
	 * @var array
	 * @since  2.6
	 */
	public $args = array();

	/**
	 * all items
	 * @var array
	 */
	public $all = array();

	/**
	 * Get things started
	 *
	 * @since 1.5
	 * @see   WP_List_Table::__construct()
	 */


	public function __construct() {
		global $status, $page;

		// Set parent defaults
		parent::__construct( array(
			'singular' => __( 'dataleak', 'complianz-gdpr' ),
			'plural'   => __( 'dataleaks', 'complianz-gdpr' ),
			'ajax'     => false,
		) );

	}

	/**
	 * Gets the name of the primary column.
	 *
	 * @return string Name of the primary column.
	 * @since  2.5
	 * @access protected
	 *
	 */
	protected function get_primary_column_ID() {
		return __( 'Title', 'complianz-gdpr' );
	}

	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param array  $item        Contains all the data of the customers
	 * @param string $column_name The name of the column
	 *
	 * @return string Column Name
	 * @since 1.5
	 *
	 */
	public function column_default( $item, $column_name ) {
		$post_id = $item['ID'];
		$value = '';

		if ( $column_name === 'post_title'){
			$title = $item[ $column_name ];
			$edit_url = get_edit_post_link( $post_id );
			$delete_url = get_delete_post_link( $post_id );
			ob_start()
			?>
			<strong>
				<a  class="row-title"
					href="<?php echo $edit_url ?>"
					aria-label='“<?php echo $title ?>” ( <?php _e('edit', 'complianz-gdpr') ?>)'>
					<?php echo $title ?>
				</a>
			</strong>
			<div class="row-actions">
				<span class="edit">
					<a href="<?php echo $edit_url ?>" aria-label='“<?php echo $title ?>” ( <?php _e('edit', 'complianz-gdpr') ?>)'>
						<?php _e('Edit', 'complianz-gdpr') ?>
					</a>
					|
				</span>
				<span class="trash">
					<a href="<?php echo $delete_url; ?>" class="submitdelete" aria-label='“<?php echo $title ?>” ( <?php _e('Move to trash', 'complianz-gdpr') ?>)'>
						<?php _e('Trash', 'complianz-gdpr') ?>
					</a>
				</span>
			</div>
			<?php
			$value = ob_get_clean();
		}

		if ( $column_name === 'post_author'){
			$author = get_user_by( 'ID', $item[ $column_name ] );
			$value = $author->display_name;
		}

		if ( $column_name === 'post_date' ) {
				$value = sprintf(
				/* translators: 1: Post date, 2: Post time. */
					__( '%1$s at %2$s' ),
					/* translators: Post date format. See https://www.php.net/manual/datetime.format.php */
					get_the_time( __( 'Y/m/d' ), $post_id ),
					/* translators: Post time format. See https://www.php.net/manual/datetime.format.php */
					get_the_time( __( 'g:i a' ), $post_id )
				);

		}

		if ( $column_name === 'download' ) {
			if ( COMPLIANZ::$dataleak->dataleak_has_to_be_reported_to_involved($post_id)) {
				$value = '<a target="_blank" href="' . get_cmplz_document_download_url($post_id) . '">' . cmplz_icon('file-download', 'default', '', 22) . '</a>';
			} else {
				$value = '';
			}
		}

		if ( $column_name === 'email_sent') {
			if ( isset( $item['email_sent'] ) ) {
				$value = _x('Email sent', 'Column header in posts overview','complianz-gdpr');
			}
		}

		if ( $column_name === 'region' ) {
			$region = COMPLIANZ::$document->get_region($post_id);
			if ($region) {
				$value = cmplz_region_icon($region, 25);
			}
		}

		return apply_filters( 'cmplz_dataleak_column_' . $column_name, $value, $post_id );
	}

	/**
	 * Retrieve the table columns
	 *
	 * @return array $columns Array of all the list table columns
	 * @since 1.5
	 */
	public function get_columns() {
		$columns = array(
			'post_title'         => __( 'Title', 'complianz-gdpr' ),
			'post_author'        => __( 'Author', 'complianz-gdpr' ),
			'post_date'  		=> __( 'Date', 'complianz-gdpr' ),
			'download'     	=> __( 'Download', 'complianz-gdpr' ),
			'email_sent'    => __( 'Email sent', 'complianz-gdpr' ),
			'region'    	=> __( 'Region', 'complianz-gdpr' ),
		);

		return apply_filters( 'cmplz_dataleak_columns', $columns );

	}

	/**
	 * Get the sortable columns
	 *
	 * @return array Array of all the sortable columns
	 * @since 2.1
	 */
	public function get_sortable_columns() {
		$columns = array(
			'post_date' => array( 'post_date', true ),
			'post_title' => array( 'post_title', true ),
		);

		return $columns;
	}

	/**
	 * Retrieve the current page number
	 *
	 * @return int Current page number
	 * @since 1.5
	 */
	public function get_paged() {
		return isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
	}


	/**
	 * Retrieves the search query string
	 *
	 * @return mixed string If search is present, false otherwise
	 * @since 1.7
	 */
	public function get_search() {
		return ! empty( $_GET['s'] ) ? trim( sanitize_text_field( $_GET['s'] ) ) : false;
	}

	/**
	 * Build all the reports data
	 *
	 * @return array $reports_data All the data for customer reports
	 * @global object $wpdb Used to query the database using the WordPress
	 *                      Database API
	 * @since 1.5
	 */

	public function reports_data() {

		if ( ! cmplz_user_can_manage() ) {
			return array();
		}

		$paged   = $this->get_paged();
		$offset  = $this->per_page * ( $paged - 1 );
		$search  = $this->get_search();
		$order   = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';
		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'post_date';

		$args = array(
			'number'  => $this->per_page,
			'offset'  => $offset,
			'order'   => $order,
			'orderby' => $orderby,
			'search'  => $search,
		);

		$this->args = $args;
		return COMPLIANZ::$dataleak->get_dataleak_records( $args, false );
	}

	/**
	 * Setup the table data
	 */

	public function prepare_items() {

		$columns  = $this->get_columns();
		$hidden   = array(); // No hidden columns
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $this->reports_data();
		$args = $this->args;
		unset($args['number']);
		unset($args['offset']);
		$this->total = COMPLIANZ::$dataleak->get_dataleak_records( $args, true );

		// Add condition to be sure we don't divide by zero.
		// If $this->per_page is 0, then set total pages to 1.

		$total_pages = $this->per_page ? ceil( (int) $this->total / $this->per_page ) : 1;
		$this->set_pagination_args( array(
			'total_items' => $this->total,
			'per_page'    => $this->per_page,
			'total_pages' => $total_pages,
		) );
	}
	/**
	 * Displays the table.
	 *
	 * @since 3.1.0
	 */
	public function display() {
		$singular = $this->_args['singular'];

		$this->display_tablenav( 'top' );
		$this->screen->render_screen_reader_content( 'heading_list' );
		?>
		<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
			<thead>
			<tr>
				<?php $this->print_column_headers(); ?>
			</tr>
			</thead>

			<tbody id="the-list"
					<?php
					if ( $singular ) {
						echo " data-wp-lists='list:$singular'";
					}
					?>
			>
			<?php $this->display_rows_or_placeholder(); ?>
			</tbody>

			<tfoot>
			<tr>
				<?php $this->print_column_headers( false ); ?>
			</tr>
			</tfoot>

		</table>
		<?php
		$this->display_tablenav( 'bottom' );
	}

	/**
	 * @param string $which
	 */
	protected function extra_tablenav( $which, $post_type = 'cmplz-dataleak' ) {
		?>
		<div class="alignleft actions">
			<?php
			if ( 'top' === $which ) {
				ob_start();
				$this->categories_dropdown( $post_type );
				$this->formats_dropdown( $post_type );

				/**
				 * Fires before the Filter button on the Posts and Pages list tables.
				 *
				 * The Filter button allows sorting by date and/or category on the
				 * Posts list table, and sorting by date on the Pages list table.
				 *
				 * @since 2.1.0
				 * @since 4.4.0 The `$post_type` parameter was added.
				 * @since 4.6.0 The `$which` parameter was added.
				 *
				 * @param string $post_type The post type slug.
				 * @param string $which     The location of the extra table nav markup:
				 *                          'top' or 'bottom' for WP_Posts_List_Table,
				 *                          'bar' for WP_Media_List_Table.
				 */
				do_action( 'restrict_manage_posts', $post_type, $which );

				$output = ob_get_clean();

				if ( ! empty( $output ) ) {
					echo $output;
					submit_button( __( 'Filter' ), '', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
				}
			}

			if ( $this->is_trash && $this->has_items()
					&& current_user_can( get_post_type_object( $post_type )->cap->edit_others_posts )
			) {
				submit_button( __( 'Empty Trash' ), 'apply', 'delete_all', false );
			}
			?>
		</div>
		<?php
		/**
		 * Fires immediately following the closing "actions" div in the tablenav for the posts
		 * list table.
		 *
		 * @since 4.4.0
		 *
		 * @param string $which The location of the extra table nav markup: 'top' or 'bottom'.
		 */
		do_action( 'manage_posts_extra_tablenav', $which );
	}
}
