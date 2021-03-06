function cptui_register_my_cpts_courselist() {

	/**
	 * Post Type: Courses.
	 */

	$labels = [
		"name" => __( "Courses", "noo" ),
		"singular_name" => __( "Course", "noo" ),
	];

	$args = [
		"label" => __( "Courses", "noo" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "courselist", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"taxonomies" => [ "category", "post_tag" ],
		"show_in_graphql" => false,
	];

	register_post_type( "courselist", $args );
}

add_action( 'init', 'cptui_register_my_cpts_courselist' );
add_action( 'rest_api_init', function () {
register_rest_route( 'wp/v2', '/books/', array(
'methods' => 'GET',
'callback' => 'cuspost'
) );
} );
//callback function
function cuspost(){
    
   $args = array( 
    'post_type' => 'books', 
    'post_status' => 'publish', 
    'nopaging' => true 
);
    $query = new WP_Query( $args ); // $query is the WP_Query Object
    $posts = $query->get_posts();   // $posts contains the post objects
    
    $output = array();
    foreach( $posts as $post ) {    // Pluck the id and title attributes
        
        $output[] = array( 'id' => $post->ID, 'title' => $post->post_title,'content' => $post->post_content);
    }
   wp_send_json( $output ); // getting data in json format.
    
      
}


add_action( 'rest_api_init', function () {
register_rest_route( 'wp/v2', '/posts/', array(
'methods' => 'GET',
'callback' => 'cuspost1'
) );
} );
//callback function
function cuspost1(){
    
   $args = [
    'post_type' => 'post', 
    'post_status' => 'publish', 
    'nopaging' => true ,
    'post_per_page'=>-1
];
    //$query = new WP_Query( $args ); // $query is the WP_Query Object
    // $posts = $query->get_posts($args);
    $posts = get_posts($args);   // $posts contains the post objects
    
    $output =[];
    $i=0;
    foreach( $posts as $post ) {    // Pluck the id and title attributes
      
      $output[$i]["id"] = $post->ID;
      $output[$i]["title"]  = $post->post_title;
        $output[$i]["content"]   = $post->post_content;
        $output[$i]["date"]   = $post->post_date;
        $output[$i]["slug"]   = $post->post_name;
       $output[$i]['featured_image']["thumbnail"] = get_the_post_thumbnail_url($post->ID,'full');
       $output[$i]['featured_image']["medium"] = get_the_post_thumbnail_url($post->ID,'full');
       
       $i++;
    }
    return  $output;
   wp_send_json( $output ); // getting data in json format.
    
      
}
