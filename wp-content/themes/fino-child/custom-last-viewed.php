<?php
/*
Plugin Name: DD Last Viewed
Version: 5.0
Plugin URI: http://wouterdijkstra.com
Description: Shows the users recently viewed/visited Posts, Pages, Custom Types and even Terms in a widget.
Author: Wouter Dijkstra
Author URI: http://wouterdijkstra.com
Text Domain: dd-lastviewed
Domain Path: /languages
*/


/*  Copyright 2020 WOUTER DIJKSTRA  (email : info@wouterdijkstra.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class customCPTlastViewed extends lastViewed
{

    const template_path = 'dd_templates/';
    const templateFileName = 'lastviewed-widget.php';
    const cookiePrefix = 'cookie_data_lastviewed_widget_';
    const widget_options_selector = 'widget_dd_last_viewed';

    private  $all_lv_widgets;
    private  $widget_id;
    private  $before_widget;
    private  $after_widget;
    private  $before_title;
    private  $title;
    private  $after_title;
    private  $post_list;
    private  $post_title_settings;
    private  $post_thumb_settings;
    private  $post_content_settings;
    private  $settings_are_set;
    private  $ajaxLoad;
    private  $cookieByJs;
    private  $args;
    private  $currentPostId;
    private  $cookieLifetime = 365;
    private  $cookieFormat = 'days';
    private  $post_type;
    private  $selectedTypesTerms;

    /**
     * lastViewed constructor.
     */
    function __construct()
    {
        $widget_ops = array(
            'classname' => 'dd_last_viewed',
            'description' => __( 'A list of the recently viewed posts, pages or custom posttypes.', 'dd-lastviewed' )
        );
        parent::__construct('dd_last_viewed', _x('DD Last Viewed', 'DD Last Viewed widget'), $widget_ops);
        add_action( 'init', array($this, 'load_textdomain' ));
        add_action('customize_controls_init', array($this, 'add_to_customizePage'));
        add_action('wp_enqueue_scripts', array($this, 'dd_lastviewed_add_front'));
        add_action('admin_init', array($this, 'dd_lastviewed_admin'));
        add_action('get_header', array($this, 'setPhpCookies'));
        add_action( 'wp_ajax_ajax_load_widget', array( $this, 'ajax_load_widget' ));
        add_action( 'wp_ajax_ajax_set_cookie_by_js', array( $this, 'ajax_set_cookie_by_js' ));
        add_shortcode('dd_lastviewed', array($this, 'shortCode_lastViewed'));
        add_shortcode('dd_lastviewed_template', array($this, 'widget_template_shortcode'));
    }

    /**
     * scripts in admins previewmode
     */
    function add_to_customizePage()
    {
        wp_register_style('dd_lastviewed_admin_styles', plugins_url('/css/admin-style.css', __FILE__));
        wp_enqueue_style('dd_lastviewed_admin_styles');
        wp_enqueue_script('jquery');
        wp_enqueue_script('select2', plugins_url('/js/select2.full.min.js', __FILE__), array('jquery'), '');
        wp_enqueue_script('dd_js_admin-lastviewed', plugins_url('/js/default.min.js', __FILE__), array(
            'jquery',
            'select2'
        ), '');
    }

    /**
     * scripts in front
     */
    function dd_lastviewed_add_front()
    {
        global $post;
        wp_register_style('dd_lastviewed_css', plugins_url('/css/style.css', __FILE__));
        wp_enqueue_style('dd_lastviewed_css');
        wp_enqueue_script( 'lvData', plugins_url('/js/ddLastViewedFront.min.js', __FILE__), array('jquery'), null, true );
        $variables = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'postID' => is_singular() ? $post->ID : '',
            'postType' => get_post_type()
        );
        wp_localize_script('lvData', "lvData", $variables);
    }

    /**
     * script in admin
     */
    function dd_lastviewed_admin()
    {
        global $pagenow;
        if($pagenow ==='widgets.php' ) {
            wp_register_style('dd_lastviewed_admin_styles', plugins_url('/css/admin-style.css', __FILE__));
            wp_enqueue_style('dd_lastviewed_admin_styles');
            wp_enqueue_script('jquery');
            wp_enqueue_script('select2', plugins_url('/js/select2.full.min.js', __FILE__), array('jquery'), '');
            wp_enqueue_script('dd_js_admin-lastviewed', plugins_url('/js/default.min.js', __FILE__), array(
                'jquery',
                'select2'
            ), '');
        }
    }

    /**
     * Set the cookie by PHP
     */
    function setPhpCookies()
    {
        global $post;
        $this->currentPostId = is_singular() ? $post->ID : '';

        if (!$this->currentPostId) {return;};

        $this->post_type = get_post_type();
        $cookieListPhp = ($this->generateCookiesDataObject())['php'];

        foreach ($cookieListPhp as $cookie) {
            setcookie($cookie['name'], $cookie['list'], $cookie['expire'], $cookie['path']);
        }
    }

    /**
     * Returns current time + total seconds set in widget
     * @param $params
     * @return int|mixed
     */
    function getExpireTime($params){
        $clc = isset($params["cookie_lifetime_checked"]) ? $params["cookie_lifetime_checked"] : false;
        $cl = isset($params["cookie_lifetime"]) ? $params["cookie_lifetime"] : 1;
        $ct = isset($params["cookie_timeformat"]) ? $params["cookie_timeformat"] : 'years';
        $time = array(
            'seconds' => 1,
            'minutes' => 60,
            'hours' => 3600,
            'days' => 86400,
            'years' => 31536000
        );

        return (time() + ($clc ? $cl * $time[$ct] : $time['years']));

    }

    /**
     * @param $cookieName
     * @return array
     * Get widget cookie
     */
    function getCookieList($cookieName) {
        $cookieVal = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : '';
        $list = explode(',', $cookieVal);

        return $list;
    }

    /**
     * @param $atts
     * @return string
     */
    function shortCode_lastViewed($atts,$instance)
    {
        $args = array(
            'widget_id' => $atts['widget_id'],
            'by_shortcode' => 'shortcode_',
            'before_widget' => $this->before_widget,
            'after_widget' => $this->after_widget,
            'before_title' => $this->before_title,
            'after_title' => $this->after_title
        );

        ob_start();
        self::widget($args, $instance);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * This shortcode Outputs the template
     * file from the templates/folder.
     *
     * @since 3.1.4
     */
    function widget_template_shortcode()
    {
        return $this->get_widget_template(self::templateFileName);
    }
    
    /**
     * Get template.
     *
     * Search for the template and include the file.
     *
     * @since 3.1.4
     *
     * @see locate_widget_template()
     *
     * @param string $template_name Template to load.
     * @param array $args Args passed for the template file.
     * @param string $template_path Path to templates.
     * @param string $default_path Default path to template files.
     */
    function get_widget_template($template_name, $args = array(), $template_path = '', $default_path = '')
    {
        if (is_array($args) && isset($args)) :
            extract($args);
        endif;
        $template_file = $this->locate_widget_template($template_name, $template_path, $default_path);
        if (!file_exists($template_file)) :
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_file), '1.0.0');
            return;
        endif;
        include $template_file;
    }
    
    /**
     * Locate template.
     *
     * Locate the called template.
     * Search Order:
     * 1. /themes/theme/dd_templates/$template_name
     * 2. /themes/theme/$template_name
     * 3. /plugins/dd_lastviewed/dd_templates/$template_name.
     *
     * @since 3.1.4
     *
     * $template_path = Set variable to search in dd-lastviewed-templates folder of theme.
     * $default_path = Set default plugin templates path.
     * $template =  1. Search template file in theme folder.
     *              2. Get plugins template file.
     *
     * @param    string $template_name Template to load.
     * @param    string $template_path Path to templates.
     * @param    string $default_path Default path to template files.
     * @return   string Path to the template file.
     */
    function locate_widget_template($template_name, $template_path = '', $default_path = '')
    {
        $template_path = $template_path ? $template_path : self::template_path;
        $default_path = $default_path ? $default_path : plugin_dir_path(__FILE__) . self::template_path;
        $template = locate_template(array($template_path . $template_name, $template_name));
        $template = $template ? $template : $default_path . $template_name;

        return apply_filters('locate_widget_template', $template, $template_name, $template_path, $default_path);
    }

    /**
     * Check if copy exist in theme directory
     * @return bool
     */
    function hasThemeTemplate() {
        $name = self::templateFileName;
        $path = self::template_path . $name;
        return  locate_template(array($path, $name)) ? true : false;
    }

    /**
     * @return array of the current post selected terms
     */
    function get_all_current_post_selected_terms()
    {
        $selected_terms = array();
        $args = array('hide_empty' => 1, 'fields' => 'ids');
        $taxonomies = get_taxonomies();

        foreach ($taxonomies as $taxonomy) {
            $termID = wp_get_post_terms($this->currentPostId, $taxonomy, $args);
            $selected_terms = array_merge($selected_terms, $termID);
        }
        array_filter($selected_terms);
        return $selected_terms;
    }

    /**
     * @param $instance
     * @return true
     */
    function form($instance)
    {
        include('dd_templates/form.php');
        return true;
    }

    /**
     * gives featured image back. If not exist take first post image
     * @param $post_id
     * @param $thumb_size
     * @return string
     */
    function get_the_dd_thumb_element($post_id, $thumb_size) {
        $object = get_the_post_thumbnail($post_id, $thumb_size);

        if(!$object) {
            $content = get_post_field('post_content', $post_id);
            $output = preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $content, $matches);
            $first_img = isset($matches[1][0]) ? $matches[1][0] : '';

            if($first_img) {
                global $wpdb;

                $sql_img_url = preg_replace('/(-+[0-9]+x[0-9]+.)/', '.', $first_img);
                $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $sql_img_url ));
                $image_id = $attachment[0];
                $image_thumb = wp_get_attachment_image($image_id, $thumb_size);

                if($image_thumb) {
                    // if we've found an image ID, correctly display it
                    $object = $image_thumb;
                } else {
                    //if no image (i.e. from an external source), echo the original URL
                    $object = '<img class="dd-lastviewed-image-external" src="'.$first_img.'" alt="'.get_the_title().'"/>';
                }
            } else {
                $object = '';
            }
        }
        return $object;
    }

    /**
     * @param $new_instance
     * @param $old_instance
     * @return mixed
     */
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['lastviewedTitle'] = strip_tags($new_instance['lastviewedTitle']);
        $instance['selected_posttypes'] = $new_instance['selected_posttypes'];
        $instance['selection'] = $new_instance['selection'];
        $instance['lastViewed_thumb'] = strip_tags($new_instance['lastViewed_thumb']);
        $instance['lastViewed_total'] = strip_tags($new_instance['lastViewed_total']);
        $instance['lastViewed_truncate'] = strip_tags($new_instance['lastViewed_truncate']);
        $instance['lastViewed_linkname'] = strip_tags($new_instance['lastViewed_linkname']);
        $instance['lastViewed_showPostTitle'] = (bool)$new_instance['lastViewed_showPostTitle'];
        $instance['lastViewed_ajaxLoad'] = (bool)$new_instance['lastViewed_ajaxLoad'];
        $instance['lastViewed_cookieByJs'] = (bool)$new_instance['lastViewed_cookieByJs'];
        $instance['lastViewed_showThumb'] = (bool)$new_instance['lastViewed_showThumb'];
        $instance['lastViewed_thumbSize'] = strip_tags($new_instance['lastViewed_thumbSize']);
        $instance['lastViewed_showExcerpt'] = (bool)$new_instance['lastViewed_showExcerpt'];
        $instance['lastViewed_content_type'] = strip_tags($new_instance['lastViewed_content_type']);
        $instance['lastViewed_showTruncate'] = (bool)$new_instance['lastViewed_showTruncate'];
        $instance['lastViewed_showMore'] = (bool)$new_instance['lastViewed_showMore'];
        $instance['lastViewed_lv_link_thumb'] = (bool)$new_instance['lastViewed_lv_link_thumb'];
        $instance['lastViewed_lv_link_title'] = (bool)$new_instance['lastViewed_lv_link_title'];
        $instance['lastViewed_lv_link_excerpt'] = (bool)$new_instance['lastViewed_lv_link_excerpt'];

        $lastviewed_excl_ids =$new_instance['lastviewed_excl_ids'];
        asort($lastviewed_excl_ids);
        $lastviewed_excl_ids = array_values($lastviewed_excl_ids);
        $instance['lastviewed_excl_ids'] = $lastviewed_excl_ids;

        $instance['cookie_lifetime_checked'] = (bool)$new_instance['cookie_lifetime_checked'];

        if ($instance['cookie_lifetime_checked']) {
            $instance['cookie_lifetime'] = strip_tags(isset($new_instance['cookie_lifetime']) ? $new_instance['cookie_lifetime'] : $this->cookieLifetime);
            $instance['cookie_timeformat'] = strip_tags($new_instance['cookie_timeformat']);
        }
        else {
            $instance['cookie_lifetime'] = $this->cookieLifetime;
            $instance['cookie_timeformat'] = $this->cookieFormat;

        }

        return $instance;
    }

    /**
     * @param $id
     * @return mixed
     */
    function contentfilter($id)
    {
        $content_type = $this->post_content_settings['type'];
        $truncate_active = $this->post_content_settings['truncate_active'];
        $truncate_size = $this->post_content_settings['truncate_size'];
        $regex = '/\[dd_lastviewed(.*?)\]/'; //avoid shortcode '[lastviewed] in order to prevent a loop
        $strip_content = $content_type === 'plain content'; // 1/0

        $content = get_post_field('post_content', $id);

        $content = preg_replace($regex, '', $content);
        $content = apply_filters('the_content', $content);
        $content = $strip_content ? strip_shortcodes($content) : $content;
        $content = $strip_content ? wp_strip_all_tags($content, true) : $content;
        $content = $content_type === 'excerpt' ? get_the_excerpt($id) : $content;
        $content = $truncate_active ? substr($content, 0, strrpos(substr($content, 0, $truncate_size), ' ')) : $content;

        return $content;
    }

    /**
     * @param $args
     * @param $instance
     *
     * The regular widget frontend-implementation
     */
    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);
        $this->args = $args;
        $this->currentPostId = is_singular() ? get_the_ID() : '';
        $this->widget_id = str_replace('dd_last_viewed-', '', $args['widget_id']);

        $this->setCurrentSidebarParams();
        $this->setAllOtherVars();

        if ($this->settings_are_set && $this->ajaxLoad) :
            echo '<div id="'. $this->args['widget_id'] .'" class="js-ddLastViewedAjax" data-id="'. $this->widget_id .'"></div>';
        elseif ($this->settings_are_set && !$this->ajaxLoad) :
            echo do_shortcode('[dd_lastviewed_template]');
        else:
            echo $this->before_widget;
            echo $this->title ? $this->before_title . $this->title . $this->after_title : '';
            echo '<p>'.sprintf(__( "No options set yet! Set the options in the <a href='%s'>widget</a>", "dd-lastviewed" ), esc_url(home_url('/wp-admin/widgets.php'))).'</p>';
            echo $this->after_widget;
        endif;

        wp_reset_query();
    }

    /**
     * Load languages for translation
     */
    function load_textdomain() {
        load_plugin_textdomain( 'dd-lastviewed', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Set all otherVars necessary for the frontend Widget template
     */
    function setAllOtherVars () {
        $this->all_lv_widgets = get_option(self::widget_options_selector);
        $thisWidget = $this->all_lv_widgets[$this->widget_id];
        $this->selectedTypesTerms = $thisWidget['selection'] ? $thisWidget['selection'] : array();
        $this->ajaxLoad = $thisWidget['lastViewed_ajaxLoad'] ? $thisWidget['lastViewed_ajaxLoad'] : 0;
        $this->cookieByJs =  $thisWidget['lastViewed_cookieByJs'] ? $thisWidget['lastViewed_cookieByJs'] : 0;
        $show_max = $thisWidget['lastViewed_total'] ? $thisWidget['lastViewed_total'] : -1;

        $idList = array_reverse($this->getCookieList($this::cookiePrefix . $this->widget_id));
        $idList = $this->currentPostId ? array_diff($idList, array($this->currentPostId)) : $idList; // strip this id from idlist if on single

        $id = get_the_ID();
        $post_type = get_post_type( $id );

        $list_args = array(
            'post__in' => $idList,
            'post_type' => $post_type,
            'post_status' => 'publish',
            'orderby' => 'post__in',
            'posts_per_page' => $show_max
        );

        $post_title_settings = array(
            "is_active" => $thisWidget['lastViewed_showPostTitle'],
            "is_link" => $thisWidget['lastViewed_lv_link_title']
        );

        $post_thumb_settings = array(
            'is_active' => $thisWidget['lastViewed_showThumb'],
            'is_link' => $thisWidget['lastViewed_lv_link_thumb'],
            'size' => $thisWidget['lastViewed_thumbSize']
        );

        $post_content_settings = array (
            'is_active' => $thisWidget['lastViewed_showExcerpt'],
            'is_link' => $thisWidget['lastViewed_lv_link_excerpt'],
            'type' => $thisWidget['lastViewed_content_type'],
            'truncate_active' => $thisWidget['lastViewed_showTruncate'],
            'truncate_size' => $thisWidget['lastViewed_truncate'] ? $thisWidget['lastViewed_truncate'] : false,
            'more_active' => $thisWidget['lastViewed_showMore'],
            'more_title' => $thisWidget['lastViewed_linkname']
        );

        $this->title = $thisWidget['lastviewedTitle'];
        $this->post_title_settings = $post_title_settings;
        $this->post_thumb_settings = $post_thumb_settings;
        $this->post_content_settings = $post_content_settings;
        $this->settings_are_set =  isset($thisWidget['selection']) && ($post_title_settings['is_active'] || $post_thumb_settings['is_active'] || $post_content_settings['is_active']);
        $this->post_list = new WP_Query($list_args);
    }

    /**
     * The Ajax call
     * echo's the widget template
     */
    function ajax_load_widget () {

        $this->currentPostId = $_POST['postId'];
        $this->widget_id = $_POST['widgetId'];

        $this->setCurrentSidebarParams();
        $this->setAllOtherVars();

        echo do_shortcode('[dd_lastviewed_template]');
        die();
    }

    /**
     * Generates a php and js object with cookies data
     * based on the widget setting (cookieByJs)
     * @return array
     */
    function generateCookiesDataObject () {
        $this->all_lv_widgets = get_option(self::widget_options_selector);
        unset($this->all_lv_widgets['_multiwidget']); //remove from list
        $post_selected_terms = $this->get_all_current_post_selected_terms();
        $cookiesList_js = $cookiesList_php = array();
        array_push($post_selected_terms, $this->post_type);

        foreach ($this->all_lv_widgets  as $widgetId => $params) {
            $selection = $params["selection"] ? $params["selection"] : array();
            $matching_selection = array_intersect($selection, $post_selected_terms);
            $exclude_ids =  $params["lastviewed_excl_ids"] ?$params["lastviewed_excl_ids"] : array();
            $exclude_post = in_array($this->currentPostId, $exclude_ids); //true/false

            if (!empty($matching_selection) && !$exclude_post) {
                $expire_time = $this->getExpireTime($params);
                $cookieName = $this::cookiePrefix . $widgetId;
                $oldList = $this->getCookieList($cookieName);

                $newList = isset($oldList) ? array_diff($oldList, array($this->currentPostId)) : array();
                array_push($newList, $this->currentPostId);
                $newList = implode(",",array_filter( $newList));
                $cookie =  array('name' => $cookieName, 'list' => $newList, 'expire' => $expire_time, 'path' => "/") ;

                if ($params['lastViewed_cookieByJs'] == 1) {
                    array_push($cookiesList_js,$cookie);
                } else {
                    array_push($cookiesList_php,$cookie);
                }
            }
        }
        return array('js' => $cookiesList_js, 'php' => $cookiesList_php);
    }

    /**
     * Returns the js Cookielist to the Ajax Call
     */
    function ajax_set_cookie_by_js() {

        $this->currentPostId = $_POST['postId'];
        $this->post_type = $_POST['postType'];
        $cookiesList_js = ($this->generateCookiesDataObject())['js'];

        echo json_encode($cookiesList_js);
        die();
    }

    /**
     * Set the current sidebars params:
     * $this->before_widget
     * $this->after_widget
     * $this->before_title
     * $this->after_title
     */
    function setCurrentSidebarParams () {
        global $wp_registered_sidebars;
        $listOfSidebars = $wp_registered_sidebars;
        $currentSidebar = $this->get_sidebar_id_from_widget_id($this->widget_id);
        $currentSidebarParams = $listOfSidebars[$currentSidebar];

        $widgetDirectives = ['%1$s','%2$s'];
        $directiveReplace = ['dd_last_viewed-'.$this->widget_id,'dd_last_viewed'];

        $this->before_widget = str_replace($widgetDirectives, $directiveReplace, $currentSidebarParams['before_widget']);
        $this->after_widget = $currentSidebarParams['after_widget'];
        $this->before_title = $currentSidebarParams['before_title'];
        $this->after_title = $currentSidebarParams['after_title'];
    }

    /**
     * @param string       $widget_id  Widget ID e.g. '17'
     * @return string|null $sidebar_id Sidebar ID e.g. 'sidebar-1'
     */
    function get_sidebar_id_from_widget_id( $widget_id )
    {
        $widget_id = 'dd_last_viewed-'.$widget_id;
        $sidebars = wp_get_sidebars_widgets();
        foreach( (array) $sidebars as $sidebar_id => $sidebar )
        {
            if( in_array( $widget_id, (array) $sidebar, true ) )
                return $sidebar_id;
        }
        return null;
    }
}
add_action( 'widgets_init', function(){register_widget( 'customCPTlastViewed' );});
