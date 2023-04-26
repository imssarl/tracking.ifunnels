<br/>
<br/>
<div class="red">
{if !empty($arrErr)}
{foreach from=$arrErr item=i}
	{$i}<br/>
{/foreach}
{/if}
</div>
 
<form action="" class="wh" id="create-form" method="post" style="width:55%">
	{if $arrPrj.id}
		<input type="hidden" name="arrPrj[id]" value="{$arrPrj.id}" id="project_id" />
		<input type="hidden" name="arrPrj[flg_status]" value="{$arrPrj.flg_status}" />
	{/if}
	<fieldset {if $arrPrj.flg_status==$arrStatus.progress} style="display:none;" {/if}>
		<legend>Select source</legend>
		<ol>
			<li>
				<label>Video</label><input type="checkbox" id="video-content" {if  $arrPrj.flg_status>=$arrStatus.pending}disabled='1'{/if} />
			</li>
			<li style="display:none;"  id="video-block">
				<a rel="width:800"{if $arrPrj.flg_status>=$arrStatus.pending} style="display:none;"{/if} 
					href="{url name='site1_video_manager' action='multibox'}?multiselect=1" class="mb">Import from Video Manager</a>
				<br/>
				<br/>
				<div style="display:none;"  id="place-video" class="popupList"></div>
				<div style="clear:both;"></div>
			</li>
			<li>
				<label>Articles</label><input type="checkbox" id="article-content"{if  $arrPrj.flg_status>=$arrStatus.pending} disabled='1'{/if} />
			</li>
			<li style="display:none;" id="article-block">
			{module name='site1_articles' action='multiboxplace' selected=$jsonContent place='article' type='multiple' className='contentList'}
				<div style="display:none;"  id="articleList" class="popupList"></div>
				<div style="clear:both;"></div>
			</li>
			<li>
				{if !empty($arrPrj.id)}
				<p class="helper">Note: if you would like to edit any of the content with 'Approved' status, you would need to delete it from the project, then edit it, and then re-add it to your syndication project. You can edit content with 'Rejected' or 'Draft' status without needing to delete and re-add it.</p>
				{/if}
				<p class="helper">IMPORTANT: if you have submitted a project for review, and it got 'Approved', you are NOT able to edit any of the project content as it has already been submitted for posting.</p>
			</li>
		</ol>
	</fieldset>

	{if $arrPrj.flg_status==$arrStatus.progress}
	<fieldset>
		<legend>Plan</legend>
		<ol>
			<li>
			<table cellpadding="3" width="100%" class="table">
				<tr>
					<th>Content</th>
					<th align="center">Status</th>
					<th align="center">Site Type</th>
				</tr>
			{foreach from=$arrPlan item=site}
				{foreach from=$site item=i key=k}
				<tr {if $k%2=='0'} class="matros"{/if}>
					<td>{$arrContent[$i.content_id].title}</td>
					<td align="center">{$arrStatus[$i.flg_status]}</td>
					<td align="center">{if $i.site_type==1}SPB{elseif  $i.site_type==2}NCSB{elseif  $i.site_type==3}NVSB{elseif  $i.site_type==4}CNB{elseif  $i.site_type==5}BF{/if}</td>
				</tr>
				{/foreach}
			{/foreach}
			</table>
			</li>
		</ol>
	</fieldset>
	{/if}

	<fieldset>
		<legend>Project Post Settings</legend>
		<ol>
			<li>
				<label>Project name <em>*</em></label><input type="text" class="required" name="arrPrj[title]" value="{$arrPrj.title}" />
			</li>
			<li>
				<label>Select category <em>*</em></label>
				
				<select  class="required" {if $arrPrj.flg_status>=$arrStatus.approved}disabled='1'{/if} id="select-category"><option value=""> - select -</select>
				
			</li>
			<li id="category-block" style="display:none;">
				<fieldset>
					<label><input type="checkbox" {if $arrPrj.flg_status>=$arrStatus.approved}disabled='1'{/if} id="select-all-category"> Select all</label>
					<div id="category-place"></div>
				</fieldset>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Backlinking</legend>
		<ol>
			<li>
				<input type="hidden" name="arrPrj[flg_backlinks]"/>
				<label>Backlinking</label><input type="checkbox"{if $arrPrj.flg_status>=$arrStatus.approved} disabled=""{/if}{if $arrPrj.flg_backlinks != 0} checked=""{/if} id="open-backlinking"  />
			</li>
			<div id="backlinking" style="display:{if $arrPrj.flg_backlinks != 0}block{else}none{/if};">
			<li>
				<input type="hidden" name="arrPrj[flg_backlinks]" value="0" />
				<label>My sites from project category</label><input  type="radio" id="default-backlinking" {if $arrPrj.flg_status>=$arrStatus.approved}disabled='1'{/if} value="1" {if $arrPrj.flg_backlinks == 1} checked='1' {/if} name="arrPrj[flg_backlinks]" class="radio-hands" />
			</li>
			<li>
				<label>Manual select</label><input type="radio" {if $arrPrj.flg_status>=$arrStatus.approved}disabled='1'{/if} name="arrPrj[flg_backlinks]" {if $arrPrj.flg_backlinks == 2} checked='1' {/if} value="2" class="radio-hands" />
			</li>
			<div style="display:{if $arrPrj.flg_backlinks == 2}block{else}none{/if};" id="hands">
			<li>
				<label>&nbsp;</label>
{if Project_Users::haveAccess( 'Unlimited' )}
				<a {if $arrPrj.flg_status>=$arrStatus.approved}style="display:none;"{/if} href="{url name="site1_cnb" action="multiboxlist"}" class="mb" rel="width:800,height:500">CNB</a>&nbsp;&nbsp;&nbsp;
				<a {if $arrPrj.flg_status>=$arrStatus.approved}style="display:none;"{/if} href="{url name="site1_psb" action="multiboxlist"}" class="mb" rel="width:800,height:500">PSB</a>&nbsp;&nbsp;&nbsp;
				<a {if $arrPrj.flg_status>=$arrStatus.approved}style="display:none;"{/if} href="{url name="site1_ncsb" action="multiboxlist"}" class="mb" rel="width:800,height:500">NCSB</a>&nbsp;&nbsp;&nbsp;
				<a {if $arrPrj.flg_status>=$arrStatus.approved}style="display:none;"{/if} href="{url name="site1_nvsb" action="multiboxlist"}" class="mb" rel="width:800,height:500">NVSB</a>&nbsp;&nbsp;&nbsp;
{/if}
				<a {if $arrPrj.flg_status>=$arrStatus.approved}style="display:none;"{/if} href="{url name="site1_blogfusion" action="multiboxlist"}?noversion=1" class="mb" rel="width:800,height:500">Blog fusion</a>&nbsp;&nbsp;&nbsp;
				
			</li>
			<li id="place2sites">
				<div style="display:none;" class="popupList" id="site_place_4"></div>
				<div style="display:none;" class="popupList" id="site_place_3"></div>
				<div style="display:none;" class="popupList" id="site_place_2"></div>
				<div style="display:none;" class="popupList" id="site_place_5"></div>
				<div style="display:none;" class="popupList" id="site_place_1"></div>
				<div style="clear:both;">&nbsp;</div>
			</li>
			</div>
			<li>
				<label>Additional 3rd party sites</label><textarea{if $arrPrj.flg_status>=$arrStatus.approved} disabled=""{/if} name="arrPrj[backlinks]" id="place-for-link" style="height:150px;">{$arrPrj.backlinks}</textarea>
				<p class="helper">Notice: Separate urls by comma, space or newline</p>
				<p class="helper">Link format example: http://www.mydomain.com/ </p>
			</li>
			</div>
			<li>
				<input type="submit" value="Save" class="save" /> 
				<input type="submit" name="arrPrj[for_review]" value="Submit for review" class="save"{if $arrPrj.flg_status>=$arrStatus.approved||$arrPoints.balance<=0} style="display:none;"{/if} />
			</li>
		</ol>
	</fieldset>

	<input type="hidden" id="json_article" value='[]' />
	<input type="hidden" id="json_video" value='[]' />
	<input type="hidden" id="jsonBlogList" value='[]' />
	<input type="hidden" id="jsonSiteList" value='[]' />
</form>

{literal}
<style>
.popupList{float:left; border:1px solid #C9DCA6; width:585px; margin:0 0 10px 0;} .table{border:1px solid #C9DCA6;} .table th{color:#000; background:#FFF;}
.popupList .l-title, .popupList .l-name, .popupList .l-name, .popupList .l-delete, .popupList .l-status, .popupList .l-num{float:left;}
.popupList .l-title span,.popupList .l-name span, .popupList .l-status span, .popupList .l-num span{display:block; padding:3px 5px 3px 5px; line-height:17px;}
.small-span{ line-height:13px !important;}
.popupList .l-delete span{display:block;  padding:3px 5px 7px 5px;}
.popupList .l-name {width:355px;}
.popupList .l-title {width:535px; padding:1px 0px 3px 0px !important;}
.popupList .l-delete {width:50px;}
.popupList .l-status {width:100px;}
.popupList .l-num {width:80px;}
.popupList .l-num input{width:50px;}
.popupList .blue{background:#EAFAFA;}
.rejected {color:red;} .pending{color:#FFD700;} .approved{color:green;} .published{background:green; color:#FFF;} .error{background:red;}
</style>
{/literal}
<script type="text/javascript">
var jsonContent = '{$jsonContent}';
var jsonCategory = '{$jsonCategory}';
var jsonSelectedCategories='{$jsonSelectedCategories}';
var jsonSiteList = '{$jsonSites}';
var disabledInput={if $arrPrj.flg_status>=$arrStatus.approved}1{else}0{/if};
{literal}
var withUrl=true;
var siteType=true;
var siteMultiboxDo = function(){
	var arrSites = JSON.decode($('jsonSiteList').value);
	var model = new projectMain();
	model.buildLink(arrSites);
};

var index=0;
var indexColor=0;
var projectMain = new Class({
	initialize: function(){
		this.initPopup();
		this.initCategory();
		this.initHands();
	},
	buildCategorySelect: function(){
		if(!$chk($('select-category'))){
			return;
		}
		var arr = new Hash(JSON.decode(jsonCategory));
		var arrSelected = JSON.decode(jsonSelectedCategories);
		if(jsonCategory == '[]'){
			var span = new Element('span').set('html','no sites shared');
			span.inject( $('select-category').getParent() );
			$('select-category').destroy();
		}
		arr.each(function( item ){
			var option = new Element('option',{'value':item.id,'html':item.title});
			if( arrSelected && arrSelected.some( function( child ){ var p= new Hash(item.node); return p.some(function( v ){  return v.id==child; }); }) ){
				option.selected=1;
			}
			option.inject($('select-category'));
		});
		this.buildCtegoriesList();
	},
	initBuildLink: function(){
		this.buildLink( JSON.decode(jsonSiteList) );
		$('jsonSiteList').value=jsonSiteList;
		$('jsonBlogList').value=jsonSiteList;
	},
	buildLink: function( arrData ){
		var place = '';
		var header = false;
		for( var i=1; i<6; i++ ){
			if( $('site_place_' + i ) && $chk(arrData) ){
				place = $('site_place_' + i );
				place.empty();
				if(!arrData[0]){
					arrData=new Hash(arrData);
				}
				arrData.each( function( value ) {
					if( i == value.flg_type ) {
						place.setStyle('display','block');
						var addClassName = '';
						if ( indexColor%2 == 0 ) { addClassName = 'blue'; }
						var data_art = new Element( 'div',{'html':'<span class="small-span"><input type="hidden" name="arrPrj[manual]['+index+'][site_id]" value="'+value.site_id+'">' + value.title.substr( 0, 100 ),'class':'l-title ' + addClassName} );
						var data_del = new Element( 'div',{'html':'<span><input type="hidden" name="arrPrj[manual]['+index+'][title]" value="'+value.title+'"><input type="hidden" name="arrPrj[manual]['+index+'][flg_type]" value="'+value.flg_type+'">'+((!disabledInput)?'<a href="#" class="delete_site" rel='+ value.site_id +'>delete</a>':'delete'),'class':'l-delete ' + addClassName} );
						data_art.inject( place );
						data_del.inject( place );
						header = true;
						indexColor++;
						index++;
					}
				},this );
				if( header ){
					var nameSite = function(type){ switch(type){
						case 4: return 'CNB'; break;
						case 3: return 'NVSB'; break;
						case 2: return 'NCSB'; break;
						case 1: return 'PSB'; break;
						case 5: return 'Blog fusion'; break;
					} };
					var header_art = new Element( 'div',{'html':'<span><b>'+nameSite(i)+'</b></span>','class':'l-title'} );
					var header_del = new Element( 'div',{'html':'<span><b>Delete</b></span>','class':'l-delete'} );
					header_del.inject( place,'top' );
					header_art.inject( place,'top' );
					indexColor=0;
					header=false;
				}
			}
		}
		this.initDeleteSite( arrData );
	},
	initHands: function(){
		$('open-backlinking').addEvent('click', function(){
			var flgDef=1;
			$('backlinking').setStyle('display', ($('open-backlinking').checked)?'block':'none' );
			$$('.radio-hands').each(function(el){
				if( el.checked ){
					flgDef=0;
				}
				if( !$('open-backlinking').checked ){
					el.checked = 0;
				}
			});
			$('default-backlinking').set('checked', ($('open-backlinking').checked)?flgDef:0);
		});
		$$('.radio-hands').each(function( el ){
			el.addEvent('click',function(){
				$('hands').setStyle('display',(el.value == 2)?'block':'none');
			});
		},this);
	},
	initPopup: function(){
		var data = JSON.decode( jsonContent );
		if( $chk( data ) ) { 
			var list = new contentList({'jsonData':data,'place':'','contentDiv':$('place-video'),'flg_type':2});
			list.set();
			var list = new contentList({'jsonData':data,'place':''});
			list.set();
		}
		$('video-content').addEvent('click', function(){
			$('video-block').setStyle('display', ( this.checked )? 'block':'none' );
		});

		$('article-content').addEvent('click', function(){
			$('article-block').setStyle('display', ( this.checked )? 'block':'none' );
		});		
	},
	initCategory: function(){
		if( !$chk($('select-category')) ){
			return;
		}
		$('select-category').addEvent('change',function(){
			this.buildCtegoriesList();
		}.bindWithEvent( this ) );
	},
	buildCtegoriesList: function(){
		if( !$('select-category') ){
			return;
		}		
		if( !$chk( $('select-category').value ) ){
			$('category-block').setStyle('display','none');
			return false;
		}		
		$('category-block').setStyle('display','block');
		var arrCategory =new Hash(JSON.decode( jsonCategory ));
		var arrSelected = JSON.decode(jsonSelectedCategories);
		var categoryPlace = $('category-place');
		categoryPlace.empty();
		arrCategory.each( function( el ){
			if ( $('select-category').value == el.id ) {
				var hash = new Hash( el.node );
				hash.each( function( item ){
					var label = new Element('label');
					var input = new Element('input',{'type':'checkbox', 'name':'arrPrj[categories][]','value':item.id, 'class':'category-item'});
					if( disabledInput == 1 ){
						input.disabled='1';
					}
					var span = new Element('span',{'html':' '+item.title + ' <b>[<span class="category-num-site" id="num_sites_'+ item.id +'">'+item.sites_num+'</span>]</b>'});
					if( arrSelected && arrSelected.some(function(v){ return v==item.id }) ){
						input.checked=1;
					}
					input.inject( label );
					span.inject( label );
					label.inject( categoryPlace );
				},this);
				this.summSite2Category();
			}
		},this);
		this.initSelcetAll();
		this.initItemCategory();
	},
	initItemCategory: function(){
		$$('.category-item').each(function( el ){
			el.addEvent('click',function(){
				this.summSite2Category();
			}.bindWithEvent(this) );
		},this);
	},
	summSite2Category: function(){
		count = 0;
		$$('.category-item').each( function( item ){
			if( $chk( item.checked ) ) {
				count += parseInt( $('num_sites_' + item.value ).get('html') );
			}
		});
		if ( $('label-num-site') ) {
			$('label-num-site').destroy();
		}
		if( count != 0 ) {
			var label = new Element('label',{'html': '<b>['+ count +'] sites</b>','id':'label-num-site'});
			label.inject( $('category-place') );
		}
	},
	initSelcetAll: function(){
		$('select-all-category').addEvent('click',function(){
			if ( $('label-num-site') ) {
				$('label-num-site').destroy();
			}			
			$$('.category-item').each( function( el ){
				el.checked = $('select-all-category').checked;
			});
			if( $chk(  $('select-all-category').checked ) ){
				var count = 0;
				$$('.category-num-site').each(function( el ){
					count += parseInt( el.get('html') );
				});
				var label = new Element('label',{'html': '<b>['+ count +'] sites</b>','id':'label-num-site'});
				label.inject( $('category-place') );		
			}
		});
	},
	initDeleteSite: function(arrSite) {
		$$( '.delete_site' ).each( function( el ) {
			el.addEvent( 'click',function( e ) {
				e && e.stop();
				if( disabledInput == 1 ){
					return;
				}
				var arr = new Array();
				var i = 0;
				arrSite.each( function( value, key ) {
					if( value.site_id != el.rel ) {
						arr[ i ] = value;
						i++;
					}
				} );
				this.buildLink(arr);
			}.bindWithEvent( this ) );
		},this );
		
	}
});

var contentIndex=1;
var contentList = new Class({
	Implements: Options,
	options: {
		jsonData:'',
		place:'',
		contentDiv:$('articleList'),
		flg_type:1
	},
	initialize: function( options ){
		this.setOptions( options );
		if( 'string' == typeof(this.options.jsonData) ){
			this._data = JSON.decode( this.options.jsonData );
		} else {
			this._data = this.options.jsonData;
		}
		this.options.contentName = ( this.options.flg_type == 1)? 'article' : 'video';
		this.formatData();
	},
	formatData: function(){
		temp = new Array();
		if(!$chk(this._data)){
			return;
		}
		if(!this._data[0]){
			this._data = new Hash(this._data);
		}
		var arrOldContent=JSON.decode(jsonContent);
		this._data.each(function( item, i ){
			var hash=new Hash(item);
			if( !hash.content_id){
				hash.include('content_id',hash.id);
				hash.id=0;
			}
			
			if( hash.content_id == 0){
				hash.content_id=hash.id;
				hash.id=0;
			}			
			if( $chk(arrOldContent) ){
				arrOldContent.each(function(old){
					if( old.content_id == hash.content_id ){
						hash.id=old.id;
						hash.include('flg_status',old.flg_status);
					}
				});
			}
			temp[i]=hash;
		});
		this._data=temp;
	},
	format2popup: function(arr){
		temp = new Array();
		if(!$chk(this._data)){
			return;
		}
		if(!arr[0]){
			arr = new Hash(arr);
		}
		arr.each(function( item, i ){
			var hash=new Hash(item);
			temp[i]={'id':hash.content_id,'title':hash.title};
		});
		return temp;		
	},
	set: function(){
		if(!$chk(this._data)){
			return;
		}		
		this.options.contentDiv.empty();
		this.options.contentDiv.setStyle('display','block');
		var index = 0;
		var header = false;
		this.url2editVideo = {/literal}'{url name="site1_video_manager" action="edit"}?id='{literal};
		this.url2editArticle = {/literal}'{url name="site1_articles" action="edit"}?id='{literal};
		this._data.each( function( value ) {
			if( value.flg_type && value.flg_type != this.options.flg_type ){return false;}
			index++ ;
			var addClassName = '';
			if ( index%2 != 0 ) { addClassName = 'blue'; }
			var num2content = JSON.decode( $('json_' + this.options.contentName).value );
			if( !num2content.some(function( v ){ if( v.id == value.id ){ this.num_sites = v.value; return true;}else {this.num_sites = false; return false; }}, this) ) {
				this.num_sites = ( value.num_sites ) ? value.num_sites : 1;
			}
			var disabled=0
			if( value.flg_status == 3 || value.flg_status == 4 || value.flg_status == 2 ){
				disabled=1;
				var data_art = new Element( 'div',{'html':'<span><input type="hidden" name="arrPrj[content]['+ contentIndex +'][id]" value="'+value.id+'"><input type="hidden" name="arrPrj[content]['+ contentIndex +'][content_id]" value="'+value.content_id+'">'+index+'.'+value.title.substr( 0, 50 ),'class':'l-name ' + addClassName} );
			} else {
				var data_art = new Element( 'div',{'html':'<span><input type="hidden" name="arrPrj[content]['+ contentIndex +'][id]" value="'+value.id+'"><input type="hidden" name="arrPrj[content]['+ contentIndex +'][content_id]" value="'+value.content_id+'">'+index+'.<a target="_blank" href=\''+((this.options.contentName == 'video' ) ? this.url2editVideo : this.url2editArticle )+value.content_id+'\'>'+value.title.substr( 0, 50 )+'</a>','class':'l-name ' + addClassName} );
			}
			var data_del = new Element( 'div',{'html':'<span><input type="hidden" name="arrPrj[content]['+ contentIndex +'][title]" value="'+value.title+'">'+( ( value.id != 0 )? '<center><input type="hidden" name="arrPrj[content]['+ contentIndex +'][del]" value="0"><input type="checkbox" '+ ( (disabled==1)?'disabled="disabled"':'' ) +' name="arrPrj[content]['+ contentIndex +'][del]" value="1"></center>' : '<a href="#" class="delete_content" rel="'+value.content_id+'">delete</a>' )+'</span>','class':'l-delete ' + addClassName} );
			var data_status = new Element( 'div',{'html':'<span class="'+this.getStatus(value.flg_status)+'"><input type="hidden" name="arrPrj[content]['+ contentIndex +'][flg_status]" value="'+( (value.flg_status)?value.flg_status:0 )+'"><input type="hidden" name="arrPrj[content]['+ contentIndex +'][flg_type]" value="'+this.options.flg_type+'">'+this.getStatus(value.flg_status)+'</span>' ,'class':'l-status ' + addClassName} );
			var data_num = new Element( 'div',{'html':'<span><input '+ ( (disabled==1)?'disabled="disabled"':'' ) +' type="text" name="arrPrj[content]['+ contentIndex +'][sites_num]" class="num-sites-'+ this.options.contentName +'" value="'+ ( (value.sites_num) ? value.sites_num : this.num_sites ) +'" id="'+ value.id +'"></span>' + (( disabled == 1 ) ? '<input type="hidden" name="arrPrj[content]['+ contentIndex +'][sites_num]" value="'+ value.sites_num +'">' : '') ,'class':'l-num ' + addClassName} );
			data_art.inject( this.options.contentDiv );
			data_del.inject( this.options.contentDiv );
			data_status.inject( this.options.contentDiv );
			data_num.inject( this.options.contentDiv );
			header = true;
			contentIndex++;
		},this );	
		if( $chk( this.options.place ) ) {
			$('multibox_ids_' + this.options.place ).value = JSON.encode( this.format2popup(this._data) );
		} else {
			placeParam = this._data;
		}
		if ( $chk( header ) ) {
			var header_art = new Element( 'div',{'html':'<span><b>'+(( this.options.contentName == 'article')?'Article':'Video')+'</b></span>','class':'l-name'} );
			var header_del = new Element( 'div',{'html':'<span><b>Delete</b></span>','class':'l-delete'} );
			var header_status = new Element( 'div',{'html':'<span><b>Status</b></span>','class':'l-status'} );
			var header_num = new Element( 'div',{'html':'<span><b>Num site</b></span>','class':'l-num'} );
			header_num.inject( this.options.contentDiv,'top' );
			header_status.inject( this.options.contentDiv,'top' );
			header_del.inject( this.options.contentDiv,'top' );
			header_art.inject( this.options.contentDiv,'top' );
		}
		this.content2site();
		if( index>0 ){
			$(this.options.contentName+'-content').set('checked', 1 );
			$(this.options.contentName+'-block').setStyle('display', 'block' );
		}		
		this.initDeleteContent();
	},
	initDeleteContent: function() {
		$$( '.delete_content' ).each( function( el ) {
			el.addEvent( 'click',function( e ) {
				e && e.stop();
				if( disabledInput == 1 ){
					return;
				}
				var arr = new Array();
				var i = 0;
				this._data.each( function( value, key ) {
					if( value.content_id != el.rel ) {
						arr[ i ] = value;
						i++;
					}
				} );
				this._data=arr;
				this.set();
			}.bindWithEvent( this ) );
		},this );
		
	},	
	getStatus: function( intFlg ){
		intFlg = parseInt(intFlg);
		if( !intFlg ){
			return 'draft';
		}
		switch(intFlg){
			case 0: return 'draft'; break;
			case 1: return 'rejected'; break;
			case 2: return 'pending review'; break;
			case 3: return 'approved'; break;
			case 4: return 'published'; break;
			case 5: return 'error'; break;
		}
	},
	content2site: function(){
		var arr = new Array();
		$$('.num-sites-' + this.options.contentName).each(function( field, key ){
			arr[ key ] = {'id': field.id, 'value':field.value};
		});
		$('json_' + this.options.contentName ).value = JSON.encode( arr );
		
		$$('.num-sites-' + this.options.contentName).each(function(el){
			el.addEvent('change',function( e ){
				var arr = new Array();
				$$('.num-sites-' + this.options.contentName).each(function( field, key ){
					arr[ key ] = {'id': field.id, 'value':field.value};
				});
				$('json_' + this.options.contentName ).value = JSON.encode( arr );
			}.bindWithEvent( this ) );
		},this );
	}
});

var check = function(e){
		var error = false;
		$$('.required').each(function(el){
			if (!el.value){
				error = true;
			}
		});	
		if( error || !$chk($('select-category')) ){
			r.alert( 'Error', 'Please fill all required fields' , 'roar_error' );
			return false;	
		}
		// проверка на выбранные категории
		if( $('select-category').value ){
			error = true;
			$$('.category-item').each(function( el ){
				if( el.checked ){ error = false; }
			});
			if( error ) {
				r.alert( 'Error', 'Please select category' , 'roar_error' );
				return false;
			}
		}
		// проверка на выбранный контент
		if( $('video-content').checked  ) {
			var videoArr =  JSON.decode( $('json_video').value  );
			if( videoArr.length < 1 ){
				r.alert( 'Error', 'Please select video' , 'roar_error' );
				error = true;
			}	
		} else if( $('article-content').checked ) {
			var articArr =  JSON.decode( $('json_article').value  );
			if( articArr.length < 1 ){
				r.alert( 'Error', 'Please select article' , 'roar_error' );
				error = true;
			}			
		} else {
			r.alert( 'Error', 'Please select source' , 'roar_error' );
			error = true;
		}
		if ( !error ) {
			return true;
		}
		e && e.stop();
}

var flgStatus = 0;
var placeParam = new Array();
var placeDo = function(){
	var list = new contentList({'jsonData':placeParam,'place':'','contentDiv':$('place-video'),'flg_type':2});
	list.set();
}
var multibox = {};
window.addEvent('domready', function(){
	var objProject = new projectMain();
	objProject.initBuildLink();
	objProject.buildCategorySelect();
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
		showControls: false,
		nobuttons: true
	});
	$$('.save').each(function(el) {el.addEvent('click',check);});
});
</script>
{/literal}