<?php   

/* For Single Release Page Results
*/

?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-img">
            <?php if(has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('fino-page-thumbnail', array('class' => 'img-responsive')); ?>
            <?php endif; ?>
        </div>

        <div class="blog-detail-content blog-detail-content--release">
            <div class=" post-detail--realese">
                <div class="col-md-12 ">
                    <h2 class="text-center mb-60 mte-60"><?php the_title(); ?></h2>
                </div>
                <div class="col-md-6 post-detail-meta">
                    <dl class="dl-horizontal">
                        <?php
                        $post_object = get_field('artist');
                        if( $post_object ):
                            $post = $post_object;
                            setup_postdata( $post );
                            ?>
                            <dt>Artist:</dt>
                            <dd><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dd>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>

                        <?php
                        $cur_terms = get_the_terms( $post->ID, 'year' );
                        if( is_array( $cur_terms ) ):
                            ?>
                            <dt>Year:</dt>
                            <?php
                            foreach( $cur_terms as $cur_term ):
                                echo '<dd><a href="'. get_term_link( $cur_term->term_id, $cur_term->taxonomy ) .'">'. $cur_term->name .'</a></dd>';
                            endforeach;
                        endif;
                        ?>

                        <?php
                        $cur_terms = get_the_terms( $post->ID, 'format' );
                        if( is_array( $cur_terms ) ):
                            ?>
                            <dt>Format:</dt>
                            <?php
                            foreach( $cur_terms as $cur_term ):
                                echo '<dd><a href="'. get_term_link( $cur_term->term_id, $cur_term->taxonomy ) .'">'. $cur_term->name .'</a></dd>';
                            endforeach;
                        endif;
                        ?>
                    </dl>
                </div>

                <div class="col-md-6 post-detail-meta">
                    <dl class="dl-horizontal">
                        <?php
                            $cur_terms = get_the_terms( $post->ID, 'country' );
                            if( is_array( $cur_terms ) ):
                            ?>
                            <dt>Country:</dt>
                            <?php
                            foreach( $cur_terms as $cur_term ):
                                echo '<dd><a href="'. get_term_link( $cur_term->term_id, $cur_term->taxonomy ) .'">'. $cur_term->name .'</a></dd>';
                            endforeach;
                        endif;
                        ?>

                        <?php
                        $cur_terms = get_the_terms( $post->ID, 'genre' );
                        if( is_array( $cur_terms ) ):
                            ?>
                            <dt>Genre:</dt>
                            <?php
                            foreach( $cur_terms as $cur_term ):
                                echo '<dd><a href="'. get_term_link( $cur_term->term_id, $cur_term->taxonomy ) .'">'. $cur_term->name .'</a></dd>';
                            endforeach;
                        endif;
                        ?>

                        <?php
                        $cur_terms = get_the_terms( $post->ID, 'style' );
                        if( is_array( $cur_terms ) ):
                            ?>
                            <dt>Style:</dt>
                            <?php
                            foreach( $cur_terms as $cur_term ):
                                echo '<dd><a href="'. get_term_link( $cur_term->term_id, $cur_term->taxonomy ) .'">'. $cur_term->name .'</a></dd>';
                            endforeach;
                        endif;
                        ?>
                    </dl>
                </div>

                <?php if(get_field('general_info')): ?>
                    <dl class="dl-horizontal">
                        <dt>General Info:</dt>
                        <dd><?php the_field( 'general_info' ); ?></dd>
                    </dl>
                <?php endif; ?>

            </div>
            <?php if( have_rows('tracklist') ): ?>
                <div class="post-detail-meta">
                    <h3 class="mb-30">Track list: </h3>
                    <table class="table table-hover tracklist">
                        <col width="5%">
                        <col>
                        <col width="10%">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Track Title</th>
                            <th scope="col">Track Length</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        <?php while ( have_rows('tracklist') ) : the_row(); ?>
                            <tr>
                                <th scope="row"><?php echo $i; ?></th>
                                <td class="trackname"><?php the_sub_field('track_name'); ?></td>
                                <td><?php the_sub_field('track_length'); ?></td>
                            </tr>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>


            <ul class="post-detail-meta">
              <li>
                 <i class="fa fa-calendar"></i><span><?php echo get_the_date(); ?></span>
              </li>
              <li><?php echo esc_html__( 'By', 'fino' ); ?> <i>:</i>
                  <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                     <i class="fa fa-user"></i> <?php the_author(); ?>
                  </a>
              </li>
              <li>
                  <span>
                    <i class="fa fa-comment"></i><?php comments_number( __('0 Comment', 'fino'), __('1 Comment', 'fino'), __('% Comments', 'fino') ); ?>
                  </span>
              </li>
              <li>
                  <a>
                    <i class="fa fa-folder-open"></i><?php the_category(',&nbsp; &nbsp;'); ?>
                  </a>
              </li>

              <?php if (has_tag()) : ?>
              <li>
                <i class="fa fa-tag"></i>
                <?php echo esc_html__(' ', 'fino' ); ?><?php the_tags('&nbsp;'); ?>
              </li>
              <?php endif; ?>
            </ul>
          <div class="para">
              <?php the_content(); ?>
                <?php
                    wp_link_pages( array(
                   'before' => '<div class="page-links">' . esc_html__('Pages: ', 'fino' ),
                    'after'  => '</div>',
                     ) );
                ?>
          </div>
        
       </div> 

  <?php 
              if ( comments_open() || get_comments_number() ) :
                comments_template();
              endif; 
          ?> 	   
    </div>