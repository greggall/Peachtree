<?php
/**
 * Displays header media
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<div class="custom-header">

		<div class="custom-header-media-peach">
			<?php the_custom_header_markup(); ?>
		</div>

	<?php if (is_active_sidebar('header-extra-widget-area')): ?>
		<?php dynamic_sidebar('header-extra-widget-area'); ?>
	<?php endif; ?>

	<?php #get_template_part( 'template-parts/header/site', 'branding' ); ?>

</div><!-- .custom-header -->
