<?php
/**
 * The template for displaying single posts.
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php generate_do_microdata( 'article' ); ?>>
	<div class="inside-article">
		<?php
		/**
		 * generate_before_content hook.
		 *
		 * @since 0.1
		 *
		 * @hooked generate_featured_page_header_inside_single - 10
		 */
		do_action( 'generate_before_content' );
		?>

		<header class="entry-header">
			<?php
			/**
			 * generate_before_entry_title hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_before_entry_title' );

			if ( generate_show_title() ) {
				the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' );
			}

			/**
			 * generate_after_entry_title hook.
			 *
			 * @since 0.1
			 *
			 * @hooked generate_post_meta - 10
			 */
			do_action( 'generate_after_entry_title' );
			?>
		</header><!-- .entry-header -->

		<?php
		/**
		 * generate_after_entry_header hook.
		 *
		 * @since 0.1
		 *
		 * @hooked generate_post_image - 10
		 */
		do_action( 'generate_after_entry_header' );
		?>

		<div class="entry-content" itemprop="text">
			<?php
				do_action( 'siw_tm_country_content' );
			?>
		</div><!-- .entry-content -->

		<?php
		/**
		 * generate_after_entry_content hook.
		 *
		 * @since 0.1
		 *
		 * @hooked generate_footer_meta - 10
		 */
		do_action( 'generate_after_entry_content' );

		/**
		 * generate_after_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'generate_after_content' );
		?>
	</div><!-- .inside-article -->
</article><!-- #post-## -->
