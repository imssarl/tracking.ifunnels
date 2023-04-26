{if $flg_content == 1}
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
	<tr  class="tableheading">
	<th >S.No.</th>
	<th >Test&nbsp;Name</th>
	<th >Campaigns&nbsp;Included</th>
	<th >Date&nbsp;Created</th>
	<th >Split&nbsp;Mode</th>
	<th >Status</th>
	<th><input type='checkbox' class="select-all" value="dams"></th>
</tr>
	{if !empty($arrList)}
		{foreach from=$arrList item=i name=i}
		<tr class='{if $smarty.foreach.i.iteration%2 != 0} backcolor1 {else} backcolor2 {/if}' >
			<td align="center">{$i.id}</td>
			<td align="left">{$i.test_name}</td>
			<td align="left" title="" >{foreach from=$i.compaigns item=compaigns name=j}{$compaigns.campaign_name}{if !$smarty.foreach.j.last}, {/if}{/foreach}</td>
			<td align="left">{$i.date_created}</td>
			<td align="left" nowrap="true">{if $i.isDuration == 'Y'}Restricted{else}Not Restricted{/if}</td>
			<td align="left">{if $i.isRunning == 'Y'}Running{else}Completed{/if}</td>
			<td><input class="item-dams" name='arrOpt[dams][ids][]' type='checkbox' value='{$i.id}' {if in_array($i.id,$ids)} checked='1' {/if} /></td>
		</tr>
		{/foreach}
	{/if}
		<tr>
			<td align='center' colspan='7'  class="heading">&nbsp;</td>
		</tr>	  
</table>
{elseif $flg_content == 2}
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr  class="tableheading">
	<th>Campaign</th>
	<th>Campaign name</th>
	<th>Start date</th>
	<th>End date</th>
	<th>Ad type</th>
	<th>On action</th>
	<th>Play sound</th>
	<th>Mode</th>
	<th>Impressions</th>
	<th>Clicks</th>
	<th>Effectiveness</th>
	<th><input type='checkbox' class="select-all" value="dams"></th>
</tr>
{if !empty($arrList)}
{foreach from=$arrList item=i name=j}
		{if $i.position !='C' && $i.position !='S' && $i.position !='F'}
			{assign var=position value=''}
			{if $i.campaign_data.positionC =="C"} 
				{assign var=position value="$position Corner,"} 
			{/if}
			{if $i.campaign_data.positionS == "S"}
				{assign var=position value="$position Slide In,"} 
			{/if}
			{if $i.campaign_data.positionF == "F"} 
				{assign var=position value="$position Fix Position"}
			{/if}
		{else}
				{assign var=position value=''}
			{if $i.position == 'C'}
				{assign var=position value='Corner'}
			{elseif $i.position == 'S'}
				{assign var=position value="Slide In"}
			{elseif $i.position == 'F'}
				{assign var=position value="Fix Position"}
			{/if}	
		{/if}	
		{if $i.on_action == "L"} 
			{assign var=on_action value="Leaving the page"}
		{elseif $i.on_action == "F"} 
			{assign var=on_action value="On load"}
		{/if}	
		{if $i.play_sound == "Y"} 
			{assign var=play_sound value="Yes"}
		{elseif $i.play_sound == "N"}
			{assign var=play_sound value="No"}
		{/if}
		{if $i.track_ad == "Y"} 
			{assign var=track_ad value="Once"}
		{elseif $i.track_ad == "N"} 
			{assign var=track_ad value="Always"}
		{/if}
	<tr  class='{if $smarty.foreach.j.iteration%2}tablematter1{else}tablematter2{/if}' >
		<td align="center">{$i.id}</td>
		<td align="left">{$i.campaign_name}</td>
		<td align="left" title="" >{if $i.start_date} {$i.start_date} {else} - {/if}</td>
		<td align="center" class="general">{if $i.end_date} {$i.end_date} {else} - {/if}</td>
		<td align="center" nowrap="true">{$position}</td>
		<td align="center">{$on_action}</td>
		<td align="center">{$play_sound}</td>		
		<td align="center">{$track_ad}</td>
        <td align="center">{if $i.impression > 0}<a target="_blank" title="Click here for details" href="/dams/impressionreport.php?cid={$i.id}">{/if}{$i.impression}{if $i.impression > 0}</a>{/if}</td>
        <td align="center">{if $i.clicks > 0}<a target="_blank" title="Click here for details" href="/dams/clicksreport.php?cid={$i.id}">{/if}{$i.clicks}{if $i.clicks > 0}</a>{/if}</td>
        <td align="center">{if $i.effectiveness > 0} <a target="_blank" title="Click here for details" href="/dams/effectivenessreport.php?cid={$i.id}">{/if}{$i.effectiveness}{if $i.effectiveness > 0} </a>{/if}</td>		
		<td><input class="item-dams" name='arrOpt[dams][ids][]' type='checkbox' value='{$i.id}' {if in_array($i.id,$ids)} checked='1' {/if} /></td>
	</tr>
	{/foreach}	
	{else}
		<tr><td align='center' colspan='12'>No Campaign Found</td></tr>
	{/if}
<tr ><td align='center' colspan='15'  class="heading">&nbsp;</td></tr>	  
</table>	
{/if}

