# wordpress-bilble

* Wordpress Bible plugin is used to show all the bible chapter in your wordpress site.

*	It is easy to install and use wordpress plugin.

* It will add a "/bible/" page to the Wordpress website, so link will be http://your-domain.com/bible/ . this page shows all the list of books and chapter of the bible. Or [bible] shortcode can also be used to show all the bible books anywhere else in the website.

*	Automatically bible tables are created in database and bible chapters are imported from SQL dump file (with 4 versions/translations in French), provided along with the plugin itself.

* When you click on a book, it opens the book with the list of all verses numbers, and then the verses with text. Each verse can be accessible directly with an anchored link before it (#3) 

* Every page has 4 tabs : "Louis Segond", "Darby", "Ostervald" and "Martin" to choose the version, so the user can read different versions (as the website I gave you) ; by default "Louis Segond" should be selected ;

* It will have a search engine : http://www.your-domain.com/bible/search/ 

* The user can look for a keyword (like "amour" -- http://www.your-domain.com/bible/search/?search=amour) ; he can also select a book in which to search, then the search engine display results on many pages ;

* The user can also search by a Biblical reference ; like "Jean 3:16" or "Jean 3,16" or "Jean 3.16", so the plugin displays directly the chapter 3 in the book of Jean and going to the verse #3 with anchor ;

* The Meta Title in HTML should be displayed like this :
  <title>Bible en ligne</title> (homepage)
  <title>BookName ChapterNumber:VerseNumber (NameVersersion)</title> <!-- ex : <title>Jean 3:16 (Louis Segond)</title> //-->
  
 * There are 2 types of shortcode for the TinyMCE editor, there is a button in tinymce editor when we click, we see a "popup" form to choose : the type of shortcode (link or include) the book, the chapter, the start verse, the end verse, the version/translation and a field to input the text on link.
  - One shortcode is to create directly a link to a chapter+verse of the Bible like [VERSE_LINK book="N66" chapter="4" verse_start="1" version="LSG"]My text[/VERSE_LINK]

  - One shortcode is to insert directly the text on an article, like [VERSE_INSERT book="N66" chapter="4" verse_start="1" verse_start="1" verse_end="5" version="LSG"][/VERSE_INSERT] ; for this shortcode, if "verse_end" for exemple is not the same as "verse_start", then, it should display all the 1,2,3,4 and 5 verses.
  
