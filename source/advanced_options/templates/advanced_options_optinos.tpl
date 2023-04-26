<input type="hidden" name="post_true" value="1">
<fieldset>
	<legend>Advanced Customization Options</legend>	
		<ol {if Project_Users::haveAccess( array( 'Site Profit Bot Hosted', 'NVSB Hosted' ) )} style="display:none;" {/if}>
			<li>
				<label style="width:100%;"><input type="checkbox" {if $arrOpt.dams.flg_content} checked='1' {/if} id="dams-show" value="1" />&nbsp;Do you want to add a floating, top / bottom, or corner ad?</label>
			</li>
			<li style="display:{if $arrOpt.dams.flg_content}block{else}none{/if};">
				<fieldset>
					<label><input type="radio" value="2" id="campaigns" {if $arrOpt.dams.flg_content==2} checked='1'{/if} name="arrOpt[dams][flg_content]" class="dams-selector" /> Campaigns</label>
					<label><input type="radio" value="1" id="split" {if $arrOpt.dams.flg_content==1} checked='1'{/if} name="arrOpt[dams][flg_content]" class="dams-selector" /> Split</label>
				</fieldset>
				<div class="content-block"></div>
			</li>
		</ol>
		<ol>
			<li>
				<fieldset id="parent-spot-fields">
					<legend>You can now customize the following spots.</legend>
					{foreach from=$arrSpots item=i name=spot}
					{assign var=spot value="spot{$smarty.foreach.spot.iteration}"}
					<!-- Start spot-->
					<fieldset>
						<legend>
							<input type="checkbox" {if !empty($arrOpt.spots[$spot].spot_name)} checked='1' {/if} name="arrOpt[spots][{$smarty.foreach.spot.index}][spot_name]" id="spot{$smarty.foreach.spot.index}" class="check-position" value="spot{$smarty.foreach.spot.iteration}" /> {$i.caption}
							{if $i.preview}<a href="#" onclick="" class="screenshot" rel="<img src='/skin/i/frontends/design/options/{Project_Sites::$code[$arrPrm.site_type]}_{$smarty.foreach.spot.iteration}.jpg'>" style="text-decoration:none"><b> ?</b></a>{/if}							
						</legend>
						<ol style="display:{if empty($arrOpt.spots[$spot].spot_name)}none{else}block{/if};">
							<li>
								<fieldset>
									<label><input type="radio" class="select-default" name="arrOpt[spots][{$smarty.foreach.spot.index}][flg_default]" value="0" {if $arrOpt.spots[$spot].flg_default!=1} checked=1 {/if} />&nbsp;Default Adsense ads</label>
									<label><input type="radio" class="select-default" name="arrOpt[spots][{$smarty.foreach.spot.index}][flg_default]" value="1" {if $arrOpt.spots[$spot].flg_default==1} checked=1 {/if} />&nbsp;Replace by...</label>
								</fieldset>
							</li>
							<li style="display:{if $arrOpt.spots[$spot].flg_default==1}block{else}none{/if};">
								<fieldset class="replace-border">
									<legend>Replace by</legend>
									<fieldset>
										<ol class="swap">
											<li id="li1">
												<label style="margin-left:0;"><input type="checkbox" value="{$i.id}" id="{$smarty.foreach.spot.index}::{Project_Options::ARTICLE}" class="content-selector" {if !empty($arrOpt.spots[$spot].articles)} checked='1' {/if}>&nbsp;Saved Article Selection:</label>
												<div class="change" id="1"><img src="/skin/i/frontends/design/down_arrow.gif" class="change-position" id="down" /></div>
												<img src="/skin/i/frontends/design/ajax-loader_new.gif" style="display:none;" class="loader" />
												<div class="content-block" id="spot{$smarty.foreach.spot.iteration}-article"></div>
												<input class="position" type="hidden" name="arrOpt[spots][{$smarty.foreach.spot.index}][type_order][{Project_Options::ARTICLE}]" value="{if !empty($arrOpt.spots[$spot])}{$arrOpt.spots[{$spot}].type_order[{Project_Options::ARTICLE}]}{else}1{/if}">
											</li>
											<span id="li1"></span>
											<li id="li2">
												<label style="margin-left:0;"><input type="checkbox" value="{$i.id}" id="{$smarty.foreach.spot.index}::{Project_Options::VIDEO}" class="content-selector" {if !empty($arrOpt.spots[$spot].video)} checked='1' {/if}>&nbsp;Embed Video{$site_type} ( <input type="checkbox" {if $arrOpt.spots[{$spot}].flg_title==1} checked='1' {/if} name="arrOpt[spots][{$smarty.foreach.spot.index}][flg_title]" value="1" > with title?)</label>
												<div class="change" id="2"><img src="/skin/i/frontends/design/up_arrow.gif" class="change-position" id="up" /><img id="down" src="/skin/i/frontends/design/down_arrow.gif" class="change-position" /></div>
												<img src="/skin/i/frontends/design/ajax-loader_new.gif" style="display:none;" class="loader" />
												<div class="content-block" id="spot{$smarty.foreach.spot.iteration}-video"></div>
												<input class="position" type="hidden" name="arrOpt[spots][{$smarty.foreach.spot.index}][type_order][{Project_Options::VIDEO}]" value="{if !empty($arrOpt.spots[$spot])}{$arrOpt.spots[$spot].type_order[{Project_Options::VIDEO}]}{else}2{/if}">
											</li>
											<span id="li2"></span>
											<li id="li3">
												<label style="margin-left:0;"><input type="checkbox" value="{$i.id}" id="{$smarty.foreach.spot.index}::{Project_Options::SNIPPET}" class="content-selector" {if !empty($arrOpt.spots[$spot].snippets)} checked='1' {/if}>&nbsp;Rotating ad / snippets</label>
												<div class="change" id="3"><img src="/skin/i/frontends/design/up_arrow.gif" class="change-position" id="up" /><img id="down" src="/skin/i/frontends/design/down_arrow.gif" class="change-position" /></div>
												<img src="/skin/i/frontends/design/ajax-loader_new.gif" style="display:none;" class="loader" />
												<div class="content-block" id="spot{$smarty.foreach.spot.iteration}-snippet"></div>
												<input class="position" type="hidden" name="arrOpt[spots][{$smarty.foreach.spot.index}][type_order][{Project_Options::SNIPPET}]" value="{if !empty($arrOpt.spots[$spot])}{$arrOpt.spots[$spot].type_order[{Project_Options::SNIPPET}]}{else}3{/if}">
											</li>
											<span id="li3"></span>
											<li id="li4">
												<label style="margin-left:0;"><input type="checkbox" value="{$i.id}" id="{$smarty.foreach.spot.index}::{Project_Options::CUSTOMER}" class="customer-selector" {if !empty($arrOpt.spots[$spot].customer)} checked='1' {/if}>&nbsp;Customer code</label>
												<div class="change" id="4"><img src="/skin/i/frontends/design/up_arrow.gif" class="change-position"  id="up" /></div>
												<img src="/skin/i/frontends/design/ajax-loader_new.gif" style="display:none;" class="loader" />
												<div class="content-block"  id="spot{$smarty.foreach.spot.iteration}-customer">{if !empty($arrOpt.spots[{$spot}].customer)}<textarea name="arrOpt[spots][$spot][customer]" style="width:400px; height:100px;">{$arrOpt.spots[{$spot}].customer}</textarea>{/if}</div>
												<input class="position" type="hidden" name="arrOpt[spots][{$smarty.foreach.spot.index}][type_order][{Project_Options::CUSTOMER}]" value="{if !empty($arrOpt.spots[$spot])}{$arrOpt.spots[{$spot}].type_order[{Project_Options::CUSTOMER}]}{else}4{/if}">
											</li>
											<span id="li4"></span>
										</ol>
									</fieldset>
								</fieldset>						
							</li>
						</ol>
					</fieldset>
					<!-- End spot-->
					{/foreach}
				</fieldset>							
			</li>
		</ol>			
</fieldset>


<script type="text/javascript">
var jsonOpt='{$jsonOpt}';
{literal}
var Advanced_Options=new Class({
	Implements: Options,
	options: {
		'siteType':{/literal}{$arrPrm.site_type}{literal},
		'siteId':false
	},
	initialize:function( options ){
		this.setOptions( options );
		this.initEvent();
		if( jsonOpt!='null' ){
			this.autorun();
		}
	},
	initEvent: function(){
		$$('.check-position').each(function(el){
			el.addEvent('click', function(){
				this.showDefault( el );
			}.bindWithEvent(this));
		},this);
		$$('.select-default').each(function(el){
			el.addEvent('click', function(){
				this.showTypes( el );
			}.bindWithEvent(this));
		},this);	
		$$('.content-selector').each(function(el){ 
			el.addEvent('click',function(){
				this.contentSelector(el);
			}.bindWithEvent(this));
		},this);		
		$$('.select-all').each(function(el){
			el.addEvent('click',function(e){
				this.selectAll(el);
			}.bindWithEvent(this));
		},this);		
		$$('.customer-selector').each(function(el){
			el.addEvent('click',function(){
				this.customerSelector(el);
			}.bindWithEvent(this));
		},this);		
		$$('.dams-selector').each(function(el){
			el.addEvent('click',function(){
				this.damsSelector(el,new Array());
			}.bindWithEvent(this));
		},this);
		$$('.change-position').each(function(el){
			el.addEvent('click',function(){
				this.position(el);
			}.bindWithEvent(this));
		},this);
		$('dams-show').addEvent('click', function(e){
			this.damsShow($('dams-show'));
		}.bindWithEvent(this) );
	},
	autorun: function(){
		var arrOpt=JSON.decode(jsonOpt);
		if($chk(arrOpt.dams)){
			var element=(arrOpt.dams.flg_content==1)?$('split'):$('campaigns');
			this.damsSelector(element,arrOpt.dams.ids);
		}
		if($chk(arrOpt.spots)){
		var hash= new Hash(arrOpt.spots);
		hash.each(function(item,index){
			index=parseInt(index.replace('spot',''))-1;
			if( $chk(item.articles) ){
				this.autoget(item.spot_name+'-article',1,item.articles,index);
			} 
			if ( $chk(item.video) ){
				this.autoget(item.spot_name+'-video',2,item.video,index);
			}
			if ( $chk(item.snippets) ){
				this.autoget(item.spot_name+'-snippet',3,item.snippets,index);
			}
		},this);
		this.checkPosition();
		}
	},
	autoget: function(block,type,content,index){
		var obj=this;
		if(!block){
			return;
		}
		var req = new Request({url: "{/literal}{url name='advanced_options' action='spots'}{literal}",
		onRequest: function(){ $(block).getPrevious('img.loader').setStyle('display','inline'); }, 
		onSuccess: function(r){
			$( block ).set('html',r);
			$$('.select-all').each(function(el){
				el.addEvent('click',function(e){
					obj.selectAll(el);
				}.bindWithEvent(obj));
			},obj);
		},
		onComplete: function(){  $(block).getPrevious('img.loader').setStyle('display','none');	}
		}).post({'type': type, 'site_type': obj.options.siteType,'ids':content, 'spot_index': index });
	},	
	selectAll: function(element){
		$$('.item-'+element.value ).each(function(el){
			el.checked=element.checked;
		});
	},
	showDefault: function( element ){
		var block=element.getParent('legend').getNext('ol');
		block.setStyle('display',(element.checked)?'block':'none');
	},
	showTypes: function( element ){
		var block=element.getParent('li').getNext('li');
		block.setStyle('display',(element.value==1)?'block':'none');
	},
	contentSelector: function( element ){
		var contentBlock=element.getParent('label').getNext('div.content-block');
		if(!$chk(element.checked)){
			contentBlock.empty();
			return false;
		}
		var params=element.id.split('::');
		var obj=this;
		var req = new Request({url: "{/literal}{url name='advanced_options' action='spots'}{literal}", 
			onRequest: function(){ element.getParent('label').getNext('img.loader').setStyle('display','inline'); }, 
			onSuccess: function(r){
				contentBlock.set('html',r);
				$$('.select-all').each(function(el){
					el.addEvent('click',function(e){
						obj.selectAll(el);
					}.bindWithEvent(obj));
				},obj);
			}, 
			onComplete: function(){ element.getParent('label').getNext('img.loader').setStyle('display','none');	}
		}).post({'type': params[1], 'site_type': this.options.siteType, 'spot_id': element.value, 'spot_index': params[0] });
	},
	customerSelector: function(element){
		var contentBlock=element.getParent('label').getNext('div.content-block');
		if(!$chk(element.checked)){
			contentBlock.empty();
			return false;
		}
		var params=element.id.split('::');
		var textarea = new Element('textarea',{
			'name':'arrOpt[spots]['+params[0]+'][customer]',
			'styles':{
				'width':'400px',
				'height':'100px'
			}
		});
		textarea.inject(contentBlock)
	},
	damsShow: function(element){
		element.getParent('li').getNext('li').setStyle('display',(element.checked)?'block':'none' );
	},
	damsSelector: function(element,ids){
		var contentBlock = element.getParent('fieldset').getNext('div');
		var obj=this;
		var req = new Request({url: "{/literal}{url name='advanced_options' action='ad'}{literal}", 
			onRequest: function(){ }, 
			onSuccess: function(r){
				contentBlock.set('html',r);
				$$('.select-all').each(function(el){
					el.addEvent('click',function(e){
						obj.selectAll(el);
					}.bindWithEvent(obj));
				},obj);				
			}, 
			onComplete: function(){ }
		}).post({ 'site_type': this.options.siteType, 'flg_content': element.value,'ids':ids });
	},
	checkPosition: function(){
		$$('.swap').each(function(ol){
			ol.getChildren('li').each(function(li){
				var value=li.getChildren('input.position')[0].value;
				var position=li.getChildren('div.change')[0].id;
				if( !$chk(value)){
					li.getChildren('input.position')[0].value=position;
					value=position;
				}
				if( $chk(value) && value!=position ){
					var b4ch = ol.getChildren('span#li'+position)[0].getPrevious('li');
					var b2ch = ol.getChildren('span#li'+value)[0].getPrevious('li');
					var pre4=b4ch.getNext('span#li'+position);
					var pre2=b2ch.getNext('span#li'+value);
					ol.insertBefore(b4ch,pre2);
					ol.insertBefore(b2ch,pre4);
					var ch1=b4ch.getChildren('div.change')[0];
					var ch2=b2ch.getChildren('div.change')[0];
					b4ch.getChildren('input.position')[0].set('value',ch2.id);
					b2ch.getChildren('input.position')[0].set('value',ch1.id);					
					b2ch.insertBefore(ch1,b2ch.getChildren('div.content-block')[0]);
					b4ch.insertBefore(ch2,b4ch.getChildren('div.content-block')[0]);					
				}
			},this);
		},this);
	},
	position: function(element){
		var direction=element.id;
		var b4ch=element.getParent('li');
		if(direction=='down'){
			var b2ch=b4ch.getNext('li');
			if(!$chk(b2ch)){
				return;
			}
			b4ch.getParent('ol').insertBefore(b2ch,b4ch);
		} else {
			var b2ch=b4ch.getPrevious('li');
			if(!$chk(b2ch)){
				return;
			}
			b4ch.getParent('ol').insertBefore(b4ch,b2ch);
		}
		var ch1=b4ch.getChildren('div.change')[0];
		var ch2=b2ch.getChildren('div.change')[0];
		b4ch.getChildren('input.position')[0].set('value',ch2.id);
		b2ch.getChildren('input.position')[0].set('value',ch1.id);
		b2ch.insertBefore(ch1,b2ch.getChildren('div.content-block')[0]);
		b4ch.insertBefore(ch2,b4ch.getChildren('div.content-block')[0]);
	}
	
});
window.addEvent('domready', function(){
	{/literal}
	  img_preload([
	  	{foreach from=$arrSpots  key=iKey item=aSpot} 
   			'/skin/i/frontends/design/options/{Project_Sites::$code[$arrPrm.site_type]}_{$iKey}.jpg',
   		{/foreach}
	  ]);
	{literal} 
	var optTips = new Tips('.screenshot');
	$$('.screenshot').each(function(el){ el.addEvent('click',function(e){ e.stop(); }); });	
	new Advanced_Options();
});
</script>
{/literal}
