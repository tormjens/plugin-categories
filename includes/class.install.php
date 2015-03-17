<?php

class PluginCategory_Install {

	public function __construct() {

		$this->create_category_table();
		$this->create_plugin_table();

	}

	public function create_category_table() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'plugin_categories';
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			name tinytext NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

	public function create_plugin_table() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'plugin_categories_plugins';
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			category bigint(20) NOT NULL,
			plugin tinytext NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

}