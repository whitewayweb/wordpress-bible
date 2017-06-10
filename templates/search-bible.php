 <?php
        /* Template Name: Bible Search */ 
		error_reporting(E_Error);
		
		$store_colon = explode(":",$s);
		if($store_colon[1] != '' || $store_colon[1] != NULL){
			$verse = $store_colon[1];
			$return_chapter = explode(" ",$store_colon[0]);
			if($return_chapter[2] == ""){
				if (isset($return_chapter[0]))
				$bookName->livre = $return_chapter[0];
			} else {
				if (isset($return_chapter[0]) && isset($return_chapter[1]))
				$bookName->livre = $return_chapter[0].' '.$return_chapter[1];
			}
			
			$chapterId = end($return_chapter);
			
			$bookName404 = $wpdb->get_row( $wpdb->prepare("SELECT livre FROM wp_ref_bible WHERE id = %d", $chapterId));
			if($bookName404->livre != ''){
				$url = site_url().'/bible/'.sanitize_title($bookName->livre).'-'.$chapterId.'-lsg/#v'.$verse;
				wp_redirect( $url );
				exit;
			} 
		}       
        get_header(); 
		
		global $wpdb;
		$traduction = $_GET['traduction'];
		$chapterId = $_GET['chapterid'];
		
		if($chapterId != '') 
		$bookName = $wpdb->get_row( $wpdb->prepare("SELECT livre FROM wp_ref_bible WHERE id = %d", $chapterId));  
		      
		if (isset($_GET['livre'])) { $livre = $_GET['livre']; }
		
		
		$currentPage = isset($_GET['page']) ? $_GET['page']: 1;
		$limit = 20;
		if($currentPage == 0 || $current_page == 1){ $startLimit = 1;} else {$startLimit = ($currentPage * $limit) - $limit + 1; }
		$endLimit = $currentPage * $limit;
		
		$words = array( $s );// words to find
		$colors = array( '#88ccff' ); // colors to use

		?>          
        
           
        <div class="contentarea">
            <div id="content" class="content_right">  
            	<div id="bible-search-results" class="clearfix">
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
                	<div id="nav-versions" class="">
                        <ul class="nav nav-tabs">
                            <li <?php if($traduction == 'lsg'){ echo 'class="active"';} ?> ><a href="<?php echo site_url(); ?>/bible/?s=<?php echo "$s"; if($chapterId != ''){ echo '&amp;chapterid='.$chapterId; } ?>&amp;traduction=lsg&post_type=bible"><font><font>Louis-Segond</font></font></a></li>
                            <li <?php if($traduction == 'darby'){ echo 'class="active"';} ?> ><a href="<?php echo site_url(); ?>/bible/?s=<?php echo "$s"; if($chapterId != ''){ echo '&amp;chapterid='.$chapterId; }  ?>&amp;traduction=darby&post_type=bible"><font><font>Darby</font></font></a></li>
                            <li <?php if($traduction == 'ostervald'){ echo 'class="active"';} ?> ><a href="<?php echo site_url(); ?>/bible/?s=<?php echo "$s"; if($chapterId != ''){ echo '&amp;chapterid='.$chapterId; }  ?>&amp;traduction=ostervald&post_type=bible"><font><font>Ostervald</font></font></a></li>
                            <li <?php if($traduction == 'martin'){ echo 'class="active"';} ?> ><a href="<?php echo site_url(); ?>/bible/?s=<?php echo "$s"; if($chapterId != ''){ echo '&amp;chapterid='.$chapterId; }  ?>&amp;traduction=martin&post_type=bible"><font><font>Martin</font></font></a></li>
                        </ul>
                    </div>
                    </div>
                
					<div id="centerColumn" class="col-sm-12 col-md-12 col-lg-12">
                    	<div class="toolbar">
                        	<div class="title-wrap">
                                <h1>Recherche de "<?php echo "$s"; ?>" dans <?php if($bookName->livre != ''){ echo $bookName->livre;} else { echo 'Toute la Bible'; } ?> (<?php echo "$traduction"; ?>)</font></font></span></h1>
                            </div>
                        </div>
                       <?php $getTestaments = $wpdb->get_results("SELECT * FROM wp_ref_bible"); ?>
                   
                           <script type="text/javascript">
								jQuery(document).ready(function($) {
									$(".dropdown img.flag").addClass("flagvisibility");
						
									$(".dropdown dt").click(function() {
										$(".dropdown dd ul").toggle();
									});
												
									$(".dropdown dd ul li a").click(function() {
										var text = $(this).html();
										$(".dropdown dt span").html(text);
										$(".dropdown dd ul").hide();
										$("#result").html("Selected value is: " + getSelectedValue("filter"));
									});
												
									function getSelectedValue(id) {
										return $("#" + id).find("dt a span.value").html();
									}
						
									$(document).bind('click', function(e) {
										var $clicked = $(e.target);
										if (! $clicked.parents().hasClass("dropdown"))
											$(".dropdown dd ul").hide();
									});
						
						
									$("#flagSwitcher").click(function() {
										$(".dropdown img.flag").toggleClass("flagvisibility");
									});
								});
							</script>
                            <div class="btn-toolbar clearfix">
                            <div class="pull-left">
								<div class="btn-group btn-group-filter">
									<label class="filter-book-label"><font><font>Filtrer les résultats :</font></font></label>
                                    <dl id="filter" class="dropdown">
                                        <dt><span><?php if($bookName->livre != ''){ echo $bookName->livre; } else {
											echo 'Toute la Bible'; } ?></span></dt>
                                        <dd>
                                            <ul>
                                            <?php
                                                foreach ( $getTestaments as $getTestament )   {
                                                    //if(substr($getTestament->id, -1) == 'O'){
                                                        echo '<li><a href="/bible/?s='.$s.'&traduction='.$traduction.'&post_type=bible&page='.$currentPage.'&chapterid='.$getTestament->id.'">'.$getTestament->livre.'</a></li>';
                                                    //}
                                                }
                                            ?>
                                                
                                            </ul>
                                        </dd>
                                    </dl>
                    			</div>
                    		</div>
                            </div>
                            
                        
                        <div class="box-results box-results-exact">
        
                            <?php
	 
							if($traduction != '' || $traduction != NULL){
								if($chapterId != ''){
									$getVersets = $wpdb->get_results( $wpdb->prepare("SELECT * FROM wp_versets  WHERE id = %d AND {$traduction} LIKE %s ORDER BY `versets_id` ASC LIMIT %d,%d", $chapterId, "%".$s."%", $startLimit, $limit));
									$countVersets = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM wp_versets  WHERE id = %d AND {$traduction} LIKE %s", $chapterId, "%".$s."%"));
								} else{
									$getVersets = $wpdb->get_results( $wpdb->prepare("SELECT * FROM wp_versets  WHERE {$traduction} LIKE %s ORDER BY `versets_id` ASC LIMIT %d,%d", "%".$s."%", $startLimit, $limit));
									$countVersets = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM wp_versets  WHERE {$traduction} LIKE %s", "%".$s."%"));
								}
							} else {
								$getVersets = $wpdb->get_results( $wpdb->prepare("SELECT * FROM wp_versets WHERE lsg LIKE %s ORDER BY `versets_id` ASC LIMIT %d,%d", "%".$s."%", $startLimit, $limit));
								$countVersets = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM wp_versets WHERE lsg LIKE %s", "%".$s."%"));
							}
							
							?>
                            <h2 class="nb-results">
                                <div class="pull-left info">
                            <i class="icomoon-checkmark"></i><?php echo $countVersets; ?> résultats exacts</div>
                                <div class="pull-right by-page"><?php echo $startLimit; ?> - <?php echo $endLimit;?>  sur <?php echo $countVersets; ?> versets</div>
                            </h2>
                            <ul class="list-verses-find"> 
                                <?php 
								foreach ( $getVersets as $getVerset )   {
									$getBookName = $wpdb->get_row( $wpdb->prepare("SELECT * FROM wp_ref_bible WHERE id = %d", $getVerset->id));             
								$results_text = highlight_words( ''.$getVerset->lsg.''.$getVerset->darby.''.$getVerset->ostervald.''.$getVerset->martin.'', $words, $colors );
								if($traduction != '' || $traduction != NULL){$traductionUrl = '-'.$traduction.'';}else{$traductionUrl = '';}
                                echo '<li id="verset_12O1934" class="highlightable">
                                    <div class="ref"><a href="/bible/'.sanitizeStringForUrl($getBookName->livre).'-'.$getVerset->ch.''.$traductionUrl.'/#v'.$getVerset->vst.'">'.$getBookName->livre.' '.$getVerset->ch.':'.$getVerset->vst.'</a></div>
                                    <div class="verse">'.$results_text.'</div>
                                </li>';
                            	
								}
								
								if($getVersets == ''){
									echo 'Aucun résultat exact trouvé dans ';
									 if($bookName != ''){ echo $bookName->livre;} else { echo 'Toute la Bible'; }
									 echo ' ("$traduction").';
								}
								?>
                            </ul>
                            <?php
								$totalPagination = ceil($countVersets / $limit);
								echo custom_pagination($currentPage, $totalPagination, '/?s='.$s.'&amp;traduction='.$traduction.'&amp;post_type=bible&amp;page=%s', $limit);
							?>
                        </div>
                	</div>
                    </div>

                </div>
                

           </div><!-- content -->    
        </div><!-- contentarea -->   
        <?php get_sidebar(); ?>
        <?php get_footer(); ?>