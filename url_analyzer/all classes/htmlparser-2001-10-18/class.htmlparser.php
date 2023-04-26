<?
// Copyright(C)MMI, Lin Zhemin.
// PHP HTML parser class
// Copyright declared according to GPLv2.
//
// GeOu{AhC
// GTp rowspan (vTj)
// G|B DTD, , CSS, PHP, ASP 


$DEBUGLEVEL = 0;            // T
$SHOWINDENT = 4;            // Y


// HU http://www.w3.org/TR/html4/index/elements.html

// HTML n end tag  (bWW End Tag = )
$HTMLbrac = array(
        'a', 'abbr', 'acronym', 'address', 'applet',
        'b', 'bdo', 'big', 'blockquote', 'button',
        'caption', 'center', 'cite', 'code',
        'del', 'dfn', 'dir', 'div', 'dl',
        'em', 'fieldset', 'font', 'form', 'frameset',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'i', 'iframe', 'ins',
        'kbd',
        'label', 'legend',
        'map', 'menu',
        'noframes', 'noscript',
        'object', 'ol', 'optgroup',
        'pre',
        'q',
        's', 'samp', 'script', 'select', 'small', 'span', 'strike', 'strong', '
style', 'sub', 'sup',
        'table', 'textarea', 'title', 'tt',
        'u', 'ul',
        'var',
        'html', 'body', 'head'  // oOvXRI
        );

// HTML ]tO (WGEnd Tag = O)
// @w]tO
$HTMLcont = array_merge($HTMLbrac, array(
            'colgroup', 'dd', 'dt', 'li', 'option', 'p',
            'tbody', 'td', 'tfoot', 'th', 'thead', 'tr'
            ));

// HTML L]tO (WGEnd Tag = F, Empty = E)
$HTMLnocn = array(
        'area', 'base', 'basefont', 'br', 'col', 'frame',
        'hr', 'img', 'input', 'isindex', 'link', 'meta',
        'param');

// NH
$HTMLCloseTag = array(
        'p'  => array('table')
        );




function HTMLdebug($l, $s) {
global $DEBUGLEVEL;
if($l > $DEBUGLEVEL)
    return;
echo "DEBUG: $s\n";
}




// Container
class Container {
    var $obj = array();
    var $iden;
    var $cnt = 0;   // obj 

    function push(&$o) {
        HTMLdebug(5, ' Container::push');
        $this->cnt++;
        return array_push($this->obj, $o);
    }
    function pop() {
        return array_pop($this->obj);
    }
    function show($in = 0) {
        HTMLdebug(7, " Container::show($in)");
        $r = "";
        for($i = 0; $i < $this->cnt; $i++) {
            $r .= $i.'C'.$this->obj[$i]->show($in);
        }
        return $r;
    }
    function out() {
        HTMLdebug(7, ' Container::out');
        $r[] = $this;
        for($i = 0; $i < $this->cnt; $i++) {
            $r[] = $this->obj[$i]->out();
        }
        return $r;
    }
    function type() {
        return "Container";
    }
}

class HTMLtxt {
    var $txt;

    function HTMLtxt(&$s) {
        HTMLdebug(5, " HTMLtxt::HTMLtxt");
        HTMLdebug(4, "HTMLtxt = $s");
        $this->txt = trim($s);
    }
    function show($in = 0) {
        HTMLdebug(7, " HTMLtxt::show($in)");
        return 'r'.str_repeat(' ', $in).$this->txt."\n";
    }
    function out() {
        HTMLdebug(7, ' HTMLtxt::out');
        return $this->txt;
    }
    function iden() {
        return "HTMLtxt";
    }
    function type() {
        return "HTMLtxt";
    }
}





// HTML Tag 
class HTMLattr {
    var $element;
    var $property;

    function HTMLattr($e, $p = "") {
        HTMLdebug(5, " HTMLattr::HTMLattr");
        $this->element = trim($e);
        $this->property = trim($p);
    }
    function show($in = 0) {
        HTMLdebug(7, " HTMLattr::show($in)");
        return ''.str_repeat(' ', $in).$this->element.' = '.$this->property
."\n";
    }
    function out() {        HTMLdebug(7, ' HTMLattr::out');
        return $this;
    }
    function type() {
        return "HTMLattr";
    }
    function iden() {
        return "HTMLattr";
    }
}






// HTML Tag  class
class HTMLtag {
    var $tag;    function HTMLtag($t) {
        HTMLdebug(5, " HTMLtag::HTMLtag");
        if(!preg_match('/(\/?\w+)(.*)/', $t, $a)) {
            die('_ tag');
        }
        $this->tag = strtolower($a[1]);
        if(count($a) > 2) {
            preg_match_all('/([\w-]+)(=\s*"?([^"]*)"?)?/', $a[2], $b);
                for($i = 0; $i < count($b[0]); $i++) {
                    $attr = new HTMLattr($b[1][$i], $b[3][$i]);
                    $this->push($attr);
                }
        }
    }
    function push(&$attr) {
        HTMLdebug(5, ' HTMLtag::push');
        $this->cnt++;
        array_push($this->attr, $attr);
    }            if($this->attr[$i]->element == $a) {
                return $this->attr[$i]->property;
            }
        }
        return NULL;
    }
    function show($in = 0) {
        global $SHOWINDENT;
        HTMLdebug(7, " HTMLtag::show($in)");
        $r = ''.str_repeat(' ', $in).'['.$this->tag."]\n";
        for($i = 0; $i < $this->cnt; $i++) {
            $r .= $i.'T'.$this->attr[$i]->show($in + $SHOWINDENT);
        }
        return $r;
    }
    function out() {
        HTMLdebug(7, ' HTMLtag::out');
        $r[] = $this;
        for($i = 0; $i < $this->cnt; $i++) {
            $r[] = $this->attr[$i]->out();
        }
        return $r;
    }
    function iden() {
        return $this->tag;
    }
    function type() {
        return "HTMLtag";
    }
}





// HTML para: hAtdh| container
// G
//      $sup Whr
//      $s   nBzry
//      $t   yz
// $this->iden er
// $this->obj[0] eyz
// $this->obj[1..]  subnodes
// $this->sup OWhr
class HTMLpara extends Container {
    var $iden;
    var $sup;

    function HTMLpara($sup, $s, $t = "") {
        HTMLdebug(5, " HTMLpara::HTMLpara, Wh [$sup]");
        $this->sup = $sup;
        if(is_object($t)) {
            $this->push($t);
        }
        if($s) {
            if(eregi('^<table', $s)) {
                $this->fixtable($s);
            }
            $this->parse(&$s);
        }
    }



    function iden() {
        HTMLdebug(5, ' HTMLpara::iden');
        return $this->iden;
    }


    function push(&$o) {
        HTMLdebug(5, ' HTMLpara::push');
        if(!isset($this->iden)) {
            $this->iden = $o->iden();
            HTMLdebug(3, 'eO ['.$this->iden.']');
        }
        if($o->type() == "HTMLpara") {
            HTMLdebug(3, 'e ['.$this->iden.']  e ['.$o->iden().']');
        } else {
            HTMLdebug(3, 'e ['.$this->iden.']   ['.$o->iden().']');
        }
        $this->cnt++;
        array_push($this->obj, $o);
    }


    function show($in = 0) {
        global $SHOWINDENT;
        HTMLdebug(7, " HTMLpara::show($in)");
        $r = 'e'.str_repeat(' ', $in).'{'.$this->iden."}\n";
        $r .= Container::show($in + $SHOWINDENT);
        return $r;
    }

    function out() {
        HTMLdebug(7, ' HTMLpara::out');
        $r[] = $this;
        for($i = 0; $i < $this->cnt; $i++) {
            $r[] = $this->obj[$i]->out();
        }
        return $r;
    }

    function type() {
        return "HTMLpara";
    }


    function parse($s) {
        HTMLdebug(5, ' HTMLpara::parse');
        while($s) {
            $this->parsetxt($s);
            $this->parsetag($s);
        }
        HTMLdebug(3, "[".$this->iden."] RF");
    }


    // RGr
    function parsetxt(&$s) {
        HTMLdebug(6, ' HTMLpara::parsetxt');
        $TxtRe = '/^([^<]*)/';
        if(preg_match($TxtRe, $s, $r)) {
            $txt = trim($r[1]);
            if(!empty($txt)) {
                $t = new HTMLtxt($txt);
                HTMLdebug(2, 'e ['.$this->iden.'] F ['.$t->iden().'] 
');
                $this->push($t);
                $s = trim(substr($s, strlen($r[1])));
                HTMLdebug(8, 's = '.$s);
            }
        }
    }


    // R: 
    function parsetag(&$s) {
        global $HTMLcont, $HTMLbrac;
        HTMLdebug(6, ' HTMLpara::parsetag');
        $TagRe = '/^<(\/?\w+[^>]*)>/';
        if(preg_match($TagRe, $s, $r)) {
            $tag = trim($r[1]);
            $t = new HTMLtag($tag);
            HTMLdebug(2, 'e ['.$this->iden.'] F ['.$t->iden().'] ')
;

            // S]tOC
            if(!in_array($t->tag, $HTMLcont)) {
                HTMLdebug(4, "$t->tag ]tOA~ parse ");
                $this->parsetagmono($s, $t, $tag);
                return true;
            }
            // Y
            $bndb = preg_quote(strtolower($tag), '/');
            $bnde = explode(" ", $tag);
            $bnde = strtolower($bnde[0]);
            $bnde = preg_quote($bnde, '/');

            // Bz
            if(in_array($t->tag, $HTMLbrac)) {
                HTMLdebug(4, "n [$tag]");
                $ns = $this->parsetagbrac($s, $bndb, $bnde);
            } else {
                HTMLdebug(4, " [$tag]");
                $ns = $this->parsetagunbrac($s, $tag, $bndb, $bnde);
            }

            $c = new HTMLpara($this->iden, trim($ns), $t);

            // SBz
            if($t->tag == 'table') {
                $c->parsetable();
            }
            $this->push($c);
        }
    }


    function parsetagmono(&$s, &$t, &$tag) {
        HTMLdebug(6, ' HTMLpara::parsetagmono');
        $s = trim(substr($s, strlen($tag) + 2));
        $this->push($t);
        HTMLdebug(8, 's = '.$s);
    }


    function parsetagbrac(&$s, &$bndb, &$bnde) {
        HTMLdebug(6, ' HTMLpara::parsetagbrac');
        $len = $this->parsebalance($s, $bnde);
        $p = trim(substr($s, 0, $len));
        $s = trim(substr($s, $len));
        $re = '/(<'.$bndb.'>(.*)(<\/'.$bnde.'[^>]*>))/i';
        // v
        if(preg_match($re, $p, $q)) {
            HTMLdebug(4, 'e: '.$q[2]);
            HTMLdebug(8, 's = '.$s);
            $ns = &$q[2];
        } else {
            HTMLdebug(0, '~Gregex = '.$re);
            HTMLdebug(0, '~Gp = '.$p);
            HTMLdebug(0, '~Gs = '.$s);
            die('io '.__line__);
        }
        return $ns;
    }


    function parsetagunbrac(&$s, &$tag, &$bndb, &$bnde) {
        global $HTMLbrac, $HTMLCloseTag;
        HTMLdebug(6, ' HTMLpara::parsetagunbrac');
        HTMLdebug(5, 'J '.$s);
        HTMLdebug(5, 'tag = '.$tag);
        $p = substr($s, strlen($tag) + 2);
        $skip = 0;
        while($p) {
            // Dr
            if(preg_match('/^([^<]+)/i', $p, $q)) {
                HTMLdebug(7, '[^<]+ = '.$q[1]);
                $k = strlen($q[1]);
                $skip += $k;
                $p = substr($p, $k);
            }
            // 
            if(preg_match('/^(<\/?([\w]+)[^>]*>[^<]*)/i', $p, $q)) {
                $CloseTag = &$q[2];
                HTMLdebug(7, 'q[2] = '.$CloseTag);
                if($CloseTag == $bnde) {
                    break;
                }
                $ct = $HTMLCloseTag["$bnde"];
                if(is_array($ct) && in_array($CloseTag, $ct)) {
                    break;
                }
            }
            // L_
            if(in_array($q[2], $HTMLbrac)) {
                $len = $this->parsebalance($p, $q[2]);
                $skip += $len;
                $p = substr($p, $len);
                continue;
            }
            $k= strlen($q[1]);
            $p = substr($p, $k);
            $skip += $k;
        }
        $re = '/(<'.$bndb.'>(.{'.$skip.'})(<\/?'.$bnde.'[^>]*>)?)/i';
        HTMLdebug(5, 'regex = '.$re);

        // v
        if(preg_match($re, $s, $p)) {
            HTMLdebug(4, "e: $p[2]");
            if($p[3] == '</'.$bnde.'>') {   // pGF
                $s = trim(substr($s, strlen($p[1])));
            } else {
                $s = trim(substr($s, strlen($p[1]) - strlen($p[3])));
            }
            HTMLdebug(8, 's = '.$s);
            $ns = &$p[2];
        } else {
            die('io, line: '.__line__);
        }
        return $ns;

    }


    // X
    function parsebalance($s, $bnde) {
        HTMLdebug(6, ' HTMLpara::parsebalance');
        HTMLdebug(5, 'J '.$s.' / '.$bnde);
        $tmp = $s;
        $balance = 0;
        $len = 0;
        $re = '/^(<\/?(\w+)[^>]*>([^<]*))/i';
        HTMLdebug(6, 'regex: '.$re);
        do {
            if(!preg_match($re, $tmp, $p)) {
                HTMLdebug(6, ' regex F');
                break;
            }
            if(strtolower($p[2]) != $bnde) {
                $sl = strlen($p[1]);
                $len += $sl;
                $tmp = trim(substr($tmp, $sl));
                continue;
            }
            if($p[1][1] == '/') {
                $balance--;
            } else {
                $balance++;
            }
            $sl = strlen($p[1]);
            $len += $sl;
            $tmp = trim(substr($tmp, strlen($p[1])));
            HTMLdebug(6, 'balance = '.$balance.' len = '.$len.' sl = '.$sl);
        } while($balance != 0);
            if($balance) {
                HTMLdebug(0, 'R~: '.$s);
                die('Line '.__line__);
            }
            $len -= strlen($p[3]);
            return $len;
    }


    // ApC
    function parsetable() {
        // `NGSBz rowspan p
        HTMLdebug(6, ' HTMLpara::parsetable');

        $td = array();
        $tr = 0;
        for($i = 1; $i < $this->cnt; $i++) {
            $o = &$this->obj[$i];
            if($o->iden() == 'tr') {
                $tr++;
                $ttd = 0;
                for($j = 1; $j < $o->cnt; $j++) {
                    $p = &$o->obj[$j];
                    if($p->iden() == 'td' || $p->iden() == 'th') {
                        if(($csp = $p->obj[0]->getattr('colspan')) != NULL) {
                            $ttd += $csp;
                        } else {
                            $ttd++;
                        }
                    }
                }
                $td[] = $ttd;
            }        }
        $max = 0;
        for($i = 0; $i < count($td); $i++) {
            if($max < $td[$i]) {
                HTMLdebug(4, "td[".$i."] = ".$td[$i]);
                $max = $td[$i];
            }
        }
        $td = $max;
        HTMLdebug(3, ' '.$tr.' CA'.$td.'');
        // oO table I
        $this->obj[0]->push(new HTMLattr('rows', $tr));
        $this->obj[0]->push(new HTMLattr('cols', $td));
    }


    //  table
    function fixtable(&$s) {
        HTMLdebug(6, ' HTMLpara::fixtable');
        HTMLdebug(8, 'JF '.$s);
        $re = '/^(<(\/?\w+)[^>]*>[^<]*)/i';
        $tmp = $s;
        $tags = array();
        $len = 0;
        while(preg_match($re, $tmp, $p)) {
            if($p[2] == 'td' || $p[2] == 'th') {
                break;
            }
            $tags[] = $p[2];
            $sl = strlen($p[1]);
            $len += $sl;
            $tmp = substr($tmp, $sl);
        }
        // [J@ <tr>
        $r = substr($s, 0, $len);
        if(!in_array('tr', $tags)) {
            HTMLdebug(6, '[JF <tr> ');
            $r .= '<tr>';
        }
        $oldlen = $len;
        while(preg_match($re, $tmp, $p)) {
            $ot = $tags;
            $ol = $len;
            $tags = $p[2];            $len = strlen($p[1]);
            $tmp = substr($tmp, $len);
        }
        // h@ <tr>
        if($ot == 'tr') {
            HTMLdebug(5, 'F <tr> ');
            $r .= substr($s, $oldlen, strlen($s) - $oldlen - $len - $ol) .
                  substr($s, strlen($s) - $len);
        } else {
            $r .= substr($s, $oldlen);
        }
        $s = trim($r);
    }
}





// HTML J class
class HTMLin extends Container {
    function HTMLin($s) {
        HTMLdebug(5, " HTMLin::HTMLin()");
        $s = strtr($s, "\n", ' ');
        $s = strtr($s, "\r", '');
        $s = preg_replace('/<!.*>/U', '', $s);          // Bz DTD//CSS
        $s = preg_replace('/<[?%].*[?%]>/U', '', $s);   // Bz PHP/ASP 
        $s = preg_replace('/>\s+</U', '><', $s);        // d
        $s = strchr($s, '<');                       // Bz@eF

        $this->iden = "HTMLin";
        $t = new HTMLtag("HTMLin");
        $this->push(new HTMLpara($this->iden, $s, $t));
    }
    function show($in = 0) {
        HTMLdebug(7, " HTMLin::show($in)");
        $r = Container::show($in);
        return $r;
    }
    function out() {
        HTMLdebug(7, ' HTMLin::out');
        for($i = 0; $i < $this->cnt; $i++) {
            $r[] = $this->obj[$i]->out();
        }
        return $r;
    }
    function type() {
        return "HTMLin";
    }
    function iden() {
        return "HTMLin";
    }
}
?>



    function getattr($a) {
        HTMLdebug(5, ' HTMLtag::getattr');
        for($i = 0; $i < $this->cnt; $i++) {

    var $attr = array();
    var $cnt = 0;   // attr 






