//<![CDATA[
function connect(div1, div2, color, thickness) {
var off1 = getOffset(div1);
var off2 = getOffset(div2);
// bottom right
var x1 = off1.left + (off1.width-10);
var y1 = off1.top + (off1.height-10);
// top right
var x2 = off2.left + (off2.width-10);
var y2 = off2.top+10;
// distance
var length = Math.sqrt(((x2-x1) * (x2-x1)) + ((y2-y1) * (y2-y1)));
// center
var cx = ((x1 + x2) / 2) - (length / 2);
var cy = ((y1 + y2) / 2) - (thickness / 2);
// angle
var angle = Math.atan2((y1-y2),(x1-x2))*(180/Math.PI);
// make hr
var htmlLine = "<div style='padding:0px; margin:0px; height:" + thickness + "px; background-color:" + color + "; line-height:1px; position:absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -moz-transform:rotate(" + angle + "deg); -webkit-transform:rotate(" + angle + "deg); -o-transform:rotate(" + angle + "deg); -ms-transform:rotate(" + angle + "deg); transform:rotate(" + angle + "deg); z-index:-10; opacity:0.55;' />";
//
//alert(htmlLine);
document.body.innerHTML += htmlLine;
}
function getOffset( el ) {
var _x = 0;
var _y = 0;
var _w = el.offsetWidth|0;
var _h = el.offsetHeight|0;
while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
_x += el.offsetLeft - el.scrollLeft;
_y += el.offsetTop - el.scrollTop;
el = el.offsetParent;
}
return { top: _y, left: _x, width: _w, height: _h};
}
window.testIt = function() {
var div1 = document.getElementById('app');
var div2 = document.getElementById('principale')
connect(div1, div2, "#555", 2);
}
function setHeight(el,taille){
     $(el).css({height:taille+'px'});
}
//]]>