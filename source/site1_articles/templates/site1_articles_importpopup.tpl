<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{module name='site1' action='head'}
	<link href="/skin/_css/site1.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="/skin/_css/style1.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="/skin/_js/mootools.js"></script>
	<script type="text/javascript" src="/skin/_js/xlib.js"></script>
	<script type="text/javascript" src="/skin/_js/moopop.js"></script>
	{*r.alert*}
	<link rel="stylesheet" type="text/css" href="/skin/_js/roar/roar.css" />
	<script type="text/javascript" src="/skin/_js/roar/roar.js"></script>
	<script type="text/javascript">
	{literal}
		var r=new Roar();
		img_preload(['/skin/_js/roar/roar.png']);
	{/literal}
	</script>
</head>
<body style="padding:10px;">
{module name='site1_articles' action='import' return=$return_type} 
<script>
{literal}
var choose = function(){
	var hashIds = JSON.decode(Ids);
	if( $chk(hash) )
	hash.each(function(value,key){
		hashIds.include(value);
	});
	Ids = JSON.encode(hashIds);
	new window.parent.multiboxArticle( {jsonData:Ids, place:'{/literal}{$smarty.get.place}{literal}'} );
}

var hash = new Hash({});	
window.addEvent('load', function(){
	if( window.parent.$('multibox_ids_{/literal}{$smarty.get.place}{literal}') && window.parent.$('multibox_ids_{/literal}{$smarty.get.place}{literal}').value ) {
		hash = JSON.decode( window.parent.$('multibox_ids_{/literal}{$smarty.get.place}{literal}').value );
	}	
	if(saveArticleTrue == 1) {
		choose();
		window.parent.multibox_article.close();
	}
});
{/literal}
</script>
</body>
</html>