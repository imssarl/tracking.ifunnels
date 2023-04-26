<br/>
<br/>
{literal}
<style>
form.wh textarea, input[type="text"], input[type="password"], select {width:220px; margin:4px;}
form.wh label {width:280px;}
</style>
{/literal}
<form  class="wh" style="width:50%" action="" method="post" id="opt-form">
<fieldset>
	<legend>Include Code</legend>
		<ol>
			<li>
				<input type="radio" name="optArt" id="select"  class="opt-radio select_one" title="radio"  value="art">&nbsp;Display single article
			</li>
			<li>
				<input type="radio" name="optArt"  class="opt-radio"  value="randart">&nbsp;Display random articles from the category
			</li>
			<li>
				<input type="radio" name="optArt" id="select"  class="opt-radio select_two" title="checkbox" class="opt-radio"   value="artcat">&nbsp;Display a number of articles from the category
			</li>
			<li>
				<input type="radio" name="optArt"   class="opt-radio"  value="kwdart">&nbsp;Display keyword relevant article
			</li>						
			<li>
				<input type="radio" name="optArt"  class="opt-radio"   value="artsnip">&nbsp;Display article snippets
			</li>		
		</ol>
</fieldset>

<fieldset>
	<legend>&nbsp;</legend>
	<ol>
		<li>
			<!--start Multibox-->
			<div id="multibox-select">
			{module name='site1_articles' action='multiboxplace'  place='select_one' type='single'}	
			{module name='site1_articles' action='multiboxplace'  place='select_two' type='single'}
			<div id="articleList"></div>	
			</div>
			<!--end Multibox-->
			
			<div id="manager-box" style="display:none;">
				
				<div class="manage-item-block" id="randart-box" style="display:none;">
					<label>Enter the number of random articles</label>
					<input type="text" name="random_number" id="random_number" />
					<label>Select Category</label>
					<select name="category_randart" id='category-filter'>
						<option value='all'> All category </option>
						{html_options options=$arrSelect.category selected=$smarty.post.category}
					</select>
				</div>
				
				<div class="manage-item-block" id="kwdart-box" style="display:none;">
					<label>Enter a keyword</label>
					<input type="text" name="keyword" id="keyword"/>
					<label>Select Category</label>
					<select name="category_kwdart" id='category-filter'>
						<option value='all'> All category </option>
						{html_options options=$arrSelect.category selected=$smarty.post.category}
					</select>				
				</div>
				
				<div class="manage-item-block" id="artsnip-box" style="display:none;">
					<label>Enter the number of article snippets</label>
					<input type="text" name="snippets_number" id="snippets_number" />
					<label>Select Category</label>
					<select name="category_artsnip" id='category-filter'>
						<option value='all'> All category </option>
						{html_options options=$arrSelect.category selected=$smarty.post.category}
					</select>				
				</div>
				
			</div>
			
		</li>
		<li>
			<input type="button" value="Get Code" id="get_code" style="display:none;" >
		</li>		
		<li>
			<div id="block_php_code" style="display:none;">
				<textarea id="php_code" name="php_code" style="width:100%; height:400px;"></textarea>
				<br/>
				<input type="button" id="save_code_view" value="Save Generated Code" />
			</div>
		</li>
		<li>
			<div id="code_save_params" style="display:none;">
				<fieldset>
					<legend><b>Save Selected Code</b></legend>
				</fieldset>
				<label>Add Code Title</label>
				<input type="text" name="code_title" id="code_name" />
				<p></p>
				<label>Add Code Description</label>
				<textarea name="code_desc" id="code_desc" style="height:150px;"></textarea>
				<p></p>
				<label>&nbsp;</label>
				<input type="button" value="Save" id="save_code"/>
			</div>
			<div id="saved_code_message"></div>
		</li>
	</ol>
</fieldset>

</form>

{literal}

<script type="text/javascript">

var articleList = new Class({
	Implements: Options,
	options: {
		jsonData:'',
		place:'',
		contentDiv:$('articleList')
	},
	initialize: function( options ){
		this.setOptions( options );
		this.hash = JSON.decode( this.options.jsonData );
	},
	set: function(){
		this.options.contentDiv.empty();
		$('multibox_ids_' + this.options.place ).value = JSON.encode( this.hash );
		var header = new Element( 'div' );
		var b = new Element( 'b' ).set( 'html','<br/><br/>Selected articles' ).injectInside( header );
		header.inject( this.options.contentDiv );
		this.hash.each( function( value, key ) {
			key++ ;
			var div = new Element( 'div' );
			var name = new Element( 'p' );
			name.set( 'html',key + '. ' + value.title.substr( 0, 50 ) + ' <a href="#" class="delete_article_' + this.options.place + '" rel="' + value.id + '">Delete from list</a>' );
			name.injectInside( div );
			div.inject( this.options.contentDiv );
		},this );	
		this.initDeleteArticle();
	},
	initDeleteArticle: function() {
		$$( '.delete_article_' + this.options.place ).each( function( el ) {
			el.addEvent( 'click',function( e ) {
				e && e.stop();
				var arr = new Array();
				var i = 0;
				this.hash.each( function( value, key ) {
					if( value.id != el.rel ) {
						arr[ i ] = value;
						i++;
					}
				} );
				this.hash = arr;
				this.set();
			}.bindWithEvent( this ) );
		},this );
	}	
	
});

var activeRadio = {};

$('save_code_view').addEvent('click',function(){
	$('code_save_params').style.display='block';
	$('saved_code_message').empty();
});

$$('.opt-radio').each(function(el){
	el.addEvent('click', function(){
		activeRadio = el;
		$$('.manage-item-block').each(function(block){
			block.style.display='none';
		});
		$('articleList').empty();
		$('multibox_ids_select_one').value='';
		$('multibox_ids_select_two').value='';
		$('opt-block-multimanage_select_two').style.display='none';
		$('opt-block-multimanage_select_one').style.display='none';
		$('get_code').style.display='none';
		$('block_php_code').style.display='none';
		$('code_save_params').style.display='none';
		$('saved_code_message').empty();		
		
		if(el.hasClass('select_one')) { // if multibox-select
			$('multibox-select').style.display='block';
			$('manager-box').style.display='block';
			if( $(el.value+'-box') ) { $(el.value+'-box').style.display = 'block';	}
			$('get_code').style.display='block';			
			$('opt-block-multimanage_select_one').style.display='block';
			$('opt-block-multimanage_select_two').style.display='noen';
		} else if(el.hasClass('select_two')) { // if multibox-select
			$('multibox-select').style.display='block';
			$('manager-box').style.display='block';
			if( $(el.value+'-box') ) { $(el.value+'-box').style.display = 'block';	}
			$('get_code').style.display='block';			
			$('opt-block-multimanage_select_two').style.display='block';
			$('opt-block-multimanage_select_one').style.display='none';
		} else	{
			$('multibox-select').style.display='none';
			$('manager-box').style.display='block';
			$('get_code').style.display='block';
			if( $(el.value+'-box') ) { $(el.value+'-box').style.display = 'block'; }
		}
	});
});

/*
 * 
 */
$('get_code').addEvent('click', function(){
	if(activeRadio.hasClass('select_one')) { // if multibox-select
		
		var hash = new Hash( JSON.decode($('multibox_ids_select_one').value ) );
		var numIds = hash.getLength();
		if(numIds <= 0) { alert("Please select one row.");	return false; }
		
	}else if(activeRadio.hasClass('select_two')) { // if multibox-select
		
		var hash = new Hash( JSON.decode($('multibox_ids_select_two').value ) );
		var numIds = hash.getLength();
		if(numIds <= 0) { alert("Please select one row.");	return false; }
		
	} else {
		
		var anum=/(^\d+$)|(^\d+\.\d+$)/;
		 if( activeRadio.value == 'randart' ) {
		 	if( !parseInt($('random_number').value) ) {  alert("Enter a valid positive number.");	return false; 	}
		 } else if(  activeRadio.value == 'kwdart' ) {
		 	if( $('keyword').value == '' || anum.test($('keyword').value) ) {  alert("Enter a valid keyword.");	return false; 	}
		 } else if(  activeRadio.value == 'artsnip' ) {
		 	if( !parseInt($('snippets_number').value) ) {  alert("Enter a valid positive number.");	return false; 	}
		 }
	}
	
	$('block_php_code').style.display='block';
	var req = new Request({url: "{/literal}{url name='site1_articles' action='generatecode'}{literal}", onSuccess: function(responseText){
	 	$('php_code').value = responseText;
	} }).post($('opt-form'));
});



/* 
 *	Save php code.
 */
$('save_code').addEvent('click', function(){
	if($('code_name').value == '' || $('code_desc').value == '' ) {
		alert('Please enter both details before saving the code');
		return false;
	}
	var req = new Request({url: "{/literal}{url name='site1_articles' action='generatecode'}?save_code=1{literal}", onSuccess: function(responseText){
	 	$('saved_code_message').set('html',responseText);
	 	$('code_save_params').style.display = 'none';
	 	$('code_name').value = '';
	 	$('code_desc').value = '';
	} }).post($('opt-form'));	
});


</script>
{/literal}
