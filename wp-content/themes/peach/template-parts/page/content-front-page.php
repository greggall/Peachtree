<?php
/**
 * Displays content for front page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'twentyseventeen-panel ' ); ?> >

	<?php if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'twentyseventeen-featured-image' );

		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );

		$thumbnail_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'twentyseventeen-featured-image' );

		// Calculate aspect ratio: h / w * 100%.
		$ratio = $thumbnail_attributes[2] / $thumbnail_attributes[1] * 100;
		?>

		<div class="panel-image" style="background-image: url(<?php echo esc_url( $thumbnail[0] ); ?>);">
			<div class="panel-image-prop" style="padding-top: <?php echo esc_attr( $ratio ); ?>%"></div>
		</div><!-- .panel-image -->

	<?php endif; ?>

	<div class="panel-content">
        <?php if(wp_is_mobile()):?>
            
			<div class="wrap">
				<div class="entry-content">
					<?php
						/* translators: %s: Name of current post */
						the_content( sprintf(
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
							get_the_title()
						) );
					?>
				</div><!-- .entry-content -->

			</div><!-- .wrap -->
			
			<!--  Display Homepage slide for mobile version    -->
            <div class="mobile-home-slider">
		        <?php echo do_shortcode("[slide-anything id='445']"); ?>
            </div>
        <?php else:?>
            <!--  Display Homepage slide for Desktop version    -->
            <div class="desktop-home-slider">
                <?php echo do_shortcode("[slide-anything id='26']"); ?>
            </div>
			
			<div class="wrap">
				<div class="entry-content">
					<?php
						/* translators: %s: Name of current post */
						the_content( sprintf(
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
							get_the_title()
						) );
					?>
				</div><!-- .entry-content -->

			</div><!-- .wrap -->
			
        <?php endif;?>
		
	</div><!-- .panel-content -->

</article><!-- #post-## -->
