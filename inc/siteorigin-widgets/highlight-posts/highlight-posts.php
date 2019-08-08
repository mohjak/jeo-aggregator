<?php

/*
 * Newsroom Highlight Posts Widget
 */

class Newsroom_Highlight_Posts_Widget extends SiteOrigin_Widget {
  function __construct() {
    parent::__construct(
      'newsroom-highlight-posts-widget',
      __('Newsroom Highlight Posts', 'newsroom'),
      array(
        'description' => __('Display a list of posts in a small highlight format.', 'newsroom'),
        // 'default_style' => 'highlight-posts'
      ),
      // $control_options array (?)
      array(),
      // $form_options array
      array(
        'title' => array(
          'type' => 'text',
          'label' => __('Title for post list.', 'newsroom'),
          'default' => ''
        ),
        'posts' => array(
          'type' => 'posts',
          'label' => __('Build query for posts to be displayed', 'newsroom')
        ),
        'per_row' => array(
          'type' => 'select',
          'label' => __('Posts to display per row', 'newsroom'),
          'options' => array(
            '5' => '5',
            '4' => '4',
            '3' => '3',
            '2' => '2',
            '1' => '1'
          ),
          'default' => 5
        ),
        'style' => array(
          'type' => 'select',
          'label' => __('Hilight post display side', 'newsroom'),
          'options' => array(
            'left' => 'Left',
            'right' => 'Right'
          ),
          'default' => 'left'
        )
      ),
      plugin_dir_path(STYLESHEETPATH . '/inc/siteorigin-widgets/highlight-posts')
    );
  }
  function get_template_name($instance) {
    return 'highlight-posts-template';
  }
  function get_template_dir($instance) {
    return '';
  }
  function get_style_name($instance) {
    // return 'highlight-posts';
    return '';
  }
  function get_less_variables($instance) {
    // print_r($instance);
    return array();
  }
  function initialize() {
    $this->register_frontend_styles(
      array(
        array( 'newsroom-highlight-posts', get_stylesheet_directory_uri() . '/inc/siteorigin-widgets/highlight-posts/highlight-posts.css', array(), '0.0.1' )
      )
    );
  }
}

if(function_exists('siteorigin_widget_register')) {
  siteorigin_widget_register('newsroom-highlight-posts-widget', __FILE__, 'Newsroom_Highlight_Posts_Widget');
}
