<?php
/**
 * Template for displaying a featured image in post excerpt area.
 */
global $post, $kt_post_with_sidebar;
if($kt_post_with_sidebar) {
	$kt_portraitimg_size = 'col-md-5';
} else {
	$kt_portraitimg_size = 'col-md-4';
}
if (has_post_thumbnail( $post->ID ) ) {
	$image_id =  get_post_thumbnail_id( $post->ID );
    $image_url = wp_get_attachment_image_src($image_id, 'full' ); 
    $thumbnailURL = $image_url[0]; 
    $image = aq_resize($thumbnailURL, 360, 360, false, false);
} else {
	$thumbnailURL = pinnacle_post_default_placeholder_square();
    $image_url = array($thumbnailURL, 360, 360);
    $image = aq_resize($thumbnailURL, 360, 360, false, false);
    $image_id = null;
}

    if(empty($image[0])) { $image = array($thumbnailURL,$image_url[1],$image_url[2]);}

    if( kad_lazy_load_filter() ) {
      $image_src_output = 'src="data:image/gif;base64,R0lGODdhAQABAPAAAP///wAAACwAAAAAAQABAEACAkQBADs=" data-lazy-src="'.esc_url($image[0]).'" '; 
   } else {
      $image_src_output = 'src="'.esc_url($image[0]).'"'; 
   }?>
    	<div class="<?php echo esc_attr($kt_portraitimg_size);?>">
                <div class="imghoverclass img-margin-center" itemprop="image">
                    <a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
                         <img <?php echo $image_src_output; ?> alt="<?php the_title(); ?>" itemprop="contentUrl" class="iconhover" width="<?php echo esc_attr($image[1]);?>" height="<?php echo esc_attr($image[2]);?>" <?php echo kt_get_srcset_output($image[1], $image[2], $thumbnailURL, $image_id);?>>
                                    <meta itemprop="url" content="<?php echo esc_url($image[0]); ?>">
                                    <meta itemprop="width" content="<?php echo esc_attr($image[1])?>">
                                    <meta itemprop="height" content="<?php echo esc_attr($image[2])?>">
                    </a> 
                </div>
        </div>
    <?php $image = null; $thumbnailURL = null; 
