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
            $("#splitter").wijsplitter({ orientation: "vertical", fullSplit: true });
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
	$('#tabs').tabs();
        slimcrm.add_tab();
});

slimcrm.search_bible = function(){
	$.getJSON('/cgi-bin/vrp.cgi?' + $.param( { 'search': $('#bible_book').val() , 'bible': $('.book').val() , 'format': 'OSIS' } ) , function( data){ $('#main').html( slimcrm.tpl( { 'data': data } ) ); } ) ;
}
slimcrm.process_osis_text = function(text){
    return text;
}


</script>
<script  >
slimcrm.bible = ['Genesis','Exodus','Leviticus','Numbers','Deuteronomy','Joshua','Judges','Ruth','1 Samuel','2 Samuel','1 Kings','2 Kings','1 Chronicles','2 Chronicles','Ezra','Nehemiah','Esther','Job','Psalm','Proverbs','Ecclesiastes','Song of Solomon','Isaiah','Jeremiah','Lamentations','Ezekiel','Daniel','Hosea','Joel','Amos','Obadiah','Jonah','Micah','Nahum','Habakkuk','Zephaniah','Haggai','Zechariah','Malachi','Matthew','Mark','Luke','John','Acts','Romans','1 Corinthians','2 Corinthians','Galatians','Ephesians','Philippians','Colossians','1 Thessalonians','2 Thessalonians','1 Timothy','2 Timothy','Titus','Philemon','Hebrews','James','1 Peter','2 Peter','1 John','2 John','3 John','Jude','Revelation'
]

</script>
<div id="splitter">
	<div>
		<button onclick="$('#main').html(slimcrm.tpl({ data: slimcrm.data} ));">Click</button>
		<button onclick="slimcrm.add_tab({ version: 'ESV'});">ESV</button>
                <button onclick="slimcrm.add_tab({ version: 'KJV'});">ESV</button>
	</div>
	<div id="tabs" >
		<ul>
		</ul>
		
	</div>
</div>
<script type="text/template" id="books-dropdown">
<% _(books).each(function( book ){ %><option value="<%= book %>" ><%= book %></option><% } ) %>
</script>
<script type="text/template" id="tab-inner">
<div id="<%= id %>" class="tab" >
			<div>
					<select class="book" >
						<option value="ESV" >ESV</option>
						<option value="KJV" >KJV</option>
					</select>
				<input type="text" id="search_string" /><
				<button onclick="slimcrm.search_bible();" >Search</button>
			<div id="main" ></div>
		</div>
</script>

<script type="text/template" id="verse-new" >
<pre>
<% _(data).each(function( line ){ %><%= line.text %><% } ) %>
</pre>
</script>
<script type="text/template" id="verse" >
<div>
<% _(data).each(function( line ){ %>
<%= line.text.replace("\n" , "<br/>") %>
<% } ) %>
</div>
</script>
</body>
</html>
