	<div>
	<fieldset>
				<br/>
				<!--start 1-->
				<h3>&nbsp;&nbsp;1) Header graphics</h3>
				<li>
					<label>Upload Header [960 X 180 px]</label><input type="file" name="header" />
				</li>
				<!--end 1-->
				<!--start 2-->
				<h3>&nbsp;&nbsp;2) Below header and navigation bar</h3>
				<li>
					<fieldset>
						<legend></legend>
						<ol>
							<li>
								<label><input type="radio" name="arrBlog[proprietary][bar]" {if $arrBlog.proprietary.bar == 'upload_banner'}checked='1'{/if} class="header_bar"  value="upload_banner" />Upload banner</label>
							</li>
							<li>
								<label><input type="radio" name="arrBlog[proprietary][bar]" {if $arrBlog.proprietary.bar == 'code'}checked='1'{/if} class="header_bar" value="code" />Code snippet</label>
							</li>
							<li>
								<label><input type="radio" name="arrBlog[proprietary][bar]" {if $arrBlog.proprietary.bar == 'adsense_code'}checked='1'{/if} class="header_bar" value="adsense_code" />Adsense code</label>
							</li>	
						</ol>
					</fieldset>
				</li>
				<div id="upload_banner" class="header_bar_block" style="display:{if $arrBlog.proprietary.bar == 'upload_banner'}block{else}none{/if};">
				<li>
					<label>Upload Banner <em>*</em></label><input type="file" name="banner" class="propRequired" />
				</li>
				<li>	
					<label>Hyperlink URL <em>*</em></label><textarea name="arrBlog[proprietary][url]" class="propRequired" style="height:45px;" >{$arrBlog.proprietary.url}</textarea>
				</li>
				</div>
					
				<div id="code" class="header_bar_block"  style="display:{if $arrBlog.proprietary.bar == 'code'}block{else}none{/if};">
					<li>
						<label>Code <em>*</em></label><textarea name="arrBlog[proprietary][code]" class="propRequired" style="height:100px;">{$arrBlog.proprietary.code}</textarea>
					</li>
				</div>
				
				<div id="adsense_code" class="header_bar_block"  style="display:{if $arrBlog.proprietary.bar == 'adsense_code'}block{else}none{/if};">
					<li>
						<label>Adsense ID <em>*</em></label> <b>pub-</b><input name="arrBlog[proprietary][adsense]" class="propRequired" value="{$arrBlog.proprietary.adsense}" type="text" />
						<p>For your Adsense ads to be displayed properly, you need to provide your Google Adsense ID (not include pub-) </p>
					</li>
				</div>
				<!--end 2-->
				
				<!--start 3-->
				<h3>&nbsp;&nbsp;3) Links </h3>
				<li>
					<label>Links to External site</label><textarea name="arrBlog[proprietary][links]" style="height:100px;" >{$arrBlog.proprietary.links}</textarea>
					<p>Please enter here links with anchor tag like
					{assign var = links value="<a href='http://www.xyz.com'> My another site</a>"}{$links|escape},
					If you want to open this link in new window then use "target='_blank'" in anchor tag.
					If you are creating more than one link, Separate Each Link with (,) comma</p>
				</li>
				<!--end 3-->
				
				<!--start 4-->
				<h3>&nbsp;&nbsp;4) Configure sidebar </h3>
				
				<li>
					<input type="hidden" class="initShuf" name="arrBlog[proprietary][place][]" value="{if isset($arrBlog.proprietary.place)}{$arrBlog.proprietary.place[0]}{else}affiliate{/if}"   id="affilate_place">
 					<p style="margin:0;"><a href="#" class="shuffel" rel="1"><img src="/skin/i/frontends/design/down_arrow.gif" border="0"></a></p>
					<div  id="affiliate" class="shuffCont"><label>Affilated Programs</label><textarea  id="affilated_programs" name="arrBlog[proprietary][affiliate]" style="height:100px;">{$arrBlog.proprietary.affiliate}</textarea></div>
				</li>
				<li>
				<input type="hidden" class="initShuf" name="arrBlog[proprietary][place][]" value="{if isset($arrBlog.proprietary.place)}{$arrBlog.proprietary.place[1]}{else}subscription{/if}" id="subscription_place" >
					<p style="margin:0;"><a href="#" class="shuffel" rel="2"><img src="/skin/i/frontends/design/up_arrow.gif"  border="0"></a><br/>
						<a href="#" class="shuffel" rel="1"><img border="0" src="/skin/i/frontends/design/down_arrow.gif"></a></p>
					<div id="subscription" class="shuffCont"><label>Subscription Form</label><textarea id="subscription_form" name="arrBlog[proprietary][subscription]" style="height:100px;">{$arrBlog.proprietary.subscription}</textarea></div>
				</li>
				<li>
				<input type="hidden" class="initShuf" name="arrBlog[proprietary][place][]" value="{if isset($arrBlog.proprietary.place)}{$arrBlog.proprietary.place[2]}{else}adsense_sky{/if}"  id="adsense_sky_place">
					<p style="margin:0;"><a href="#" class="shuffel" rel="3"><img src="/skin/i/frontends/design/up_arrow.gif"  border="0"></a></p>
					<div  id="adsense_sky" class="shuffCont"><label>Adsense Skycraper</label><textarea  id="adsense_skycraper" name="arrBlog[proprietary][adsense_sky]" style="height:100px;">{$arrBlog.proprietary.adsense_sky}</textarea></div>
				</li>
				<li><a href="#" class="acc_prev">Prev step</a> / <a href="#" class="acc_next">Next step</a></li>
				<!--end 4-->
		</fieldset>
	</div>	