<?php
/*************************************************************
  html-class.php
  Simple functional HTML Element library in spirit to the perl 
  CGI or HTML::Element modules.

  Author: Reini Urban <rurban@x-ray.at>
  Time-stamp: <2004-05-12 23:37:10 rurban>
  Created: 2002-04-08
  Changed to class: 2004-05-12
  Url:    http://xarch.tu-graz.ac.at/home/rurban/software/html-class.php.gz
          http://xarch.tu-graz.ac.at/home/rurban/software/html-class.phps
          http://xarch.tu-graz.ac.at/home/rurban/software/html-class.example.phps

  Features:
  Ensures proper nesting of html tags (esp. with emacs),
  XHTML, XML compliant
  If the constant XHTML is defined, XHTML conforming tags are returnd, otherwise HTML4.
  Variable number of content arguments for all tags, not only containers.
  Does no attribute and proper nesting checking. (is_contained_in(), 
    may_contain(), is_valid_attribute())

  Requires: At least 4.1.0, using call_user_func_array() object method as callback.

  See also Jeff Dairiki's XmlElement/HtmlElement library within phpwiki, 
  which is more like HTML::Element, using validition.

  Todo: Improve HTML::attr_default().
         Better support for attribute defaults to help with user_defined tags
         (class='myclass', ...)

  Copyright (c) 2002,2004 Reini Urban
  This is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This software is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with PhpWiki; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA 
  
*************************************************************/

$rcs_id = '$Id: html-class.php,v 1.12 2003/02/06 14:01:52 rurban Exp $';
if (function_exists('rcs_id')) { rcs_id($rcs_id); }
// if you want XHTML define it.
if (!defined('XHTML')) define('XHTML',false);

// support attr-name without quotes: HTML::img(array(src=>$url,alt=>''));
// only class is a reserved php name.
foreach (explode(':','type:alt:src:name:value:href:title:width:height:border:'.
		     'align:valign:style:id:bgcolor:colspan:rowspan:checked:selected') as $attr) {
    if (!defined($attr)) define($attr,$attr);
}
if (!defined('NBSP')) define('NBSP','&nbsp;');

class HTML {
    function nbsp ()  { return '&nbsp;'; }

    // _tag0() collapses to <tag />. normally no content.
    //   _tag ( [attr-array] content... )
    //   <tag /> or <tag attr=value... /> or <tag>content</tag> or <tag attr=value...>content</tag>

    // _tag1() collapses to <tag></tag>. normally no attributes.
    // with xml attributes array as optional first argument.
    //   _tag ( [attr-array] content... )
    //   <tag></tag> or <tag attr=value...>content</tag> or <tag>content</tag>
    
    // special attribute: 'more_attr_pairs'=>$events (unformatted string)

    function br ()    { $args = func_get_args(); return HTML::_tag0('br',$args); }
    function hr ()    { $args = func_get_args(); return HTML::_tag0('hr',$args); }
    //function img () { $args = func_get_args(); return HTML::_tag0('img',$args); } // see below

    // Top-Level Elements
    function html ()  { $args = func_get_args(); return HTML::_tag1('html',$args); }
    function head ()  { $args = func_get_args(); return HTML::_tag1('head',$args); }
    function body ()  { $args = func_get_args(); return HTML::_tag1('body',$args); }
    
    // Head Elements
    function meta ()  { $args = func_get_args(); return HTML::_tag0('meta',$args); }
    function style () { $args = func_get_args(); return HTML::_tag1('style',$args); }
    function link ()  { $args = func_get_args(); return HTML::_tag1('link',$args); }
    //function jscript(){;}	//see below

    // Font Style Elements
/*
	B - Bold text 
	BIG - Large text 
	I - Italic text 
	S - Strike-through text (non-strict)
	SMALL - Small text 
	STRIKE - Strike-through text (non-strict)
	TT - Teletype text 
	U - Underlined text  (non-strict)
*/
    function b ()     { $args = func_get_args(); return HTML::_tag1('b',$args); }
    function i ()     { $args = func_get_args(); return HTML::_tag1('i',$args); }
    function u ()     { $args = func_get_args(); return HTML::_tag1('u',$args); }
    function strike() { $args = func_get_args(); return HTML::_tag1('strike',$args); }
    function small () { $args = func_get_args(); return HTML::_tag1('small',$args); }
    function big ()   { $args = func_get_args(); return HTML::_tag1('big',$args); }
    function sub ()   { $args = func_get_args(); return HTML::_tag1('sub',$args); }
    function sup ()   { $args = func_get_args(); return HTML::_tag1('sup',$args); }
    function tt ()    { $args = func_get_args(); return HTML::_tag1('tt',$args); }
    function font ()  { $args = func_get_args(); return HTML::_tag1('font',$args); }

    // Phrase Elements
/*
	ABBR - Abbreviation 
	ACRONYM - Acronym 
	CITE - Citation 
	CODE - Computer code 
	DEL - Deleted text 
	DFN - Defined term 
	EM - Emphasis 
	INS - Inserted text 
	KBD - Text to be input 
	SAMP - Sample output 
	STRONG - Strong emphasis 
	VAR - Variable
*/
    function abbr ()  { $args = func_get_args(); return HTML::_tag1('abbr',$args); }
    function acronym(){ $args = func_get_args(); return HTML::_tag1('acronym',$args); }
    function cite ()  { $args = func_get_args(); return HTML::_tag1('cite',$args); }
    function code ()  { $args = func_get_args(); return HTML::_tag1('code',$args); }
    function del ()   { $args = func_get_args(); return HTML::_tag1('del',$args); }
    function dfn ()   { $args = func_get_args(); return HTML::_tag1('dfn',$args); }
    function em ()    { $args = func_get_args(); return HTML::_tag1('em',$args); }
    function ins ()   { $args = func_get_args(); return HTML::_tag1('ins',$args); }
    function kbd ()   { $args = func_get_args(); return HTML::_tag1('kbd',$args); }
    function samp  () { $args = func_get_args(); return HTML::_tag1('samp',$args); }
    function strong (){ $args = func_get_args(); return HTML::_tag1('strong',$args); }
    //function var ()   { $args = func_get_args(); return HTML::_tag1('var',$args); }

    // Generic Block-level Elements
/*
	ADDRESS - Address 
	BLOCKQUOTE - Block quotation 
	DEL - Deleted text 
	DIV - Generic block-level container 
	H1 - Level-one heading 
	H2 - Level-two heading 
	H3 - Level-three heading 
	H4 - Level-four heading 
	H5 - Level-five heading 
	H6 - Level-six heading 
	HR - Horizontal rule 
	INS - Inserted text 
	NOSCRIPT - Alternate script content 
	P - Paragraph 
	PRE - Preformatted text 
*/
    function div  ()  { $args = func_get_args(); return HTML::_tag1('div',$args); }
    function code ()  { $args = func_get_args(); return HTML::_tag1('code',$args); }
    function pre ()   { $args = func_get_args(); return HTML::_tag1('pre',$args); }
    function p ()     { $args = func_get_args(); return HTML::_tag1('p',$args); }
    function h1 ()    { $args = func_get_args(); return HTML::_tag1('h1',$args); }
    function h2 ()    { $args = func_get_args(); return HTML::_tag1('h2',$args); }
    function h3 ()    { $args = func_get_args(); return HTML::_tag1('h3',$args); }
    function h4 ()    { $args = func_get_args(); return HTML::_tag1('h4',$args); }
    function h5 ()    { $args = func_get_args(); return HTML::_tag1('h5',$args); }
    function h6 ()    { $args = func_get_args(); return HTML::_tag1('h6',$args); }
    function address(){ $args = func_get_args(); return HTML::_tag1('address',$args); }
    function blockquote(){$args = func_get_args(); return HTML::_tag1('blockquote',$args); }
    function noscript(){ $args = func_get_args(); return HTML::_tag1('noscript',$args); }

    // Tables
    function table () { $args = func_get_args(); return HTML::_tag1('table',$args); }
    function tr ()    { $args = func_get_args(); return HTML::_tag1('tr',$args); }
    function th ()    { $args = func_get_args(); return HTML::_tag1('th',$args); }
    function td ()    { $args = func_get_args(); return HTML::_tag1('td',$args); }

    // Lists
    function ul()     { $args = func_get_args(); $args = func_get_args(); return HTML::_tag1('ul',$args); }
    function ol()     { $args = func_get_args(); $args = func_get_args(); return HTML::_tag1('ol',$args); }
    function dl()     { $args = func_get_args(); $args = func_get_args(); return HTML::_tag1('dl',$args); }
    function dd()     { $args = func_get_args(); $args = func_get_args(); return HTML::_tag1('dd',$args); }
    function dt()     { $args = func_get_args(); $args = func_get_args(); return HTML::_tag1('dt',$args); }
    function li()     { $args = func_get_args(); $args = func_get_args(); return HTML::_tag1('li',$args); }

// Special Inline Elements
/*
	A - Anchor 
	APPLET - Java applet (non-strict)
	BASEFONT - Base font change (non-strict)
	BDO - BiDi override 
	BR - Line break 
	FONT - Font change (non-strict)
	IFRAME - Inline frame (non-strict)
	IMG - Inline image 
	MAP - Image map 
	AREA - Image map region 
	OBJECT - Object 
	PARAM - Object parameter 
	Q - Short quotation 
	SCRIPT - Client-side script 
	SPAN - Generic inline container 
	SUB - Subscript 
	SUP - Superscript 
 */
    function a ()     { $args = func_get_args(); return HTML::_tag1('a',$args); }
    function script (){ $args = func_get_args(); return HTML::_tag1('script',$args); }
    function font ()  { $args = func_get_args(); return HTML::_tag1('font',$args); }
    function bdo ()   { $args = func_get_args(); return HTML::_tag1('bdo',$args); }
    function applet (){ $args = func_get_args(); return HTML::_tag1('applet',$args); }
    function iframe (){ $args = func_get_args(); return HTML::_tag1('iframe',$args); }
    function map ()   { $args = func_get_args(); return HTML::_tag1('map',$args); }
    function area ()  { $args = func_get_args(); return HTML::_tag1('area',$args); }
    function object (){ $args = func_get_args(); return HTML::_tag1('object',$args); }
    function param () { $args = func_get_args(); return HTML::_tag1('param',$args); }
    function q ()     { $args = func_get_args(); return HTML::_tag1('q',$args); }
    function span ()  { $args = func_get_args(); return HTML::_tag1('span',$args); }
    function sub ()   { $args = func_get_args(); return HTML::_tag1('sub',$args); }
    function sup ()   { $args = func_get_args(); return HTML::_tag1('sup',$args); }

    // Forms
    function form ()  { $args = func_get_args(); return HTML::_tag1('form',$args); }
    function select() { $args = func_get_args(); return HTML::_tag1('select',$args); }
    function option() { $args = func_get_args(); return HTML::_tag1('option',$args); }
    function input () { $args = func_get_args(); return HTML::_tag0('input',$args); }
    // ROWS=Number COLS=Number DISABLED READONLY ACCESSKEY
    function textarea(){$args = func_get_args(); return HTML::_tag1('textarea',$args); }
    // HTML::fieldset(HTML::legend(HTML::label(array(ACCESSKEY=>"V"),HTML_Input::text(...)),
    //                       HTML::label(array(ACCESSKEY=>"V"), ...))))
    function fieldset(){$args = func_get_args(); return HTML::_tag1('fieldset',$args); }
    // ACCESSKEY
    function legend() { $args = func_get_args(); return HTML::_tag1('legend',$args); }
    // FOR=IDREF (associated form field) ACCESSKEY (shortcut key) ONFOCUS ONBLUR
    function label()  { $args = func_get_args(); return HTML::_tag1('label',$args); }


    // functions with attr overrides, forcing certain attributes to be set.
    //   array(name=>value), [attr] rest...

    // functions with attr defaults:
    // these take optionally no array as first parameter, instead 
    // the default attribute only.
    function img ()   { 
        $args = func_get_args(); 
        $tag = 'img';
        if (empty($args)) return "<$tag />";
        elseif (is_array($args[0])) {
            $attr = array_shift($args);
            $attr_str = trim(HTML::_attr($attr));
            // on empty attr strip the first space
            if (empty($args)) return "<$tag" . (strlen($attr_str) ? " $attr_str" : '') . (XHTML ? ' />' : '>');
            else return "<$tag" . (strlen($attr_str) ? " $attr_str>" : '>') . join("\n",$args) . "</$tag>";
        } else {
            if (count($args) > 0) $attr['src'] = $args[0];
            if (count($args) > 1) $attr['alt'] = $args[1];
            if (count($args) > 2) $attr['width'] = $args[2];
            if (count($args) > 3) $attr['height'] = $args[3];
            return "<$tag " . HTML::_attr($attr) . " />";
        }
    }

    // a_href($url,$text...) <=> a(array('href'=>$url),$text...);
    //   a_href($url,$text...) or 
    //   a_href(array(href=>$url,...),$text...)
    function a_href () { 
        $args = func_get_args(); 
        $attr = array_shift($args);
        return HTML::_attr_default ('a', 'href', $attr, $args);
    }
    // a_name($name,$text...) <=> a(array('name'=>$name),$text...);
    //   a_name($name,$text...) or 
    //   a_name(array(name=>$name,...),$text...)
    function a_name () { 
        $args = func_get_args(); 
        $attr = array_shift($args);
        return HTML::_attr_default ('a', 'name', $attr, $args);
    }
    
    // label_for($id,...), label_for(array(),$id)
    //   FOR=IDREF (associated form field) ACCESSKEY (shortcut key) ONFOCUS ONBLUR
    function label_for()  { 
        $args = func_get_args(); 
        $attr = array_shift($args);
        return HTML::_attr_default ('label', 'for', $attr, $args);
    }

    // other special functions:
    
    // like the plain script, but forces the type to be set,
    // adds newlines and comments for older, non-jscript browsers.
    function jscript () { 
        $tag = 'script';
        $args = func_get_args(); 
        if (empty($args)) {
            return "\n<$tag></$tag>\n"; 
        } else {
            if (is_array($args[0])) {
                $attr = array_shift($args);  // array_merge overwrites
                $attr = trim(_attr(array_merge($attr,array('type'=>'text/javascript'))));
            } else {
                $attr = "type=\"text/javascript\"";
            }
            return "\n<$tag $attr>\n<!--\n" . join("\n",$args) . "\n// -->\n</$tag>\n";
        }
    }

    // HTML_Input::hidden shortcut
    // hidden($name,$value)
    function hidden($name,$value) {
        return HTML_Input::hidden(array('name'=>$name,'value'=>$value));
    }

    /******************************************************************************/

    // internal functions
    
    // no attributes, just content
    function _tag0 ($tag, $args = false) {
        if (empty($args)) return  XHTML ? "<$tag />" : "<$tag>";
        elseif (is_array($args[0])) {
            $attr = array_shift($args);
            $attr_str = trim(_attr($attr));
            // on empty attr strip the first space
            if (empty($args)) return "<$tag" . (strlen($attr_str) ? " $attr_str" : '') . (XHTML ? ' />' : '>');
            else return "<$tag" . (strlen($attr_str) ? " $attr_str>" : '>') . join("\n",$args) . "</$tag>";
        } else {
            return "<$tag>" . join('',$args) . "</$tag>";
        }
    }
    // with optional attributes-array as first parameter
    function _tag1 ($tag, $args = false) {
        if (! $args ) {
            return "<$tag></$tag>"; 
        } else {
            if (is_array($args[0])) {
                $attr = array_shift($args);
                $attr_str = trim(HTML::_attr($attr));
                // on empty attr strip the first space
                return "<$tag" . (strlen($attr_str) ? " $attr_str>" : '>') . join("\n",$args) . "</$tag>";
            } else {
                return "<$tag>" . join('',$args) . "</$tag>";
            }
        }
    }
    // with attributes-array and rest parameters
    function _tag2 ($tag, $attr, $args = false) {
        if (empty($attr))
            $attr_str = '';
        else
            $attr_str = trim(HTML::_attr($attr));
        if ( $args ) {
            $args = join("\n",$args);
            return "<$tag" . (strlen($attr_str) ? " $attr_str>" : '>') . "$args</$tag>";
        } else {
            return "<$tag" . (strlen($attr_str) ? " $attr_str" : '') . (XHTML ? ' />' : '>');
        }
    }
    function _attr ($attr) {
        $tag = '';
        if (!empty($attr)) {
            foreach ($attr as $key => $value) {
                if (XHTML)
                    $key = strtolower($key);
                if ($key == 'more_attr_pairs')
                    $tag .= ($value . " ");
                elseif ($key == 'checked')
                    $tag .= (XHTML ? 'checked="checked" ' : 'checked ');
                else
                    $tag .= ($key . '="' . $value . '" ');
            }
        }
        return $tag;
    }
    // provide default attribute name=>value pair if no attrs were defined
    // for a_name, a_href, ...
    function _attr_default ($tag, $def_name, $attr, $args = false) {
        if (!empty($attr) and is_array($attr)) {  
            return HTML::_tag2($tag,$attr,$args);
        } else {
            return HTML::_tag2($tag,array($def_name => $attr),$args);
        }
    }
    function _attr_overrides ($tag, $overrides, $args=false) {
        if (!empty($args) and is_array($args[0])) { // and !isset($args[0][0])) { //
            $attr = array_shift($args);
            return HTML::_tag2($tag,array_merge($attr,$overrides),$args);
        } else { // no attr
            return HTML::_tag2($tag,$overrides,$args);
        }
    }
    // not yet used.
    // to apply defaults and overrides
    function _attr_split ($args) {
        if (!empty($args) and is_array($args[0])) { // and !isset($args[0][0])) { //
            $attr = array_shift($args);
            return array($attr,$args);
        } else { // no attr
            return array(array(),$args);
        }
    }
}

class HTML_Input extends HTML {

    // INPUT type= "text | password | checkbox | radio | submit | reset | file | hidden | image | button"

    // HTML_Input::checkbox(array(name=>$name,value=$value))
    function checkbox() {
        $args = func_get_args();
        return call_user_func_array(array($this,'_attr_overrides'),
                                    array('input',array('type'=>'checkbox'),$args));
    }
    // HTML_Input::radio(array(name=>$name,value=$value,checked=>true))
    function radio() {
        $args = func_get_args();
        return call_user_func_array(array($this,'_attr_overrides'),
                                    array('input',array('type'=>'radio'),$args)); 
    }
    // HTML_Input::hidden(array(name=>$name,value=$value))
    function hidden() {
        $args = func_get_args();
        return HTML::_attr_overrides('input',array('type'=>'hidden'),$args); 
    }
    // HTML_Input::text(array(name=>$name,value=$value,size=>$size,'class'=>$class,...))
    function text() {
        $args = func_get_args();
        return call_user_func_array(array('HTML','_attr_overrides'),
                                    array('input',array('type'=>'text'),$args)); 
    }
    // HTML_Input::password(array(name=>$name,value=$value,size=>$size,'class'=>$class,...))
    function password() {
        $args = func_get_args();
        return call_user_func_array(array('HTML','_attr_overrides'),
				array('input',array('type'=>'password'),$args)); 
    }
    // HTML_Input::submit(array(name=>$name,value=$value,'class'=>$class,...))
    function submit() {
        $args = func_get_args();
        return call_user_func_array(array('HTML','_attr_overrides'),
                                    array('input',array('type'=>'submit'), $args)); 
    }
    // HTML_Input::reset(array(value=$value,'class'=>$class,...))
    function reset() {
        $args = func_get_args();
        return call_user_func_array(array('HTML','_attr_overrides'),
                                    array('input',array('type'=>'reset'), $args)); 
    }
    // HTML_Input::file(array(name=>$name,accept=>'text/html',...))
    function file() {
        $args = func_get_args();
        return call_user_func_array(array('HTML','_attr_overrides'),
                                    array('input',array('type'=>'file'), $args)); 
    }
    // HTML_Input::image(array(name=>$name,value=$value,src=>$url,alt=$name,'usemap'=>'#mapname','class'=>$class,...))
    function image() {
        $args = func_get_args();
        return call_user_func_array(array('HTML','_attr_overrides'),
                                    array('input',array('type'=>'image'), $args)); 
    }
    // HTML_Input::button(array(value=$value,id=>$id,'onClick'=>"jsfunc()",'class'=>$class,...))
    function button() {
        $args = func_get_args();
        return call_user_func_array(array('HTML','_attr_overrides'),
                                    array('input',array('type'=>'button'), $args)); 
    }
}

// Example: class=gumb buttons
class Gumb extends HTML_Input {

    function a_button1 (/* $href, $text... */) {
        $args = func_get_args(); 
        // $args = HTML::_attr_split($args);
        if (! empty($args) and is_array($args[0])) {
            $attr = array_shift($args);
            $href = $attr['href'];
        } else {
            $href = array_shift($args);
        }
        return HTML::_attr_default ('a', 'href', array('class'=>'gumb','href'=>$href), $args);
        //HTML::a_href(array('class'=>'gumb',href=>$href), $args); 
    }

    // Gumb::a_button($url,$text...) or 
    // Gumb::a_button(array(href=>url,...),$text...)
    function a_button (/* [$attr] $text... | $href, $text... */) {
        $args = func_get_args(); 
        if (! empty($args) and is_array($args[0]))
            return call_user_func_array(array('HTML','_attr_overrides'),
                                        array('a',array('class'=>'gumb'), $args));
        else {
            $attr = array_shift($args);
            return HTML::_tag2('a',array('class'=>'gumb','href'=>$attr),$args);
        }
    }

    function small_a_button (/* [$attr] $text... | $href, $text... */) {
        $args = func_get_args();
        if (! empty($args) and is_array($args[0])) 
            return call_user_func_array(array('HTML','_attr_overrides'),
                                        array('a',array('class'=>'gumb1'), $args)); 
        else {
            $attr = array_shift($args);
            return HTML::_tag2('a',array('class'=>'gumb1','href'=>$attr), $args);
        }
    }
}

// end of html-lib
?>