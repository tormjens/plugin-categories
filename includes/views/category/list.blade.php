@extends( 'master' )

@section( 'title', __( 'Plugin Categories', 'plugin_categories' ) )

@section( 'sidebar' )

	@include('category.new')

@endsection

@section( 'content' )
	
	<h3>{{ __( 'All Categories', 'plugin_categories' ) }}</h3>

	<table class="wp-list-table widefat fixed striped tags">
		<thead>
			<tr>
				<th scope="col" id="name" class="manage-column column-name  desc" style="">
					<span>{{ __( 'Name' ) }}</span>
				</th>
				<th scope="col" id="posts" class="manage-column column-posts num  desc" style="">
					<span>{{ __( 'Total', 'plugin_categories' ) }}</span>
				</th>	
			</tr>
		</thead>

		<tbody id="the-list" data-wp-lists="list:tag">
			@foreach($categories as $category)
				<tr id="tag-1">
					<td class="name column-name">
						<strong>
							<a class="row-title" href="plugins.php?page=plugin_categories&amp;action=edit&amp;id={{ $category->id }}">{{ $category->name }}</a>
						</strong><br>
						<div class="row-actions">
							<span class="edit">
								<a href="plugins.php?page=plugin_categories&amp;action=edit&amp;id={{ $category->id }}">{{ __( 'Edit', 'plugin_categories' ) }}</a> | 
							</span>
							<span class="delete">
								<a href="plugins.php?page=plugin_categories&amp;action=delete&amp;id={{ $category->id }}">{{ __( 'Delete', 'plugin_categories' ) }}</a>
							</span>
						</div>
					</td>
					<td class="posts column-posts">
						<a href="plugins.php?plugin_category_name=uncategorized">2</a>
					</td>
				</tr>
			@endforeach
		</tbody>

		<tfoot>
			<tr>
				<th scope="col" id="name" class="manage-column column-name  desc" style="">
					<span>{{ __( 'Name' ) }}</span>
				</th>
				<th scope="col" id="posts" class="manage-column column-posts num  desc" style="">
					<span>{{ __( 'Total', 'plugin_categories' ) }}</span>
				</th>	
			</tr>
		</tfoot>

	</table>

@endsection