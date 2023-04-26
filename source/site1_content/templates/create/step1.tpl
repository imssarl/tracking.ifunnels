<h3 class="toggler" >Source select</h3>
<div class="element initElement">
	<fieldset>
		<legend></legend>
		<ol>
			<li>
				<label>Select content: <em>*</em></label>
				<select class="required" id="select_content" name="arrPrj[flg_source]">
				<option value="">- select content -</option>
				{html_options options=Project_Content::toOptgroupSelect({$projectType}) selected={$arrPrj.flg_source}}
				</select>
			</li>
{module name='site1_accounts' action='externalData' modelSettings='1' selectedSource=$arrPrj.flg_source}
			{if {$projectType}== Project_Sites::BF}
			<li><ol><li class="no_source_selected" {if empty($arrPrj.flg_source)}style="display:none;"{/if}>
				<label>Post tags: </label>
			<input class="required" name="arrPrj[tags]" type="text" value="{if !empty($arrPrj.tags)}{$arrPrj.tags}{/if}"/>
			</li></ol></li>
			{/if}
			<fieldset class="no_source_selected" {if empty($arrPrj.flg_source)}style="display:none;"{/if}>
			<li>
					<fieldset>
					<legend>Content selection: <em>*</em></legend>
						<label for="manual" class="no_select_manual"><input type="radio" id="manual" name="arrPrj[flg_mode]" {if $arrPrj.flg_mode =='1'}checked="checked"{/if} value="1" class="content_select"/>&nbsp;Manual</label>
						<label for="automat" class="no_select_automat"><input type="radio" id="automat" name="arrPrj[flg_mode]" {if $arrPrj.flg_mode == '0'}checked="checked"{/if} value="0" class="content_select"/>&nbsp;Automatic</label>
					</fieldset>
			</li>
			<li {if $arrPrj.flg_mode != '1' || empty($arrPrj.flg_mode)}style="display:none;"{/if} id="content_multibox">
				<a rel="width:800,height:500" href="{url name='site1_content' action='selectcontent'}" class="smb">Select Content</a>
			<p></p>
			</li>
			</fieldset>
			<li>
				<div id="place_content" {if $arrPrj.flg_mode == '1'}style="display:none;"{/if} ></div>
				<p></p>
			</li>
			<li>
				<a href="#" class="acc_next" rel="1">Next step</a>
			</li>
		</ol>
	</fieldset>
</div>
{literal}<script type="text/javascript">

//function replace_string(txt,cut_str,paste_str) { var reg=/cut_str/g; var ht=txt.replace(reg,paste_str); return ht }

window.addEvent('domready', function() {

$$('a.smb').addEvent('click',function(anchor){ 

	var newURI = new URI(anchor.target);
	var arrquery = new Hash();
	var getSource = $$('select#select_content').get('value');
	$('content_'+getSource).getElements('input, select, textarea').each(function(el){
		arrquery.set( (el.name).replace( /arrCnt\[\d\]\[settings\]/ , 'arrFlt') , el.value);
	});
	arrquery.set('flg_source',getSource[0]);
	$$('.smb').set('href', newURI.setData(arrquery).toString());
});

// changer для выбора типа контента
$('select_content').addEvent('change',function(event){

	jsonContentIds.empty();
	$( 'jsonContentIds' ).value = '';
	jsonContentList = '';
	placeParam.empty();
	$('place_content').empty();

	$$('li.option_content').setStyle('display','none');
	if (this.value) {
		$('content_'+this.value).setStyle('display','');
		
		//default
		$$('.no_select_automat').setStyle('display','');
		$$('.no_select_manual').setStyle('display','');
		$$('.no_source_selected').setStyle('display','');
		$$('h3.novideo').setStyle('display','');
		$$('.nonet').setStyle('display','');
		$$('a.smb').set('html', 'Select content ');
						
						$('automat').erase('checked');
						$('manual').erase('checked');
		
		switch (this.value) {
		
			case '1'://articles
			break		
			
			case '2'://videos
				$$('h3.novideo').setStyle('display','none');
				$$('a.smb').set('html', 'Upload content ');
				$$('.no_select_manual').setStyle('display','');
			break

			case '3'://keywords
				$$('a.smb').set('html' , 'Get keywords from Keyword Research');
				$('manual').set('checked','true');
				$$('.no_select_automat').setStyle('display','none');
				$('content_multibox').setStyle('display','none');
			break

			case '6'://rss
				$$('.no_select_manual').setStyle('display','none');
				$('automat').set('checked','true');
				$('content_multibox').setStyle('display','none');
				$$('.nonet').setStyle('display','none');
			break

		}

	} else {
		$$('a.smb').set('html', 'Select content ');
		$$('.no_source_selected').setStyle('display','none');
	}
});
// changer для выбора content selection
$$('.content_select').addEvent('click',function(event){
	if (this.value == 1)	{
		$('content_multibox').setStyle('display','');
		$$('.contents_manual').setStyle('display','');
		$$('.contents_automat').setStyle('display','none');
		$$('.nonet').setStyle('display','');
	}	else	{
		$('content_multibox').setStyle('display','none');
		$$('.contents_manual').setStyle('display','none');
		$$('.contents_automat').setStyle('display','');
		$$('.nonet').setStyle('display','none');
	}
});

});
</script>{/literal}