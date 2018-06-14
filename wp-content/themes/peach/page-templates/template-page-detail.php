<?php
/*
 * Template Name: Custom Detail Template
 * Description: Page template for detail pages
 */


get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<header class="entry-header">
				<div class="header-title">
                    <?php if(wp_is_mobile()):?>
                        <div class="breadcrumb">
		                    <?php
		                    if (function_exists('menu_breadcrumb')) {
			                    menu_breadcrumb(
				                    'detail',                             // Menu Location to use for breadcrumb
				                    ' / ',                        // separator between each breadcrumb
				                    '<p class="menu-breadcrumb">',      // output before the breadcrumb
				                    '</p>'                              // output after the breadcrumb
			                    );
		                    }
		                    ?>
                        </div>
                    <?php endif;?>
					<h1 class="entry-title"><?php echo __('Detail'); ?></h1>
					<?php twentyseventeen_edit_link( get_the_ID() ); ?>
					<?php if(!wp_is_mobile()):?>
                        <div class="breadcrumb">
							<?php
							if (function_exists('menu_breadcrumb')) {
								menu_breadcrumb(
									'detail',                             // Menu Location to use for breadcrumb
									' / ',                        // separator between each breadcrumb
									'<p class="menu-breadcrumb">',      // output before the breadcrumb
									'</p>'                              // output after the breadcrumb
								);
							}
							?>
                        </div>
					<?php endif;?>
				</div>
			</header><!-- .entry-header -->
			<div class="content">
				<?php
				if (has_nav_menu('detail')): ?>
					<div class="abt-left-panel">
						<div class="widget widget_nav_menu">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'detail',
								'menu_class'     => 'menu',
							) );
							?>
						</div>

						<div class="abt-left-panel-news"></div>
					</div>
				<?php endif; ?>
				<div class="news-right-panel">
					<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/page/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
				</div>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();
