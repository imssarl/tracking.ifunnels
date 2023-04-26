///////////////////////////////////////////////////////
// function getCookie(c_name)
// { 
// if (document.cookie.length>0)
//   {
//   c_start=document.cookie.indexOf(c_name + "=")
//   if (c_start!=-1)
//     { 
//     c_start=c_start + c_name.length+1 
//     c_end=document.cookie.indexOf(";",c_start)
//     if (c_end==-1) c_end=document.cookie.length
//     return unescape(document.cookie.substring(c_start,c_end))
//     } 
//   }
// return null
// }
// 
// function setCookie(c_name,value)
// {
// //var exdate=new Date()
// //exdate.setDate(exdate.getDate()+expiredays)
// document.cookie=c_name+ "=" +escape(value)
// //((expiredays==null) ? "" : ";expires="+exdate)
// //alert(document.cookie)
// }
// 
// ///////////////////////////////////////////////////////
// function iPop_close(pop_name) 
// { 
//   setCookie('username',"abc123456def");
//   if(!pop_name) pop_name = 'pid';
//   DHTMLAPI_hide(pop_name);
// 
// }
function iPop_close() 
 { 
 //setCookie('username',"abc123456def");
 DHTMLAPI_hide('pid');
 }
function iPop_init0() {
  if (!iPop_CookieCheck()) return;
  DHTMLAPI_init();
  pid_Obj = DHTMLAPI_getRawObject('pid');
  pid_move();
  window.onscroll=pid_move;
  window.onresize=pid_move;
}
function iPop_init1() {
  if (!iPop_CookieCheck()) return;
  DHTMLAPI_init();
  pid_delta = 16;
  pid_Obj = DHTMLAPI_getRawObject('pid');

  var theObj = pid_Obj; if (theObj && isCSS) theObj = theObj.style;
  if (theObj && theObj.visibility == 'hidden') return;
  if (theObj && theObj.display == 'none') { theObj.display = 'block'; theObj.position = 'absolute';  }
  DHTMLAPI_shiftTo(pid_Obj, 0, 5000);
  var center = DHTMLAPI_positionWindow(pid_Obj, true);
  pid_x = center[0];
  pid_y = center[1];
  var w_scroll = DHTMLAPI_getScrollWindow();
  var start_y = parseInt((w_scroll[1]-pid_y-DHTMLAPI_getObjectHeight(pid_Obj)-100)/100)*100 + pid_y;
  //DHTMLAPI_shiftTo(pid_Obj, pid_x, start_y);
  pid_dropstart=setInterval('pid_drop()',50);
}
function pid_move() {
  if (window.pid_timeout) clearTimeout(window.pid_timeout);
  if (!pid_Obj) return;
  var theObj = pid_Obj; if (theObj && isCSS) theObj = theObj.style;
  if (theObj && theObj.visibility == 'hidden') return;
  if (theObj && theObj.display == 'none') { theObj.display = 'block'; theObj.position = 'absolute'; }
  DHTMLAPI_positionWindow(pid_Obj);
  window.pid_timeout = setTimeout('pid_move()', 100);
}
function DHTMLAPI_positionWindow(elemID, positionOnly) {
  var obj = DHTMLAPI_getRawObject(elemID);
  var position = obj.getAttribute('pos');
  var scrollX = 0, scrollY = 0;
  if (document.body && typeof(document.body.scrollTop) != 'undefined') {
    scrollX += document.body.scrollLeft;
    scrollY += document.body.scrollTop;
    if (0 == document.body.scrollTop
    && document.documentElement
    && typeof(document.documentElement.scrollTop) != 'undefined') {
      scrollX += document.documentElement.scrollLeft;
      scrollY += document.documentElement.scrollTop;
    }	
  } else if (typeof(window.pageXOffset) != 'undefined') {
    scrollX += window.pageXOffset;
    scrollY += window.pageYOffset;
  }
  var x = Math.round((DHTMLAPI_getInsideWindowWidth( )/2) - (DHTMLAPI_getObjectWidth(obj)/2)) + scrollX;
  var y = Math.round((DHTMLAPI_getInsideWindowHeight( )/2) - (DHTMLAPI_getObjectHeight(obj)/2)) + scrollY;
  var shift_position = parseInt(0);
  if (isNaN(shift_position)) shift_position = 0;
  switch (position) { 
    case 'tc': y = scrollY+shift_position; break;
    case 'tl': y = scrollY+shift_position; x = scrollX+shift_position; break;
    case 'tr': y = scrollY+shift_position; x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position; break;
    case 'ml': x = scrollX+shift_position; break;
    case 'mr': x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position; break;
    case 'bc': y = Math.round(DHTMLAPI_getInsideWindowHeight( ) - DHTMLAPI_getObjectHeight(obj)) + scrollY-shift_position; break;
    case 'bl': y = Math.round(DHTMLAPI_getInsideWindowHeight( ) - DHTMLAPI_getObjectHeight(obj)) + scrollY-shift_position; x = scrollX+shift_position; break;
    case 'br': y = Math.round(DHTMLAPI_getInsideWindowHeight( ) - DHTMLAPI_getObjectHeight(obj)) + scrollY-shift_position; x = Math.round(DHTMLAPI_getInsideWindowWidth( ) - DHTMLAPI_getObjectWidth(obj)) + scrollX-shift_position; break;
  }
  if (!positionOnly) DHTMLAPI_shiftTo(obj, x, y);
  return [x, y];
}
function pid_drop() {
  var y = DHTMLAPI_getObjectTop(pid_Obj);
  if ( pid_y > y ) DHTMLAPI_shiftTo(pid_Obj, pid_x, 50+y);
  else { 
    clearInterval(pid_dropstart);
    pid_vibrostart = setInterval('pid_vibro()',40);
  }
}
function pid_vibro() {
  var y = DHTMLAPI_getObjectTop(pid_Obj);
  DHTMLAPI_shiftTo(pid_Obj, pid_x, y-pid_delta);
  if (pid_delta<0) pid_delta += 4;
  pid_delta *= -1;
  if (pid_delta==0) { 
    clearInterval(pid_vibrostart);
    pid_move();
    window.onscroll=pid_move;
    window.onresize=pid_move;
  }
}
function DHTMLAPI_hide(obj) {
  var theObj = DHTMLAPI_getObject(obj);
  if (theObj) theObj.visibility = 'hidden';
}
function DHTMLAPI_getRawObject(obj) {
  var theObj;
  if (typeof obj == 'string') {
    if (isW3C) theObj = document.getElementById(obj);
    else if (isIE4) theObj = document.all(obj);
    else if (isNN4) theObj = DHTMLAPI_seekLayer(document, obj);
  } else theObj = obj;
  return theObj;
}
function DHTMLAPI_shiftTo(obj, x, y) {
  var theObj = DHTMLAPI_getObject(obj);
  if (theObj) {
    if (isCSS) {
      var units = (typeof theObj.left == 'string') ? 'px' : 0;
      theObj.left = x + units;
      theObj.top = y + units;

    } else if (isNN4) theObj.moveTo(x,y);
  }
}
function DHTMLAPI_getScrollWindow() {
  var scrollX = 0, scrollY = 0;
  if (document.body && typeof(document.body.scrollTop) != 'undefined') {
    scrollX += document.body.scrollLeft;
    scrollY += document.body.scrollTop;
  } else if (typeof(window.pageXOffset) != 'undefined') {
    scrollX += window.pageXOffset;
    scrollY += window.pageYOffset;
  }
  return [scrollX, scrollY];
}
function DHTMLAPI_getObjectHeight(obj)  {
  var elem = DHTMLAPI_getRawObject(obj);
  var result = 0;
  if (elem.offsetHeight){ result = elem.offsetHeight;   }
  else if (elem.clip && elem.clip.height)  result = elem.clip.height; 
  else if (elem.style && elem.style.pixelHeight) result = elem.style.pixelHeight;
  return parseInt(result);
}
function DHTMLAPI_getObjectTop(obj)  {
  var elem = DHTMLAPI_getRawObject(obj);
  var result = 0;
  if (document.defaultView) {
    var style = document.defaultView;
    var cssDecl = style.getComputedStyle(elem, '');
    result = cssDecl.getPropertyValue('top');
//alert(result);
//result= '1000px';	
  }
  else if (elem.currentStyle) result = elem.currentStyle.top;
  else if (elem.style) result = elem.style.top;
  else if (isNN4) result = elem.top;
  return parseInt(result);
}
function DHTMLAPI_getObject(obj) {
  var theObj = DHTMLAPI_getRawObject(obj);
  if (theObj && isCSS) theObj = theObj.style;
  return theObj;
}
function DHTMLAPI_seekLayer(doc, name) {
  var theObj;
  for (var i = 0; i < doc.layers.length; i++) {
    if (doc.layers[i].name == name) {
      theObj = doc.layers[i];
      break;
    }
    if (doc.layers[i].document.layers.length > 0) theObj = DHTMLAPI_seekLayer(document.layers[i].document, name);
  }
  return theObj;
}
function DHTMLAPI_getInsideWindowWidth( ) {
   if (window.innerWidth) return window.innerWidth;
   else if (isIE6CSS) alert(window.innerWidth);
   else if (document.body && document.body.clientWidth) return document.body.clientWidth;
  return 0;
}
function DHTMLAPI_getInsideWindowHeight( ) {
//   if (window.innerHeight) return window.innerHeight; 
//   else if (isIE6CSS) return document.body.parentElement.clientHeight;
//   else if (document.body && document.body.clientHeight) return document.body.clientHeight;
  return 122;
}
function DHTMLAPI_getObjectWidth(obj)  {
  var elem = DHTMLAPI_getRawObject(obj);
  var result = 0;
  if (elem.offsetWidth) result = elem.offsetWidth;
  else if (elem.clip && elem.clip.width) result = elem.clip.width;
  else if (elem.style && elem.style.pixelWidth) result = elem.style.pixelWidth;
  return parseInt(result);
}
function DHTMLAPI_init( ) {
  if (document.images) {
    isCSS = (document.body && document.body.style) ? true : false;
    isW3C = (isCSS && document.getElementById) ? true : false;
    isIE4 = (isCSS && document.all) ? true : false;
    isNN4 = (document.layers) ? true : false;
    isIE6CSS = (document.compatMode && document.compatMode.indexOf('CSS1') >= 0) ? true : false;
  }
}
setTimeout('iPop_init1()', 500);

function iPop_CookieCheck() {return true;}