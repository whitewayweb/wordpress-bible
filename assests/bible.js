(function($) {
	'use strict';
    tinyMCE.PluginManager.add('bibleButton', function( editor, url ) {
        editor.addButton( 'bibleButton', {
            text: tinyMCE_object.button_name,
            icon: false,
            onclick: function() {
                var win = editor.windowManager.open( {
                    title: tinyMCE_object.button_title,
					top: 10,
                    body: [
                        {
                            type   : 'listbox',
                            name   : 'listbox_type',
                            label  : 'Type de Shortcode',
                            values : [
                                { text: 'Link', value: 'VERSE_LINK' },
                                { text: 'Include', value: 'VERSE_INSERT' }
                            ],
							classes: 'bible_shortcode_type',
                            value : 'VERSE_LINK', // Sets the default
							onChange: function( ) {
								console.log('Editor was initialized.');
							},
                        },
						{
                            type   : 'listbox',
                            name   : 'book_number',
                            label  : 'Livre',
                            //values : editor.settings.cptBooksList,
							classes: 'bible_book_number',
							values:  [
										{ value: '01O', text: 'Genèse' },
										{ value: '02O', text: 'Exode' },
										{ value: '03O', text: 'Lévitique' },
										{ value: '04O', text: 'Nombres' },
										{ value: '05O', text: 'Deutéronome' },
										{ value: '06O', text: 'Josué' },
										{ value: '07O', text: 'Juges' },
										{ value: '08O', text: 'Ruth' },
										{ value: '09O', text: '1 Samuel' },
										{ value: '10O', text: '2 Samuel' },
										{ value: '11O', text: '1 Rois' },
										{ value: '12O', text: '2 Rois' },
										{ value: '13O', text: '1 Chroniques' },
										{ value: '14O', text: '2 Chroniques' },
										{ value: '15O', text: 'Esdras' },
										{ value: '16O', text: 'Néhémie' },
										{ value: '17O', text: 'Esther' },
										{ value: '18O', text: 'Job' },
										{ value: '19O', text: 'Psaumes' },
										{ value: '20O', text: 'Proverbes' },
										{ value: '21O', text: 'Ecclésiaste' },
										{ value: '22O', text: 'Cantiques' },
										{ value: '23O', text: 'Esaïe' },
										{ value: '24O', text: 'Jérémie' },
										{ value: '25O', text: 'Lamentations' },
										{ value: '26O', text: 'Ezéchiel' },
										{ value: '27O', text: 'Daniel' },
										{ value: '28O', text: 'Osée' },
										{ value: '29O', text: 'Joël' },
										{ value: '30O', text: 'Amos' },
										{ value: '31O', text: 'Abdias' },
										{ value: '32O', text: 'Jonas' },
										{ value: '33O', text: 'Michée' },
										{ value: '34O', text: 'Nahum' },
										{ value: '35O', text: 'Habakuk' },
										{ value: '36O', text: 'Sophonie' },
										{ value: '37O', text: 'Aggée' },
										{ value: '38O', text: 'Zacharie' },
										{ value: '39O', text: 'Malachie' },
										{ value: '40N', text: 'Matthieu' },
										{ value: '41N', text: 'Marc' },
										{ value: '42N', text: 'Luc' },
										{ value: '43N', text: 'Jean' },
										{ value: '44N', text: 'Actes' },
										{ value: '45N', text: 'Romains' },
										{ value: '46N', text: '1 Corinthiens' },
										{ value: '47N', text: '2 Corinthiens' },
										{ value: '48N', text: 'Galates' },
										{ value: '49N', text: 'Ephésiens' },
										{ value: '50N', text: 'Philipiens' },
										{ value: '51N', text: 'Colossiens' },
										{ value: '52N', text: '1 Thessaloniciens' },
										{ value: '53N', text: '2 Thessaloniciens' },
										{ value: '54N', text: '1 Timothée' },
										{ value: '55N', text: '2 Timothée' },
										{ value: '56N', text: 'Tite' },
										{ value: '57N', text: 'Philémon' },
										{ value: '58N', text: 'Hébreux' },
										{ value: '59N', text: 'Jacques' },
										{ value: '60N', text: '1 Pierre' },
										{ value: '61N', text: '2 Pierre' },
										{ value: '62N', text: '1 Jean' },
										{ value: '63N', text: '2 Jean' },
										{ value: '64N', text: '3 Jean' },
										{ value: '65N', text: 'Jude' },
										{ value: '66N', text: 'Apocalypse' },
							],
							//onclick: function(e) {console.log(tinyMCE.activeEditor.settings);},
                        },
                        {
                            type   : 'textbox',
                            name   : 'chapter',
                            label  : 'Chapter',
                            tooltip: 'Choisir le livre',
                            value  : '',
							classes: 'bible_chapter',
                        },
                        {
                            type   : 'textbox',
                            name   : 'verse_start',
                            label  : 'Verset de départ',
                            tooltip: 'Entrez le verset de départ',
                            value  : '',
							classes: 'bible_verse_start',
                        },
                        {
                            type   : 'textbox',
                            name   : 'verse_end',
                            label  : 'Verset de fin',
                            tooltip: 'Entrez le verset de fin',
                            value  : '',
							classes: 'bible_verse_end',
                        },
                        {
                            type   : 'textbox',
                            name   : 'link_text',
                            label  : 'Texte du lien',
                            tooltip: 'Entrer le texte du lien',
                            value  : '',
							classes: 'bible_link_text',
                        },
                        {
                            type   : 'listbox',
                            name   : 'listbox_version',
                            label  : 'Version',
                            values : [
                                { text: 'Louis Segond', value: 'lsg' },
                                { text: 'Darby', value: 'darby' },
								{ text: 'Ostervald', value: 'ostervald' },
                                { text: 'Martin', value: 'martin' }
                            ],
							classes: 'bible_version',
                            value : 'LSG' // Sets the default
                        },
                        //{
//                            type   : 'textbox',
//                            name   : 'test_text',
//                            label  : 'test Text',
//                            tooltip: 'Enter the text to be shown on link',
//                            value  : tinyMCE_object1,
//							classes: 'bible_test_text',
//                        }
                    ],
                    onsubmit: function( e ) {
                        editor.insertContent( '[' + e.data.listbox_type + ' book="' + e.data.book_number + '" chapter="' + e.data.chapter + '" verse_start="' + e.data.verse_start + '" verse_end="' + e.data.verse_end + '" version="' + e.data.listbox_version + '" link_text="' + e.data.link_text + '"]' + e.data.link_text + '[/' + e.data.listbox_type + ']');
                    }
                });
            },
        });
    });
 
})();

jQuery(document).ready(function($){
    $(document).on('click', '.mce-my_upload_button', upload_image_tinymce);
 
    function upload_image_tinymce(e) {
        e.preventDefault();
        var $input_field = $('.mce-my_input_image');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Add Image',
            button: {
                text: 'Add Image'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $input_field.val(attachment.url);
        });
        custom_uploader.open();
    }
	
	$(document).on('change', '.mce-bible_shortcode_type', hide_field);
	function hide_field(e){
		e.preventDefault();
		consol.log('hide');
        $('.mce-verse_end').parent().hide();
	}
	
});