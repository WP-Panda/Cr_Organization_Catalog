<?php 
	/**
		* @package Cr_Organization_Catalog
		* @version 1.0.3
	*/
	get_header(); 
	
	echo cr_bredcrumbus(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="post-region">
	<div class="date-region"><?php $x = the_date('','','',false);  if ( $x !== '01.01.1111') echo $x; ?></div>
	<div class="tagger"><a class='objekt-region-s' href="<?php echo get_post_meta($post->ID, 'link', 1); ?>"><?php echo get_post_meta($post->ID, 'ankor', 1); ?></a>
	<?php _e('Регион - ','wp_panda') ?>
<?php
$cur_terms = get_the_terms( $post->ID,'region');
foreach($cur_terms as $cur_term){
	echo '<a href="'. get_term_link( (int)$cur_term->term_id, $cur_term->taxonomy ) .'">'. $cur_term->name .'</a> ';
}
 ?></div>
 <div class="clear"></div>
</div>
<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif;	
get_footer();