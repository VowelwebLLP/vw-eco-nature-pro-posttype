<?php
 
/*

 Plugin Name: VW Eco Nature Pro Posttype
 lugin URI: https://www.vwthemes.com/
 Description: Creating new post type for VW Eco Nature Pro Theme.
 Author: VW Themes
 Version: 1.0
 Author URI: https://www.vwthemes.com/

*/

define( 'VW_ECO_NATURE_PRO_POSTTYPE_VERSION', '1.0' );
add_action( 'init', 'projectscategory');
add_action( 'init', 'vw_eco_nature_pro_posttype_create_post_type' );

function vw_eco_nature_pro_posttype_create_post_type() {

  register_post_type( 'services',
    array(
        'labels' => array(
            'name' => __( 'Services','vw-eco-nature-pro-posttype' ),
            'singular_name' => __( 'Services','vw-eco-nature-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'events',
    array(
        'labels' => array(
            'name' => __( 'Events','vw-eco-nature-pro-posttype' ),
            'singular_name' => __( 'Events','vw-eco-nature-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  
  register_post_type( 'projects',
    array(
        'labels' => array(
            'name' => __( 'Projects','vw-eco-nature-pro-posttype' ),
            'singular_name' => __( 'Projects','vw-eco-nature-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  
  register_post_type( 'testimonials',
    array(
      'labels' => array(
        'name' => __( 'Testimonial','vw-eco-nature-pro-posttype' ),
        'singular_name' => __( 'Testimonial','vw-eco-nature-pro-posttype' )
      ),
      'capability_type' => 'post',
      'menu_icon'  => 'dashicons-businessman',
      'public' => true,
      'supports' => array(
        'title',
        'editor',
        'thumbnail'
      )
    )
  );
  register_post_type( 'team',
    array(
      'labels' => array(
        'name' => __( 'Team','vw-eco-nature-pro-posttype' ),
        'singular_name' => __( 'Team','vw-eco-nature-pro-posttype' )
      ),
        'capability_type' => 'post',
        'menu_icon'  => 'dashicons-businessman',
        'public' => true,
        'supports' => array( 
          'title',
          'editor',
          'thumbnail'
      )
    )
  );
}

/* ----------------- Services --------------------- */

function vw_eco_nature_pro_posttype_images_metabox_enqueue($hook) {
  if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
    wp_enqueue_script('vw-eco-nature-pro-posttype-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

    global $post;
    if ( $post ) {
      wp_enqueue_media( array(
          'post' => $post->ID,
        )
      );
    }
  }
}
add_action('admin_enqueue_scripts', 'vw_eco_nature_pro_posttype_images_metabox_enqueue');
// Services Meta
function vw_eco_nature_pro_posttype_bn_custom_meta_services() {

    add_meta_box( 'bn_meta', __( 'Services Meta', 'vw-eco-nature-pro-posttype' ), 'vw_eco_nature_pro_posttype_bn_meta_callback_services', 'services', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'vw_eco_nature_pro_posttype_bn_custom_meta_services');
}

function vw_eco_nature_pro_posttype_bn_meta_callback_services( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $service_icon = get_post_meta( $post->ID, 'meta-image', true );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <p>
            <label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
            <input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo esc_html($service_icon); ?>">
            <input type="button" class="button image-upload" value="Browse">
          </p>
          <div class="image-preview"><img src="<?php echo ($service_icon); ?>" style="max-width: 250px;"></div>
        </tr>
        
      </tbody>
    </table>
  </div>
  <?php
}

function vw_eco_nature_pro_posttype_bn_meta_save_services( $post_id ) {


  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  // Save Image
  if( isset( $_POST[ 'meta-image' ] ) ) {
      update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
  }
  if( isset( $_POST[ 'meta-url' ] ) ) {
      update_post_meta( $post_id, 'meta-url', esc_url_raw($_POST[ 'meta-url' ]) );
  }
}
add_action( 'save_post', 'vw_eco_nature_pro_posttype_bn_meta_save_services' );

/* Services shortcode */
function vw_eco_nature_pro_posttype_services_func( $atts ) {

  $services = '';
  $services = '<div class="row services-short" id="our-services">';
  $query = new WP_Query( array( 'post_type' => 'services') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=services');

  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $services_icon= get_post_meta($post_id,'meta-image',true);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $excerpt = wp_trim_words(get_the_excerpt(),15);
        $custom_url = get_permalink();
        $services .= '<div class="col-lg-3 col-md-6 col-sm-6 our-services-content">
                        <div class="our-services-box1">
                          <div class="our-services-box2">
                            <span><img class="services-img" src="'.esc_url($services_icon).'" /></span>
                            <h5 class="services_name"><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h5>
                          </div>
                        </div>
                      </div>';


    if($k%2 == 0){
      $services.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $services = '<h2 class="center">'.esc_html__('Post Not Found','vw-eco-nature-pro-posttype').'</h2>';
  endif;
  $services .= '</div>';
  return $services;
}

add_shortcode( 'vw-eco-nature-pro-services', 'vw_eco_nature_pro_posttype_services_func' );

// ---------------- Events ---------------

// Projects  Meta
function vw_eco_nature_pro_posttype_bn_custom_meta_events() {

    add_meta_box( 'bn_meta', __( 'Events Meta', 'vw-eco-nature-pro-posttype-pro' ), 'vw_eco_nature_pro_posttype_bn_meta_callback_events', 'events', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'vw_eco_nature_pro_posttype_bn_custom_meta_events');
}

function vw_eco_nature_pro_posttype_bn_meta_callback_events( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );

    $event_date = get_post_meta( $post->ID, 'meta-event-date', true );
    $event_location = get_post_meta( $post->ID, 'meta-event-location', true );
    $event_time = get_post_meta( $post->ID, 'meta-event-time', true );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <td class="left">
            <?php _e( 'Event Date', 'vw-eco-nature-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-event-date" id="meta-event-date" value="<?php echo esc_html($event_date); ?>" />
          </td>
        </tr>
        <tr id="meta-2">
          <td class="left">
            <?php _e( 'Event Location', 'vw-eco-nature-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-event-location" id="meta-event-location" value="<?php echo esc_html($event_location); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php _e( 'Event Time', 'vw-eco-nature-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-event-time" id="meta-event-time" value="<?php echo esc_html($event_time); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

function vw_eco_nature_pro_posttype_bn_meta_save_events( $post_id ) {

  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  if( isset( $_POST[ 'meta-event-date' ] ) ) {
    update_post_meta( $post_id, 'meta-event-date', sanitize_text_field($_POST[ 'meta-event-date' ]) );
  } 
  if( isset( $_POST[ 'meta-event-location' ] ) ) {
    update_post_meta( $post_id, 'meta-event-location', sanitize_text_field($_POST[ 'meta-event-location' ]) );
  }
  if( isset( $_POST[ 'meta-event-time' ] ) ) {
    update_post_meta( $post_id, 'meta-event-time', sanitize_text_field($_POST[ 'meta-event-time' ]) );
  }
}
add_action( 'save_post', 'vw_eco_nature_pro_posttype_bn_meta_save_events' );

/* projects shortcode */
function vw_eco_nature_pro_posttype_event_func( $atts ) {
  $thumb_url="";
  $projects = '';
  $projects = '<div class="row" id="upcoming-events">';
  $query = new WP_Query( array( 'post_type' => 'events') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=events');
  while ($new->have_posts()) : $new->the_post();


        $post_id = get_the_ID();
        $event_location= get_post_meta($post_id,'meta-event-location',true);
        $event_date= get_post_meta($post_id,'meta-event-date',true);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $excerpt = wp_trim_words(get_the_excerpt(),12);
        $custom_url = get_permalink();
        $projects .= '
            <div class="col-lg-6 col-sm-6 col-md-6 upcoming-events-content">
              <div class="row">
                <div class="col-lg-4 col-md-12">
                  <img class="services-img" src="'.esc_url($thumb_url).'" />
                </div>
                <div class="col-lg-8 col-md-12 upcoming-events-box">
                  <h5><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h5>
                  <span>  
                    <i class="fas fa-map-marker-alt"></i>
                    '.$event_location.'
                  </span>
                  <span class="event-date">
                    <i class="fas fa-calendar-alt"></i>
                    '.$event_date.'
                  </span>
                  <div class="event-text">
                    '.$excerpt.'
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $projects.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $projects = '<h2 class="center">'.esc_html__('Post Not Found','vw_eco_nature_pro_posttype').'</h2>';
  endif;
  return $projects;
}

add_shortcode( 'vw-eco-nature-pro-events', 'vw_eco_nature_pro_posttype_event_func' );

// ------------------ Projects --------------------

function projectscategory() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( 'Categories', 'vw-eco-nature-pro-posttype' ),
    'singular_name'     => __( 'Categories', 'vw-eco-nature-pro-posttype' ),
    'search_items'      => __( 'Search cats', 'vw-eco-nature-pro-posttype' ),
    'all_items'         => __( 'All Categories', 'vw-eco-nature-pro-posttype' ),
    'parent_item'       => __( 'Parent Categories', 'vw-eco-nature-pro-posttype' ),
    'parent_item_colon' => __( 'Parent Categories:', 'vw-eco-nature-pro-posttype' ),
    'edit_item'         => __( 'Edit Categories', 'vw-eco-nature-pro-posttype' ),
    'update_item'       => __( 'Update Categories', 'vw-eco-nature-pro-posttype' ),
    'add_new_item'      => __( 'Add New Categories', 'vw-eco-nature-pro-posttype' ),
    'new_item_name'     => __( 'New Categories Name', 'vw-eco-nature-pro-posttype' ),
    'menu_name'         => __( 'Categories', 'vw-eco-nature-pro-posttype' ),
  );
  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'projectscategory' ),
  );
  register_taxonomy( 'projectscategory', array( 'projects' ), $args );
}


//  --------------- Projects  Meta ---------------
function vw_eco_nature_pro_posttype_bn_custom_meta_projects() {

    add_meta_box( 'bn_meta', __( 'Project Meta', 'vw-eco-nature-pro-posttype-pro' ), 'vw_eco_nature_pro_posttype_bn_meta_callback_projects', 'projects', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'vw_eco_nature_pro_posttype_bn_custom_meta_projects');
}

function vw_eco_nature_pro_posttype_bn_meta_callback_projects( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );

    $project_category = get_post_meta( $post->ID, 'meta-project-cat', true );
    $project_location = get_post_meta( $post->ID, 'meta-project-location', true );
    $project_client = get_post_meta( $post->ID, 'meta-project-client', true );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-2">
          <td class="left">
            <?php _e( 'Project Category', 'vw-eco-nature-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-project-cat" id="meta-project-cat" value="<?php echo esc_html($project_category); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php _e( 'Project Location', 'vw-eco-nature-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-project-location" id="meta-project-location" value="<?php echo esc_html($project_location); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php _e( 'Client Or Company Name', 'vw-eco-nature-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-project-client" id="meta-project-clientn" value="<?php echo esc_html($project_client); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

function vw_eco_nature_pro_posttype_bn_meta_save_projects( $post_id ) {

  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  if( isset( $_POST[ 'meta-project-cat' ] ) ) {
    update_post_meta( $post_id, 'meta-project-cat', sanitize_text_field($_POST[ 'meta-project-cat' ]) );
  } 
  if( isset( $_POST[ 'meta-project-location' ] ) ) {
    update_post_meta( $post_id, 'meta-project-location', sanitize_text_field($_POST[ 'meta-project-location' ]) );
  }
  if( isset( $_POST[ 'meta-project-client' ] ) ) {
    update_post_meta( $post_id, 'meta-project-client', sanitize_text_field($_POST[ 'meta-project-client' ]) );
  }
}
add_action( 'save_post', 'vw_eco_nature_pro_posttype_bn_meta_save_projects' );

/* projects shortcode */
function vw_eco_nature_pro_posttype_projects_func( $atts ) {
  $projects = '';
  $projects = '<div class="row our-project-outer" id="our-project">';
  $query = new WP_Query( array( 'post_type' => 'projects') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=projects');
  while ($new->have_posts()) : $new->the_post();

        $post_id = get_the_ID();
        $project_cat= get_post_meta($post_id,'meta-project-cat',true);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $excerpt = wp_trim_words(get_the_excerpt(),10);
        $custom_url = get_permalink();
        $projects .= '
            <div class="col-lg-3 col-md-4 col-sm-6 our_projects_box">
              <div class="our-project-content">
                <div class="box our-project-box">         
                  <img class="services-img" src="'.esc_url($thumb_url).'" />
                  <div class="box-content">
                    <div class="inner-content">
                      <p class="post">
                        '.$project_cat.'
                      </p>
                      <span class="title"><a href="'.esc_url($custom_url).'"><i class="'.get_theme_mod('vw_eco_nature_pro_our_project_link_icon').'"></i></a></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $projects.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $projects = '<h2 class="center">'.esc_html__('Post Not Found','vw_eco_nature_pro_posttype').'</h2>';
  endif;
  return $projects;
}

add_shortcode( 'vw-eco-nature-pro-projects', 'vw_eco_nature_pro_posttype_projects_func' );



/*---------------------------------- Testimonial section -------------------------------------*/

/* Adds a meta box to the Testimonial editing screen */
function vw_eco_nature_pro_posttype_bn_testimonial_meta_box() {
  add_meta_box( 'vw-eco-nature-pro-posttype-testimonial-meta', __( 'Enter Details', 'vw-eco-nature-pro-posttype' ), 'vw_eco_nature_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_eco_nature_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function vw_eco_nature_pro_posttype_bn_testimonial_meta_callback( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'vw_eco_nature_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
  $desigstory = get_post_meta( $post->ID, 'vw_eco_nature_pro_posttype_testimonial_desigstory', true );
  $test_facebook = get_post_meta( $post->ID, 'meta-tes-facebookurl', true );
  $test_linkedin = get_post_meta( $post->ID, 'meta-tes-linkdenurl', true );
  $test_twitter = get_post_meta( $post->ID, 'meta-tes-twitterurl', true );
  $test_gplus = get_post_meta( $post->ID, 'meta-tes-googleplusurl', true );
  $test_instagram = get_post_meta( $post->ID, 'meta-tes-instagram', true );
  $test_pinterest = get_post_meta( $post->ID, 'meta-tes-pinterest', true );
  ?>
  <div id="testimonials_custom_stuff">
    <table id="list">
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <td class="left">
            <?php _e( 'Designation', 'vw-eco-nature-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="vw_eco_nature_pro_posttype_testimonial_desigstory" id="vw_eco_nature_pro_posttype_testimonial_desigstory" value="<?php echo esc_attr( $desigstory ); ?>" />
          </td>
        </tr>
        <tr id="meta-2">
          <td class="left">
            <?php _e( 'Facebook Url', 'vw-eco-nature-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-facebookurl" id="meta-tes-facebookurl" value="<?php echo esc_html($test_facebook); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php _e( 'Linkedin Url', 'vw-eco-nature-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-linkdenurl" id="meta-tes-linkdenurl" value="<?php echo esc_html($test_linkedin); ?>" />
          </td>
        </tr>
        <tr id="meta-4">
          <td class="left">
            <?php _e( 'Twitter Url', 'vw-eco-nature-pro-posttype' ); ?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-twitterurl" id="meta-tes-twitterurl" value="<?php echo esc_html($test_twitter); ?>" />
          </td>
        </tr>
        <tr id="meta-5">
          <td class="left">
            <?php _e( 'GooglePlus Url', 'vw-eco-nature-pro-posttype' ); ?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-googleplusurl" id="meta-tes-googleplusurl" value="<?php echo esc_html($test_gplus); ?>" />
          </td>
        </tr>
        <tr id="meta-6">
          <td class="left">
            <?php _e( 'Instagram Url', 'vw-eco-nature-pro-posttype' ); ?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-instagram" id="meta-tes-instagram" value="<?php echo esc_html($test_instagram); ?>" />
          </td>
        </tr>
        <tr id="meta-7">
          <td class="left">
            <?php _e( 'Pinterest Url', 'vw-eco-nature-pro-posttype' ); ?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-pinterest" id="meta-tes-pinterest" value="<?php echo esc_html($test_pinterest); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

/* Saves the custom meta input */
function vw_eco_nature_pro_posttype_bn_metadesig_save( $post_id ) {
  if (!isset($_POST['vw_eco_nature_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['vw_eco_nature_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // Save desig.
  if( isset( $_POST[ 'vw_eco_nature_pro_posttype_testimonial_desigstory' ] ) ) {
    update_post_meta( $post_id, 'vw_eco_nature_pro_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'vw_eco_nature_pro_posttype_testimonial_desigstory']) );
  }
  // Save facebookurl
  if( isset( $_POST[ 'meta-tes-facebookurl' ] ) ) {
      update_post_meta( $post_id, 'meta-tes-facebookurl', esc_url($_POST[ 'meta-tes-facebookurl' ]) );
  }
  // Save linkdenurl
  if( isset( $_POST[ 'meta-tes-linkdenurl' ] ) ) {
      update_post_meta( $post_id, 'meta-tes-linkdenurl', esc_url($_POST[ 'meta-tes-linkdenurl' ]) );
  }
  if( isset( $_POST[ 'meta-tes-twitterurl' ] ) ) {
      update_post_meta( $post_id, 'meta-tes-twitterurl', esc_url($_POST[ 'meta-tes-twitterurl' ]) );
  }
  // Save googleplusurl
  if( isset( $_POST[ 'meta-tes-googleplusurl' ] ) ) {
      update_post_meta( $post_id, 'meta-tes-googleplusurl', esc_url($_POST[ 'meta-tes-googleplusurl' ]) );
  }

  // Save Instagram
  if( isset( $_POST[ 'meta-tes-instagram' ] ) ) {
      update_post_meta( $post_id, 'meta-tes-instagram', esc_url($_POST[ 'meta-tes-instagram' ]) );
  }
  // Save Pinterest
  if( isset( $_POST[ 'meta-tes-pinterest' ] ) ) {
      update_post_meta( $post_id, 'meta-tes-pinterest', esc_url($_POST[ 'meta-tes-pinterest' ]) );
  }

}

add_action( 'save_post', 'vw_eco_nature_pro_posttype_bn_metadesig_save' );

/*---------------------------------- testimonials shortcode --------------------------------------*/
function vw_eco_nature_pro_posttype_testimonial_func( $atts ) {
  $testimonial = '';
  $testimonial = '<div class="row all-testimonial">';
  $query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=testimonials');
  while ($new->have_posts()) : $new->the_post();

        $post_id = get_the_ID();
         $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        
        $excerpt = wp_trim_words(get_the_excerpt(),15);
        $tdegignation= get_post_meta($post_id,'vw_eco_nature_pro_posttype_testimonial_desigstory',true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $testimonial .= '

            <div class="our_testimonial_outer col-lg-4 col-md-6 col-sm-6">
              <div class="testimonial_inner">
                <div class="row hover_border">
                  <div class="col-md-12">
                     <img class="classes-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                    <h5><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h5>
                    <div class="tdesig">'.$tdegignation.'</div>
                    <div class="short_text">'.$excerpt.'</div>
                  </div>
                </div>
              </div>
            </div>';
    if($k%2 == 0){
      $testimonial.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $testimonial = '<h2 class="center">'.esc_html__('Post Not Found','vw_eco_nature_pro_posttype').'</h2>';
  endif;
  return $testimonial;
}

add_shortcode( 'vw-eco-nature-pro-testimonials', 'vw_eco_nature_pro_posttype_testimonial_func' );

/*-------------------------------------- team-------------------------------------------*/
/* Adds a meta box for Designation */
function vw_eco_nature_pro_posttype_bn_team_meta() {
    add_meta_box( 'vw_eco_nature_pro_posttype_bn_meta', __( 'Enter Details','vw-eco-nature-pro-posttype' ), 'vw_eco_nature_pro_posttype_ex_bn_meta_callback', 'team', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_eco_nature_pro_posttype_bn_team_meta');
}
/* Adds a meta box for custom post */
function vw_eco_nature_pro_posttype_ex_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'vw_eco_nature_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $teacher_email = get_post_meta( $post->ID, 'meta-team-email', true );
    $teacher_phone = get_post_meta( $post->ID, 'meta-team-phone', true );
    $teacher_facebook = get_post_meta( $post->ID, 'meta-tfacebookurl', true );
    $teacher_linkedin = get_post_meta( $post->ID, 'meta-tlinkdenurl', true );
    $teacher_twitter = get_post_meta( $post->ID, 'meta-ttwitterurl', true );
    $teacher_gplus = get_post_meta( $post->ID, 'meta-tgoogleplusurl', true );
    $teacher_desig = get_post_meta( $post->ID, 'meta-designation', true );
    $teacher_instagram = get_post_meta( $post->ID, 'meta-tinstagram', true );
    $teacher_pinterest = get_post_meta( $post->ID, 'meta-pinterest', true );
    ?>
  
    <div id="agent_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                  <td class="left">
                      <?php _e( 'Email', 'vw-eco-nature-pro-posttype' )?>
                  </td>
                  <td class="left" >
                      <input type="text" name="meta-team-email" id="meta-team-email" value="<?php echo esc_html($teacher_email); ?>" />
                  </td>
                </tr>
                <tr id="meta-1">
                  <td class="left">
                      <?php _e( 'Phone', 'vw-eco-nature-pro-posttype' )?>
                  </td>
                  <td class="left" >
                      <input type="text" name="meta-team-phone" id="meta-team-phone" value="<?php echo esc_html($teacher_phone); ?>" />
                  </td>
                </tr>
                <tr id="meta-3">
                  <td class="left">
                    <?php _e( 'Facebook Url', 'vw-eco-nature-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tfacebookurl" id="meta-tfacebookurl" value="<?php echo esc_html($teacher_facebook); ?>" />
                  </td>
                </tr>
                <tr id="meta-4">
                  <td class="left">
                    <?php _e( 'Linkedin Url', 'vw-eco-nature-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tlinkdenurl" id="meta-tlinkdenurl" value="<?php echo esc_html($teacher_linkedin); ?>" />
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php _e( 'Twitter Url', 'vw-eco-nature-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-ttwitterurl" id="meta-ttwitterurl" value="<?php echo esc_html($teacher_twitter); ?>" />
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php _e( 'GooglePlus Url', 'vw-eco-nature-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tgoogleplusurl" id="meta-tgoogleplusurl" value="<?php echo esc_html($teacher_gplus); ?>" />
                  </td>
                </tr>
                <tr id="meta-7">
                  <td class="left">
                    <?php _e( 'Instagram Url', 'vw-eco-nature-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tinstagram" id="meta-tinstagram" value="<?php echo esc_html($teacher_instagram); ?>" />
                  </td>
                </tr>
                <tr id="meta-8">
                  <td class="left">
                    <?php _e( 'Pinterest Url', 'vw-eco-nature-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-pinterest" id="meta-pinterest" value="<?php echo esc_html($teacher_pinterest); ?>" />
                  </td>
                </tr>
                <tr id="meta-9">
                  <td class="left">
                    <?php _e( 'Designation', 'vw-eco-nature-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_html($teacher_desig); ?>" />
                  </td>
                </tr>

            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom Designation meta input */
function vw_eco_nature_pro_posttype_ex_bn_metadesig_save( $post_id ) {

  
    if( isset( $_POST[ 'meta-team-email' ] ) ) {
        update_post_meta( $post_id, 'meta-team-email', esc_html($_POST[ 'meta-team-email' ]) );
    }
    if( isset( $_POST[ 'meta-team-phone' ] ) ) {
        update_post_meta( $post_id, 'meta-team-phone', esc_html($_POST[ 'meta-team-phone' ]) );
    }
    
    // Save facebookurl
    if( isset( $_POST[ 'meta-tfacebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tfacebookurl', esc_url($_POST[ 'meta-tfacebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-tlinkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tlinkdenurl', esc_url($_POST[ 'meta-tlinkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-ttwitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-ttwitterurl', esc_url($_POST[ 'meta-ttwitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-tgoogleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tgoogleplusurl', esc_url($_POST[ 'meta-tgoogleplusurl' ]) );
    }

    // Save Instagram
    if( isset( $_POST[ 'meta-tinstagram' ] ) ) {
        update_post_meta( $post_id, 'meta-tinstagram', esc_url($_POST[ 'meta-tinstagram' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'meta-pinterest' ] ) ) {
        update_post_meta( $post_id, 'meta-pinterest', esc_url($_POST[ 'meta-pinterest' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', esc_html($_POST[ 'meta-designation' ]) );
    }
}
add_action( 'save_post', 'vw_eco_nature_pro_posttype_ex_bn_metadesig_save' );

add_action( 'save_post', 'bn_meta_save' );
/* Saves the custom meta input */
function bn_meta_save( $post_id ) {
  if( isset( $_POST[ 'vw_eco_nature_pro_posttype_team_featured' ] )) {
      update_post_meta( $post_id, 'vw_eco_nature_pro_posttype_team_featured', esc_attr(1));
  }else{
    update_post_meta( $post_id, 'vw_eco_nature_pro_posttype_team_featured', esc_attr(0));
  }
}
/*------------------------------------- SHORTCODES -------------------------------------*/

/*------------------------------------- team Shorthcode -------------------------------------*/
function vw_eco_nature_pro_posttype_team_func( $atts ) {
  $team = '';
  $team = '<div class="row all-team" id="our-team">';
  $query = new WP_Query( array( 'post_type' => 'team') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=team');
  while ($new->have_posts()) : $new->the_post();
        $post_id = get_the_ID();
         $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $team_desig= get_post_meta($post_id,'meta-designation',true);
        $team_phone= get_post_meta($post_id,'meta-team-phone',true);
        $team_email= get_post_meta($post_id,'meta-team-email',true);
        $facebookurl= get_post_meta($post_id,'meta-tfacebookurl',true);
        $linkedin=get_post_meta($post_id,'meta-tlinkdenurl',true);
        $twitter=get_post_meta($post_id,'meta-ttwitterurl',true);
        $instagram=get_post_meta($post_id,'meta-tinstagram',true);
        $googleplusurl= get_post_meta($post_id,'meta-tgoogleplusurl',true);
        $pinterest= get_post_meta($post_id,'meta-pinterest',true);
        $custom_url = get_permalink();
        $team .= '
            <div class="our_team_outer our-team-content col-lg-3 col-md-4 col-sm-6">
              <div class="box team-image">
                <img src="'.esc_url($thumb_url).'">
                <div class="box-content">
                  <div class="social-profiles">';
                      if($facebookurl != ''){
                        $team .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                      } if($twitter != ''){
                        $team .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                      } if($instagram != ''){
                        $team .= '<a class="" href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram align-middle" aria-hidden="true"></i></a>';
                      } if($linkedin != ''){
                        $team .= '<a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                      }if($googleplusurl != ''){
                        $team .= '<a class="" href="'.esc_url($googleplusurl).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                      }if($pinterest != ''){
                        $team .= '<a class="" href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p align-middle " aria-hidden="true"></i></a>';
                      }
                    $team .= '
                  </div>
                </div>
              </div>
              <h5><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h5>
              <p class="team-desig">
                '.$team_desig.'
              </p>
            </div>';
    if($k%2 == 0){
      $team.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $team = '<h2 class="center">'.esc_html__('Post Not Found','vw_eco_nature_pro_posttype').'</h2>';
  endif;
  return $team;
}

add_shortcode( 'vw-eco-nature-pro-team', 'vw_eco_nature_pro_posttype_team_func' );
