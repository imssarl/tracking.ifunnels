<div align="center" style="padding:10px 0 0 0; ">
	<a href="{url name='site1_content' action='blog'}">Manage</a> | <a href="{url name='site1_content' action='blog_create'}">Create</a>
</div>
{literal}
<script type="text/javascript">
var objAccordion = {};
window.addEvent( 'domready', function() {
	objAccordion = new myAccordion( $( 'accordion' ), $$( '.toggler' ), $$( '.element' ), { fixedHeight:false } );
} );
</script>
{/literal}
<form action="" method="post" class="wh" id="submit_form" style="width:67%; visibility: hidden; opacity: 0;" >
<input type="hidden" name="arrPrj[id]" value="{$arrPrj.id}" />
<input type="hidden" name="arrPrj[flg_type]" value="1" />
<input type="hidden" name="arrPrj[flg_status]" value="{$arrPrj.flg_status}" />
<div id="accordion">
{include file="create/step1.tpl"}
{include file="create/step2.tpl"}
{include file="create/step3.tpl"}
{include file="create/step4.tpl"}
{include file="create/step5.tpl"}
<p></p>
<input type="hidden" name="arrPrj[jsonContentIds]" id="jsonContentIds" />
	</div>
	{if $arrPrj.flg_status == 2}
	<div style="padding:10px  0  10px 0;">
		<label><input type="checkbox" name="arrPrj[restart]" value="1" /> re-start project</label>
	</div>
	{/if}
	<div>
		<input type="button" value="{if $arrPrj.id}Save{else}Add{/if} project" id="create" />
	</div>
</form>
<div style="clear:both;"></div>
{literal}
<!-- multibox -->
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" >
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script type="text/javascript" src="/skin/_js/multibox/overlay.js"></script>
<script type="text/javascript" src="/skin/_js/multibox/multibox.js"></script>
<!-- /multibox -->
<link rel="stylesheet" type="text/css" media="all" href="/skin/_js/jscalendar/skins/aqua/theme.css" title="Aqua" >
<script type="text/javascript" src="/skin/_js/jscalendar/calendar.js"></script>
<script type="text/javascript" src="/skin/_js/jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="/skin/_js/jscalendar/calendar-setup.js"></script>
<style type="text/css">
.toggler{cursor:pointer;padding:5px 0 0 8px;}
.element{padding:10px 0 10px 0;}
fieldset{padding:15px 0 0 0;}
fieldset fieldset{padding:0;}
ol li{position:relative;}
</style>
<script type="text/javascript">
var jsonShedule = {/literal}'{$jsonShedule|default:"[]"}'{literal};
var json2edit = {/literal}'{$strJson}'{literal};
var jsonContentIds = new Array();

// здесь вставляются элементы выбранные в фрэйме
var placeParam = new Array();

var placeDo = function(){

	if( this.jsonShedule != '[]' ||  placeParam.toString() != '' ) {
		var index = 1;	
		$( 'place_content' ).empty();
		$( 'place_content' ).setStyle( 'display', 'block' );
		
		var b = new Element( 'b',{'html':'Selected content'} );
		b.inject( $( 'place_content' ) );
		
		placeParam.each( function( v,i ) {
			//Ids[i] = v.id;
			var p = new Element( 'p', { 'html':index+'. '+v.title+' ','rel':v.title } );
			var a = new Element( 'a', {'href':'#', 'class':'content-delete-list', 'rel':v.id} );
			
			if( flgStatus != 0 ) {
				var contentList = JSON.decode(jsonContentList);
				
				if( !contentList || !contentList.some(function(i){ return i.id == v.id;}) ) {
					a.set('html','Delete from list');
				}
			} else {
				a.set('html','Delete from list');
			}
			p.inject( $( 'place_content' ) );
			a.inject( p );
			index++;
		});
		//показывает старые shedule
		var oldShedule = new Array();
		oldShedule = JSON.decode( jsonShedule.clean() );
		
		oldShedule.each( function (v,i) {
			var p = new Element( 'p', { 'class':'content-delete-list','html':index+'. '+v.title+' ','rel':v.title } );
			p.inject( $( 'place_content' ) );
			index++;
		} );
	}
		// /показывает старые shedule
	$$( '.content-delete-list' ).each( function( el ){
		el.addEvent( 'click', function( e ){
			e && e.stop();
			var temp = placeParam;
			
			if( !$chk( temp ) ) {
				return false;
			}
			var i = 0;
			placeParam = new Array();
			
			temp.each( function( v ){
				if( v.id != el.rel ){
					placeParam[i] = {'id':v.id,'title':v.title};
					i++;
				}
			} );
			placeDo();
		} );
	} );
	
	var Ids = new Array();
	var jsonIds = '';
	var i = 0;
	//сделать проход по <a href="#" class="content-delete-list" rel="56475">
	$$('.content-delete-list').each( function( el ){
		Ids[i] = el.get('rel');
		i++;	
	});

	var jsonIds = JSON.encode( Ids );
	jsonContentIds = JSON.decode( jsonIds );
	$( 'jsonContentIds' ).value = jsonIds;
	return true;
	
/*	var input = new Element( 'input', { 'type':'hidden','id':'ids_content_wizard', 'value':JSON.encode( Ids ) } );
	input.inject( $( 'place_content' ) );
	*/
};


	//визуал

	var flgStatus =  {/literal}{$arrPrj.flg_status|default:'null'}{literal};
	var jsonContentList =  '{/literal}{$jsonContentList|replace:"'":"`"|default:"null"}{literal}';
	var jsonSitesList = '{/literal}{$jsonSitesList|replace:"'":"`"}{literal}';
	var masterBlogId = {/literal}{$arrPrj.masterblog_id|default:'null'}{literal};
	var Visual = new Class( {
		initialize: function(){
			this.initEventTypeContent( 'content-type' );	
			this.initMasterBlog();
			this.initSelectBlogs();
			this.initValid();
			this.initSchedulingType();
			this.initBlogList();
			this.initContent();
			if( flgStatus == 2 || flgStatus == 0 ) {
				$$('.blog-list').each( function( element ){
					if( element.checked ) {
						this.getBlogList( element );
					}
				},this);
			}
		},
		initContent: function() {
			if( jsonContentList != 'null' ) {
				placeParam = JSON.decode( jsonContentList );
				placeDo();
			}
		},
		initSchedulingType: function(){
			$$( '.scheduling-type' ).each( function( el ){
				el.addEvent( 'click', function(){
					$( 'start-date' ).setStyle( 'display',( el.value == 2 )?'block':'none' );
				} );
			} );
		},
		initSelectBlogs: function(){
			$$( '.blog-list' ).each( function( input ){
				input.addEvent( 'click',function(){
					$$( '.fieldset-blog-list' ).each( function( el ){
						el.empty();
					} );					
					this.getBlogList( input );
					this.selectMasterBlog();
				}.bindWithEvent( this ) );
			},this );
			
			$( 'category_child' ).addEvent( 'change',function(){
				$$( '.fieldset-blog-list' ).each( function( el ){
					el.empty();
				} );
				$$( '.blog-list' ).each( function( el ){
					if ( el.checked ) {
						this.getBlogList( el );
					}
				},this );
				this.selectMasterBlog();
			}.bindWithEvent( this ) );
			
			$( 'category' ).addEvent( 'change',function(){
				$$( '.fieldset-blog-list' ).each( function( el ){
					el.empty();
				} );
				this.selectMasterBlog();
			}.bindWithEvent( this ) );			
			
		},
		getBlogList: function( element ) {
			if( !$( 'category_child' ).value ) {
				return false;
			}
			var arr = JSON.decode( jsonSitesList );
			var arrShedule = JSON.decode( jsonShedule );
			arr.each( function( item ){
				if( $( 'category_child' ).value != item.category_id ) {
					return false;
				}
				var label = new Element( 'label' );
				var input = new Element( 'input', {'type':'checkbox','name':'arrPrj[arrSiteIds]['+item.id+'][site_id]', 'value':item.id, 'class':'blog-item'} );
				if( arrShedule.some(function(v){ return item.id==v.site_id; }) ){
					input.set('checked',1);
				}
				if ( element.value == 1 ) {
					input.checked = true;
				}
				var span = new Element( 'span', {'html':' '+item.title} );
				input.inject( label );
				span.inject( label );
				label.inject( element.getNext() );
				var fieldset = new Element( 'fieldset' ).setStyle( 'display', ((input.checked)?'block':'none') ).setStyle( 'padding-left','16px' );
				if( $chk( item.categories )) {
				item.categories.each( function( category ){
					var label2 = new Element( 'label' );
					var input2 = new Element( 'input', {'type':'radio','name':'arrPrj[arrSiteIds]['+item.id+'][ext_category_id]','value':category.ext_id} );
					if( arrShedule.some(function(v){ return category.ext_id==v.ext_category_id && item.id==v.site_id; }) ){
						input2.set('checked',1);
					}					
					var span2 = new Element( 'span', {'html':' '+category.title} );
					input2.inject( label2 );
					span2.inject( label2 );
					label2.inject( fieldset );
				} );
				}
				fieldset.inject( element.getNext() );
			} );
			if ( element.value != 1 ) {
				element.getNext().setStyle( 'display','block' );
			}
			this.initBlogList();
		},
		
		initBlogList: function() {
			$$( '.blog-item' ).each( function( el ){
				el.addEvent( 'click',function(){
					var fieldset = el.getParent().getNext();
					fieldset.setStyle( 'display', ( el.checked )?'block':'none' );
					this.selectMasterBlog();
				}.bindWithEvent( this ) );
			},this );
		},
		
		initMasterBlog: function(){
			if( masterBlogId != null ) {
				var arrList = JSON.decode( jsonSitesList );
				arrList.each( function( el ){
					if ( el.id == masterBlogId ) {
						var option = new Element( 'option', { 'value':el.id, 'html':el.title } );
						option.inject( $( 'master-blog-list' ) );
					}
				} );
			}
			$( 'master-blog' ).addEvent( 'click', function(){
				$( 'select-master-blog' ).setStyle( 'display', ( $chk( $( 'master-blog' ).checked ) )?'block':'none' );
				if( $( 'master-blog' ).checked ){
					this.selectMasterBlog();
				}
			}.bindWithEvent( this ) );
		},
		
		selectMasterBlog: function() {
			if( !$( 'master-blog' ).checked ){
				return false;
			}
			$( 'master-blog-list' ).empty();
			
			var arrList = JSON.decode( jsonSitesList );
			
			var option = new Element( 'option', { 'value':'', 'html':'- select blogs first -' } );
			option.inject( $( 'master-blog-list' ) );

			arrList.each( function( blog ) {
				var category_id = $( 'category_child' ).value;
				if ( !category_id || category_id != blog.category_id ) {
					return false;
				}
				if ( $$( '.blog-list' ).some( function( v ){ return v.checked; } ) ) {
					if ( $$( '.blog-item' ).some( function( v ){ return ( blog.id == v.value && !v.checked ) } ) ) {
						return false;
					}
				}
				var option = new Element( 'option', { 'value':blog.id, 'html':blog.title } );
				if (blog.id == '{/literal}{$arrPrj.mastersite_id}{literal}') {option.set('selected','selected');}
				
				option.inject( $( 'master-blog-list' ) );
			} );
		},
		
		initEventTypeContent: function( elements ) {
			$$( '.'+elements ).each( function( el ){
				el.addEvent( 'click', function( e ) {
					$( 'rss_fields' ).setStyle( 'display','none' );
					$( 'video_wizard' ).setStyle( 'display','none' );
					$( 'article_wizard' ).setStyle( 'display','none' );
					switch( el.id ){
						case 'rss': 
							$( 'rss_fields' ).setStyle( 'display','block' );
							$( 'not-rss' ).setStyle( 'display', 'none' );
							$( 'select-rss' ).setStyle( 'display', 'block' );
							$( 'networking' ).setStyle( 'display', 'none' );
							$( 'to_networking' ).setStyle( 'display', 'none' );
						break;
						case 'article': 
							$( 'article_wizard' ).setStyle( 'display','block' );
							$( 'not-rss' ).setStyle( 'display', 'block' );
							$( 'select-rss' ).setStyle( 'display', 'none' );							
							$( 'networking' ).setStyle( 'display', 'block' );
							$( 'to_networking' ).setStyle( 'display', 'inline' );
						break;
						case 'video':
							$( 'video_wizard' ).setStyle( 'display','block' );
							$( 'not-rss' ).setStyle( 'display', 'block' );
							$( 'select-rss' ).setStyle( 'display', 'none' );
							$( 'networking' ).setStyle( 'display', 'block' );							
							$( 'to_networking' ).setStyle( 'display', 'inline' );							
						break;
					}
				} );
			} );
		},
		
		initValid: function() {
			
			$( 'create' ).addEvent( 'click',function( e ){
				var error = true;
				if( !$chk($$( 'select#select_content' ).value) ) {
						error = false;}
				if( error ) {
					r.alert( 'Messages', 'Please select content sorce', 'roar_error' );
					return false;
				}
				if( !$chk( $( 'project_title' ).value ) ) {
					r.alert( 'Messages', 'Please fill project title', 'roar_error' );
					return false;
				}
				var error = true;
				$$( '.clear-blog-list' ).each( function( input ){
					if( input.checked ) { error = false }
				} );
				var error2 = true;
				if ( $('select_below_list').checked || $('select_list').checked ) {
					$$('.blog-item').each(function( el ){
						if ( el.checked ) {
							error2 = false;
						}
					});
				} else {
					error2=false;
				}
				if( flgStatus != 1 ) { 
				if( ( !$chk( $( 'category' ).value ) && !$chk( $( 'category_child' ).value ) ) || error || error2 ){
					r.alert( 'Messages', 'Please select blogs on which you want to publish.', 'roar_error' );
					return false;
				}
				}
				placeDo();
				$( 'create' ).disabled=true;
				$( 'submit_form' ).submit();
			}.bindWithEvent( this ) );
		}
	} );
	
	
	// конец.визуал
	
	

var myAccordion = new Class( {
	Extends: Accordion,
	initialize: function( container, toggler, element, options ){
		this.form = container.getParent( 'form' );
		this.parent( container, toggler, element, options );
		this.initButton();
	},
	initButton:function(){
		this.prev = $$( 'a.acc_prev' );
		this.next = $$( 'a.acc_next' );		
		var obj = this;
		this.prev.each( function( el ){
			el.addEvent( 'click',function( e ){e.stop(); obj.display( obj.previous-1 );  } );
		} );
		this.next.each( function( el ){
			el.addEvent( 'click',function( e ){e.stop(); obj.display( obj.previous+1 );  } );
		} );
		setTimeout( 'displayForm()', 1000 );
	}
} );

var displayForm = function(){
	$( 'submit_form' ).fade( 1 );
};


// подключаемые категории и категории второго уровня
var categoryId = {/literal}{$arrPrj.category_id|default:'null'}{literal};
var jsonCategory = {/literal}{$treeJson}{literal};
var CategoriesContent = new Class( {
	Implements: Options,
	options: {
		firstLevel: 'category',
		secondLevel: 'category_child',
		intCatId: categoryId
	},
	
	initialize: function( options ){
		this.setOptions( options );
		this.arrContentCategories = new Hash(jsonCategory);
		$( this.options.firstLevel ).addEvent( 'change',function(){
			this.setFromFirstLevel( $( this.options.firstLevel ).value );
		}.bindWithEvent( this ) );
		if( $chk( this.options.intCatId ) && this.checkLevel( this.options.intCatId ) ) {
			this.setFromFirstLevel( this.options.intCatId );
		} else if( $chk( this.options.intCatId ) ) {
			this.setFromSecondLevel( this.options.intCatId );
		}
	},
	
	checkLevel: function( id ){
		var bool=false;
		this.arrContentCategories.each( function( el ){
			if( el.id == id ) { bool=true; }
		} ); 
		return bool;
	},
	
	setFromFirstLevel: function( id ){
		if( !id ) {
			$( this.options.secondLevel ).empty();
			var option = new Element( 'option',{'value':'','html':'- select -'} );
			option.inject( $( this.options.secondLevel ) );			
			return false;
		}
		this.arrContentCategories.each( function( item ){
			if( item.id == id ) {
				$A( $( this.options.firstLevel ).options ).each( function( i ){
					if( i.value == id ){
						i.set( 'selected',true );
					}
				} );					

				$( this.options.secondLevel ).empty();
				var option = new Element( 'option',{'value':'','html':'- select -'} );
				option.inject( $( this.options.secondLevel ) );
				var hash = new Hash( item.node );
				hash.each( function( i,k ){
					var option = new Element( 'option',{'value':i.id,'html':i.title} );
					if( i.id == this.options.intCatId ){
						option.set( 'selected', true );
					}
					option.inject( $( this.options.secondLevel ) );
				},this );
			}
		},this );
	},
	
	setFromSecondLevel: function( id ) {
		this.arrContentCategories.each( function( item ){
			var hash = new Hash( item.node );
			hash.each( function( el ){
				if ( id == el.id ) {
					this.setFromFirstLevel( el.pid );
				}
			},this );
		},this );
	}
	
} );
// конец.подключаемые категории и категории второго уровня
if ( multibox == null ) {
var multibox = {};
var visual = {};

window.addEvent('load', function() {
	multibox = new multiBox( {
			mbClass: '.smb',
			container: $( document.body ),
			useOverlay: true,
			nobuttons: true
		} );
});
}
visual = new Visual();

window.addEvent( 'domready', function() {

	new CategoriesContent( {firstLevel:'category',secondLevel:'category_child',intCatId: categoryId} );
	$$( 'div.element' ).each( function( div,index ){
		div.set( 'id',index );
	} );	

	this.placeDo();
	
	// Title functions
	var optTips = new Tips('.Tips', {className: 'tips'});
	$$('.Tips').each(function(a){
			a.addEvent('click',
					function(e){
						if (!empty(e.get('href')))
						e.stop()
						})
			});	
			
			
			
visual.selectMasterBlog();
visual.initMasterBlog();

} );

var blogMultiboxDo = function(){
	visual.selectMasterBlog();
	
};
</script>
{/literal}