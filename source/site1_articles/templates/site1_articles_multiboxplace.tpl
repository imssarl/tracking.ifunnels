{assign var=place value=$arrPrm.place}
{assign var=type value=$arrPrm.type}
{assign var=input value=$arrPrm.input}
{assign var=required value=$arrPrm.required}
{assign var=return value=$arrPrm.return}
{assign var=className value=$arrPrm.className}
{if $type == 'multiple'}
<div id="opt-block-multimanage_{$place}" >
<div  {if $arrPrm.disabled==1} style="display:none;" {/if}>
<label>Select articles{if $required == 1} <em>*</em>{/if} </label><a {if Project_Users::haveAccess( array( 'NVSB Hosted', 'NVSB Hosted Pro', 'Site Profit Bot Pro', 'Site Profit Bot Hosted' ) )} style="display:none;"{/if}  href="" id="open_mutlibox_select_{$place}" class="mb_article"  title="Articles Select" rel="width:800,height:500">Content Wizard</a>&nbsp;&nbsp;&nbsp;
<a  href="" id="open_mutlibox_import_{$place}" class="mb_article"  title="Articles Import" rel="width:800,height:500">Upload Article</a>
</div>
</div>
{else}
<div id="opt-block-multimanage_{$place}" style="display:none;">
	<a  href="" id="open_mutlibox_select_{$place}" class="mb_article" style="display:none;" title="Articles Select" rel="width:800,height:500">Select from article wizard</a>
	<br/>
	<a  href="" id="open_mutlibox_import_{$place}" class="mb_article" style="display:none;" title="Articles Import" rel="width:800,height:500">Upload articles</a>
</div>
{/if}
<input type="hidden" id="multibox_ids_{$place}" name="multibox_ids_{$place}" value=""/>
<input type="hidden" id="count_article_{$place}" value="0" />

<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
{literal}
<script>
 
/*
*  multibox select article initialize place
*/
var multiboxPlace = new Class({
	Implements:Options,
	options: {
		place: '{/literal}{$place|default:"article_wizard"}{literal}',
		type: '{/literal}{$type|default:"multiple"}{literal}',
		input: '{/literal}{$input|default:"checkbox"}{literal}',
		links: {
			importLink:'',
			selectLink:''
		},
		return2import:'{/literal}{$return|default:"list"}{literal}'
	},
	initialize: function( options ){
		this.setOptions( options );
		this.initEvents();
		this.initSelectLink();
		this.initImportLink();
	},
	initEvents: function() {
		$$('.' + this.options.place ).each( function( el ){
			el.addEvent( 'click', function( e ){ 
				if(  el.checked && el.id == 'import' ) {
					$( 'open_mutlibox_select_' + this.options.place ).style.display = 'none';
					$( 'open_mutlibox_import_' + this.options.place ).style.display = 'block';					
				} else if( el.checked && el.id == 'select' ) {
					$( 'open_mutlibox_select_' + this.options.place ).style.display = 'block';
					$( 'open_mutlibox_import_' + this.options.place ).style.display = 'none';					
				}			
			}.bindWithEvent( this ) );
		}, this );
		$('opt-block-multimanage_' + this.options.place ).setStyle('display','block');
	},
	
	initSelectLink: function() {
		if( this.options.type != 'multiple') {
		$$( '.' + this.options.place ).each( function( el ) {
			var type = 'checkbox';
			if( el.title ) { type = el.title; }
			else if ( el.rel ) { type = el.rel;	}
			$( 'open_mutlibox_select_' + this.options.place ).href = this.options.links.selectLink + "?place=" + this.options.place + "&type_input_element=" + type;
		},this );			
		} else {
			$( 'open_mutlibox_select_' + this.options.place ).href = this.options.links.selectLink + "?place=" + this.options.place + "&type_input_element=" + this.options.input;
		}
	},

	initImportLink: function() {
		if( this.options.return2import == '' ) {
			this.options.return2import = 'list';
		}
		$( 'open_mutlibox_import_' + this.options.place ).href = this.options.links.importLink + "?place=" + this.options.place + "&return=" + this.options.return2import;
	},		
});

/************************/


var multiboxArticle = new Class( {
	Implements: Options,
	options: {
		jsonData: '',
		place:'article_wizard'		
	},
	initialize: function( options ) {
		this.setOptions( options );
		this.parentClass = new {/literal}{$className|default:"articleList"}{literal}( this.options );
		$( 'multibox_ids_' + this.options.place ).value = this.options.jsonData;
		this.parentClass.set();
	}
}); 


var multibox_article ={};
var disabled = {/literal}{if $arrPrm.disabled}true{else}false{/if};{literal}

window.addEvent( 'domready', function() {
	/*
	* Initialization multibox
	*/
	if( !$chk( multibox_article.options ) ) {
		multibox_article = new multiBox( {
			mbClass: '.mb_article',
			container: $( document.body ),
			useOverlay: true,
			nobuttons: true
		} );			
	}
	/***********************/
	
	/*
	* initialization multiboxPlace
	*/
	new multiboxPlace({
		place:'{/literal}{$place}{literal}',
		type:'{/literal}{$type}{literal}',
		links: {
			importLink:'{/literal}{url name="site1_articles" action="importpopup"}{literal}',
			selectLink:'{/literal}{url name="site1_articles" action="multiboxselect"}{literal}'
		}
	});
	/***************/

	/*
	* initialization multiboxArticle if edit. Get JSON from Smarty
	*/
	{/literal}
	{if $arrPrm.selected != ''}
		json_{$place} = '{$arrPrm.selected|replace:"'":"`"}';
		new multiboxArticle( {literal}{{/literal}jsonData:json_{$place}, place:'{$place}'{literal}}{/literal});
	{/if}
	{literal}
	/***********************/
} );

</script>
{/literal}