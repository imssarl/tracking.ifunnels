<h3 class="toggler nonet" >Networking</h3>
<div class="element initElement">
	<fieldset>
		<legend></legend>
			<ol>
				<li>
					<label for="master-blog">Master Site Links</label>
					<input type="checkbox" name="arrPrj[flg_mastersite]"{if $arrPrj.flg_status==1} disabled="disabled"{/if}  {if $arrPrj.flg_mastersite =='1'}checked="checked"{/if} value="1" id="master-blog"/>
				</li>
				<li style="display:{if $arrPrj.flg_mastersite == 1}block{else}none{/if};" id="select-master-blog">
					<label></label>
					<select id="master-blog-list"{if $arrPrj.flg_status==1} disabled="disabled"{/if} name="arrPrj[mastersite_id]"><option></option></select>
				</li>
				<li>
					<label for="circular_links">Circular Links</label>
					<input id="circular_links" type="checkbox"{if $arrPrj.flg_status==1} disabled="disabled"{/if} value="1"  {if $arrPrj.flg_circular =='1'}checked="checked"{/if} name="arrPrj[flg_circular]"/>
				</li>
				<li>
					<a href="#" class="acc_prev" >Prev</a>
				</li>				
			</ol>
	</fieldset>
</div>