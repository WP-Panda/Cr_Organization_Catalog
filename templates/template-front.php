<?php 
	/**
		* @package Cr_Organization_Catalog
		* @version 1.0.3
	*/
	get_header();

	echo cr_bredcrumbus();
	
	$have_posts = category_has_children();
	$objekt = get_queried_object();
	if ($have_posts == true) {
		$terms = get_terms("region",array('hide_empty' => 0));  
		$count = count($terms);
	if($count > 0){
	/*	echo '<h2>';
		_e('Выберите страну','wp_panda');
		echo '</h2>';*/
			echo "<ul class='region-info'>";
			foreach ($terms as $term) {
				$cat_parent = $term->parent;
				if ($cat_parent == 0 ) {
					echo '<li class="region-info"><a href="'. get_term_link( (int) $term-> term_id , 'region' ) .'">'. $term->name .'</a></li>';
				}
			}
			echo "</ul>";
		}
		
		} else { 
		
		__('Извините, но данная категория пуста'); 
		}
	get_footer();	