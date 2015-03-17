<?php
$name = '';
if( isset($category) && $category ) {
	$name = $category->name;
}
?>

<div class="form-wrap">
	<div class="form-field form-required term-name-wrap">
		<label for="category-name">{{ __( 'Name' ) }}</label>
		<input name="category-name" id="category-name" type="text" value="<?php echo $name ?>" size="40" aria-required="true">
		<p>{{ __( 'The name of the category.', 'plugin_categories' ) }}</p>
	</div>
</div>

{{ submit_button( __( 'Save Category', 'plugin_categories' ) ) }}