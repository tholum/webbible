<html>
<head>
<script src="js/jquery-1.8.2.min.js" ></script>
<script src="js/jquery-ui-1.9.1.custom.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/0.9.10/backbone-min.js" ></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js" ></script>
    <link href="./css/wijmo/aristo/jquery-wijmo.css" rel="stylesheet" type="text/css" />
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <link href="css/wijmo/wijmo/jquery.wijmo.wijsplitter.css" rel="stylesheet" type="text/css" />
    <link href="css/bible/jquery-ui-1.10.1.custom.css" rel="stylesheet" type="text/css" />
    <script src="./js/wijmo/jquery.wijmo.wijutil.js" type="text/javascript"></script>
    <script src="./js/wijmo/jquery.wijmo.wijsplitter.js" type="text/javascript"></script>
    <script id="scriptInit" type="text/javascript">

	

        $(document).ready(function () {
            $("#splitter").wijsplitter({ orientation: "vertical", fullSplit: true, panel2:
{
minSize:1, 
collapsed:false, 
scrollBars:"hidden"} 
} );
	    $("#splitter2").wijsplitter({ orientation: "vertical", fullSplit: false, collapsingPanel: "panel2"}); //, collapsingPanel: "commentary"
        slimcrm.set_slider_widths();
	});
    </script>
<style>
</style>
</head>
<body>
<script>
var slimcrm = { 
    tabs: 0 ,
    defaults: {
        format: 'OSIS',
        version: 'ESV',
        book: 'Romans',
        chapter: 8,
        verse: 1
    },
    set_slider_widths: function(){
	/*
		I hope to find an easer way to do this, but for now it works so we are sticking with it
	*/
	var book_list = $('#splitter .wijmo-wijsplitter-wrapper .wijmo-wijsplitter-v-panel1').css('width');
	var tab_list = $('#splitter2 .wijmo-wijsplitter-wrapper .wijmo-wijsplitter-v-panel1').css('width');
	var commentary = $('#splitter2 .wijmo-wijsplitter-wrapper .wijmo-wijsplitter-v-panel2').css('width');
	var book_list_int = book_list.replace("px" , "" );
	var tab_list_int = tab_list.replace("px"  , "" );
	var commentary_int = commentary.replace("px"  , "" );
	var total = parseInt(book_list_int) + parseInt(tab_list_int) + parseInt(commentary_int);
	$('#splitter .wijmo-wijsplitter-wrapper .wijmo-wijsplitter-v-panel1').css('width', ( total/5) + 'px' );
	$('#splitter2 .wijmo-wijsplitter-wrapper .wijmo-wijsplitter-v-panel1').css('width', ((total/5)*3 ) + 'px' );
	$('#splitter2 .wijmo-wijsplitter-wrapper .wijmo-wijsplitter-v-panel2').css('width', ( total/5) + 'px');
    }
};


slimcrm.add_tab = function(options){
    var settings = slimcrm.defaults;
    $.extend( settings , options );
    slimcrm.tabs++;
    settings.id = "bible_tab" + slimcrm.tabs;
    $('#tabs').append( slimcrm.bible.new_tab( settings ) );$('#tabs').tabs('add','#' + settings.id , settings.version + ' ' + settings.book + ' ' + settings.chapter + ':' + settings.verse );
    
};

$(document).ready(function(){
	slimcrm.tpl = _.template( $('#verse').html() );
	slimcrm.bbl = _.template( $('#books-dropdown').html() );
        slimcrm.bible = {
            new_tab: _.template( $('#tab-inner').html() )
        };
	$('.bk').html('<select id="bible_book" >' + slimcrm.bbl( { 'books': slimcrm.bible }) + '</select>' );
	$.getJSON('/cgi-bin/vrp.cgi' , function( data ){ slimcrm.data = data; } );
	$.getJSON('modules.json' , function( data ){ slimcrm.modules = data; } );
	$('#tabs').tabs();
        slimcrm.add_tab();
});



slimcrm.search_bible = function(id){
	$.getJSON('/cgi-bin/vrp.cgi?' + $.param( { 'search': $("#search_string_" + id  ).val() , 'bible': $('#book_' + id ).val() , 'format': 'OSIS' } ) , function( data){ $('#main_' + id ).html( slimcrm.tpl( { 'data': data } ) ); } ) ;
}
slimcrm.process_osis_text = function(text){
    return text;
}
slimcrm.process_verse_text = function(text){
    var ta = text.split(' ');
    var book = "";
    var vat = ta[ta.length - 1];
    var va = vat.split(":");
    var chapter = 0;
    var verse = 0;
    if( va.length = 2 ){
        chapter = va[0];
        verse = va[1];
    }
    slimcrm.ta = ta;
    book = text.replace( vat , "" );
    slimcrm.verses = { 'book': book , 'verse': verse , 'chapter': chapter };
    return verse;
}
slimcrm.num_click = function( verse ){
	
}
</script>
<script src="bible.js" type="text/javascript"></script>
<script  >
slimcrm.bible = ['Genesis','Exodus','Leviticus','Numbers','Deuteronomy','Joshua','Judges','Ruth','1 Samuel','2 Samuel','1 Kings','2 Kings','1 Chronicles','2 Chronicles','Ezra','Nehemiah','Esther','Job','Psalm','Proverbs','Ecclesiastes','Song of Solomon','Isaiah','Jeremiah','Lamentations','Ezekiel','Daniel','Hosea','Joel','Amos','Obadiah','Jonah','Micah','Nahum','Habakkuk','Zephaniah','Haggai','Zechariah','Malachi','Matthew','Mark','Luke','John','Acts','Romans','1 Corinthians','2 Corinthians','Galatians','Ephesians','Philippians','Colossians','1 Thessalonians','2 Thessalonians','1 Timothy','2 Timothy','Titus','Philemon','Hebrews','James','1 Peter','2 Peter','1 John','2 John','3 John','Jude','Revelation'
]

</script>
<div id="splitter">
	<div>
		<button onclick="$('#main').html(slimcrm.tpl({ data: slimcrm.data} ));">Click</button>
		<button onclick="slimcrm.add_tab({ version: 'ESV'});">ESV</button>
                <button onclick="slimcrm.add_tab({ version: 'KJV'});">KJV</button>
		<button onclick="$.getJSON('modules.json' , function( data ){ slimcrm.modules = data; } );">Load</button>
		<button onclick='$("#splitter2").wijsplitter({ orientation: "vertical",  collapsingPanel: "commentary" });'>V</button>
	</div>
	<div>
	<div id="splitter2" >
		<div  id="tabs" style="height: 100%;" >
			<ul>
			</ul>
			Tabs
		</div>
		<div id="commentary" style="height: 100%;" >Commentary</div>
		
	</div></div>

</div>
<script type="text/template" id="books-dropdown">
<% _(books).each(function( book ){ %><option value="<%= book %>" ><%= book %></option><% } ) %>
</script>
<script type="text/template" id="tab-inner">
<div id="<%= id %>" class="tab" >
			<div>
					<select class="book" id="book_<%= id %>" >
						<option value="ESV" >ESV</option>
						<option value="KJV" >KJV</option>
					</select>
				<input type="text" id="search_string_<%= id %>" />
				<button onclick="slimcrm.search_bible('<%= id %>');" >Search</button>
                                <button onclick="alert( slimcrm.process_verse_text( $('#search_string_<%= id %>' ).val() ));">Test</button>
			<div id="main_<%= id %>" ></div>
		</div>
</script>

<script type="text/template" id="verse-new" >
<pre>
<% _(data).each(function( line ){ %><%= line.verse  %> <%= line.text %><% } ) %>
</pre>
</script>
<script type="text/template" id="verse" >
<div>
<% _(data).each(function( line ){ %>
           <a onclick="slimcrm.num_click( '<%= line.verse %>' );" data-verse="<%= line.verse %>" > <%= slimcrm.process_verse_text( line.verse)  %></a>
<%= line.text.replace("\n" , "<br/>") %>
<% } ) %>
</div>
</script>
</body>
</html>
