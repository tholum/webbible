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
var slimcrm = {};
$(document).ready(function(){
	slimcrm.tpl = _.template( $('#verse').html() );
	slimcrm.bbl = _.template( $('#books-dropdown').html() );
	$('.bk').html('<select id="bible_book" >' + slimcrm.bbl( { 'books': slimcrm.bible }) + '</select>' );
	$.getJSON('/cgi-bin/vrp.cgi' , function( data ){ slimcrm.data = data; } ); 
	$('#tabs').tabs();	
});

slimcrm.search_bible = function(){
	$.getJSON('/cgi-bin/vrp.cgi?' + $.param( { 'search': $('#bible_book').val() , 'bible': $('.book').val() , 'format': $('.format').val() } ) , function( data){ $('#main').html( slimcrm.tpl( { 'data': data } ) ); } ) ;
}
slimcrm.process_osis_text = function(text){
 
 
}


</script>
<script  >
slimcrm.bible = ['Genesis','Exodus','Leviticus','Numbers','Deuteronomy','Joshua','Judges','Ruth','1 Samuel','2 Samuel','1 Kings','2 Kings','1 Chronicles','2 Chronicles','Ezra','Nehemiah','Esther','Job','Psalm','Proverbs','Ecclesiastes','Song of Solomon','Isaiah','Jeremiah','Lamentations','Ezekiel','Daniel','Hosea','Joel','Amos','Obadiah','Jonah','Micah','Nahum','Habakkuk','Zephaniah','Haggai','Zechariah','Malachi','Matthew','Mark','Luke','John','Acts','Romans','1 Corinthians','2 Corinthians','Galatians','Ephesians','Philippians','Colossians','1 Thessalonians','2 Thessalonians','1 Timothy','2 Timothy','Titus','Philemon','Hebrews','James','1 Peter','2 Peter','1 John','2 John','3 John','Jude','Revelation'
]

</script>
<div id="splitter">
	<div>
		<button onclick="$('#main').html(slimcrm.tpl({ data: slimcrm.data} ));">Click</button>
		<button onclick="$('#tabs').append('<div id=abc>This is a test</div>');$('#tabs').tabs('add','#abc','Name');">Add A Tab</button>
	</div>
	<div id="tabs" >
		<ul>
			<li><a href="#maintab">Main</a></li>
		</ul>
		<div id="maintab" >
			<ul class="searchbar">
				<li>
					<select class="book" >
						<option value="ESV" >ESV</option>
						<option value="KJV" >KJV</option>
					</select>
				</li>
				<li>
					<select class="format" >
						<option value="OSIS" >OSIS</option>
						<option value="PLAIN" >PLAIN</option>
						<option value="HTML" >HTML</option>
					</select>
				</li>
				<li class="bk"></li>
				<li><input type="text" id="search_string" /></li>
				<li><button onclick="slimcrm.search_bible();" >Search</button></li>
			</ul>
			<div id="main" ></div>
		</div>
	</div>
</div>
<script type="text/template" id="books-dropdown">
<% _(books).each(function( book ){ %><option value="<%= book %>" ><%= book %></option><% } ) %>
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
