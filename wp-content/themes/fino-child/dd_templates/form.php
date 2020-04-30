<?php

$args_custom_types = array(
    'public' => true,
    '_builtin' => false
);//grab the post_types active in theme
$args_default_types = array(
    'public' => true,
    '_builtin' => true
);
$lastviewedTitle = isset($instance['lastviewedTitle']) ? $instance['lastviewedTitle'] : __("Last Viewed", "dd-lastviewed");
$widgetID = str_replace('dd_last_viewed-', '', $this->id);
$output = 'names'; // names or objects, note names is the default
$operator = 'and'; // 'and' or 'or'
$custom_post_types = get_post_types($args_custom_types, $output, $operator);
$default_post_types = get_post_types($args_default_types, $output, $operator);
$post_types = array_merge($custom_post_types, $default_post_types);
$lastViewed_total = isset($instance['lastViewed_total']) ? $instance['lastViewed_total'] : 5;
$lastViewed_total = esc_attr($lastViewed_total);
$lastViewed_truncate = isset($instance['lastViewed_truncate']) ? $instance['lastViewed_truncate'] : 78;
$lastViewed_truncate = esc_attr($lastViewed_truncate);
$lastViewed_linkname = isset($instance['lastViewed_linkname']) ? $instance['lastViewed_linkname'] : "More";
$lastViewed_linkname = esc_attr($lastViewed_linkname);
$lastViewed_showPostTitle = isset($instance['lastViewed_showPostTitle']) ? (bool)$instance['lastViewed_showPostTitle'] : false;
$lastViewed_ajaxLoad = isset($instance['lastViewed_ajaxLoad']) ? (bool)$instance['lastViewed_ajaxLoad'] : false;
$lastViewed_cookieByJs = isset($instance['lastViewed_cookieByJs']) ? (bool)$instance['lastViewed_cookieByJs'] : false;
$lastViewed_showThumb = isset($instance['lastViewed_showThumb']) ? (bool)$instance['lastViewed_showThumb'] : false;
$lastViewed_thumbSize = isset($instance['lastViewed_thumbSize']) ? $instance['lastViewed_thumbSize'] : "thumbnail";
$lastViewed_thumbSize = esc_attr($lastViewed_thumbSize);
$lastViewed_showExcerpt = isset($instance['lastViewed_showExcerpt']) ? (bool)$instance['lastViewed_showExcerpt'] : false;
$lastViewed_content_type = isset($instance['lastViewed_content_type']) ? $instance['lastViewed_content_type'] : "excerpt";
$lastViewed_showTruncate = isset($instance['lastViewed_showTruncate']) ? (bool)$instance['lastViewed_showTruncate'] : false;
$lastViewed_showMore = isset($instance['lastViewed_showMore']) ? (bool)$instance['lastViewed_showMore'] : false;
$lastviewed_excl_ids = isset($instance['lastviewed_excl_ids']) ? $instance['lastviewed_excl_ids'] : array();
$lastViewed_lv_link_title = isset($instance['lastViewed_lv_link_title']) ? (bool)$instance['lastViewed_lv_link_title'] : false;
$lastViewed_lv_link_thumb = isset($instance['lastViewed_lv_link_thumb']) ? (bool)$instance['lastViewed_lv_link_thumb'] : false;
$lastViewed_lv_link_excerpt = isset($instance['lastViewed_lv_link_excerpt']) ? (bool)$instance['lastViewed_lv_link_excerpt'] : false;
$cookie_lifetime_checked = isset($instance['cookie_lifetime_checked']) ? (bool)$instance['cookie_lifetime_checked'] : false;
$cookie_lifetime = isset($instance['cookie_lifetime'] ) && $instance['cookie_lifetime'] != 0 ? $instance['cookie_lifetime'] : $this->cookieLifetime;
$cookie_timeformat = esc_attr(isset($instance['cookie_timeformat']) ? $instance['cookie_timeformat'] : $this->cookieFormat);

?>
<p>
    <label for="<?php echo $this->get_field_id('lastviewedTitle'); ?>"><?php _e('Title:','dd-lastviewed'); ?></label>
    <input id="<?php echo $this->get_field_id('lastviewedTitle'); ?>" class=" widefat textWrite_Title" type="text" value="<?php echo esc_attr($lastviewedTitle); ?>" name="<?php echo $this->get_field_name('lastviewedTitle'); ?>">
</p>

<p class="NumberItems">
    <label><?php _e('Number of items to show:','dd-lastviewed'); ?></label>
    <input type="number" name="<?php echo $this->get_field_name('lastViewed_total'); ?>" min="1" value="<?php echo $lastViewed_total; ?>">
</p>
<hr>

<?php
$selection = isset($instance['selection']) ? $instance['selection'] : array();

?>
<p class="selectholder"><label for="id_label_multiple_<?php echo $widgetID; ?>"><?php _e('Filter on Posttypes/Terms:','dd-lastviewed'); ?></label><br/>
    <select class="js-types-and-terms types-and-terms" id="id_label_multiple_<?php echo $widgetID; ?>" multiple="multiple" tabindex="-1" aria-hidden="true" name="<?php echo $this->get_field_name('selection') . '[]'; ?>">
        <optgroup label="Post Types">
            <?php foreach ($post_types as $post_type) :
                    $selected = in_array($post_type, $selection) ? 'selected' : '';
                    $obj = get_post_type_object($post_type);
                    $RealName = $obj->labels->name;
                    echo '<option '.$selected.' value="'.$post_type.'">'.$RealName.'</option>';
            endforeach; ?>
        </optgroup>
        <?php

        $args_taxonomies = array('public' => 1);
        $args_terms = array('hide_empty' => 0);
        $taxonomies = get_taxonomies($args_taxonomies);

        foreach ($taxonomies as $taxonomy) :
            $taxonomy_terms = get_terms($taxonomy, $args_terms);

            if(!empty($taxonomy_terms)) : ?>
                <optgroup label="<?php echo ucfirst($taxonomy); ?>">
                    <?php foreach ($taxonomy_terms as $term) : ?>
                        <?php $selected = in_array($term->term_id, $selection) ? 'selected' : ''; ?>
                        <option <?php echo $selected; ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
</p>

<p class="exclude_ids">
    <label for="<?php echo $this->get_field_id('lastviewed_excl_ids'); ?>"><?php _e('Exclude IDs (Separate with commas):','dd-lastviewed'); ?></label>
    <select id="<?php echo $this->get_field_id('lastviewed_excl_ids'); ?>" class="js-exclude-ids" aria-hidden="true" name="<?php echo $this->get_field_name('lastviewed_excl_ids'). '[]'; ?>" multiple="multiple">
        <?php foreach ($lastviewed_excl_ids as $id) :
            echo '<option value="' . $id . '" selected>' . $id . '</option>';
        endforeach; ?>
    </select>
</p>

<hr>
<?php if(self::hasThemeTemplate()): ?>
<div class="notice-customTemplate">
    <i>This widget uses a custom template in the theme folder. it may be possible that some functionalities have been overwritten.</i>
</div>

<?php endif; ?>
<div class="showTitle LV_setting_row">
    <?php

    $checked = $lastViewed_showPostTitle == true ? 'checked="checked"' : '';
    $checked_link_title = $lastViewed_lv_link_title == true ? 'checked="checked"' : '';
    $class_lv_link = $checked_link_title ? 'button-primary' : '';
    $value = $lastViewed_showPostTitle;
    $status = $value == '1' ? 'on' : '';

    ?>
    <div class="showItem">
        <div class="dd-switch <?php echo $status; ?>">
            <div class="switchHolder">
                <div class="onSquare button-primary"></div>
                <div class="buttonSwitch"></div>
                <div class="offSquare"></div>
            </div>
        </div>
        <input id="<?php echo $this->get_field_id('lastViewed_showPostTitle'); ?>" name="<?php echo $this->get_field_name('lastViewed_showPostTitle'); ?>" type="checkbox" <?php echo $checked; ?> title="Load posts with ajax"/>
    </div>
    <div class="linkItem">
        <div class="button lv_link <?php echo $class_lv_link; ?>"></div>
        <input id="<?php echo $this->get_field_id('lastViewed_lv_link_title'); ?>" name="<?php echo $this->get_field_name('lastViewed_lv_link_title'); ?>" type="checkbox" <?php echo $checked_link_title; ?> title="Link"/>
    </div>
    <div class="contentItem">
        <?php _e('Title of the post','dd-lastviewed'); ?>
    </div>
</div>

<div class="showThumb LV_setting_row">

    <?php
    $checked = $lastViewed_showThumb == true ? 'checked="checked"' : '';
    $checked_link_thumb = $lastViewed_lv_link_thumb == true ? 'checked="checked"' : '';
    $class_lv_link = $checked_link_thumb ? 'button-primary' : '';
    $all_sizes = get_intermediate_image_sizes();
    $value = $lastViewed_showThumb;
    $status = $value == '1' ? 'on' : '';

    ?>
    <div class="showItem">
        <div class="dd-switch <?php echo $status; ?>">
            <div class="switchHolder">
                <div class="onSquare button-primary"></div>
                <div class="buttonSwitch"></div>
                <div class="offSquare"></div>
            </div>
        </div>
        <input id="<?php echo $this->get_field_id('lastViewed_showThumb'); ?>" name="<?php echo $this->get_field_name('lastViewed_showThumb'); ?>" type="checkbox" <?php echo $checked; ?> title="Show"/>
    </div>
    <div class="linkItem">
        <div class="button lv_link <?php echo $class_lv_link; ?>"></div>
        <input id="<?php echo $this->get_field_id('lastViewed_lv_link_thumb'); ?>" name="<?php echo $this->get_field_name('lastViewed_lv_link_thumb'); ?>" type="checkbox" <?php echo $checked_link_thumb; ?> title="Link Thumbnail"/>
    </div>
    <div class="contentItem">
        <select id="<?php echo $this->get_field_id('lastViewed_thumbSize'); ?>" name="<?php echo $this->get_field_name('lastViewed_thumbSize'); ?>">
            <?php
            foreach ($all_sizes as $size) {
                $selected = $lastViewed_thumbSize == $size ? 'selected' : '';
                echo '<option value="' . $size . '" ' . $selected . '>' . $size . '</option>';
            }
            ?>
        </select>
    </div>
</div>

<div class="showExcerpt LV_setting_row">
    <?php

    $checked = $lastViewed_showExcerpt == true ? 'checked="checked"' : '';
    $checked_link_excerpt = $lastViewed_lv_link_excerpt == true ? 'checked="checked"' : '';
    $class_lv_link = $checked_link_excerpt ? 'button-primary' : '';
    $value = $lastViewed_showExcerpt;
    $status = $value == '1' ? 'on' : '';

    ?>
    <div class="showItem">
        <div class="dd-switch <?php echo $status; ?>">
            <div class="switchHolder">
                <div class="onSquare button-primary"></div>
                <div class="buttonSwitch"></div>
                <div class="offSquare"></div>
            </div>
        </div>
        <input id="<?php echo $this->get_field_id('lastViewed_showExcerpt'); ?>" name="<?php echo $this->get_field_name('lastViewed_showExcerpt'); ?>" type="checkbox" <?php echo $checked; ?> title="Show"/>
    </div>
    <div class="linkItem">
        <div class="button lv_link <?php echo $class_lv_link; ?>"></div>
        <input id="<?php echo $this->get_field_id('lastViewed_lv_link_excerpt'); ?>" name="<?php echo $this->get_field_name('lastViewed_lv_link_excerpt'); ?>" type="checkbox" <?php echo $checked_link_excerpt; ?> title="Link"/>
    </div>
    <div class="contentItem">
        <select id="<?php echo $this->get_field_id('lastViewed_content_type'); ?>" name="<?php echo $this->get_field_name('lastViewed_content_type'); ?>">
            <?php
            $all_contentTypes = array('excerpt', 'plain content', 'rich content');
            foreach ($all_contentTypes as $type) {
                $selected = $lastViewed_content_type == $type ? 'selected' : '';
                echo '<option value="' . $type . '" ' . $selected . '>' . __($type,'dd-lastviewed') . '</option>';
            }
            ?>
        </select>
    </div>
</div>
<div class="contentSettings <?php echo $lastViewed_showExcerpt != true ? 'hidden' : '' ?>">
    <div class="showTruncate">
        <?php
        $checked = $lastViewed_showTruncate == true ? 'checked="checked"' : '';
        $value = $lastViewed_showTruncate;
        $status = $value == '1' ? 'on' : '';

        ?>
        <label for="lastViewed_showTruncate"><?php _e('Truncate Characters:','dd-lastviewed'); ?></label>
        <div class="LV_setting_row">
            <div class="showItem">
                <div class="dd-switch <?php echo $status; ?>">
                    <div class="switchHolder">
                        <div class="onSquare button-primary"></div>
                        <div class="buttonSwitch"></div>
                        <div class="offSquare"></div>
                    </div>
                </div>
                <input id="<?php echo $this->get_field_id('lastViewed_showTruncate'); ?>" name="<?php echo $this->get_field_name('lastViewed_showTruncate'); ?>" type="checkbox" <?php echo $checked; ?>/>
            </div>
            <input id="<?php echo $this->get_field_id('lastViewed_truncate'); ?>" type="number" name="<?php echo $this->get_field_name('lastViewed_truncate'); ?>" min="1" value="<?php echo $lastViewed_truncate ?>">
        </div>
    </div>

    <div class="showMore">
        <?php

        $checked = $lastViewed_showMore == true ? 'checked="checked"' : '';
        $value = $lastViewed_showMore;
        $status = $value == '1' ? 'on' : '';

        ?>
        <label for="lastViewed_showMore"><?php _e('Breaklink:','dd-lastviewed'); ?></label>
        <div class="LV_setting_row">
            <div class="showItem">
                <div class="dd-switch <?php echo $status; ?>">
                    <div class="switchHolder">
                        <div class="onSquare button-primary"></div>
                        <div class="buttonSwitch"></div>
                        <div class="offSquare"></div>
                    </div>
                </div>
                <input id="<?php echo $this->get_field_id('lastViewed_showMore'); ?>" name="<?php echo $this->get_field_name('lastViewed_showMore'); ?>" type="checkbox" <?php echo $checked; ?>/>
            </div>
            <input id="<?php echo $this->get_field_id('lastViewed_linkname'); ?>" title="Breaklink label" class="textWrite_Title" type="text" value="<?php echo esc_attr($lastViewed_linkname); ?>" name="<?php echo $this->get_field_name('lastViewed_linkname'); ?>">
        </div>
    </div>
</div>

<hr>
<div class="cookie_lifetime">
    <?php
    $checked = $cookie_lifetime_checked == true ? 'checked="checked"' : '';
    $value = $cookie_lifetime_checked;
    $status = $value == '1' ? 'on' : '';
    ?>
    <label><?php _e('Cookie Lifetime:','dd-lastviewed'); ?></label>
    <div class="LV_setting_row">
        <div class="showItem">
            <div class="dd-switch <?php echo $status; ?>">
                <div class="switchHolder">
                    <div class="onSquare button-primary"></div>
                    <div class="buttonSwitch"></div>
                    <div class="offSquare"></div>
                </div>
            </div>
            <input id="<?php echo $this->get_field_id('cookie_lifetime_checked'); ?>" name="<?php echo $this->get_field_name('cookie_lifetime_checked'); ?>" type="checkbox" <?php echo $checked; ?>/>
        </div>
        <input id="<?php echo $this->get_field_id('cookie_lifetime'); ?>" type="number" name="<?php echo $this->get_field_name('cookie_lifetime'); ?>" min="1" value="<?php echo $cookie_lifetime ?>">
        <select id="<?php echo $this->get_field_id('cookie_timeformat'); ?>" name="<?php echo $this->get_field_name('cookie_timeformat'); ?>">
            <?php
            $timeformat = array(
                    'seconds' => ucfirst(__('seconds','dd-lastviewed')),
                    'minutes' => ucfirst(__('minutes','dd-lastviewed')),
                    'hours' => ucfirst(__('hours','dd-lastviewed')),
                    'days' => ucfirst(__('days','dd-lastviewed')),
                    'years' => ucfirst(__('years','dd-lastviewed'))
            );
            foreach ($timeformat as $format => $translation) {
                $selected = $cookie_timeformat == $format ? 'selected' : '';
                echo '<option value="' . $format . '" ' . $selected . '>' . $translation . '</option>';
            }
            ?>
        </select>
    </div>
</div>
<hr>
<div class="ajaxLoad">
    <?php
    echo "<label>".__('Avoid Widget Caching:','dd-lastviewed')."</label>";
    ?>
    <p style="font-size: 11px; opacity:0.6">
        <?php echo __('Avoid this widget gets cached by any 3th parties. Depends on what kind of cache, configure those settings below, to still get the best performance out of this widget.','dd-lastviewed') ?>
    </p>
    <?php

    $checked = $lastViewed_ajaxLoad == '1' ? 'checked="checked"' : '';
    $status = $lastViewed_ajaxLoad == '1' ? 'on' : '';

    ?>
    <div class="LV_setting_row">
        <div class="showItem">
            <div class="dd-switch <?php echo $status; ?>">
                <div class="switchHolder">
                    <div class="onSquare button-primary"></div>
                    <div class="buttonSwitch"></div>
                    <div class="offSquare"></div>
                </div>
            </div>
            <input id="<?php echo $this->get_field_id('lastViewed_ajaxLoad'); ?>" name="<?php echo $this->get_field_name('lastViewed_ajaxLoad'); ?>" type="checkbox" <?php echo $checked; ?> title="Load widget with Ajax"/>
        </div>
        <div class="contentItem">
            <?php _e('Load widget with Ajax','dd-lastviewed'); ?>
        </div>
    </div>

    <?php
    $checked = $lastViewed_cookieByJs == '1' ? 'checked="checked"' : '';
    $status = $lastViewed_cookieByJs == '1' ? 'on' : '';
    ?>

    <div class="LV_setting_row">
        <div class="showItem">
            <div class="dd-switch <?php echo $status; ?>">
                <div class="switchHolder">
                    <div class="onSquare button-primary"></div>
                    <div class="buttonSwitch"></div>
                    <div class="offSquare"></div>
                </div>
            </div>
            <input id="<?php echo $this->get_field_id('lastViewed_cookieByJs'); ?>" name="<?php echo $this->get_field_name('lastViewed_cookieByJs'); ?>" type="checkbox" <?php echo $checked; ?> title="Set the Cookie by Javascript"/>
        </div>
        <div class="contentItem">
            <?php _e('Set cookie by Javascript','dd-lastviewed'); ?>
        </div>
    </div>
</div>
<?php if (is_numeric($widgetID)): ?>
    <hr>
    <p style="font-size: 11px; opacity:0.6">
        <span class="shortcodeTtitle"><?php _e('Shortcode','dd-lastviewed'); ?>:</span>
        <span class="shortcode">[dd_lastviewed widget_id="<?php echo $widgetID; ?>"]</span>
    </p>
<?php endif; ?>
<hr>
<div class="donateReview">
    <a href="#" class="js-collapse collapse-trigger"><?php _e('Donate & Review','dd-lastviewed'); ?></a>
    <div class="js-collapse-content collapse-content">
        <p><?php _e('This software is free as in beer and as in freedom; however...........','dd-lastviewed'); ?></p>
        <p><?php echo sprintf(__( "Donations allow me to spend more time developing all aspects of this plugin and providing the <a href='%s'>free support</a> that so many people have enjoyed.", "dd-lastviewed" ), 'https://wordpress.org/support/plugin/dd-lastviewed'); ?></p>
        <p><?php _e('It also keeps me motivated: it is a great feeling for someone to be willing to pay ( even a few Euros ) for something they can get for free. So be kind and please consider donating.','dd-lastviewed'); ?></p>
        <p><?php echo sprintf(__( "If donating ( even a small amount ) is too much for you, but still you feel a little guilty ( because in your heart this plugin is one of your favourites ) consider then at least a <a href='%s'>review</a> (it's free btw). Your 'free' review keeps me motivated as well and helps prospects to choose for this plugin.", "dd-lastviewed" ), 'https://wordpress.org/support/view/plugin-reviews/dd-lastviewed#postform'); ?></p>
        <p><?php _e("You can't make me happier if you do both! ;)","dd-lastviewed"); ?></p>
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=5V2C94HQAN63C&amp;lc=US&amp;item_name=Dijkstra%20Design&amp;currency_code=EUR&amp;bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted" target="_blank" class="beer button button-secondary" title="Donate the developer"><?php _e('Donate', 'dd-lastviewed'); ?></a>
        <a href="https://wordpress.org/support/view/plugin-reviews/dd-lastviewed#postform" target="_blank" class="beer button button-secondary" title="Review Plugin"><?php _e('Review', 'dd-lastviewed'); ?></a>
    </div>
</div>