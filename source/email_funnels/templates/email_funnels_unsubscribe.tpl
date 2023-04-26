<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="robots" content="noindex, nofollow, noarchive">
		<title>Subscribe/Unsubscribe/Change Options</title>
		<meta name="description" content="Subscriber options page.">
		<meta name="keywords" content="subscriber options unsubscribe page">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style type="text/css">
			body{
				background:white;
				margin:0;
				padding:0;
				color:#4a4a4a;
				font-family:helvetica,arial,sans-serif;
				font-size:14px;
				line-height:1.6
			}
			table{
				font-size:14px;
				line-height:1.6
			}
			table th,table td{
				padding:4px 5px
			}
			table th{
				color:#2e7ad1;
				border-bottom:1px solid #dbdcde;
				font-size:14px
			}
			h1{
				font-size:1.8em;
				font-weight:normal;
				margin:20px 0 10px 0
			}
			.center{
				text-align:center
			}
			.left{
				text-align:left
			}
			.container{
				width:960px;
				max-width:100%;
				margin:0 auto;
				padding:20px;
				box-sizing:border-box
			}
			.table-container{
				width:100%
			}
			#subscriber_info{
				border:1px solid #dbdcde;
				padding:20px;
				background-color:#f5f5f5;
				margin-top:0;
				margin-bottom:25px;
				text-align:left;
				display:flex;
				flex-flow:row wrap
			}
			#subscriber_info .subscriber_col{
				width:50%
			}
			.button{
				box-sizing:border-box;
				-webkit-box-sizing:border-box;
				-moz-box-sizing:border-box;
				display:inline-block;
				padding:9px 25px;
				background-color:#ababab;
				border-radius:4px;
				border:1px solid transparent;
				color:#fff !important;
				font-size:16px;
				font-weight:normal;
				text-decoration:none;
				cursor:pointer;
				transition:opacity .2s,border .2s,background-color .2s
			}
			.button:hover{
				background:#929292
			}
			.button a{
				color:#fff !important;
				text-decoration:none;
				white-space:nowrap
			}
			.button.blue-bg{
				background-color:#2e7ad1
			}
			.button.blue-bg:hover,.button.blue-bg:focus{
				background-color:#074e9f
			}
			.button.tiny{
				font-size:13px;
				padding:5px 20px
			}
			.separator {
				display: block;
				height: 1px;
				border-bottom: 1px solid #c3c3c3;
				line-height: 0px;
				text-align: center;
				color: #c3c3c3
			}

			.separator span {
				font-weight: 700;
				padding: 0 10px;
				background: #fff;
				line-height: 3px;
			}

			@media(max-width:640px){
				#subscriber_info .subscriber_col{
					width:100%
				}
				#subscriber_info .subscriber_col div{
					margin-bottom:5px
				}
				.save-changes input[type="submit"]{
					display:block;
					width:100%
				}
				.table-container{
					overflow-y:hidden
				}
			}
			.save-changes {
				text-align: right;
			}
		</style>

		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>
	<body>
		{if !isset($message)}
		<div id="content" class="container">
			
			<form method="post">
				<input type="hidden" name="c" value="{$strCode}">
				<h1>Subscriber Information</h1>
				<div id="subscriber_info">
					<div class="subscriber_col">
						<strong>Email:</strong>&nbsp;{$arrData.codedEmail}<br />
					</div>
					<div class="subscriber_col">
						<strong>Signup Date:</strong>&nbsp;{$arrData.added|date_local:$config->date_time->dt_full_format}<br />
					</div>
				</div>
				<p>You are subscribed to the following lists:</p>
				<div class="table-container">
					<table border="0" cellpadding="1" width="100%" cellspacing="0">
						<tr>
							<th class="center" width="100">Subscribed</th>
							<th class="center" width="120">Unsubscribed</th>
							<th class="left" width="140">List Name</th>
							<th class="left" width="310">Description</th>
							<th class="left">Signup Date</th>
						</tr>
						{foreach $efunnelsList as $efid => $efunnel}
						{*if isset($efunnel.subscribed) && $efunnel.subscribed != 0*}
						<tr>
							<td class="center">
								<label><input type="radio" name="flg_subscribe[{$efid}]" value="1" {if $efunnel.subscribed != 0 && $efid != $arrData.efunnel_id}checked{/if}></label>
							</td>
							<td class="center"><label><input type="radio" name="flg_subscribe[{$efid}]" value="0" {if $efunnel.subscribed==0 || $efid==$arrData.efunnel_id} checked{/if}></label></td>
							<td>{$efunnel.title}</td>
							<td>{$efunnel.description}</td>
							<td nowrap>{if $efunnel.subscribed!=0}{$efunnel.subscribed|date_local:$config->date_time->dt_full_format}{/if}</td>
						</tr>
						{*/if*}
						{/foreach}
					</table>
				</div>
				<br />
				<div class="save-changes">
					<input type="submit" class="button blue-bg tiny" value="Save My Subscriber Settings">
				</div>
			</form>
		</div>

		<div class="container">
			<p class="separator"><span>OR</span></p>
			
		</div>

		<div class="container">
			<form method="post">
				<input type="hidden" name="c" value="{$strCode}">
				<input type="hidden" name="global_unsubscribe" value="1">
				<div class="save-changes">
					<input type="submit" class="button blue-bg tiny" value="Unsubscribe Me from All Future Emails">
				</div>
			</form>
		</div>
		{else}
		<div class="container">
			<div class="alert alert-{$message.status}" role="alert"> <strong>{$message.header}!</strong> {$message.text}</div>
		</div>	
		{/if}
	</body>
</html>
