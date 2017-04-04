<?php global $post, $pinnacle;
    $kt_feat_width = 1170;
    $kt_portraittext = 'col-md-8';
    $kt_portraitimg_size = 'col-md-4';
    // Get summary setting
	$postsummery = 'img_portrait';

	$post_id		= $post->ID;
	$event_data 	= siw_get_event_data( $post_id );

?>
<article id="agenda-<?php the_ID(); ?>" <?php post_class('kad_blog_item postclass kad-animation'); ?> data-animation="fade-in" data-delay="0" itemscope="" itemtype="http://schema.org/BlogPosting">
    <div class="row">
        <?php $textsize = $kt_portraittext;
        get_template_part('templates/post', 'excerpt-portraitimg');
		?>
		<div class="<?php echo esc_attr( $textsize );?> postcontent">
            <header>

				<a href="<?php the_permalink() ?>" rel="bookmark" class="url">
				<?php echo '<h2 class="entry-title" itemprop="name headline">';  the_title(); echo '</h2>'; ?>
				<h4><?php echo esc_html( $event_data['duration'] );?></h4>
				</a>

            </header>
            <div class="entry-content" itemprop="articleBody">
			<p class="agenda-location"><?php echo esc_html( $event_data['location'] ) . '<br/>'. esc_html( $event_data['address'] ) . '<br/>' . esc_html( $event_data['postal_code'] . ' ' .  $event_data['city'] ) ; ?></p>
            <?php
				the_excerpt();
            ?>
			<a class="read-more" href="<?php the_permalink() ?>"><?php esc_html_e('Lees meer', 'siw');?></a>
            </div>
        </div><!-- Text size -->
        <div class="col-md-12 postfooterarea">
		<footer class="single-footer clearfix">
			<?php $categories= get_the_terms( $post->ID, 'soort_evenement');
			if ( $categories && ! is_wp_error( $categories ) ){
				$agenda_categories = array();
				foreach ( $categories as $category ) {
					$agenda_categories[] = $category->name;
				}
				$agenda_category = join( ", ", $agenda_categories );
			}
			if ( isset( $agenda_category ) ) {?>
				<span class="postedinbottom"><i class="kt-icon-tag4"></i> <?php echo esc_html( $agenda_category ); ?></span><?php
			}?>
		</footer>
        </div>
    </div><!-- row-->
</article> <!-- Article -->
