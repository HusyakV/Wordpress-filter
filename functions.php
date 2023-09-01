<?php


 //Register a custom post type called "Event".
function wpdocs_codex_event_init() {
	$labels = array(
		'name'                  => _x( 'Events', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Event', 'Post type singular name', 'textdomain' ),
		'menu_name'             => _x( 'Events', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar'        => _x( 'event', 'Add New on Toolbar', 'textdomain' ),
		'add_new'               => __( 'Add New Event', 'textdomain' ),
		'add_new_item'          => __( 'Add New Event', 'textdomain' ),
		'new_item'              => __( 'New Event', 'textdomain' ),
		'edit_item'             => __( 'Edit Event', 'textdomain' ),
		'view_item'             => __( 'View Event', 'textdomain' ),
		'all_items'             => __( 'All Events', 'textdomain' ),
		'search_items'          => __( 'Search Events', 'textdomain' ),
		'parent_item_colon'     => __( 'Parent Events:', 'textdomain' ),
		'not_found'             => __( 'No events found.', 'textdomain' ),
		'not_found_in_trash'    => __( 'No events found in Trash.', 'textdomain' ),
		'featured_image'        => _x( 'Event Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'archives'              => _x( 'Event archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
		'insert_into_item'      => _x( 'Insert into event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
		'filter_items_list'     => _x( 'Filter events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
		'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
		'items_list'            => _x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'event' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-admin-site',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		'taxonomies'         => array( 'category', 'post_tag' ),
		'menu_position'      => 4,
	);
	register_post_type( 'event', $args );
}

add_action( 'init', 'wpdocs_codex_event_init' );


//Enqueue stylesheet
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	$parenthandle = 'parent-style'; 
	$theme        = wp_get_theme();
	wp_enqueue_style( $parenthandle,
		get_template_directory_uri() . '/style.css',
		array(), 
		$theme->parent()->get( 'Version' )
	);
	wp_enqueue_style( 'child-style',
		get_stylesheet_uri(),
		array( $parenthandle ),
		$theme->get( 'Version' ) 
	);
}


//Add custom css
function enqueue_custom_assets() {
    if ( is_page_template( 'template-events.php' ) ) {
        $css_file = get_stylesheet_directory() . '/assets/style/events.css'; 
        $css_version = filemtime( $css_file );
        wp_enqueue_style( 'event-css', get_stylesheet_directory_uri() . '/assets/style/events.css', array(), $css_version );
    }
    wp_enqueue_script('jquery');
    wp_enqueue_script('app', get_stylesheet_directory_uri() . '/assets/js/app.js', array('jquery'), null, true);
    wp_localize_script('app', 'variables', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_assets' );

add_action('wp_ajax_filter_posts', 'filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts', 'filter_posts');
function filter_posts(){
	$args = [
		'post_type' => 'event',
		'posts_per_page' => -1,
	];
	$type = $_REQUEST['cat'];
	$years = $_REQUEST['years'];
	$months = $_REQUEST['months'];
	if(!empty($type)){
		$args['tax_query'][] = [
			'taxonomy' => 'category',
			'field' => 'slug',
			'terms' => $type,
		];
	}
    if (!empty($years)) {
		$args['meta_query'][] = [
			'key' => 'event_year',
			'value' => $years,
			'compare' => 'IN',
		];
    }
	if (!empty($months)) {
		$args['meta_query'][] = [
			'key' => 'event_month',
			'value' => $months,
			'compare' => 'IN',
		];
    }

	$events = new WP_Query($args);
	if($events->have_posts()):
		while($events->have_posts()) : $events->the_post();
			get_template_part('template/event-loop', 'event');
		endwhile;
		wp_reset_postdata();
	else:
		echo "Event not found";
	endif;
	wp_die();
}

