
<h3>{{ __( 'Create a new category', 'plugin_categories' ) }}</h3>

<form method="post" action="">

	{{ wp_nonce_field( 'create', 'plugin_categories' ) }}
	
	@include( 'category.partials.form' )

</form>
