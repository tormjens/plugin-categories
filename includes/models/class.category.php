<?php  

/**
* Model for handling Plugin Categories
* 
* @author Tor Morten Jensen <tormorten@tormorten.no>
*/
class PluginCategory extends PluginCategory_Model {


	/**
	 * Creates a new instance of the model. 
	 * 
	 * When $id equals null it will return void. When an integer is provided, the result will be the plugin object. For arrays it will try to create.
	 * 
	 * @param 	mixed 	$id 	The category id or an array
	 *
	 * @return 	mixed 
	 **/
	public function __construct( $id = null ) {

		global $wpdb;

		$this->table = $wpdb->prefix .'plugin_categories';

		parent::__construct();

		if( $id !== null && !is_array( $id ) ) {

			return self::find($id);

		}
		elseif( is_array( $id ) ) {
			return self::create( $id );
		}

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

		$exists = $this->builder->where( 'name', '=', $item['name'] )->first();

		if( $exists )
			return false;

		parent::save( $item );

	}
}