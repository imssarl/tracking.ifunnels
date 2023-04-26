	<div class="element initElement">
	<br/>
	<fieldset>
		<ol>
			<li>
				<label>Blog URL(full URL) <em>*</em></label><input type="text" class="required {if $arrErr.filtered.url}error{/if}" value="{$arrBlog.url}" name="arrBlog[url]" id="domain" />&nbsp;{module name='cpanel_tools' action='set' type='subdomain' set='one' info='allocate'}
				<p><font color="Red">Note:</font> Please provide full URL here including the folder name, where you want to install the blog. i.e. http://www.mysite.com/blog or http://blog.mysite.com. Otherwise blog will not be properly installed on your remote server.</p>
			</li>
		</ol>
	</fieldset>
	{module name='ftp_tools' action='set' selected=$arrBlog.arrFtp arrayName='arrFtp'}
	<fieldset>
		<legend>Database Detail</legend>
		<ol>
			<li>
				Createe new database and users: {module name='cpanel_tools' action='set' type='database' info='allocate'}  or fill database detail below 
			</li>
			<li>
				<label>Host Name <em>*</em></label><input type="text" title="Host Name" class="required {if $arrErr.filtered.db_host}error{/if}" value="{$arrBlog.db_host}" name="arrBlog[db_host]" id="db_host" /><p>(Please enter host name like "localhost" or "192.168.1.7")</p>
			</li>
			<li>
				<label>Database Name <em>*</em></label><input type="text"  title="Database Name" class="required {if $arrErr.filtered.db_name}error{/if}"  value="{$arrBlog.db_name}" name="arrBlog[db_name]" id="db_name" />
			</li>
			<li>
				<label>Database User Name <em>*</em></label><input type="text" title="Database User Name" class="required {if $arrErr.filtered.db_user}error{/if}" value="{$arrBlog.db_username}" name="arrBlog[db_username]" id="db_user" />
			</li>
			<li>
				<label>Database Password <em>*</em></label><input type="password" title="Database Password" class="required {if $arrErr.filtered.db_password}error{/if}" value="{$arrBlog.db_password}" name="arrBlog[db_password]" id="db_pass" /> <a href="#" id="test_db">Test connection <img src="/skin/i/frontends/design/ajax-loader_new.gif" style="display:none;" id="test_db_loader"></a>
			</li>
			<li>
				<label>Table Prefix</label><input type="text" name="arrBlog[db_tableprefix]"  value="{if $arrBlog.db_tableprefix}{$arrBlog.db_tableprefix}{else}wp_{/if}"/>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Dashboard Login detail</legend>
		<ol>	
			<li>
				<label>Login ID <em>*</em></label><input type="text" title="Login ID"  class="required {if $arrErr.filtered.dashboad_username}error{/if}"  value="{$arrBlog.dashboad_username}" name="arrBlog[dashboad_username]" />
			</li>
			<li>
				<label>Password <em>*</em></label><input type="password" title="Password"  value="{$arrBlog.dashboad_password}" class="required {if $arrErr.filtered.dashboad_password}error{/if}" name="arrBlog[dashboad_password]"/>
			</li>
			<li>
				<a href="#" class="acc_prev">Prev step</a> / <a href="#" class="acc_next">Next step</a>
			</li>
		</ol>
	</fieldset>
	</div>