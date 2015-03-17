<?php  

/**
* Model for handling categories for a plugin
* 
* @author Tor Morten Jensen <tormorten@tormorten.no>
*/
class PluginCategory_Plugin extends PluginCategory_Model {

	/**
	 * Creates a new instance of the model. 
	 * 
	 * When $id equals null it will return void. When an integer is provided, the result will be the plugin object. For arrays it will try to create.
	 * 
	 * @param 	mixed 	$id 	The slug of a plugin or an array
	 *
	 * @return 	mixed 
	 **/
	public function __construct( $id = null ) {

		global $wpdb;

		$this->table = $wpdb->prefix .'plugin_categories_plugins';

		parent::__construct();

		if( $id !== null && !is_array( $id ) ) {

			return self::find($id);

		}
		elseif( is_array( $id ) ) {
			return self::create( $id );
		}

	}

	/**
	 * Searches the database for a plugin
	 * 
	 * @param 	string 	$id 	The slug of the plugin
	 *
	 * @return 	object 			The results from the database
	 **/
	public static function find( $id ) {

		$id 	= esc_sql( $id );

		$self 	= new static;

		$query = $self->builder->where( 'plugin', '=', $id )->groupBy('category');

		return $query->get();

	}

	/**
	 * Saves an item
	 * 
	 * When the class variable $item it will try to save it, if an own item is not provided
	 * 
	 * @param 	array 	$item 	A creation array
	 *
	 * @return 	boolean 		The result of the insert
	 **/
	public function save( $item = null ) {

		$exists = $this->builder->where( 'plugin', $item['plugin'] )->where( 'category', $item['category'] )->first();


		if( (boolean)$exists )
			return false;

		return parent::save( $item );

	}
}