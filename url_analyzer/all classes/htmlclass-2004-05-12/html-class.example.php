<?php // -*- php -*-
// some real-world examples using html-class, compared to previous usage (commented)
require_once ("html-class.php");

function empty_row ($colspan = false) {
    if ($colspan)
	return HTML::tr(HTML::td(array(colspan=>$colspan),NBSP)) . "\n";
	//return "<tr><td colspan=\"$colspan\">&nbsp;</td></tr>\n";
    else
	return HTML::tr(HTML::td(NBSP)) . "\n";
        //return "<tr><td>&nbsp;</td></tr>\n";
}

function display_error ($msg, $colspan=5) {
    return HTML::tr(array(valign=>"top"),
                    HTML::td(array(align => "right"),
                             HTML::img(array(src=>"img/icon_red.gif",alt => _("Error"), 
                                             width => 20, height => 20)), 
                             NBSP, NBSP),
                    HTML::td(array(colspan => $colspan-1), HTML::div(array('class' => 'error'),
                                                                     $msg))) . "\n";
    //echo '<tr valign="top"><td align="right"><img src="img/icon_red.gif" alt="Error" '.$size.'>&nbsp;&nbsp;</td>';
    //echo "<td colspan=\"",$colspan-1,"\"><div class=\"error\">$msg</div></tr>\n";
}

function check_file_js() {
    return HTML::jscript("
var check = new Array();

function check_file(key,v) {
  var Match = /^\w[\w/\.]+$/;
  return Match.test(v);
}
");
}

function browserAgent() {
    static $HTTP_USER_AGENT = false;
    if (!$HTTP_USER_AGENT)
        $HTTP_USER_AGENT = @$GLOBALS['HTTP_SERVER_VARS']['HTTP_USER_AGENT'];
    if (!$HTTP_USER_AGENT) // CGI
        $HTTP_USER_AGENT = $GLOBALS['HTTP_ENV_VARS']['HTTP_USER_AGENT'];
    return $HTTP_USER_AGENT;
}
function browserDetect($match) {
    return strstr(browserAgent(), $match);
}
function isBrowserMozilla() {
    return (browserDetect('Mozilla/') and 
            browserDetect('Gecko/') and 
            !browserDetect('MSIE'));
}

// mixed usage of dirty tags as string, and the functional html-class style, which guarantees proper nesting.
function show_main_buttons() {
    global $mid_width, $HTTP_POST_VARS;
    $out = '';
    if (!isBrowserMozilla())
	$js = "onmouseover=\"hover(this,'#0072e4','')\" onmouseout=\"hover(this,'#005abd','')\"";
    if (HAVE_FORM_JS) $js .= ' onclick="return checkForm()"';
    $out .= '<tr><td width="'.$mid_width.'"><table width="'.$mid_width.'" border="0"><tr>';
    if (!$GLOBALS['error']) {
	$out .= HTML::td(array(align=>'right'),NBSP,HTML_Input::submit(array('class'=>'gumb',name=>"check",value=>"Check",'accesskey'=>"P",'more_attr_pairs'=>$js)),NBSP,HTML_Input::submit(array('class'=>'gumb',name=>"save",value=>"Save",'accesskey'=>"S",'more_attr_pairs'=>$js)));
    }
    //...
    $out .= "</tr></table></td></tr>\n";
    return $out;
}

echo "<html>";
echo HTML::head(check_file_js(),
                HTML::style('
	.gumb { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; background-color: #005abd; color: #EEEEEE; border-color: #000000; border-width: 1px; text-decoration: none; }
	a.gumb, a.gumb:link, a.gumb:visited { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; background-color: #005abd; color: #EEEEEE; border-color: #000000; border-width: 1px; text-decoration: none; border-style: groove; }
'));
echo "<body>";
//echo "<table>";
$mid_width=480;
echo HTML::form(array(action=>$_SERVER['PHP_SELF']),
                HTML::table(array(),
                            empty_row(2),
                            display_error('wrong setting',2),
                            empty_row(2),
                            show_main_buttons())
                );
//echo "</table>";
echo "</body>";
echo "</html>";
?>