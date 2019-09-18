<?php
  /**
   * Plugin Name: ajax-sidebar
   * Plugin URI:
   * Description: Load more Articles with Ajax.
   * Version: 1.0.0
   * Author: Frank van de Voorde
   * Author URI:
   * License: GPL2
   */

   add_action('wp_ajax_get_sidebar_posts', 'get_sidebar_posts');
   add_action('wp_ajax_nopriv_get_sidebar_posts', 'get_sidebar_posts');
   function get_sidebar_posts() {
      $paged = $_REQUEST['page'];
      $type = $_REQUEST['type'];
      $data = '';
      $meta = '';

      if ($type == 1):
        // Load the newest articles
        $type = 'date';
        $html = '<div class="sidebar__content newest-stories">';
        $the_query = new WP_Query(
     		 array(
     			 'posts_per_page' => 5,
     			 'cat' => 5,
           'paged' => $paged,
     			 'orderby' => 'date',
     			 'order'   => 'DESC'
     	 	 )
     	  );
      else:
        // Load the most popular articles
        $html = '<div class="sidebar__content">';
        $the_query = new WP_Query(
  				array(
  					'posts_per_page' => 5,
  					'cat' => 5,
            'paged' => $paged,
  					'meta_key' =>
  					'wpb_post_views_count',
  					'orderby' => 'meta_value_num',
  					'order' => 'DESC'
  				)
  			);
      endif;

   	if($the_query->have_posts()):
       while($the_query->have_posts()): $the_query->the_post();

        $image = get_the_post_thumbnail_url();
        if ($image):
          $image = '<img src="' . $image .'" alt="' . get_the_title() . '" />';
        else:
          $image = '<img src="' . get_template_directory_uri() . '/images/default.jpg" alt="' . get_the_title() . '" />';
        endif;

        $data = $data . $html .
          ' <a href="">
    					<div class="sidebar__content__text">
                '
                . get_the_title() .
                '
    					</div>
    					<div class="sidebar__content__image">
                '
                . $image .
                '
    					</div>
    				</a>
    			</div>';

       endwhile;
   	endif;

    $result['data'] = $data;
    $result = json_encode($result);
   	echo $result;

   	die();
   }

   add_action( 'init', 'script_enqueuer' );
   function script_enqueuer() {
      wp_register_script( 'addSidebarContent', plugin_dir_url(__FILE__).'ajax-sidebar.js', array('jquery', 'fontawesome') );
      wp_localize_script( 'addSidebarContent', 'addSidebarContent', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'addSidebarContent' );
   }
 ?>
