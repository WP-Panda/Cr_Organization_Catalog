<?php 
	/**
		* @package Cr_Organization_Catalog
		* @version 1.0.3
	*/
	/*----------------------------------------------------------------------------*/
	/*  проверка рубрики на наличие дочерних
	/*----------------------------------------------------------------------------*/
	function category_has_children()
	{
		global $wpdb;
		$term = get_queried_object();
		$category_children_check = $wpdb->get_results(" SELECT * FROM wp_term_taxonomy WHERE parent = '$term->term_id' ");
		if ($category_children_check) 
		{
			return true;
			//echo 'есть дочерние';
		} 
		else 
		{
			return false;
			//echo 'нет дочерних';
		}
		
	}
	
	/*----------------------------------------------------------------------------*/
	/*
	/*----------------------------------------------------------------------------*/
	add_action('save_post', 'wpds_check_thumbnail');
	add_action('admin_notices', 'wpds_thumbnail_error');
	
	function wpds_check_thumbnail($post_id) {
		
		// меняем на любой произвольный тип записи
		if(get_post_type($post_id) != 'organization')
        return;
		
		$x = get_post_meta($post_id,'link',1);
		if ( $x =="" ) {
			// устанавливаем блокировку для вывода ее пользователям в виде административного сообщения
			set_transient( "has_post_thumbnail", "no" );
			// делаем анхук функции, чтобы та не впала в бесконечный цикл
			remove_action('save_post', 'wpds_check_thumbnail');
			// обновляем запись, переводим ее в черновики
			wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
			
			add_action('save_post', 'wpds_check_thumbnail');
			} else {
			delete_transient( "has_post_thumbnail" );
		}
	}
	
	function wpds_thumbnail_error()
	{
		// проверяем, установлена ли блокировка, и выводим сообщение об ошибке
		if ( get_transient( "has_post_thumbnail" ) == "no" ) {
			//echo "<div id='message' class='error'><p><strong>Вы должны задать cсылку. Ваша запись сохранена, но не может быть опубликована.</strong></p></div>";
			delete_transient( "has_post_thumbnail" );
		}
		
	}
	
	
	/*----------------------------------------------------------------------------*/
	/* Хлебные крошки
	/*----------------------------------------------------------------------------*/
	
	function cr_bredcrumbus()
	{
		$term = get_queried_object();
		$out = array();
		$out[] ="<div id='cr_bred'>";
		if ( !is_front_page() )
		{
			$url = get_home_url();
			$out[] = "<a href='" . $url . "' alt=''>" . __('Главная','wp_panda') . "</a> / ";  // для главной
		}
		
		if( !is_search() )
		{
			if( is_tax('region')  ||  is_tag() ) // если это таксономия или тэги 
			{   
				$parent_cat_id = $term->parent;  // получаем родительскую категорию
				
				if( $parent_cat_id !='0' ) // если категория имеет родительские
				{
					$url  = get_term_link( $term->parent, 'region' );
					$name = get_term( $term->parent, 'region' );
					$out[] = "<a href='" . $url . "' alt=''>" . $name->name . "</a> / ";
					
				} 
				
				if( is_tag() ) {
					global $query_string; 
					parse_str( $query_string ); // разбираем запрос
					$this_category = get_term_by( 'slug', $region, 'region' ); 
					$this_category_id = $this_category -> term_id; // ID категории по которой происходил поиск
					$this_category_url  = get_term_link( $this_category_id , 'region' );
					$cat_parent_id = $this_category ->parent; // ID родительской категории
					$cat_parent_url  = get_term_link( $cat_parent_id , 'region' );
					$cat_parent = get_term( $cat_parent_id, 'region' );
					if( isset( $cat_parent ) ) $out[] = "<a href='" . $cat_parent_url. "' alt=''>" . $cat_parent->name . "</a> / ";
					$out[] = "<a href='" . $this_category_url . "' alt=''>" . $this_category -> name . "</a> / ";		
				}
				
				$out[] = $term ->name; // текущаяя категория
				
			}
		}
		else
		{
			$out[]= __('Результаты поиска по запросу - ','wp_panda') . get_search_query(); 
		}
		$out[] ='</div>';
		foreach ( $out as $key) echo $key ;
		
	}										
	
	
	
	function cr_wp_title() {
		
		if ( is_feed() )
		return $title;
		
		// Add the site name.
		
		$term = get_queried_object();
		$out = array();
		
		
		
		
	
		
		if( is_tax('region')  ||  is_tag() ) // если это таксономия или тэги 
		{   
		
		$out[] = $term ->name; // текущаяя категория
		
		if( is_tag() ) {
				global $query_string; 
				parse_str( $query_string ); // разбираем запрос
				$this_category = get_term_by( 'slug', $region, 'region' ); 
				$cat_parent_id = $this_category ->parent; // ID родительской категории
				$cat_parent = get_term( $cat_parent_id, 'region' );
				$out[] = ' | ' . $this_category->name;	
				$out[] = ' | ' . $cat_parent->name;	
			}
			
			$parent_cat_id = $term->parent;  // получаем родительскую категорию
			
			if( $parent_cat_id !='0' ) // если категория имеет родительские
			{
				$name = get_term( $term->parent, 'region' );
				$out[] = ' | ' . $name->name;		
			} 
		}
		
		if ( is_front_page() )
		{
			$out[] = get_bloginfo( 'description', 'display' );
		}
			
		$out[] = ' | ' . get_bloginfo( 'name' );
		$output ='';
		foreach ( $out as $key) $output .= $key;
		echo trim($output);
	}
