<?php
/**
 * Template Name: Home Page
 *
 */

get_header();

?>

<div class="container blog sp-80">
    <div class="row">
        <div class="col-md-3">
            <aside class="sidebar ">
                <?php get_sidebar(); ?>
            </aside>
        </div>
        <article class="col-md-9 blog-wrap">
            <?php echo facetwp_display( 'sort' ) ?>
            <?php echo do_shortcode('[facetwp template="releases"]')?>
        </article>
    </div>
    <div class="row">
        <div class="col-md-12 float-right">
            <?php echo do_shortcode('[facetwp facet="page_numbers"]')?>
        </div>
    </div>
</div>

<?php





//get_template_part( 'section-parts/front-page-blogs' );

get_footer();?>
