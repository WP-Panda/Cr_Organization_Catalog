<?php
	/**
		* @package Cr_Organization_Catalog
		* @version 1.0.4
	*/
	/*----------------------------------------------------------------------------*/
	/*register taxonomy
	/*----------------------------------------------------------------------------*/
	add_action( 'init', 'register_regions_info' );  
	
	function register_regions_info() {
		
		$labels = array(
		'name' =>__('Организации','wp_panda'),
		'singular_name' =>__('Организации','wp_panda'),
		);
		
		$args = array(
		'labels' => $labels,
		'public' => true,
		'menu_position' => 15,
		'capability_type' => 'post',
		'hierarchical' => false,
		'query_var' => true,
		'supports' => array( 'title'/*, 'editor', 'comments', 'thumbnail', 'custom-fields' */,'tags'),
		'taxonomies' => array( 'post_tag' ),
		'menu_icon' => 'dashicons-location-alt',
		'has_archive' => true,
		'capability_type' => 'post'
		);
		
		register_post_type( 'organization', $args );
	}
	
	add_action( 'init', 'create_regions' );  
	
	function create_regions() {  
		
		register_taxonomy('region','organization',
		
		array(  
		'hierarchical' => true,  
		'label' => __('Регионы','wp_panda'),
		"singular_label" => __('Регион','wp_panda'),
		"rewrite" => true,
		"query_var" => true
		)
		);  
		
	} 
	
	/*----------------------------------------------------------------------------*/
	/*Include custom template
	/*----------------------------------------------------------------------------*/
	
	add_filter( 'template_include','include_region_template_function', 1 );
	
	function include_region_template_function( $template_path ) 
	{
		
		if ( is_single() && get_post_type() == 'organization' ) 
		{
			if ( ! locate_template('single-organization.php', false) ) 
			{
				$template_path = CR_ORGANIZATION_CATALOG_DIR . 'templates/single-organization.php';
			}
		} 
		elseif ( is_tax('region') ) 
		{
			if ( ! locate_template('taxonomy-region.php', false) )
			{
				$template_path = CR_ORGANIZATION_CATALOG_DIR . 'templates/taxonomy-region.php';
			}
		}
		elseif ( is_post_type_archive( 'organization' ) )
		{
			if ( ! locate_template('archive-organization.php', false) ) {
				$template_path = CR_ORGANIZATION_CATALOG_DIR . 'templates/archive-organization.php';
			}
		}
		elseif ( is_tag() ) 
		{
			//if ( ! locate_template('archive-organization.php', false) ) {
			$template_path = CR_ORGANIZATION_CATALOG_DIR . 'templates/tag.php';
			//}
		}
		elseif( is_search() )
		{
			$template_path = CR_ORGANIZATION_CATALOG_DIR . 'templates/search.php';
		}
		
		return $template_path;
		
	}
	
	/*----------------------------------------------------------------------------*/
	/*add metabox
	/*----------------------------------------------------------------------------*/
	add_action('add_meta_boxes', 'organization_info_fields', 1);
	
	function organization_info_fields() {
		add_meta_box( 'info_fields', __('Тип записи','wp_panda'), 'info_fields_box_func', 'organization', 'normal', 'high'  );
	}
	global $post;
	function info_fields_box_func( $post ){
	?>
	
	<p><label>Ссылка на организацию<br><input type="text" style='width:100%;' name="info[link]" value="<?php echo get_post_meta($post->ID, 'link', 1); ?>" /> </label></p>
	<p><label>Анкор ссылки на организацию<br><input type="text" style='width:100%;' name="info[ankor]" value="<?php echo get_post_meta($post->ID, 'ankor', 1); ?>" /> </label></p>
	<input type="hidden" name="info_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
	}
	
	add_action('save_post', 'organization_info_fields_update', 0);
	
	function organization_info_fields_update( $post_id ){
		if( !empty( $_POST['info_fields_nonce'] ) ) {
			if ( !wp_verify_nonce($_POST['info_fields_nonce'], __FILE__) ) return false;
		}
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false;
		if ( !current_user_can('edit_post', $post_id) ) return false;
		
		if( !isset($_POST['info']) ) return false;	
		
		$_POST['info'] = array_map('trim', $_POST['info']);
		foreach( $_POST['info'] as $key=>$value ){
			if( empty($value) ){
				delete_post_meta($post_id, $key);
				continue;
			}
			
			update_post_meta($post_id, $key, $value);
		}
		return $post_id;
	}		