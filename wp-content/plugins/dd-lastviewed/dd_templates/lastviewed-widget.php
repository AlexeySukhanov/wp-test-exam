<?php

$title_is_active    = $this->post_title_settings['is_active'];
$title_is_link      = $this->post_title_settings['is_link'];

$thumb_is_active    = $this->post_thumb_settings['is_active'];
$thumb_is_link      = $this->post_thumb_settings['is_link'];
$thumb_size         = $this->post_thumb_settings['size'];

$content_is_active  = $this->post_content_settings['is_active'];
$content_is_link    = $this->post_content_settings['is_link'];
$content_type       = $this->post_content_settings['type'];
$more_active        = $this->post_content_settings['more_active'];
$more_title         = $this->post_content_settings['more_title'];

echo $this->before_widget;
echo $this->title ? $this->before_title . $this->title . $this->after_title : '';

if ($this->post_list->have_posts()) : ?>
    <ul class="lastViewedList">
        <?php while ($this->post_list->have_posts()) : $this->post_list->the_post();

            $id = get_the_ID();
            $title = get_the_title();
            $content = $this->contentfilter($id);

            $thumb = $this->get_the_dd_thumb_element($id, $thumb_size);
            $hasThumb = $thumb && $thumb_is_active;
            $permalink = get_permalink();
            $class = $hasThumb ? "lastViewedItem clearfix" : "lastViewedItem";

            ?>
            <li class="<?php echo $class; ?>">
                <?php if ($hasThumb): ?>
                    <?php if (!$thumb_is_link): ?>
                        <div class="lastViewedThumb"><?php echo $thumb; ?></div>
                    <?php else : ?>
                        <a class="lastViewedThumb" href="<?php echo $permalink; ?>"><?php echo $thumb; ?></a>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="lastViewedcontent">
                    <?php if ($title_is_active && $title_is_link) : ?>
                        <a class="lastViewedTitle" href="<?php echo $permalink; ?>"><?php echo $title; ?></a>
                    <?php elseif ($title_is_active && !$title_is_link) : ?>
                        <h3 class="lastViewedTitle"><?php echo $title; ?></h3>
                    <?php endif; ?>

                    <?php if ($content_is_link && $content_is_active) : ?>
                        <a href="<?php echo $permalink; ?>" class="lastViewedExcerpt">
                            <div>
                                <?php echo $content; ?>
                                <?php if ($more_active) : ?>
                                    <span class="more"><?php echo $more_title; ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php elseif (!$content_is_link && $content_is_active) : ?>
                        <div class='lastViewedExcerpt'>
                            <?php echo $content; ?>
                            <?php if ($more_active) : ?>
                                <a href="<?php echo $permalink; ?>" class="more"><?php echo $more_title; ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>
<?php echo $this->after_widget; ?>
