<?php 
	/**
		* @package Cr_Organization_Catalog
		* @version 1.0.3
	*/
	get_header();
	
	echo cr_bredcrumbus();
	
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="post-region">
	<div class="date-region"><?php $x = the_date('','','',false);  if ( $x !== '01.01.1111') echo $x; ?></div>
	<a class='objekt-region' href="<?php echo get_post_meta($post->ID, 'link', 1); ?>"><?php echo get_post_meta($post->ID, 'ankor', 1); ?></a>
</div>
<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif;	
get_footer();