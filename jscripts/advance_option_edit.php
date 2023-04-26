<fieldset>
			<legend>Advanced Customization Options</legend>	
			
				<table border="0" width="100%" cellpadding="4" cellspacing="0">
					
					<tr><td>
						<input type="checkbox" name="damscode_spot1" id="damscode_spot1" value="yes" onchange="show_div('damscode','spot1');"/>
						Do you want to add a floating, top / bottom, or corner ad?<br/><br/>
						<textarea id="dmascodetext" name="dmascodetext" rows="10" cols="40" style="display:none;"></textarea>
						<div id="damscodes_spot1" style="display:none;float:left;">
						
						<select name="headlines_spot1" id="headlines_spot1" onchange="get_headlines_spot1(this.form);">
							<option value="">--Select--</option>
							<option value="manage">Campaigns</option>
							<option value="split">Split Tests</option>
						</select><img id="headline_processing_spot1" src='./images/ajax-loader_new.gif' alt='processing'/><br/><br/>
						
						<span id="get_headline_spot1">&nbsp;</span>
						</div>
						</td></tr>
						<tr>
							<td height="2"></td>
						</tr>
						<tr>
							<td align="left">
							You can now customize the following spots.<br/><br/>
								<input type="checkbox" name="spot1" id="spot1" value="yes" onchange="show_spotdiv('spot1');"/> 
								Spot1 (appers just below the &quot;Navigation&quot; header)&nbsp;<a href="<?php echo SERVER_PATH;?>/images/spot_1.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>/images/spot_1.jpg" style="text-decoration:none"><b>?</b></a><br/>
							<!-- Start spot1-->
								<div id="div_spot1" style="display:none;margin-left:20px;"><fieldset><legend>Spot1</legend>
								<input type="radio" checked="checked" name="spot1_choice" id="spot1_default" value="default" onchange="show_default('spot1');"/>Default Adsense ads<br/>
								<input type="radio" name="spot1_choice" id="spot1_customze" value="customze" onchange="show_replaceby('spot1');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot1" style="display:none;">
								<fieldset><legend>Replace by</legend>								
								<!-- Start spot1 option1- conent wizard save selection listing-->
								<div id="one_spot1">
								<textarea id="txtsaveselection_spot1" name="txtsaveselection_spot1" rows="10" cols="50" style="display:none;"></textarea>
									
										
											<input type="checkbox" name="chkcontents_spot1" id="chkcontents_spot1" value="content" onchange="get_saveselection(this.form,'spot1');"/>Saved Selection:&nbsp;<img id="save_processing_spot1" src='./images/ajax-loader_new.gif' alt='processing'/>
																		
									
										<div id="get_saveselections_spot1" style="display: inline;"></div>
									
									<br/><br/>
								</div>
								<!-- End spot1 option1- conent wizard save selection listing-->
								
								<div><a href="javascript:shuffel('1','spot1')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot1 option2- Rotating ad / snippets:-->
								<div id="two_spot1">
								<textarea id="snippetscodetext_spot1" name="snippetscodetext_spot1" rows="10" cols="50" style="display:none;"></textarea>
									
											<input type="checkbox" name="chksnippets_spot1" id="chksnippets_spot1" value="snippets" onchange="get_snippets(this.form,'spot1');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot1" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
								
									<span id="get_snippets_spot1"></span><br/>
								
								</div>
								<!-- End spot1 option2-->
								
								<div><a href="javascript:shuffel('2','spot1')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot1')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot1 option3- Customer code: -->
								<div id="three_spot1">
								<input type="checkbox" name="chkcustomer_code_spot1" id="chkcustomer_code_spot1" value="yes" onchange="show_div('chkcustomer_code','spot1');"/>Customer code <br/>
									<div id="div_customercode_spot1" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot1" name="customercode_spot1" rows="10" cols="90" ></textarea>
									</div>
								</div>								
								<!-- End spot1 option3-->
								<div><a href="javascript:shuffel('4','spot1')"><img src="./images/up_arrow.gif" border="0"></a></div>								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot1-->
								
								
								<!-- Start spot2-->
								<input type="checkbox" name="spot2" id="spot2" value="yes" onchange="show_spotdiv('spot2');"/> 
								Spot2 (appers on the left side, below list of articles)&nbsp;<a href="<?php echo SERVER_PATH;?>images/spot_2.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>images/spot_2.jpg" style="text-decoration:none"><b>?</b></a><br/>
								
								<div id="div_spot2" style="display:none;margin-left:20px;"><fieldset><legend>Spot2</legend>
								<input type="radio" checked="checked" name="spot2_choice" id="spot2_default" value="default" onchange="show_default('spot2');"/>Default Adsense ads<br/>
								<input type="radio" name="spot2_choice" id="spot2_customze" value="customze" onchange="show_replaceby('spot2');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot2" style="display:none;">
								<fieldset><legend>Replace by</legend>
								
								<!-- Start spot2 option1- conent wizard save selection listing-->
								<div id="one_spot2">
								<textarea id="txtsaveselection_spot2" name="txtsaveselection_spot2" rows="10" cols="50" style="display:none;"></textarea>
									
										
											<input type="checkbox" name="chkcontents_spot2" id="chkcontents_spot2" value="content" onchange="get_saveselection(this.form,'spot2');"/>Saved Selection:&nbsp;<img id="save_processing_spot2" src='./images/ajax-loader_new.gif' alt='processing'/>
										
									
									
										<div id="get_saveselections_spot2" style="display: inline;"></div>
									
									<br/><br/>
								</div>
								<!-- End spot2 option1- conent wizard save selection listing-->
								
								<div><a href="javascript:shuffel('1','spot2')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot2 option2- Rotating ad / snippets:-->
								<div id="two_spot2">
					<textarea id="snippetscodetext_spot2" name="snippetscodetext_spot2" rows="10" cols="50" style="display:none;"></textarea>
									
											<input type="checkbox" name="chksnippets_spot2" id="chksnippets_spot2" value="snippets" onchange="get_snippets(this.form,'spot2');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot2" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
									
								
									<span id="get_snippets_spot2"></span><br/>
								
								</div>
								<!-- End spot2 option2-->
								
								<div><a href="javascript:shuffel('2','spot2')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot2')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot2 option3- Customer code: -->
								<div id="three_spot2">
								<input type="checkbox" name="chkcustomer_code_spot2" id="chkcustomer_code_spot2" value="yes" onchange="show_div('chkcustomer_code','spot2');"/>Customer code <br/>
									<div id="div_customercode_spot2" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot2" name="customercode_spot2" rows="10" cols="90"></textarea>
									</div>
								</div>								
								<!-- End spot2 option3-->
								<div><a href="javascript:shuffel('4','spot2')"><img src="./images/up_arrow.gif" border="0"></a></div>
								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot2-->
								
								<!-- Start spot3-->
								<input type="checkbox" name="spot3" id="spot3" value="yes" onchange="show_spotdiv('spot3');"/>  
															  Spot3 (appears  on the left side, below Best product output)&nbsp;<a href="<?php echo SERVER_PATH;?>images/spot_3.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>images/spot_3.jpg" style="text-decoration:none"><b>?</b></a><br/>
								
								
								<div id="div_spot3" style="display:none;margin-left:20px;"><fieldset><legend>Spot3</legend>
								<input type="radio" checked="checked" name="spot3_choice" id="spot3_default" value="default" onchange="show_default('spot3');"/>Default Adsense ads<br/>
								<input type="radio" name="spot3_choice" id="spot3_customze" value="customze" onchange="show_replaceby('spot3');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot3" style="display:none;">
								<fieldset><legend>Replace by</legend>
								
								<!-- Start spot3 option1- conent wizard save selection listing-->
								<div id="one_spot3">
								<textarea id="txtsaveselection_spot3" name="txtsaveselection_spot3" rows="10" cols="50" style="display:none;"></textarea>
									
										
											<input type="checkbox" name="chkcontents_spot3" id="chkcontents_spot3" value="content" onchange="get_saveselection(this.form,'spot3');"/>Saved Selection:&nbsp;<img id="save_processing_spot3" src='./images/ajax-loader_new.gif' alt='processing'/>
										
									
									
										<div id="get_saveselections_spot3" style="display: inline;"></div>
									
									<br/><br/>
								</div>
								<!-- End spot3 option1- conent wizard save selection listing-->
								
								<div><a href="javascript:shuffel('1','spot3')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot3 option2- Rotating ad / snippets:-->
								<div id="two_spot3">
								<textarea id="snippetscodetext_spot3" name="snippetscodetext_spot3" rows="10" cols="50" style="display:none;"></textarea>
									
											<input type="checkbox" name="chksnippets_spot3" id="chksnippets_spot3" value="snippets" onchange="get_snippets(this.form,'spot3');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot3" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
									
									<span id="get_snippets_spot3"></span><br/>
								
								</div>
								<!-- End spot3 option2-->
								
								<div><a href="javascript:shuffel('2','spot3')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot3')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot3 option3- Customer code: -->
								<div id="three_spot3">
								<input type="checkbox" name="chkcustomer_code_spot3" id="chkcustomer_code_spot3" value="yes" onchange="show_div('chkcustomer_code','spot3');"/>Customer code<br/>
									<div id="div_customercode_spot3" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot3" name="customercode_spot3" rows="10" cols="90"></textarea>
									</div>
								</div>								
								<!-- End spot3 option3-->
								<div><a href="javascript:shuffel('4','spot3')"><img src="./images/up_arrow.gif" border="0"></a></div>
								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot3-->
								
								<!-- Start spot5-->
								<input type="checkbox" name="spot5" id="spot5" value="yes" onchange="show_spotdiv('spot5');"/>							  
								Spot4 (spots of metatag content)&nbsp;<a href="<?php echo SERVER_PATH;?>images/spot_3.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>images/spot_3.jpg" style="text-decoration:none"><b>?</b></a><br/>
								
								
								<div id="div_spot5" style="display:none;margin-left:20px;"><fieldset><legend>Spot4</legend>
								<input type="radio" checked="checked" name="spot5_choice" id="spot5_default" value="default" onchange="show_default('spot5');"/>Default Adsense ads<br/>
								<input type="radio" name="spot5_choice" id="spot5_customze" value="customze" onchange="show_replaceby('spot5');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot5" style="display:none;">
								<fieldset><legend>Replace by</legend>
								
								<!-- Start spot5 option1- conent wizard save selection listing-->
							<!--	<div id="one_spot5">
								<textarea id="txtsaveselection_spot5" name="txtsaveselection_spot5" rows="10" cols="50" style="display:none;"></textarea>
									
										<form name="saveselection_spot5" id="saveselection_spot5">
											<input type="checkbox" name="chkcontents_spot5" id="chkcontents_spot5" value="content" onchange="get_saveselection(this.parentNode,'spot5');"/>Saved Selection:&nbsp;<img id="save_processing_spot5" src='./images/ajax-loader_new.gif' alt='processing'/>
										</form><br/><br/>
									
									<form name="frmsaveselection_spot5">
										<div id="get_saveselections_spot5" style="display: inline;"></div>
									</form>
									<br/><br/>
								</div>-->
								<!-- End spot5 option1- conent wizard save selection listing-->
								
								<!--<div><a href="javascript:shuffel('1','spot5')"><img src="./images/down_arrow.gif" border="0"></a></div>-->
								
								<!-- Start spot5 option2- Rotating ad / snippets:-->
								<div id="two_spot5">
								<textarea id="snippetscodetext_spot5" name="snippetscodetext_spot5" rows="10" cols="50" style="display:none;"></textarea>
									
											<input type="checkbox" name="chksnippets_spot5" id="chksnippets_spot5" value="snippets" onchange="get_snippets(this.form,'spot5');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot5" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
									
									<span id="get_snippets_spot5"></span><br/>
								
								</div>
								<!-- End spot5 option2-->
								
								<div><a href="javascript:shuffel('2','spot5')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot5')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot5 option3- Customer code: -->
								<div id="three_spot5">
								<input type="checkbox" name="chkcustomer_code_spot5" id="chkcustomer_code_spot5" value="yes" onchange="show_div('chkcustomer_code','spot5');"/>Customer code<br/>
									<div id="div_customercode_spot5" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot5" name="customercode_spot5" rows="10" cols="90"></textarea>
									</div>
								</div>								
								<!-- End spot5 option3-->
								<!--<div><a href="javascript:shuffel('4','spot5')"><img src="./images/up_arrow.gif" border="0"></a></div>-->
								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot5-->
								
								
								<!-- Start spot6-->
								<input type="checkbox" name="spot6" id="spot6" value="yes" onchange="show_spotdiv('spot6');"/>							  
								Spot5 (spots of metatag content)&nbsp;<a href="<?php echo SERVER_PATH;?>images/spot_3.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>images/spot_3.jpg" style="text-decoration:none"><b>?</b></a><br/>
								
								
								<div id="div_spot6" style="display:none;margin-left:20px;"><fieldset><legend>Spot5</legend>
								<input type="radio" checked="checked" name="spot6_choice" id="spot6_default" value="default" onchange="show_default('spot6');"/>Default Adsense ads<br/>
								<input type="radio" name="spot6_choice" id="spot6_customze" value="customze" onchange="show_replaceby('spot6');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot6" style="display:none;">
								<fieldset><legend>Replace by</legend>
								
								<!-- Start spot6 option1- conent wizard save selection listing-->
								<!--<div id="one_spot6">
								<textarea id="txtsaveselection_spot6" name="txtsaveselection_spot6" rows="10" cols="50" style="display:none;"></textarea>
									
										<form name="saveselection_spot6" id="saveselection_spot6">
											<input type="checkbox" name="chkcontents_spot6" id="chkcontents_spot6" value="content" onchange="get_saveselection(this.parentNode,'spot6');"/>Saved Selection:&nbsp;<img id="save_processing_spot6" src='./images/ajax-loader_new.gif' alt='processing'/>
										</form><br/><br/>
									
									<form name="frmsaveselection_spot6">
										<div id="get_saveselections_spot6" style="display: inline;"></div>
									</form>
									<br/><br/>
								</div>-->
								<!-- End spot6 option1- conent wizard save selection listing-->
								
								<!--<div><a href="javascript:shuffel('1','spot6')"><img src="./images/down_arrow.gif" border="0"></a></div>-->
								
								<!-- Start spot6 option2- Rotating ad / snippets:-->
								<div id="two_spot6">
								<textarea id="snippetscodetext_spot6" name="snippetscodetext_spot6" rows="10" cols="50" style="display:none;"></textarea>
									
											<input type="checkbox" name="chksnippets_spot6" id="chksnippets_spot6" value="snippets" onchange="get_snippets(this.form,'spot6');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot6" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
									
									<span id="get_snippets_spot6"></span><br/>
								
								</div>
								<!-- End spot6 option2-->
								
								<div><a href="javascript:shuffel('2','spot6')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot6')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot6 option3- Customer code: -->
								<div id="three_spot6">
								<input type="checkbox" name="chkcustomer_code_spot6" id="chkcustomer_code_spot6" value="yes" onchange="show_div('chkcustomer_code','spot6');"/>Customer code<br/>
									<div id="div_customercode_spot6" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot6" name="customercode_spot6" rows="10" cols="90"></textarea>
									</div>
								</div>								
								<!-- End spot6 option3-->
								<!--<div><a href="javascript:shuffel('4','spot6')"><img src="./images/up_arrow.gif" border="0"></a></div>-->
								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot6-->
								
								
								
								<!-- Start spot7-->
								<input type="checkbox" name="spot7" id="spot7" value="yes" onchange="show_spotdiv('spot7');"/>							  
								Spot6 (leaderboard spot below main header graphics)&nbsp;<a href="<?php echo SERVER_PATH;?>images/spot_3.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>images/spot_3.jpg" style="text-decoration:none"><b>?</b></a><br/>
								
								
								<div id="div_spot7" style="display:none;margin-left:20px;"><fieldset><legend>Spot6</legend>
								<input type="radio" checked="checked" name="spot7_choice" id="spot7_default" value="default" onchange="show_default('spot7');"/>Default Adsense ads<br/>
								<input type="radio" name="spot7_choice" id="spot7_customze" value="customze" onchange="show_replaceby('spot7');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot7" style="display:none;">
								<fieldset><legend>Replace by</legend>
								
								<!-- Start spot7 option1- conent wizard save selection listing-->
								<div id="one_spot7">
								<textarea id="txtsaveselection_spot7" name="txtsaveselection_spot7" rows="10" cols="50" style="display:none;"></textarea>
									
										
											<input type="checkbox" name="chkcontents_spot7" id="chkcontents_spot7" value="content" onchange="get_saveselection(this.form,'spot7');"/>Saved Selection:&nbsp;<img id="save_processing_spot7" src='./images/ajax-loader_new.gif' alt='processing'/>
										
										<div id="get_saveselections_spot7" style="display: inline;"></div>
									
									<br/><br/>
								</div>
								<!-- End spot7 option1- conent wizard save selection listing-->
								
								<div><a href="javascript:shuffel('1','spot7')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot7 option2- Rotating ad / snippets:-->
								<div id="two_spot7">
								<textarea id="snippetscodetext_spot7" name="snippetscodetext_spot7" rows="10" cols="50" style="display:none;"></textarea>
									
											<input type="checkbox" name="chksnippets_spot7" id="chksnippets_spot7" value="snippets" onchange="get_snippets(this.form,'spot7');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot7" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
								
									<span id="get_snippets_spot7"></span><br/>
							
								</div>
								<!-- End spot7 option2-->
								
								<div><a href="javascript:shuffel('2','spot7')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot7')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot7 option3- Customer code: -->
								<div id="three_spot7">
								<input type="checkbox" name="chkcustomer_code_spot7" id="chkcustomer_code_spot7" value="yes" onchange="show_div('chkcustomer_code','spot7');"/>Customer code<br/>
									<div id="div_customercode_spot7" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot7" name="customercode_spot7" rows="10" cols="90"></textarea>
									</div>
								</div>								
								<!-- End spot7 option3-->
								<div><a href="javascript:shuffel('4','spot7')"><img src="./images/up_arrow.gif" border="0"></a></div>
								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot7-->
								
								
								<!-- Start spot8-->
								<input type="checkbox" name="spot8" id="spot8" value="yes" onchange="show_spotdiv('spot8');"/>							  
								Spot7 (square spot within the main body, above all content)&nbsp;<a href="<?php echo SERVER_PATH;?>images/spot_3.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>images/spot_3.jpg" style="text-decoration:none"><b>?</b></a><br/>
								
								
								<div id="div_spot8" style="display:none;margin-left:20px;"><fieldset><legend>Spot7</legend>
								<input type="radio" checked="checked" name="spot8_choice" id="spot8_default" value="default" onchange="show_default('spot8');"/>Default Adsense ads<br/>
								<input type="radio" name="spot8_choice" id="spot8_customze" value="customze" onchange="show_replaceby('spot8');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot8" style="display:none;">
								<fieldset><legend>Replace by</legend>
								
								<!-- Start spot8 option1- conent wizard save selection listing-->
								<div id="one_spot8">
								<textarea id="txtsaveselection_spot8" name="txtsaveselection_spot8" rows="10" cols="50" style="display:none;"></textarea>
									
										
											<input type="checkbox" name="chkcontents_spot8" id="chkcontents_spot8" value="content" onchange="get_saveselection(this.form,'spot8');"/>Saved Selection:&nbsp;<img id="save_processing_spot8" src='./images/ajax-loader_new.gif' alt='processing'/>
										
									
									
										<div id="get_saveselections_spot8" style="display: inline;"></div>
									
									<br/><br/>
								</div>
								<!-- End spot8 option1- conent wizard save selection listing-->
								
								<div><a href="javascript:shuffel('1','spot8')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot8 option2- Rotating ad / snippets:-->
								<div id="two_spot8">
								<textarea id="snippetscodetext_spot8" name="snippetscodetext_spot8" rows="10" cols="50" style="display:none;"></textarea>
								
											<input type="checkbox" name="chksnippets_spot8" id="chksnippets_spot8" value="snippets" onchange="get_snippets(this.form,'spot8');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot8" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
									
									<span id="get_snippets_spot8"></span><br/>
								
								</div>
								<!-- End spot8 option2-->
								
								<div><a href="javascript:shuffel('2','spot8')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot8')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot8 option3- Customer code: -->
								<div id="three_spot8">
								<input type="checkbox" name="chkcustomer_code_spot8" id="chkcustomer_code_spot8" value="yes" onchange="show_div('chkcustomer_code','spot8');"/>Customer code<br/>
									<div id="div_customercode_spot8" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot8" name="customercode_spot8" rows="10" cols="90"></textarea>
									</div>
								</div>								
								<!-- End spot8 option3-->
								<div><a href="javascript:shuffel('4','spot8')"><img src="./images/up_arrow.gif" border="0"></a></div>
								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot8-->
								
								
								<!-- Start spot9-->
								<input type="checkbox" name="spot9" id="spot9" value="yes" onchange="show_spotdiv('spot9');"/>							  
								Spot8 (appears on the right side)&nbsp;<a href="<?php echo SERVER_PATH;?>images/spot_3.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>images/spot_3.jpg" style="text-decoration:none"><b>?</b></a><br/>
								
								
								<div id="div_spot9" style="display:none;margin-left:20px;"><fieldset><legend>Spot8</legend>
								<input type="radio" checked="checked" name="spot9_choice" id="spot9_default" value="default" onchange="show_default('spot9');"/>Default Adsense ads<br/>
								<input type="radio" name="spot9_choice" id="spot9_customze" value="customze" onchange="show_replaceby('spot9');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot9" style="display:none;">
								<fieldset><legend>Replace by</legend>
								
								<!-- Start spot9 option1- conent wizard save selection listing-->
								<div id="one_spot9">
								<textarea id="txtsaveselection_spot9" name="txtsaveselection_spot9" rows="10" cols="50" style="display:none;"></textarea>
									
										
											<input type="checkbox" name="chkcontents_spot9" id="chkcontents_spot9" value="content" onchange="get_saveselection(this.form,'spot9');"/>Saved Selection:&nbsp;<img id="save_processing_spot9" src='./images/ajax-loader_new.gif' alt='processing'/>
									
										<div id="get_saveselections_spot9" style="display: inline;"></div>
									
									<br/><br/>
								</div>
								<!-- End spot9 option1- conent wizard save selection listing-->
								
								<div><a href="javascript:shuffel('1','spot9')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot9 option2- Rotating ad / snippets:-->
								<div id="two_spot9">
								<textarea id="snippetscodetext_spot9" name="snippetscodetext_spot9" rows="10" cols="50" style="display:none;"></textarea>
									
											<input type="checkbox" name="chksnippets_spot9" id="chksnippets_spot9" value="snippets" onchange="get_snippets(this.form,'spot9');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot9" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
									
									<span id="get_snippets_spot9"></span><br/>
								
								</div>
								<!-- End spot9 option2-->
								
								<div><a href="javascript:shuffel('2','spot9')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot9')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot9 option3- Customer code: -->
								<div id="three_spot9">
								<input type="checkbox" name="chkcustomer_code_spot9" id="chkcustomer_code_spot9" value="yes" onchange="show_div('chkcustomer_code','spot9');"/>Customer code<br/>
									<div id="div_customercode_spot9" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot9" name="customercode_spot9" rows="10" cols="90"></textarea>
									</div>
								</div>								
								<!-- End spot9 option3-->
								<div><a href="javascript:shuffel('4','spot9')"><img src="./images/up_arrow.gif" border="0"></a></div>
								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot9-->
								
								
								
								<!-- Start spot10-->
								<input type="checkbox" name="spot10" id="spot10" value="yes" onchange="show_spotdiv('spot10');"/>							  
								Spot9 (appears on the right side)&nbsp;<a href="<?php echo SERVER_PATH;?>images/spot_3.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>images/spot_3.jpg" style="text-decoration:none"><b>?</b></a><br/>
								
								
								<div id="div_spot10" style="display:none;margin-left:20px;"><fieldset><legend>Spot9</legend>
								<input type="radio" checked="checked" name="spot10_choice" id="spot10_default" value="default" onchange="show_default('spot10');"/>Default Adsense ads<br/>
								<input type="radio" name="spot10_choice" id="spot10_customze" value="customze" onchange="show_replaceby('spot10');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot10" style="display:none;">
								<fieldset><legend>Replace by</legend>
								
								<!-- Start spot10 option1- conent wizard save selection listing-->
								<div id="one_spot10">
								<textarea id="txtsaveselection_spot10" name="txtsaveselection_spot10" rows="10" cols="50" style="display:none;"></textarea>
									
									
											<input type="checkbox" name="chkcontents_spot10" id="chkcontents_spot10" value="content" onchange="get_saveselection(this.form,'spot10');"/>Saved Selection:&nbsp;<img id="save_processing_spot10" src='./images/ajax-loader_new.gif' alt='processing'/>
										
										<div id="get_saveselections_spot10" style="display: inline;"></div>
									
									<br/><br/>
								</div>
								<!-- End spot10 option1- conent wizard save selection listing-->
								
								<div><a href="javascript:shuffel('1','spot10')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot10 option2- Rotating ad / snippets:-->
								<div id="two_spot10">
								<textarea id="snippetscodetext_spot10" name="snippetscodetext_spot10" rows="10" cols="50" style="display:none;"></textarea>
									
											<input type="checkbox" name="chksnippets_spot10" id="chksnippets_spot10" value="snippets" onchange="get_snippets(this.form,'spot10');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot10" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
								
									<span id="get_snippets_spot10"></span><br/>
								
								</div>
								<!-- End spot10 option2-->
								
								<div><a href="javascript:shuffel('2','spot10')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot10')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot10 option3- Customer code: -->
								<div id="three_spot10">
								<input type="checkbox" name="chkcustomer_code_spot10" id="chkcustomer_code_spot10" value="yes" onchange="show_div('chkcustomer_code','spot10');"/>Customer code<br/>
									<div id="div_customercode_spot10" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot10" name="customercode_spot10" rows="10" cols="90"></textarea>
									</div>
								</div>								
								<!-- End spot10 option3-->
								<div><a href="javascript:shuffel('4','spot10')"><img src="./images/up_arrow.gif" border="0"></a></div>
								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot10-->
								
								
								<!-- Start spot4-->
								<input type="checkbox" name="spot4" id="spot4" value="yes" onchange="show_spotdiv('spot4');"/>							  
								Spot10 (appears on the right side)&nbsp;<a href="<?php echo SERVER_PATH;?>images/spot_3.jpg" class="screenshot" rel="<?php echo SERVER_PATH;?>images/spot_3.jpg" style="text-decoration:none"><b>?</b></a><br/>
								
								
								<div id="div_spot4" style="display:none;margin-left:20px;"><fieldset><legend>Spot10</legend>
								<input type="radio" checked="checked" name="spot4_choice" id="spot4_default" value="default" onchange="show_default('spot4');"/>Default Adsense ads<br/>
								<input type="radio" name="spot4_choice" id="spot4_customze" value="customze" onchange="show_replaceby('spot4');"/>Replace by...<br/>	
								
								<!--Start replaced by -->
								<div id="replace_spot4" style="display:none;">
								<fieldset><legend>Replace by</legend>
								
								<!-- Start spot4 option1- conent wizard save selection listing-->
								<div id="one_spot4">
								<textarea id="txtsaveselection_spot4" name="txtsaveselection_spot4" rows="10" cols="50" style="display:none;"></textarea>
									
										
											<input type="checkbox" name="chkcontents_spot4" id="chkcontents_spot4" value="content" onchange="get_saveselection(this.form,'spot4');"/>Saved Selection:&nbsp;<img id="save_processing_spot4" src='./images/ajax-loader_new.gif' alt='processing'/>
										
										<div id="get_saveselections_spot4" style="display: inline;"></div>
									
									<br/><br/>
								</div>
								<!-- End spot4 option1- conent wizard save selection listing-->
								
								<div><a href="javascript:shuffel('1','spot4')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot4 option2- Rotating ad / snippets:-->
								<div id="two_spot4">
								<textarea id="snippetscodetext_spot4" name="snippetscodetext_spot4" rows="10" cols="50" style="display:none;"></textarea>
									
								<input type="checkbox" name="chksnippets_spot4" id="chksnippets_spot4" value="snippets" onchange="get_snippets(this.form,'spot4');"/>Rotating ad / snippets &nbsp;<img id="snippets_processing_spot4" src='./images/ajax-loader_new.gif' alt='processing'/><br/>
									
								<span id="get_snippets_spot4"></span><br/>
								
								</div>
								<!-- End spot4 option2-->
								
								<div><a href="javascript:shuffel('2','spot4')"><img src="./images/up_arrow.gif" border="0"></a><a href="javascript:shuffel('3','spot4')"><img src="./images/down_arrow.gif" border="0"></a></div>
								
								<!-- Start spot4 option3- Customer code: -->
								<div id="three_spot4">
								<input type="checkbox" name="chkcustomer_code_spot4" id="chkcustomer_code_spot4" value="yes" onchange="show_div('chkcustomer_code','spot4');"/>Customer code<br/>
									<div id="div_customercode_spot4" style="display:none;margin-left:35px;">
									<textarea id="customercode_spot4" name="customercode_spot4" rows="10" cols="90"></textarea>
									</div>
								</div>								
								<!-- End spot4 option3-->
								<div><a href="javascript:shuffel('4','spot4')"><img src="./images/up_arrow.gif" border="0"></a></div>
								
								</fieldset>	
								</div>
								<!--End replace by -->								
								</fieldset>							
								</div>
								<!-- End spot4-->
								
								
							</td>
						</tr>	
			</table>
				
		</fieldset>



								
