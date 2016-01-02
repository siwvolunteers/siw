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
    $image_url = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'full' ); 
    $thumbnailURL = $image_url[0]; 
    $image = aq_resize($thumbnailURL, 360, 360, false);
    if(empty($image)) { $image = $thumbnailURL; } ?>
    	<div class="<?php echo esc_attr($kt_portraitimg_size);?>">
                <div class="imghoverclass img-margin-center" itemprop="image">
                    <a href="<?php the_permalink()  ?>" title="<?php the_title(); ?>">
                        <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>" width="360" height="360" class="iconhover" style="display:block;">
                    </a> 
                </div>
        </div>
    <?php $image = null; $thumbnailURL = null; 
} else {
    $thumbnailURL = pinnacle_post_default_placeholder_square();
    $image = aq_resize($thumbnailURL, 360, 360, false);
        if(empty($image)) { $image = $thumbnailURL; } 
        ?>
        <div class="<?php echo esc_attr($kt_portraitimg_size);?>">
            <div class="imghoverclass img-margin-center" itemprop="image">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <img src="<?php echo esc_url($image) ?>" alt="<?php the_title(); ?>" width="360" height="360" class="iconhover" style="display:block;">
                </a> 
            </div>
       	</div>
        <?php $image = null; $thumbnailURL = null; 
} ?>