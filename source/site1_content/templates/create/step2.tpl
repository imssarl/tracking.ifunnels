<h3 class="toggler novideo" >Content rewriting</h3>
<div class="element initElement">
	<fieldset>
		<legend></legend>
		<ol>
			<li>
				<label>Use text rewriting: </label>
			<input class="required" name="arrPrj[flg_textrewrite]" type="checkbox" {if $arrPrj.flg_textrewrite=='1'}checked="checked"{/if} value="1"  />
			<a style="text-decoration:none" class="Tips" title="Use rewrite text."><b> ?</b></a>
			</li>
			<li>
				<label>Select rewriting depth: </label>
				<select class="required" name="arrPrj[selectdepth]">
					<option value="1" {if $arrPrj.selectdepth == '1'||!empty($arrPrj.selectdepth)}selected="selected"{/if}>light</option>
					<option value="2" {if $arrPrj.selectdepth == '2'}selected="selected"{/if}>moderate</option>
					<option value="3" {if $arrPrj.selectdepth == '3'}selected="selected"{/if}>high</option>
				</select>
			</li>
			<li><a href="#" class="acc_prev">Prev step</a> / <a href="#" class="acc_next">Next step</a></li>
		</ol>
	</fieldset>
</div>