<?php

/**
 * Plugin Name: Wp Post Word Count
 * Plugin URI: https://bddevfarid.com/
 * Description: Wp Post Word Count plugin is a post word count plugin. This plugin gives you post word count features for this plugin.
 * Version: 1.0.0
 * Author: Faridul Islam
 * Author URI: https://bddevfarid.com/
 * Text Domain: wp-post-word-count
 * Domain Path: /languages
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Tested up to: 5.8
 * Stable tag: 1.0.0
 * Tags: post word count, word count, post reading time, post human time diff, post author name, post author avatar, post author link, post categories, post tags, post total views
 */

if (!defined('ABSPATH')) {
	exit;
}

//plugin load text domain
function wp_post_word_count_load_textdomain(){
    load_plugin_textdomain('wp-post-word-count', false, dirname(__FILE__)."/languages");
}
// init action hook
add_action('init', 'wp_post_word_count_load_textdomain');

// post word count words text in post list
function wp_post_word_count_words_text($words_text){
    $words_text['word_count'] = __('Words', 'wp-post-word-count');
    return $words_text;
}
// manage_posts_columns filter hook
add_filter('manage_posts_columns', 'wp_post_word_count_words_text', 10, 1);

// post word count
function wp_post_word_count($column_name, $post_id){
    if ($column_name == 'word_count') {
        $content = get_post_field('post_content', $post_id);
        $word_count = str_word_count(strip_tags($content));
        echo $word_count;
    }
}
// manage_posts_custom_column action hook
add_action('manage_posts_custom_column', 'wp_post_word_count', 10, 2);

// post word count css
function wp_post_word_count_css(){
    wp_enqueue_style('wp-post-word-count-css', plugins_url('/assets/css/style.css', __FILE__), null, time());
}
// admin_enqueue_scripts action hook
add_action('admin_enqueue_scripts', 'wp_post_word_count_css');

//manage_posts_columns thumbnail image column
function wp_post_word_count_thumbnail_image_column_text($columns){
    $columns['thumbnail_image'] = __('Thumbnail Image', 'wp-post-word-count');
    return $columns;
}
// manage_posts_columns filter hook
add_filter('manage_posts_columns', 'wp_post_word_count_thumbnail_image_column_text', 10, 1);

// post count thumbnail image column
function wp_post_word_count_thumbnail_image_column($column_name, $post_id){
    if ($column_name == 'thumbnail_image') {
        $thumbnail_image = get_the_post_thumbnail($post_id, array(100, 100));
        echo $thumbnail_image;
    }
}
// manage_posts_custom_column action hook
add_action('manage_posts_custom_column', 'wp_post_word_count_thumbnail_image_column', 10, 2);

// the_content filter hook
/**
 * @param $content
 * @return string
 * @since 1.0.0
 * @description post word count in the_content
 * @filter the_content
 * @hook wp_post_word_count_the_content
 */
function wp_post_word_count_the_content($content){
    $word_count = str_word_count(strip_tags($content));
    $label = __('Total Words', 'wp-post-word-count');
    $label = apply_filters('wp_post_word_count_label', $label);
    $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, $word_count);
    return $content;
}
// the_content filter hook
add_filter('the_content', 'wp_post_word_count_the_content');

// the_content reading time filter hook
/**
 * @param $content
 * @return string
 * @since 1.0.0
 * @description post word count in the_content
 * @filter the_content
 * @hook wp_post_word_count_the_content
 */
function wp_post_word_count_the_content_reading_time($content){
    $word_count = str_word_count(strip_tags($content));
    $reading_minute = floor($word_count / 200);
    $reading_seconds = floor($word_count % 200 / (200 / 60));
    $is_visible = apply_filters('wp_post_word_count_readin_time_visible', 1);
    if ($is_visible) {
        $label = __('Total Reading Time', 'wp-post-word-count');
        $label = apply_filters('wp_post_word_count_label', $label);
        $label = apply_filters('wp_post_word_count_readin_time_label', $label);
        $content .= sprintf('<p><strong>%s: </strong> %s minutes %s seconds</p>', $label, $reading_minute, $reading_seconds);
    }
    return $content;
}
// the_content filter hook
add_filter('the_content', 'wp_post_word_count_the_content_reading_time');

// human time difference filter hook
/**
 * @param $content
 * @return string
 * @since 1.0.0
 * @description post word count in the_content
 * @filter the_content
 * @hook wp_post_word_count_the_content
 */
function wp_post_word_count_the_content_human_time_diff($content){
    $post_date = get_the_date('U');
    $current_date = current_time('U');
    $time_diff = human_time_diff($post_date, $current_date);
    $label = __('Total Time', 'wp-post-word-count');
    $label = apply_filters('wp_post_word_count_label', $label);
    $label = apply_filters('wp_post_word_count_human_time_diff_label', $label);
    $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, $time_diff);
    return $content;
}
// the_content filter hook
add_filter('the_content', 'wp_post_word_count_the_content_human_time_diff');

// the_content author name & avatar filter hook
/**
 * @param $content
 * @return string
 * @since 1.0.0
 * @description post word count in the_content
 * @filter the_content
 * @hook wp_post_word_count_the_content
 */
function wp_post_word_count_the_content_author_name_avatar($content){
    $author_id = get_the_author_meta('ID');
    $author_avatar = get_avatar($author_id);

    $author_name = get_the_author_meta('display_name');
    $label = __('Author Name', 'wp-post-word-count');
    $label = apply_filters('wp_post_word_count_label', $label);
    $label = apply_filters('wp_post_word_count_author_name_label', $label);
    $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, $author_name);

    $label = __('Author Avatar', 'wp-post-word-count');
    $label = apply_filters('wp_post_word_count_label', $label);
    $label = apply_filters('wp_post_word_count_author_avatar_label', $label);
    $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, $author_avatar);
    return $content;
}
// the_content filter hook
add_filter('the_content', 'wp_post_word_count_the_content_author_name_avatar');

// the_content author link  filter hook
/**
 * @param $content
 * @return string
 * @since 1.0.0
 * @description post word count in the_content
 * @filter the_content
 * @hook wp_post_word_count_the_content
 */
function wp_post_word_count_the_content_author_link($content){
    $author_id = get_the_author_meta('ID');
    $author_link = get_author_posts_url($author_id);

    $label = __('Author Link', 'wp-post-word-count');
    $label = apply_filters('wp_post_word_count_label', $label);
    $label = apply_filters('wp_post_word_count_author_link_label', $label);
    $content .= sprintf('<p><strong>%s: </strong> <a href="%s">%s</a></p>', $label, $author_link, $author_link);
    return $content;
}
// the_content filter hook
add_filter('the_content', 'wp_post_word_count_the_content_author_link');

// the_content categories  filter hook
/**
 * @param $content
 * @return string
 * @since 1.0.0
 * @description post word count in the_content
 * @filter the_content
 * @hook wp_post_word_count_the_content
 */
function wp_post_word_count_the_content_categories($content){
    $categories = get_the_category();
    $output = array();
    if ($categories) {
        foreach ($categories as $category) {
            $output[] = sprintf('<a href="%s">%s</a>', esc_url(get_category_link($category->term_id)), esc_html($category->cat_name));
        }
    }
    $label = __('Categories', 'wp-post-word-count');
    $label = apply_filters('wp_post_word_count_label', $label);
    $label = apply_filters('wp_post_word_count_categories_label', $label);
    $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, implode(', ', $output));
    return $content;
}
// the_content filter hook
add_filter('the_content', 'wp_post_word_count_the_content_categories');

// the_content tags  filter hook
/**
 * @param $content
 * @return string
 * @since 1.0.0
 * @description post word count in the_content
 * @filter the_content
 * @hook wp_post_word_count_the_content
 */
function wp_post_word_count_the_content_tags($content){
    $tags = get_the_tags();
    $output = array();
    if ($tags) {
        foreach ($tags as $tag) {
            $output[] = sprintf('<a href="%s">%s</a>', esc_url(get_tag_link($tag->term_id)), esc_html($tag->name));
        }
    }
    $label = __('Tags', 'wp-post-word-count');
    $label = apply_filters('wp_post_word_count_label', $label);
    $label = apply_filters('wp_post_word_count_tags_label', $label);
    $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, implode(', ', $output));
    return $content;
}
// the_content filter hook
add_filter('the_content', 'wp_post_word_count_the_content_tags');

// the_content total comments  filter hook
/**
 * @param $content
 * @return string
 * @since 1.0.0
 * @description post word count in the_content
 * @filter the_content
 * @hook wp_post_word_count_the_content
 */
function wp_post_word_count_the_content_total_comments($content){
    $total_comments = get_comments_number();
    $label = __('Total Comments', 'wp-post-word-count');
    $label = apply_filters('wp_post_word_count_label', $label);
    $label = apply_filters('wp_post_word_count_total_comments_label', $label);
    $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, $total_comments);
    return $content;
}
// the_content filter hook
add_filter('the_content', 'wp_post_word_count_the_content_total_comments');
