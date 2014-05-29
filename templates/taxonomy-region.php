<?php 
	/**
		* @package Cr_Organization_Catalog
		* @version 1.0.4
	*/
	get_header();

	echo cr_bredcrumbus();
	
	$have_posts = category_has_children();
	$objekt = get_queried_object();
	if ($have_posts == true) {
		$terms = get_terms("region",array('hide_empty' => 0, 'parent' => $objekt ->term_id  ));  
		$count = count($terms);
		if($count > 0){
			/*echo '<h2>';
			$cat_parent = $terms[0]->parent;
			if ($cat_parent == 0 ) {
				_e('Выберите страну','wp_panda');
				} else {
				_e('Выберите город или остров','wp_panda');
			}
			echo '</h2>';*/
			echo "<ul class='region-info'>";
			foreach ($terms as $term) {
				echo '<li class="region-info"><a href="'. get_term_link( (int) $term-> term_id , 'region' ) .'">'. $term->name .'</a></li>';
			}
			echo "</ul>";
		}
		
		} else {
		
		/*echo '<h1>'. $objekt ->name. '</h1>';*/
		$tag_array = array();
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		$posttags = get_the_tags();
		if ($posttags) {
			foreach($posttags as $tag) {
				$tag_array[] = $tag->slug  ; 
			}
		}
	endwhile; else: ?>
	<p><?php _e('Извините, но данная категория пуста'); ?></p>
	<?php endif; 
		$tagger = array_unique($tag_array);
		$too_count = count($tagger);
		if($too_count > 0){
			$out='';
			$out .='<ul class="region-info">';
			$tags = array();
			foreach ($tagger  as $tagg ) {
				$tages = get_tags(array('slug'=>$tagg));
				$name_tag = $tages[0]->name;
				$tags[$tagg] = mb_strtolower($name_tag);
			}
			
			asort( $tags ); 
			reset( $tags);
			
			while (list($key, $val) = each($tags)) {
			//	$out .= '<li class="region-info"><a href="?tag=' . $key . '">'. $val .'</a></li>';
				$out .= '<li class="region-info"><a href="' . $_SERVER['REQUEST_URI'] . '?tag=' .$key .'">'. $val .'</a></li>';
				
			}
			$out .='<ul>';
			echo $out;
		}
	}
get_footer();