<?php

/**
 * Plugin Name:       Wp Post Word Count
 * Plugin URI:        https://github.com/bddevfarid/wp-post-word-count
 * Description:       Wp Post Word Count plugin gives you post words count, post reading time, post human time diff, post author name, post author avatar, post author link, post categories, post tags, post total views features.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Faridul Islam
 * Author URI:        https://bddevfarid.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-post-word-count
 * Domain Path:       /languages
 */


if (!defined('ABSPATH')) {
	exit;
}


if ( ! class_exists( 'WP_Post_Word_Count_Plugin' ) ) {
    class WP_Post_Word_Count_Plugin {

        /**
         * Text Domain
         * @since 1.0.0
         * @description load plugin text domain
         * @hook plugins_loaded
         * @filter wp_post_word_count_load_textdomain
         * @return void
         */
        public function wp_post_word_count_load_textdomain(){
            load_plugin_textdomain('wp-post-word-count', false, dirname(__FILE__)."/languages");
        }
        

        /**
         * @param $words_text
         * @return array
         * @since 1.0.0
         * @description post word count text
         * @filter wp_post_word_count_words_text
         * @hook manage_posts_columns
         */
        public function wp_post_word_count_words_text($words_text){
            $words_text['word_count'] = __('Words', 'wp-post-word-count');
            return $words_text;
        }

        /**
         * @param $column_name
         * @param $post_id
         * @return void
         * @since 1.0.0
         * @description post word count
         * @hook manage_posts_custom_column
         * @filter wp_post_word_count
         */
        public function wp_post_word_count($column_name, $post_id){
            if ($column_name == 'word_count') {
                $content = get_post_field('post_content', $post_id);
                $word_count = str_word_count(strip_tags($content));
                echo $word_count;
            }
        }
        
        /**
         * @return void
         * @since 1.0.0
         * @description post word count css
         * @hook admin_enqueue_scripts
         * @filter wp_post_word_count_css
         */
        public function wp_post_word_count_css(){
            wp_enqueue_style('wp-post-word-count-css', plugins_url('/assets/css/style.css', __FILE__), null, time());
        }
        
        /**
         * @param $columns
         * @return array
         * @since 1.0.0
         * @description post word count thumbnail image column text
         * @filter manage_posts_columns
         * @hook wp_post_word_count_thumbnail_image_column_text
         */
        public function wp_post_word_count_thumbnail_image_column_text($columns){
            $columns['thumbnail_image'] = __('Thumbnail Image', 'wp-post-word-count');
            return $columns;
        }
        
        /**
         * @param $column_name
         * @param $post_id
         * @return void
         * @since 1.0.0
         * @description post word count thumbnail image column
         * @hook manage_posts_custom_column
         * @filter wp_post_word_count_thumbnail_image_column
         */
        public function wp_post_word_count_thumbnail_image_column($column_name, $post_id){
            if ($column_name == 'thumbnail_image') {
                $thumbnail_image = get_the_post_thumbnail($post_id, array(100, 100));
                echo $thumbnail_image;
            }
        }

        /**
         * @param $content
         * @return string
         * @since 1.0.0
         * @description post word count in the_content
         * @filter the_content
         * @hook wp_post_word_count_the_content
         */
        public function wp_post_word_count_the_content($content){
            $word_count = str_word_count(strip_tags($content));
            $label = __('Total Words', 'wp-post-word-count');
            $label = apply_filters('wp_post_word_count_label', $label);
            $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, $word_count);
            return $content;
        }

        /**
         * @param $content
         * @return string
         * @since 1.0.0
         * @description post word count in the_content
         * @filter the_content
         * @hook wp_post_word_count_the_content
         */
        public function wp_post_word_count_the_content_reading_time($content){
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

        /**
         * @param $content
         * @return string
         * @since 1.0.0
         * @description post word count in the_content
         * @filter the_content
         * @hook wp_post_word_count_the_content
         */
        public function wp_post_word_count_the_content_human_time_diff($content){
            $post_date = get_the_date('U');
            $current_date = current_time('U');
            $time_diff = human_time_diff($post_date, $current_date);
            $label = __('Published On', 'wp-post-word-count');
            $label = apply_filters('wp_post_word_count_label', $label);
            $label = apply_filters('wp_post_word_count_human_time_diff_label', $label);
            $content .= sprintf('<p><strong>%s: </strong> %s ago</p>', $label, $time_diff);
            return $content;
        }

        /**
         * @param $content
         * @return string
         * @since 1.0.0
         * @description post word count in the_content
         * @filter the_content
         * @hook wp_post_word_count_the_content_author
         */
        public function wp_post_word_count_the_content_author($content){
            $author_id = get_the_author_meta('ID');
            $author_link = get_author_posts_url($author_id);
            $author_avatar = get_avatar($author_id);

            $author_name = get_the_author_meta('display_name');
            $label = __('Author Name', 'wp-post-word-count');
            $label = apply_filters('wp_post_word_count_label', $label);
            $label = apply_filters('wp_post_word_count_author_name_label', $label);
            $content .= sprintf('<p><strong>%s: </strong> <a href="%s">%s</a></p>', $label, $author_link, $author_name);

            $label = __('Author Avatar', 'wp-post-word-count');
            $label = apply_filters('wp_post_word_count_label', $label);
            $label = apply_filters('wp_post_word_count_author_avatar_label', $label);
            $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, $author_avatar);
            return $content;
        }
        

        /**
         * @param $content
         * @return string
         * @since 1.0.0
         * @description post word count in the_content
         * @filter the_content
         * @hook wp_post_word_count_the_content
         */
        public function wp_post_word_count_the_content_categories($content){
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

        /**
         * @param $content
         * @return string
         * @since 1.0.0
         * @description post word count in the_content
         * @filter the_content
         * @hook wp_post_word_count_the_content
         */
        public function wp_post_word_count_the_content_tags($content){
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

        /**
         * @param $content
         * @return string
         * @since 1.0.0
         * @description post word count in the_content
         * @filter the_content
         * @hook wp_post_word_count_the_content
         */
        public function wp_post_word_count_the_content_total_comments($content){
            $total_comments = get_comments_number();
            $label = __('Total Comments', 'wp-post-word-count');
            $content .= sprintf('<p><strong>%s: </strong> %s</p>', $label, $total_comments);
            return $content;
        }

    }

    $obj = new WP_Post_Word_Count_Plugin;

    add_action('plugins_loaded', [$obj, 'wp_post_word_count_load_textdomain']);
    add_filter('manage_posts_columns', [$obj, 'wp_post_word_count_words_text'], 10, 1);
    add_action('manage_posts_custom_column', [$obj, 'wp_post_word_count'], 10, 2);
    add_action('admin_enqueue_scripts', [$obj, 'wp_post_word_count_css']);
    add_filter('manage_posts_columns', [$obj, 'wp_post_word_count_thumbnail_image_column_text'], 10, 1);
    add_action('manage_posts_custom_column', [$obj, 'wp_post_word_count_thumbnail_image_column'], 10, 2);
    add_filter('the_content', [$obj, 'wp_post_word_count_the_content']);
    add_filter('the_content', [$obj, 'wp_post_word_count_the_content_reading_time']);
    add_filter('the_content', [$obj, 'wp_post_word_count_the_content_human_time_diff']);
    add_filter('the_content', [$obj, 'wp_post_word_count_the_content_author']);
    add_filter('the_content', [$obj, 'wp_post_word_count_the_content_categories']);
    add_filter('the_content', [$obj, 'wp_post_word_count_the_content_tags']);
    add_filter('the_content', [$obj, 'wp_post_word_count_the_content_total_comments']);

}