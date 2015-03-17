<?php  

class PluginCategory_Controller {
	
	/**
	 * Holds the category model
	 *
	 * @var object
	 **/
	public $category;
	
	/**
	 * Holds the plugin model
	 *
	 * @var object
	 **/
	public $plugin;
	
	/**
	 * We're gonna use Blade for our templates, we need some place to store it
	 *
	 * @var object
	 **/
	public $blade;
	
	/**
	 * Intiates most of the plugins functionality
	 *
	 * @return void
	 **/
	function __construct() {
		
		// instantiate category model
		$this->category 	= new PluginCategory;
		
		// instantiate plugin model
		$this->plugin 		= new PluginCategory_Plugin;

		// require the composer autoload
		require_once PLUGIN_CATEGORIES_PATH . 'vendor/autoload.php';

		// set folder
		$views 				= PLUGIN_CATEGORIES_PATH . 'includes/views/';
		$cache 				= PLUGIN_CATEGORIES_PATH . 'includes/cache/';

		// create a new blade instance
		$this->blade 		= new \Philo\Blade\Blade($views, $cache);

		// run actions (non can be loaded before 'plugins_loaded')
		$this->actions();

	}

	/**
	 * Run actions
	 *
	 * @return void
	 **/
	public function actions() {

		// create admin stuff
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// do the saveing
		add_action( 'admin_init', array( $this, 'save' ) );

		// give scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

		// add bulk actions
		add_action( 'admin_footer-plugins.php', array( $this, 'bulk_actions' ) );
		add_action( 'load-plugins.php', array( $this, 'bulk_actions_save' ) );
		add_action( 'pre_current_active_plugins', array( $this, 'bulk_actions_categories' ) );

		// columns
		add_filter( 'manage_plugins_columns', array( $this, 'columns' ) );
		add_filter( 'manage_plugins_custom_column', array( $this, 'column_content' ), 99, 3 );

	}

	/**
	 * Adds stuff to the WordPress admin menu
	 *
	 * @return void
	 **/
	public function admin_menu() {
		add_plugins_page( __( 'Plugin Categories', 'plugin_categories' ), __( 'Categories', 'plugin_categories' ), 'install_plugins', 'plugin_categories', array( $this, 'show_category_page' ) );
	}

	/**
	 * Shows the admin page
	 *
	 * @return void
	 **/
	public function show_category_page(){

		$action 		= isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
		$id 			= isset($_REQUEST['id']) ? $_REQUEST['id'] : false;

		if( $action === 'list' ) {
			$categories = $this->category->all();
			echo $this->blade->view()->make( 'category.list', compact( 'categories' ) );
		}
		elseif( $action === 'edit' ) {
			$category 	= $this->category->find( $id );
			echo $this->blade->view()->make( 'category.edit', compact( 'category' ) );
		}
	}

	/**
	 * Saves the data
	 *
	 * @return void
	 **/
	public function save() {

		if( isset( $_POST['plugin_categories'] ) && wp_verify_nonce( $_POST['plugin_categories'], 'create' ) ) {

			$name 	= esc_sql( $_POST['category-name'] );

			$insert = array(
				'name' 		=> $name,
				'time'		=> date_i18n( 'Y-m-d H:i:s' )
			);

			$this->category->save($insert);

			wp_redirect( admin_url('plugins.php?page=plugin_categories&updated=true') );
			exit;

		}

		if( isset( $_POST['plugin_categories'] ) && wp_verify_nonce( $_POST['plugin_categories'], 'edit' ) ) {

			$name 	= esc_sql( $_POST['category-name'] );

			$update = array(
				'name' 		=> $name,
				'time'		=> date_i18n( 'Y-m-d H:i:s' )
			);

			$this->category->update($_GET['id'], $update);

			wp_redirect( admin_url('plugins.php?page=plugin_categories&updated=true') );
			exit;

		}

	}

	/**
	 * Adds some scripts and styles
	 *
	 * @return void
	 **/
	public function scripts() {
		wp_enqueue_style( 'plugin-categories-styles', PLUGIN_CATEGORIES_URL . 'assets/css/plugin_categories.min.css' );
		wp_enqueue_script( 'plugin-categories-script', PLUGIN_CATEGORIES_URL . 'assets/js/plugin_categories.min.js', array('jquery') );
	}

	/**
	 * Add bulk actions
	 *
	 * @return array
	 **/
	public function bulk_actions() {

		?>
		<script type="text/javascript">
	      jQuery(document).ready(function() {
	        jQuery('<option>').val('categorize').text('<?php _e('Add Category', 'plugin_categories')?>').appendTo("select[name='action']");
	        jQuery('<option>').val('categorize').text('<?php _e('Add Category', 'plugin_categories')?>').appendTo("select[name='action2']");
	      });
	    </script>
		<?php
	}

	/**
	 * Add bulk actions
	 *
	 * @return array
	 **/
	public function bulk_actions_categories() {

		$output  = '<select name="plugin_category_select" id="bulk-action-selector-category" style="display:none">';

		foreach( (array)$this->category->all() as $category ) {
			$output .= '<option value="'. $category->id .'">'. $category->name .'</option>';
		}

		$output .= '</select>';

		echo $output;

	}

	/**
	 * Add bulk actions
	 *
	 * @return array
	 **/
	public function bulk_actions_save() {

		$wp_list_table = _get_list_table('WP_Plugins_List_Table');
		$action = $wp_list_table->current_action();

		$sendback = 'plugins.php';

		switch($action) {
			
			case 'categorize':

					$plugins = isset( $_POST['checked'] ) ? (array) $_POST['checked'] : array();
					$category = isset( $_POST['plugin_category_select'] ) ? (string) $_POST['plugin_category_select'] : false;

					if( ! empty( $plugins ) && $category ) {
						$added = array();
						foreach( $plugins as $plugin ) {

							$this->plugin->save( array('category' => $category, 'plugin' => $plugin) );
							$added[] = urlencode($plugin);

						}

						$sendback = add_query_arg( array( 'category' => $category, 'categories-added' => true, 'added' => implode(',', $added) ), $sendback );

					}
					wp_redirect($sendback);

					exit();

				return;
				
				break;
			default: return;
		}
	}

	/**
	 * Add columns
	 *
	 * @return array
	 **/
	public function columns( $columns ) {

		$columns['categories'] = __( 'Categories' );

		return $columns;
	}

	/**
	 * Column content
	 *
	 * @return array
	 **/
	public function column_content( $column_name, $plugin_file, $plugin_data ){
		
		if( $column_name === 'categories' ) {

			$categories = $this->plugin->find($plugin_file);

			if(!$categories) {
				echo '&ndash;';
				return;
			}

			$return = array();
			foreach( $categories as $category ) {
				$cat = $this->category->find($category->category);
				$return[] = '<a href="plugins.php?plugins.php?plugin_category_name='. $cat->id .'">'. $cat->name .'</a>';
			}

			echo implode( ', ', $return );

		}

	}

}

?>