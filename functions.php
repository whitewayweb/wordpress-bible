<?php
/********* ENABLING SHORTCODES IN WIDGETS ***************/
add_filter('widget_text', 'do_shortcode');

/********* ENABLING SHORTCODES IN COMMENTS ***************/
add_filter( 'the_excerpt', 'do_shortcode');

/********** Enque plugin stylesheet *******************/
function add_bible_stylesheet() 
{
    wp_enqueue_style( 'bible-bootstrap', plugins_url( '/assests/bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'bibleCSS', plugins_url( '/assests/bible.css', __FILE__ ) );
}

add_action('wp_enqueue_scripts', 'add_bible_stylesheet');

function bible_ajax_load_scripts() {
	//wp_enqueue_script( "jquery-min", plugin_dir_url( __FILE__ ) . 'assests/jquery-1.10.2.min.js', array( 'jquery' ) );
	wp_enqueue_script( "bootstrap-min", plugin_dir_url( __FILE__ ) . 'assests/bootstrap.min.js' , array( 'jquery' ));
	// load our jquery file that sends the $.post request
	//wp_enqueue_script( "ajax-bible", plugin_dir_url( __FILE__ ) . 'assests/bible.js', array( 'jquery' ) );
	//wp_enqueue_script( "jquery-bible", 'https://code.jquery.com/jquery-2.1.1.min.js', array( 'jquery' ) );
	// make the ajaxurl var available to the above script
	wp_localize_script( 'ajax-bible', 'bible_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
}
add_action('wp_print_scripts', 'bible_ajax_load_scripts');

function bible_post_type() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'Bible en ligne', 'Post Type General Name', 'whitewayweb' ),
		'singular_name'       => _x( 'Bible', 'Post Type Singular Name', 'whitewayweb' ),
		'menu_name'           => __( 'Bible en ligne', 'whitewayweb' ),
		'parent_item_colon'   => __( 'Parent Bible', 'whitewayweb' ),
		'all_items'           => __( 'Toutes les Bibles', 'whitewayweb' ),
		'view_item'           => __( 'Lire la Bible', 'whitewayweb' ),
		'add_new_item'        => __( 'Ajouter une nouvelle Bible', 'whitewayweb' ),
		'add_new'             => __( 'Ajouter', 'whitewayweb' ),
		'edit_item'           => __( 'Editer la Bible', 'whitewayweb' ),
		'update_item'         => __( 'Updater la  Bible', 'whitewayweb' ),
		'search_items'        => __( 'Chercher dans la Bible', 'whitewayweb' ),
		'not_found'           => __( 'Non trouvé', 'whitewayweb' ),
		'not_found_in_trash'  => __( 'Non trouvé dans la corbeille', 'whitewayweb' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Bible en ligne', 'whitewayweb' ),
		'description'         => __( 'Bible news and reviews', 'whitewayweb' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		
		// This is where we add taxonomies to our CPT
		//'taxonomies'          => array( 'category' ),
	);
	
	// Registering your Custom Post Type
	register_post_type( 'bible', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action( 'init', 'bible_post_type', 0 );


function bible_taxonomy() {  
    register_taxonomy(  
        'bible_category',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'bible',        //post type name
        array(  
            'hierarchical' => true,  
            'label' => 'Bible Categories',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'bibles', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before 
            )
        )  
    );  
	
	//$this->taxonomy = 'bibles_category';
	global $wpdb;
	$getBooks = $wpdb->get_results("SELECT * FROM wp_ref_bible");
	foreach ( $getBooks as $getBook )   {
        $terms[] =  array (
                'name'          => $getBook->livre,
                'slug'          => $getBook->livre,
                'description'   => '',
            
        );  
	}

        foreach ( $terms as $term_key=>$term) {
                wp_insert_term(
                    $term['name'],
                    'bible_category', 
                    array(
                        'description'   => $term['description'],
                        'slug'          => $term['slug'],
                    )
                );
            unset( $term ); 
        }
}  
add_action( 'init', 'bible_taxonomy');

/********************/
add_action ( 'after_wp_tiny_mce', 'bible_tinymce_extra_vars' );
 
if ( !function_exists( 'bible_tinymce_extra_vars' ) ) {
	function bible_tinymce_extra_vars() { 
	global $wpdb;
        $cpt = $wpdb->get_results( $wpdb->prepare(
        "SELECT id,livre FROM wp_ref_bible"
    ) );

	
	?>
		<script type="text/javascript">
			
			var tinyMCE_object = <?php echo json_encode(
				array(
					'button_name' => esc_html__('Bible', 'whitewayweb'),
					'button_title' => esc_html__('Insert Bible Shortcode', 'whitewayweb'),
					'image_title' => esc_html__('Image', 'whitewayweb'),
					'image_button_title' => esc_html__('Upload image', 'whitewayweb'),
				)
				);
			?>;
			
		</script><?php
	}
}
/*****************************/
/**
 * Function to fetch books list
 * @return json
 */
function twd_posts(  ) {

	global $wpdb;
        $cpt = $wpdb->get_results( $wpdb->prepare(
        "SELECT id,livre FROM wp_ref_bible"
    ) );

    $list = array();

    foreach ( $cpt as $post ) {
		$selected = '';
		$id = $post->id;
		$book_name = $post->livre;
		$list[] = array(
			'text' =>	$book_name,
			'value'	=>	$id
		);
	}

	wp_send_json( $list );
}
/**
 * Function to fetch buttons
 * @since  1.6
 * @return string
 */
function twd_list_ajax() {
	// check for nonce
	check_ajax_referer( 'twd-nonce', 'security' );
	$posts = twd_posts( '' );
	return $posts;
}
add_action( 'wp_ajax_twd_cpt_list', 'twd_list_ajax' );

/**
 * Function to output button list ajax script
 * @since  1.6
 * @return string
 */
function twd_cpt_list() {
	// create nonce
	global $pagenow;
	if( $pagenow != 'admin.php' ){
		$nonce = wp_create_nonce( 'twd-nonce' );
		?><script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				var data = {
					'action'	: 'twd_cpt_list', // wp ajax action
					'security'	: '<?php echo $nonce; ?>' // nonce value created earlier
				};
				// fire ajax
			  	jQuery.post( ajaxurl, data, function( response ) {
			  		// if nonce fails then not authorized else settings saved
			  		if( response === '-1' ){
				  		// do nothing
				  		console.log('error');
			  		} else {
						//console.log(response);
			  			if (typeof(tinyMCE) != 'undefined') {
			  				if (tinyMCE.activeEditor != null) {
								tinyMCE.activeEditor.settings.cptBooksList = response;
								//console.log(tinyMCE.activeEditor.settings);
							}
						}
			  		}
			  	});
			});
		</script>
<?php 
	}
}
add_action( 'admin_footer', 'twd_cpt_list' );


/*********** [VERSE LINK] Shortcode Button *****************/
add_action( 'admin_head', 'bible_shortcode_buttons' );
 
if ( ! function_exists( 'bible_shortcode_buttons' ) ) {
    function bible_shortcode_buttons() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }
 
        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }
 
        add_filter( 'mce_external_plugins', 'bible_add_buttons' );
        add_filter( 'mce_buttons', 'bible_register_buttons' );
    }
}
 
if ( ! function_exists( 'bible_add_buttons' ) ) {
    function bible_add_buttons( $plugin_array ) {
        $plugin_array['bibleButton'] = plugins_url( 'assests/bible.js', __FILE__ );
        return $plugin_array;
    }
}
 
if ( ! function_exists( 'bible_register_buttons' ) ) {
    function bible_register_buttons( $buttons ) {
        array_push( $buttons, 'bibleButton' );
        return $buttons;
    }
}
 

/***************************/
function sanitizeStringForUrl($string){
    $string = strtolower($string);
    $string = html_entity_decode($string, ENT_COMPAT, 'utf-8');
    $string = str_replace(array('ä','ü','ö','ß','è','é','ï','ë'),array('ae','ue','oe','ss','e','e','i','e'),$string);
    $string = preg_replace('#[^\w\säüöß]#',null,$string);
    $string = preg_replace('#[\s]{2,}#',' ',$string);
    $string = str_replace(array(' '),array('-'),$string);
    return $string;
}
/***************************************/
function create_posts($post_slug, $post_title, $post_type, $post_content, $post_category) {

	// Initialize the page ID to -1. This indicates no action has been taken.
	$post_id = -1;

	// Setup the author, slug, and title for the post
	$author_id = 1;

	// If the page doesn't already exist, then create it
	if ( post_exists( $post_title ) == 0 ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'			=>	$post_slug,
				'post_title'		=>	$post_title,
				'post_status'		=>	'publish',
				'post_type'			=>	$post_type,
				'post_content'		=>  $post_content,
				'post_category' 	=>  $post_category,
			)
		);
	// Otherwise, we'll stop
	} else {

    		// Arbitrarily use -2 to indicate that the page with the title already exists
    		$post_id = -2;

	} // end if
	return $post_id;
} // end programmatically_create_post

function load_bible_template($template) {
    global $post;

    // Is this a "my-custom-post-type" post?
    if ($post->post_type == "bible"){

        //Your plugin path 
        $plugin_path = plugin_dir_path( __FILE__ ).'/templates/';

        // The name of custom post type single template
        $template_name = 'single-bible.php';

        // A specific single template for my custom post type exists in theme folder? Or it also doesn't exist in my plugin?
        if($template === get_stylesheet_directory() . '/' . $template_name
            || !file_exists($plugin_path . $template_name)) {

            //Then return "single.php" or "single-my-custom-post-type.php" from theme directory.
            return $template;
        }

        // If not, return my plugin custom post type template.
        return $plugin_path . $template_name;
    }

    //This is not my custom post type, do nothing with $template
    return $template;
}
add_filter('single_template', 'load_bible_template');

add_filter( 'archive_template', 'bible_archive_template' );
function bible_archive_template( $template ) {
  if ( is_post_type_archive('bible') ) {
    $theme_files = array('archive-bible.php', 'wordpress-bible/archive-bible.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return plugin_dir_path(__FILE__) . '/templates/archive-bible.php';
    }
  }
  return $template;
}


add_filter('template_include', 'bible_search_template');
function bible_search_template($template)   
{    
  global $wp_query;   
  $post_type = get_query_var('post_type');
  $traduction = get_query_var('traduction');   
  if( $wp_query->is_search && $post_type == 'bible' )   
  {
    //return locate_template('search-bible.php');  //  redirect to archive-search.php
	$theme_files = array('search-bible.php', 'wordpress-bible/search-bible.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return plugin_dir_path(__FILE__) . '/templates/search-bible.php';
    }
  }   
  return $template;   
} 

/************ Highlight Words in Search result ***************************/
function highlight_word( $content, $word, $color ) {
    //$replace = '<span class="highlight" style="background-color: ' . $color . ';">' . $word . '</span>'; // create replacement
	$replace = '<span class="highlight">' . $word . '</span>'; // create replacement
    $content = str_replace( $word, $replace, $content ); // replace content

    return $content; // return highlighted data
}

function highlight_words( $content, $words, $colors ) {
    $color_index = 0; // index of color (assuming it's an array)

    // loop through words
    foreach( $words as $word ) {
        $content = highlight_word( $content, $word, $colors[$color_index] ); // highlight word
        $color_index = ( $color_index + 1 ) % count( $colors ); // get next color index
    }

    return $content; // return highlighted data
}

/************ Pagination ***************************/
function custom_pagination($page, $totalpage, $link, $show)  //$link = '&page=%s' 
{ 
    //show page 
if($totalpage == 0) 
{ 
return 'Page 0 of 0'; 
} else { 
    $nav_page = '<ul class="pagination"><li class="current"><a>Page '.$page.' of '.$totalpage.': </a></li>'; 
    $limit_nav = 3; 
    $start = ($page - $limit_nav <= 0) ? 1 : $page - $limit_nav; 
    $end = $page + $limit_nav > $totalpage ? $totalpage : $page + $limit_nav; 
    if($page + $limit_nav >= $totalpage && $totalpage > $limit_nav * 2){ 
        $start = $totalpage - $limit_nav * 2; 
    } 
    if($start != 1){ //show first page 
        $nav_page .= '<li class="item"><a href="'.sprintf($link, 1).'"> 1 </a></li>'; 
    } 
    if($start > 2){ //add ... 
        $nav_page .= '<li class="current"><a>...</a></li>'; 
    } 
    if($page > 5){ //add prev 
        $nav_page .= '<li class="item"><a href="'.sprintf($link, $page-5).'"><<</a></li>'; 
    } 
    for($i = $start; $i <= $end; $i++){ 
        if($page == $i) 
            $nav_page .= '<li class="active"><a>'.$i.'</a></li>'; 
        else 
            $nav_page .= '<li class="item"><a href="'.sprintf($link, $i).'"> '.$i.' </a></li>'; 
    } 
    if($page + 3 < $totalpage){ //add next 
        $nav_page .= '<li class="item"><a href="'.sprintf($link, $page+4).'">>></a></li>'; 
    } 
    if($end + 1 < $totalpage){ //add ... 
        $nav_page .= '<li class="current"><a>...</a></li>'; 
    }     
    if($end != $totalpage) //show last page 
        $nav_page .= '<li class="item"><a href="'.sprintf($link, $totalpage).'"> '.$totalpage.' </a></li>'; 
    $nav_page .= '</ul>'; 
    return $nav_page; 
} 
} 
