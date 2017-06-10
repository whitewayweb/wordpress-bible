<?php
function bible_func($atts, $content = null) {
	ob_start();
	global $wpdb;
	
	$getTestaments = $wpdb->get_results("SELECT * FROM wp_ref_bible");
	
	$bibleShortcode = '
	<div id="bible" class="">
		<div class="row">
			<div class="col-md-6">
				<div class="col-title">Ancien Testament <span class="caret"></span></div>
				<div class="panel panel-default">
						<ul class="list-group">
	';
						foreach ( $getTestaments as $getTestament )   {
							if(substr($getTestament->id, -1) == 'O'){
								$bibleShortcode .=  '<li class="list-group-item"><a href="/bible/'.sanitizeStringForUrl($getTestament->livre).'-1-lsg"><font><font>'.$getTestament->livre.'</font></font></a></li>';
							}
						}
						$bibleShortcode .= '
						</ul> 
					</div>
			</div>
			
			<div class="col-md-6">
				<div class="col-title">Nouveau Testament <span class="caret"></span></div>
				<div class="panel panel-default">
			
						<ul class="list-group">
	';
						foreach ( $getTestaments as $getTestament )   {
							if(substr($getTestament->id, -1) == 'N'){
								$bibleShortcode .= '<li class="list-group-item"><a href="/bible/'.sanitizeStringForUrl($getTestament->livre).'-1-lsg"><font><font>'.$getTestament->livre.'</font></font></a></li>';
							}
						}
						$bibleShortcode .= '
						</ul> 
					</div>
			</div>
		</div>
	</div>
	';
	ob_end_clean();
	return $bibleShortcode;
}
add_shortcode('bible', 'bible_func');

function verse_link_func($atts, $content = null) {
	ob_start();
	global $wpdb;
	
	extract( shortcode_atts( array(
        'book' => '',
        'chapter' => '',
		'verse_start' => '',
		'verse_end' => '',
		'version' => '',
		'link_text' => '',
    ), $atts ) );
	
	$getBookName = $wpdb->get_row( $wpdb->prepare("SELECT * FROM wp_ref_bible WHERE id = %d", $book));

	$book_name = sanitizeStringForUrl($getBookName->livre);
	$version = sanitizeStringForUrl($version);
	
	$verseLink = '<a href="'.site_url().'/bible/'.$book_name.'-'.$chapter.'-'.$version.'/#v'.$verse_start.'">'.$link_text.'</a>';
	ob_end_clean();
	
	return $verseLink;

}
add_shortcode('VERSE_LINK', 'verse_link_func');

function verse_insert_func($atts, $content = null) {
	ob_start();
	global $wpdb;
	
	extract( shortcode_atts( array(
        'book' => '',
        'chapter' => '',
		'verse_start' => '',
		'verse_end' => '',
		'version' => '',
		'link_text' => '',
    ), $atts ) );
 
	$getBookName = $wpdb->get_row( $wpdb->prepare("SELECT * FROM wp_ref_bible WHERE id = %d", $book));

	$book_name = sanitizeStringForUrl($getBookName->livre);
	$version_slug = sanitizeStringForUrl($version);
	
	 $getVerses = $wpdb->get_results( $wpdb->prepare("SELECT vst,{$version} FROM wp_versets WHERE id = %d AND ch = '%d' AND vst BETWEEN %d AND %d", $book, $chapter, $verse_start, $verse_end));
	 //var_dump($getVerses);
	 
	if($version == 'lsg'){ $version = 'Louis Segond';}
     $verseInsert = '<div class="panel with-nav-tabs panel-success">
				<div class="panel-heading">
                                <ul class="nav nav-tabs">
                                
                                    <li class="active"><a href=/bible/'.$book_name.'-'.$chapter.'-'.$version_slug.'>'.$version.'</a></li>
                                </ul>
                        </div>
				
			<div class="panel-body">
			<div><strong>'.$getBookName->livre.' '.$chapter.':'.$verse_start.'-'.$verse_end.'</strong></div>
				<div class="tab-pane fade in active" id="tab1success">
					<div class="tab-content">';                             
				foreach ( $getVerses as $getVerse )   {
					$verseInsert .= '<div class="p">
						<div id="v'.$getVerse->vst.'" class="verse v'.$getVerse->vst.'">
							<div class="content ">
								<span class="num"><font><font>'.$getVerse->vst.' </font></font></span> 
								<font><font>'.$getVerse->$version_slug.'</font></font>
							</div> 
						</div>
					</div>';
				}
		
				$verseInsert .='</div>
				</div>
			</div>
	</div>';
	ob_end_clean();
	
	return $verseInsert;

}
add_shortcode('VERSE_INSERT', 'verse_insert_func');