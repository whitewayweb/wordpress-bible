<?php
function create_bible_posts() {
	global $wpdb;
	$versions = array('LSG', 'darby','ostervald','martin');
	
	$getBooks = $wpdb->get_results("SELECT * FROM wp_ref_bible");
	
	foreach ( $versions as $version )   {
		if($version == 'LSG'){ $lsg = 'Louis Segond';} else { $lsg = $version;}
	foreach ( $getBooks as $getBook )   {
		$book_slug = sanitizeStringForUrl($getBook->livre);
		$i = 1;
        while($i<= $getBook->chap){
			$book_post_id = create_posts($book_slug.'-'.$i.'-'.$version, $title = $getBook->livre.' '.$i.' '.$lsg, 'bible', '', $book_slug);
			if ( ! add_post_meta( $book_post_id, 'livre_id', $getBook->id, true ) ) { 
			   update_post_meta( $book_post_id, 'livre_id', $getBook->id );
			}
			if ( ! add_post_meta( $book_post_id, 'book_slug', $book_slug, true ) ) { 
			   update_post_meta( $book_post_id, 'book_slug', $book_slug );
			}
			if ( ! add_post_meta( $book_post_id, 'book_name', $getBook->livre, true ) ) { 
			   update_post_meta( $book_post_id, 'book_name', $getBook->livre );
			}
			if ( ! add_post_meta( $book_post_id, 'book_chapter', $i, true ) ) { 
			   update_post_meta( $book_post_id, 'book_chapter', $i );
			}
			if ( ! add_post_meta( $book_post_id, 'book_version', $version, true ) ) { 
			   update_post_meta( $book_post_id, 'book_version', $version );
			}
			$i++;
		}
		//$book_post_id = create_pages($slug = $book_slug, $title = $getBook->livre, $content='', $parent= $post_id);
	}
	}

} // end programmatically_create_post
//add_filter( 'init', 'create_bible_pages' );
