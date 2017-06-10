<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage 
 * @since 1.0
 * @version 1.0
 */

get_header(); 

global $wpdb, $post;
$getLiverId = get_post_meta( get_the_ID(), 'livre_id', true );
$bookName = get_post_meta( get_the_ID(), 'book_name', true );
$getBookSlug = get_post_meta( get_the_ID(), 'book_slug', true );
$chapterNumber = get_post_meta( get_the_ID(), 'book_chapter', true );
$version = get_post_meta( get_the_ID(), 'book_version', true );
?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        <?php
		if($getLiverId != '' && $bookName != '' && $getBookSlug != '' && $chapterNumber != ''){
			?>
       
        <div class="bible-container">
            <div class="row">
                <div class="col-md-12">
                
                <div id="bible-search">   
                        <form role="search" action="<?php echo site_url('/').'bible/'; ?>" method="get" id="searchform" class="search-bible">
                        <div class="input-group">
                            <input type="text" name="s" placeholder="Rechercher un verset dans la Bible" value="<?php echo get_search_query() ?>" class="form-control input-search"/>
                            <input type="hidden" name="traduction" value="lsg"/>
                            <input type="hidden" name="post_type" value="bible" /> <!-- // hidden 'products' value -->
                            <input type="submit" alt="Search" value="Search" class="btn btn-default btn-submit-form no-outline icomoon-search" />
                        </div>
                      </form>
                     </div>
                
                    <div class="panel with-nav-tabs panel-success">
                        <div class="panel-heading">
                                <ul class="nav nav-tabs">
                                
                                    <li <?php if($post->post_name == $getBookSlug.'-'.$chapterNumber.'-lsg'){ echo 'class="active"';} ?> ><a href=<?php echo '/bible/'.$getBookSlug.'-'.$chapterNumber.'-lsg'; ?>>Louis Segond</a></li>
                                    <li <?php if($post->post_name == $getBookSlug.'-'.$chapterNumber.'-darby'){ echo 'class="active"';} ?> ><a href=<?php echo '/bible/'.$getBookSlug.'-'.$chapterNumber.'-darby'; ?>>Darby</a></li>
                                    <li <?php if($post->post_name == $getBookSlug.'-'.$chapterNumber.'-ostervald'){ echo 'class="active"';} ?> ><a href=<?php echo '/bible/'.$getBookSlug.'-'.$chapterNumber.'-ostervald'; ?>>Ostervald</a></li>
                                    <li <?php if($post->post_name == $getBookSlug.'-'.$chapterNumber.'-martin'){ echo 'class="active"';} ?> ><a href=<?php echo '/bible/'.$getBookSlug.'-'.$chapterNumber.'-martin'; ?>>Martin</a></li>
                                </ul>
                        </div>
                        
                        
                        <div class="panel-body">
                        <h1 class="book"><a id="book-selector" href="#" data-current-book="01O" data-toggle="modal" data-target="#myModal"><font><font><?php echo $bookName; ?></font></font><i class="icon-book icomoon-arrow-down-2"></i></a> <span class="label-chapters"><font><font>chapter <?php echo $chapterNumber; ?></font></font></span></h1>
                        <!-- Modal -->
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Livres de la Bible</h4>
                              </div>
                              <div class="modal-body">
                                <?php echo do_shortcode( '[bible]' ); ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <div class="list-chapters">
        
                                      
                            <ul class="pagination">
                            <?php 
                            $q = "SELECT chap FROM wp_ref_bible WHERE id ='".$getLiverId."'";
                            $getChaps = $wpdb->get_row($q);
                                $i = 01;
                                while($i<= $getChaps->chap){
									if($post->post_name == $getBookSlug.'-'.$i.'-'.sanitizeStringForUrl($version)){ $active = 'active';} else {$active = '';}
                                    echo '<li class="'.$active.'"><a href="/bible/'.$getBookSlug.'-'.$i.'-'.sanitizeStringForUrl($version).'" ><font>'.$i.'</font></a></li>';
                                    $i++;
                                }
                            ?>
                                
                            </ul>
                     	</div>
                        
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1success">
                                    <?php
                                        
                                        $getLSGs = $wpdb->get_results( $wpdb->prepare("SELECT vst, {$version} FROM wp_versets WHERE id = %d AND ch = '%d'", $getLiverId, $chapterNumber));
                                        
                                        foreach ( $getLSGs as $getLSG )   {
                                        echo '<div class="p">
                                            <div id="v'.$getLSG->vst.'" class="verse v'.$getLSG->vst.'">
                                                <div class="num">'.$getLSG->vst.' </div> 
                                                <div class="content ">'.$getLSG->$version.'</div> 
                                            </div> 
                                        </div>';
                                        }
                                    ?>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

<br/>
			<?php
		} else {
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/post/content', get_post_format() );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

					the_post_navigation( array(
						'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous1', 'twentyseventeen' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '</span>%title</span>',
						'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'twentyseventeen' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ) . '</span></span>',
					) );

				endwhile; // End of the loop.
			}
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
