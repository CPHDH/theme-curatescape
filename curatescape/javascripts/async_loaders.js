/*! 
loadJS: load a JS file asynchronously. 
[c]2014 @scottjehl, Filament Group, Inc. (Based on http://goo.gl/REQGQ by Paul Irish). 
Licensed MIT 
*/

function loadJS(src,cb){"use strict";var ref=window.document.getElementsByTagName("script")[0];var script=window.document.createElement("script");script.src=src;script.async=true;ref.parentNode.insertBefore(script,ref);if(cb&&typeof(cb)==="function"){script.onload=cb;}
return script;}

/*!
loadCSS: load a CSS file asynchronously.
[c]2014 @scottjehl, Filament Group, Inc.
Licensed MIT
*/

function loadCSS(href,before,media){"use strict";var ss=window.document.createElement("link");var ref=before||window.document.getElementsByTagName("script")[0];var sheets=window.document.styleSheets;ss.rel="stylesheet";ss.href=href;ss.media="only x";ref.parentNode.insertBefore(ss,ref);function toggleMedia(){var defined;for(var i=0;i<sheets.length;i++){if(sheets[i].href&&sheets[i].href.indexOf(href)>-1){defined=true;}}
if(defined){ss.media=media||"all";}
else{setTimeout(toggleMedia);}}
toggleMedia();return ss;}