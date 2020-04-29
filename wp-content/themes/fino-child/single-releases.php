<?php
/**
 * The template for displaying single release for releases cpt.
 *
 * @package Fino
 */
 get_header();

 if(get_header_image()){
    $fino_overlay = "banner";
 }
 else{
    $fino_overlay = "nobanner";
 }
?>

 <section class="<?php echo esc_attr($fino_overlay);?>" <?php if ( get_header_image() ){ ?> style="background-image:url('<?php header_image(); ?>')"  <?php } ?>>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="<?php echo esc_attr($fino_overlay);?>-heading">

                        <?php
                        $post_object = get_field('artist');
                        if( $post_object ):
                            $post = $post_object;
                            setup_postdata( $post );
                            ?>
                            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php wp_title(''); ?></h1>
                            <?php wp_reset_postdata(); ?>
                        <?php else: ?>
                            <h1><?php wp_title(''); ?></h1>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== blogs ====== -->

  <div class="blog">
        <div class="container">

            <div class="row">
                <div class="col-md-12 ">
                    <div class="blog-detail">
                    <?php
                       if(have_posts()) :
                    ?>
                    <?php while(have_posts()) : the_post(); ?>

                       <?php  get_template_part( 'content-parts/content', 'single-release' ); ?>

                     <?php endwhile; ?>
                           <?php else :
                  get_template_part( 'content-parts/content', 'none' );
                endif; ?>

                    </div>

                </div>

            </div>

            <div class="row watched-container">
                <div class="col-md-12">
                    <h2 class="text-center watched-title">Already Watched</h2>
                    <?php echo do_shortcode('[dd_lastviewed widget_id="2"]'); ?>
                </div>
            </div>

        </div>
    </div>

<?php get_footer(); ?>