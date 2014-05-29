<?php 
	/**
		* @package Cr_Organization_Catalog
		* @version 1.0.4
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
	
		function get_taxonomy_parents( $id, $taxonomy = 'category',$link = false, $separator = '/', $nicename = false,$visited = array() ) {

            $chain = '';
			//$out ='';
            $parent = get_term( $id, $taxonomy );
			$term = get_queried_object();
			$term_id = $term->term_id;
			
            if ( is_wp_error( $parent ) )
                    return $parent;

            if ( $nicename )
                    $name = $parent->slug;
            else
                    $name = $parent->name;

            if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
                    $visited[] = $parent->parent;
                    $chain .= get_taxonomy_parents( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
            }

           if ( $link )
			{
				if ( $parent->term_id != $term_id )
				{
						$chain .= '<a href="' . esc_url( get_term_link( $parent,$taxonomy ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $parent->name ) ) . '">'.$name.'</a>' . $separator;
				}
				else
				{
						$chain .= $name;
				}
			}
			else
			{
				if ( $parent->term_id != $term_id )
				{
					$chain .= $name.$separator;
				}
				else
				{
					$chain .= $name;
				}
			}
			
			return $chain; 
						
    }
	
	
		function get_taxonomy_parents_revers( $id, $taxonomy = 'category',$link = false, $separator = '/', $nicename = false,$visited = array() ) {

            $chain = array();
			$out ='';
            $parent = get_term( $id, $taxonomy );
			$term = get_queried_object();
			$term_id = $term->term_id;
			
            if ( is_wp_error( $parent ) )
                    return $parent;

            if ( $nicename )
                    $name = $parent->slug;
            else
                    $name = $parent->name;

            if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
                    $visited[] = $parent->parent;
                    $chain[] = get_taxonomy_parents_revers( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
            }

           if ( $link )
			{
				if ( $parent->term_id != $term_id )
				{
						$chain[] = '<a href="' . esc_url( get_term_link( $parent,$taxonomy ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $parent->name ) ) . '">'.$name.'</a>' . $separator;
				}
				else
				{
						$chain[] = $name;
				}
			}
			else
			{
					$chain[] = $name . $separator  ;

			}
			
		$chain = array_reverse( $chain , true );
			
			foreach ($chain as $key)
			{
				$out .= $key;
			}
            return $out ;
			
			
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
			$out[] = "<a href='" . $url . "' alt=''>" . __('Главная','wp_panda') . "</a> | ";  // для главной
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
				}	
				
					global $query_string; 
					parse_str( $query_string ); // разбираем запрос
					$this_category = get_term_by( 'slug', $region, 'region' ); 
					$this_category_id = $this_category -> term_id; // ID категории по которой происходил поиск
					
					$chain = get_taxonomy_parents( $this_category_id ,'region',true, ' | ');
					
					$out[] = $chain;

	

				if (is_tag() )
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
		
		$term = get_queried_object();
		$out = array();
		
		if( is_tax('region')  ||  is_tag() ) // если это таксономия или тэги 
		{   
		
		if( is_tag() ) {
		$out[] = $term ->name .' | ' ; // текущаяя категория
		}
		global $query_string; 
					parse_str( $query_string ); // разбираем запрос
					$this_category = get_term_by( 'slug', $region, 'region' ); 
					$this_category_id = $this_category -> term_id; // ID категории по которой происходил поиск
					
					$chain = get_taxonomy_parents_revers( $this_category_id ,'region',false, ' | ');
					
					$out[] = $chain;
			
			$parent_cat_id = $term->parent;  // получаем родительскую категорию
			
			if( $parent_cat_id !='0' ) // если категория имеет родительские
			{
				$name = get_term( $term->parent, 'region' );
				//$out[] = ' | ' . $name->name;		
			} 
		} 
		
		if ( is_front_page() )
		{
			$out[] = get_bloginfo( 'description', 'display' );
		}
			
		$out[] = '' . get_bloginfo( 'name' );
		$output ='';
		foreach ( $out as $key) $output .= $key;
		echo trim($output);
	}