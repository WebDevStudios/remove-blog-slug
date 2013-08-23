<?php
/*
    Plugin Name: Remove /blog slug
    Plugin URI: http://webdevstudios.com
    Description: Removes /blog from permalinks once they've been generated
    Author: WebDevStudios
    Version: 1.0
    Author URI: http://webdevstudios.com
    License: GPLv2
 */

class Remove_Blog_Slug {

    public function __construct() {

        add_action( 'admin_menu', array( $this, 'remove_blog_slug_menu' ) );

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

        // If both options were updated, success!
        if ( $structure && $rules ) {

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


}

new Remove_Blog_Slug;