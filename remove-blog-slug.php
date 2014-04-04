<?php
/*
    Plugin Name: Remove /blog slug
    Plugin URI: http://webdevstudios.com
    Description: Removes /blog from permalinks and default taxonomy bases once they've been generated.
    Author: WebDevStudios
    Version: 1.0.2
    Author URI: http://webdevstudios.com
    License: GPLv2
 */

class Remove_Blog_Slug {

    public function __construct() {

        add_action( 'admin_menu', array( $this, 'remove_blog_slug_menu' ) );
        add_action( 'admin_menu', array( $this, 'permalinks_update_reminder' ) );

    }


    /**
     * Add 'Remove /blog Slug' menu page under Settings menu
     *
     * @since  1.0
     *
     * @return void
     */
    public function remove_blog_slug_menu() {

        add_options_page( 'Remove /blog Slug', 'Remove /blog Slug', 'manage_options', 'remove-blog-slug', array( $this, 'remove_blog_slug_page' ) );

    }


    /**
     * Adds notice on permalinks screen to remind to remove /blog
     *
     * @since  1.0.1
     *
     * @return void
     */
    public function permalinks_update_reminder() {

        global $pagenow;

        if ( ( 'options-permalink.php' == $pagenow ) && ( ! empty( $_GET['settings-updated'] ) ) )
            add_settings_error( 'permalink', esc_attr( 'settings_updated' ), 'Remove /blog Slug plugin is active. You may want to <a href="options-general.php?page=remove-blog-slug">run that now</a> since you\'ve flushed permalinks.', 'updated' );

    }


    /**
     * Display Remove /blog Slug admin page
     *
     * @since  1.0
     *
     * @return void
     */
    public function remove_blog_slug_page(){

        $update = $this->remove_blog_slug();

        echo '<div class="wrap">';
            echo '<h2>Remove /blog Slug</h2>';
            settings_errors();
        echo '</div><!-- /.wrap -->';

    }


    /**
     * Process rewrite rules update
     *
     * @since  1.0
     *
     * @return bool  True on success, false on failure
     */
    protected function remove_blog_slug() {

        $structure = $this->update_permalink_structure();

        $rules     = $this->update_rewrite_rules();

        $cat_base  = $this->update_category_base();

        $tag_base  = $this->update_tag_base();

        // If any options were updated, success!
        if ( ( $structure && $rules ) || $cat_base || $tag_base ) {

            add_settings_error( 'remove_blog_slug', esc_attr( 'settings_updated' ), 'Updated rules successfully!', 'updated' );

            return true;

        } else {

            add_settings_error( 'remove_blog_slug', esc_attr( 'settings_updated' ), 'Failed to update rules. It\'s possible they\'ve been updated already.', 'error' );

            return false;

        }

    }


    /**
     * Update 'permalink_structure' option
     *
     * @since  1.0
     *
     * @return bool  True if option was updated, false on failure
     */
    protected function update_permalink_structure() {

        $structure = get_option( 'permalink_structure' );

        $structure = str_replace( '/blog', '', $structure );

        $structure = update_option( 'permalink_structure', $structure );

        return $structure;

    }


    /**
     * Update 'rewrite_rules' option
     *
     * @since  1.0
     *
     * @return bool  True if option was updated, false on failure
     */
    protected function update_rewrite_rules() {

        $rules     = get_option( 'rewrite_rules' );

        $new_rules = array();

        // Rules are stored as the array keys so we need to loop through
        // and modify the key to remove 'blog/'
        foreach ( $rules as $key => $value ) {

            $new_key             = str_replace( 'blog/', '', $key );

            $new_rules[$new_key] = $value;

        }

        $new_rules = update_option( 'rewrite_rules', $new_rules );

        return $new_rules;

    }


    /**
     * Update 'category_base' option
     *
     * @since  1.0.2
     *
     * @return bool  True if option was updated, false on failure
     */
    protected function update_category_base() {

        $cat_base = get_option( 'category_base' );

        $cat_base = str_replace( 'blog/', '', $cat_base );

        $cat_base = update_option( 'category_base', $cat_base );

        return $cat_base;

    }


    /**
     * Update 'tag_base' option
     *
     * @since  1.0.2
     *
     * @return bool  True if option was updated, false on failure
     */
    protected function update_tag_base() {

        $tag_base = get_option( 'tag_base' );

        $tag_base = str_replace( 'blog/', '', $tag_base );

        $tag_base = update_option( 'tag_base', $tag_base );

        return $tag_base;

    }


}

new Remove_Blog_Slug;