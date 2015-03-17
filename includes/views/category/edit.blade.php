
<h3>{{ __( 'Edit category', 'plugin_categories' ) }}: {{ $category->name }}</h3>

<form method="post" action="">

	{{ wp_nonce_field( 'edit', 'plugin_categories' ) }}

	@include( 'category.partials.form', compact( 'category' ) )

</form>
