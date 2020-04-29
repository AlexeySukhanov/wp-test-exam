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

        <div class="blog-detail-content blog-detail-content--artist">
            <div class="post-detail-meta post-detail--realese">
                <div class="col-md-12 ">
                    <h1 class="text-center mb-60"><?php the_title(); ?></h1>
                    <h2 class="text-center mb-20">General Info</h2>
                    <div class="post-detail-meta"><?php the_content(); ?></div>
                </div>

                <div class="col-md-12 ">
                <?php

                // Get formats and releases for current artist
                $formats_names = array();
                $formatted_releases = array();

                global $wp_query;
                $current_post_id = $wp_query->queried_object->ID;
                $args = array(
                    'post_type' => 'releases',
                    'meta_query' => array(
                        array(
                            'key' =>'artist',
                            'value' => $current_post_id,
                            'compare' => 'LIKE'
                        )
                    )
                );
                $query = new WP_Query( $args );

                if ( $query->have_posts() ):
                    while( $query->have_posts() ):
                        $query->the_post();
                        $cur_terms = get_the_terms( $post->ID, 'format' );
                        if( is_array( $cur_terms ) ):
                            foreach( $cur_terms as $cur_term ):
                                $formats_names[] = $cur_term->name;
                                $formatted_releases[ $post->ID ] = $cur_term->name;
                            endforeach;
                        endif;
                    endwhile;
                    wp_reset_postdata();

                    $unique_formats_names = array_unique( $formats_names );


                    // Output discography with separation by release format
                    echo '<h2 class="text-center mt-60">Discography</h2>';
                    foreach ( $unique_formats_names as $unique_formats_name ):
                        echo '<h3  class="text-center">' . $unique_formats_name . '</h3>';

                        $titles_years = array();
                        foreach ( $formatted_releases as $current_release_id => $current_format ):
                            if ( $current_format == $unique_formats_name ):
                                $cur_terms = get_the_terms( $current_release_id, 'year' );
                                $titles_years[ get_the_title( $current_release_id ) ] = (int) $cur_terms[0]->name;
                            endif;
                        endforeach;
                        arsort( $titles_years );

                        foreach ( $titles_years as $title => $year  ):
                            echo '<div class="realise-line"><div>' . $title . '</div><div class="float-right">' . $year . '</div></div>';
                        endforeach;

                    endforeach;

                ?>

                 <div class="post-detail-meta">

                <?php
                while( $query->have_posts() ):
                    $query->the_post();
                    $cur_terms = get_the_terms( $post->ID, 'year' );
                    if( is_array( $cur_terms ) ):
                        foreach( $cur_terms as $cur_term ):
                            $formats_names[] = $cur_term->name;
                            $releases_year[ $post->ID ] = $cur_term->name;
                        endforeach;
                    endif;
                endwhile;

                wp_reset_postdata();
                arsort($releases_year);

                echo '<h2 class="text-center">Tracks List</h2>';

                $last_year = '';

                foreach ( $releases_year as $release => $year ):

                    if ( $last_year !== $year ) {
                        echo '<h3 class="text-center">'  . $year .  '</h3>';
                    } else {
                        continue;
                    }
                    ?>

                    <table class="table table-hover tracklist">
                        <colgroup>
                            <col width="90%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">Track Title</th>
                                <th scope="col">Track Length</th>
                            </tr>
                        </thead>
                    <tbody>

                    <?php
                    while( $query->have_posts() ):
                        $query->the_post();

                        $current_year = get_the_terms( $post->ID, 'year' )[0]->name;

                        if ( $current_year == $year ):
                            $id = $post->ID;

                            if( have_rows('tracklist', $id ) ): ?>
                                        <?php while ( have_rows('tracklist') ) : the_row(); ?>
                                            <tr>
                                                <td class="trackname"><?php the_sub_field('track_name'); ?></td>
                                                <td><?php the_sub_field('track_length'); ?></td>
                                            </tr>
                                            <?php $i++; ?>
                                        <?php endwhile; ?>
                            <?php endif; // end track list

                        endif; // end year checking

                    endwhile; // end posts loop

                    wp_reset_postdata();
                    ?>
                    </tbody>
                    </table>

                    <?php
                    $last_year = $year;
                endforeach; // end years loop ?>
                    </div>

                <?php endif; // end have releases ?>

            </div>

       </div> 

    </div>