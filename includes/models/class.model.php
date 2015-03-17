<?php  

/**
* Model for handling Plugin Categories
* 
* @author Tor Morten Jensen <tormorten@tormorten.no>
*/

class PluginCategory_Model {

	/**
	 * The name of the MySQL table
	 *
	 * @var string
	 **/
	public $table;

	/**
	 * Where a newly created item is stored temporarily
	 *
	 * @var array
	 **/
	public $item;

	/**
	 * Query Builder
	 *
	 * @var object
	 **/
	public $builder;

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

		// require the composer autoload
		require_once PLUGIN_CATEGORIES_PATH . 'vendor/autoload.php';

		$config = array(
            'driver'    => 'mysql', // Db driver
            'host'      => DB_HOST,
            'database'  => DB_NAME,
            'username'  => DB_USER,
            'password'  => DB_PASSWORD,
            'charset'   => DB_CHARSET, // Optional
            'collation' => DB_COLLATE, // Optional
            'options'   => array( // PDO constructor options, optional
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_EMULATE_PREPARES => false,
            ),
        );

		$connection 	= new \Pixie\Connection('mysql', $config);
		$builder 		= new \Pixie\QueryBuilder\QueryBuilderHandler($connection);

		$this->builder 	= $builder->table( $this->table );

	}

	public function __call($name, $arguments) {
        // Note: value of $name is case sensitive.
        echo "Calling object method '$name' "
             . implode(', ', $arguments). "\n";
    }

    /**  As of PHP 5.3.0  */
    public static function __callStatic($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        echo "Calling static method '$name' "
             . implode(', ', $arguments). "\n";
    }

	/**
	 * Searches the database for all categories
	 * 
	 * @param 	string 	$id 	The slug of the plugin
	 *
	 * @return 	object 			The results from the database
	 **/
	public static function all() {

		$self 	= new static;

		$query = $self->builder;

		return $query->get();

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

		$query = $self->builder->where( 'id', '=', $id );

		return $query->first();

	}

	/**
	 * Creates a new object
	 * 
	 * @param 	array 	$create A creation array
	 *
	 * @return 	object 			An instance of the model
	 **/
	public static function create( $create ) {

		$self 			= new static;

		if( !is_array( $create ) ) {
			return false;
		}

		$self->item  	= $create;

		return $self;

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

		if( $item !== null ) {
			$this->item = $item;
		}

		if( $this->item ) {

			return $this->builder->insert( $this->item );

		}

		return false;

	}

	/**
	 * Updates an item
	 * 
	 * @param 	string 	$id 	The plugin slug
	 * @param 	array 	$item 	A creation array
	 *
	 * @return 	boolean 		The result of the insert
	 **/
	public function update( $id, array $item ) {

		if( $item ) {

			return $this->builder->where( 'id', $id )->update( $item ) ;

		}

		return false;

	}

	/**
	 * Deletes an item
	 * 
	 * @param 	string 	$id 	The plugin slug
	 *
	 * @return 	boolean 		The result of the delete
	 **/
	public function delete( $id ) {

		return $this->builder->where( 'id', '=', $id )->delete();

	}
}