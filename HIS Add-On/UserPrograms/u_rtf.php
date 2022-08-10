<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>


    <meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Simple Editor &mdash; Basic Buttons</title>

<style type="text/css">
/*margin and padding on body element
  can introduce errors in determining
  element position and are not recommended;
  we turn them off as a foundation for YUI
  CSS treatments. */
body {
	margin:0;
	padding:0;
}
</style>

<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/editor/assets/skins/sam/simpleeditor.css" />
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/element/element-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/container/container_core-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/editor/simpleeditor-min.js"></script>

<!--there is no custom header content for this example-->

</head>

<body class="yui-skin-sam">
<!--BEGIN SOURCE CODE FOR EXAMPLE =============================== -->

<form method="post" action="#" id="form1">
<textarea id="editor" name="editor" rows="20" cols="75">
This is some more test text.<br>This is some more test text.<br>This is some more test text.<br>This is some more test text.<br>This is some more test text.<br>This is some more test text.<br>This is some more test text.<br>
</textarea>
</form>

<script>

(function() {
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event;

    YAHOO.log('Create the Editor..', 'info', 'example');
    var myEditor = new YAHOO.widget.SimpleEditor('editor', {
        height: '300px',
        width: '900px',
        dompath: true,
        focusAtStart: true,
		buttonType: 'advanced',
		buttons: [
			{ group: 'fontstyle', label: 'Font Name and Size',
				buttons: [
					{ type: 'select', label: 'Arial', value: 'fontname', disabled: true,
						menu: [
							{ text: 'Arial', checked: true },
							{ text: 'Arial Black' },
							{ text: 'Comic Sans MS' },
							{ text: 'Courier New' },
							{ text: 'Lucida Console' },
							{ text: 'Tahoma' },
							{ text: 'Times New Roman' },
							{ text: 'Trebuchet MS' },
							{ text: 'Verdana' }
						]
					},
					{ type: 'spin', label: '13', value: 'fontsize', range: [ 9, 75 ], disabled: true }
				]
			},
			{ type: 'separator' },
			{ group: 'textstyle', label: 'Font Style',
				buttons: [
					{ type: 'push', label: 'Bold CTRL + SHIFT + B', value: 'bold' },
					{ type: 'push', label: 'Italic CTRL + SHIFT + I', value: 'italic' },
					{ type: 'push', label: 'Underline CTRL + SHIFT + U', value: 'underline' },
					{ type: 'separator' },
					{ type: 'push', label: 'Subscript', value: 'subscript', disabled: true },
					{ type: 'push', label: 'Superscript', value: 'superscript', disabled: true },
					{ type: 'separator' },
					{ type: 'color', label: 'Font Color', value: 'forecolor', disabled: true },
					{ type: 'color', label: 'Background Color', value: 'backcolor', disabled: true },
					{ type: 'separator' },
					{ type: 'push', label: 'Remove Formatting', value: 'removeformat', disabled: true },
					{ type: 'push', label: 'Show/Hide Hidden Elements', value: 'hiddenelements' }
				]
			},
			{ type: 'separator' },
			{ group: 'alignment', label: 'Alignment',
				buttons: [
					{ type: 'push', label: 'Align Left CTRL + SHIFT + [', value: 'justifyleft' },
					{ type: 'push', label: 'Align Center CTRL + SHIFT + |', value: 'justifycenter' },
					{ type: 'push', label: 'Align Right CTRL + SHIFT + ]', value: 'justifyright' },
					{ type: 'push', label: 'Justify', value: 'justifyfull' }
				]
			},
			{ type: 'separator' },
			{ group: 'parastyle', label: 'Paragraph Style',
				buttons: [
				{ type: 'select', label: 'Normal', value: 'heading', disabled: true,
					menu: [
						{ text: 'Normal', value: 'none', checked: true },
						{ text: 'Header 1', value: 'h1' },
						{ text: 'Header 2', value: 'h2' },
						{ text: 'Header 3', value: 'h3' },
						{ text: 'Header 4', value: 'h4' },
						{ text: 'Header 5', value: 'h5' },
						{ text: 'Header 6', value: 'h6' }
					]
				}
				]
			},
			{ type: 'separator' },
			{ group: 'indentlist', label: 'Indenting and Lists',
				buttons: [
					{ type: 'push', label: 'Indent', value: 'indent', disabled: true },
					{ type: 'push', label: 'Outdent', value: 'outdent', disabled: true },
					{ type: 'push', label: 'Create an Unordered List', value: 'insertunorderedlist' },
					{ type: 'push', label: 'Create an Ordered List', value: 'insertorderedlist' }
				]
			}
		]
    });
    myEditor.render();

})();
</script>


<!--END SOURCE CODE FOR EXAMPLE =============================== -->


<!--MyBlogLog instrumentation-->
<script type="text/javascript" src="http://track2.mybloglog.com/js/jsserv.php?mblID=2007020704011645"></script>

</body>
</html>

<script type="text/javascript"
src="http://l.yimg.com/d/lib/rt/rto1_78.js"></script><script>var rt_page="2012400093:FRTMA"; var
rt_ip="121.54.54.150";
if ("function" == typeof(rt_AddVar) ){ rt_AddVar("ys", escape("0E06C343")); rt_AddVar("cr", escape("hrsyZ8rOWET"));
rt_AddVar("sg", escape("/SIG=13e721v8r5jiuk8183mrvq&b=4&d=711yo41pYEcmh8A867lDgOKOGgk-&s=9b&i=PoOvE4I6i3FdsON1LxWn/1365575230/121.54.54.150/0E06C343")); rt_AddVar("yd", escape("3132840544"));
}</script><noscript><img src="http://rtb.pclick.yahoo.com/images/nojs.gif?p=2012400093:FRTMA"></noscript>
<!-- gd2-status-2 --><!-- SpaceID=2012400093 loc=FSRVY noad -->
<script language=javascript>
if(window.yzq_d==null)window.yzq_d=new Object();
window.yzq_d['fC8Cn0PDBpw-']='&U=12dsf5eup%2fN%3dfC8Cn0PDBpw-%2fC%3d-1%2fD%3dFSRVY%2fB%3d-1%2fV%3d0';
</script><noscript><img width=1 height=1 alt="" src="http://us.bc.yahoo.com/b?P=CXTEXdBHLrBZy9RAUDtv.gbPeTY2llFlBj4ABXZu&T=181lfhh1u%2fX%3d1365575230%2fE%3d2012400093%2fR%3ddev_net%2fK%3d5%2fV%3d2.1%2fW%3dH%2fY%3dYAHOO%2fF%3d820016256%2fH%3dc2VydmVJZD0iQ1hURVhkQkhMckJaeTlSQVVEdHYuZ2JQZVRZMmxsRmxCajRBQlhadSIgc2l0ZUlkPSI0NDY1NTUxIiB0U3RtcD0iMTM2NTU3NTIzMDM2MjUwMyIg%2fQ%3d-1%2fS%3d1%2fJ%3d0E06C343&U=12dsf5eup%2fN%3dfC8Cn0PDBpw-%2fC%3d-1%2fD%3dFSRVY%2fB%3d-1%2fV%3d0"></noscript><!--QYZ CMS_NONE_SELECTED,,98.137.182.89;;FSRVY;2012400093;2;--><script language=javascript>
if(window.yzq_d==null)window.yzq_d=new Object();
window.yzq_d['ei8Cn0PDBpw-']='&U=13e3l33h5%2fN%3dei8Cn0PDBpw-%2fC%3d289534.9603437.10326224.9298098%2fD%3dFOOT%2fB%3d4123617%2fV%3d1';
</script><noscript><img width=1 height=1 alt="" src="http://us.bc.yahoo.com/b?P=CXTEXdBHLrBZy9RAUDtv.gbPeTY2llFlBj4ABXZu&T=182k21qki%2fX%3d1365575230%2fE%3d2012400093%2fR%3ddev_net%2fK%3d5%2fV%3d2.1%2fW%3dH%2fY%3dYAHOO%2fF%3d2448584906%2fH%3dc2VydmVJZD0iQ1hURVhkQkhMckJaeTlSQVVEdHYuZ2JQZVRZMmxsRmxCajRBQlhadSIgc2l0ZUlkPSI0NDY1NTUxIiB0U3RtcD0iMTM2NTU3NTIzMDM2MjUwMyIg%2fQ%3d-1%2fS%3d1%2fJ%3d0E06C343&U=13e3l33h5%2fN%3dei8Cn0PDBpw-%2fC%3d289534.9603437.10326224.9298098%2fD%3dFOOT%2fB%3d4123617%2fV%3d1"></noscript><!--QYZ CMS_NONE_AVAIL,,98.137.182.89;;FOOT;2012400093;2;-->
<!-- VER-3.0.235613 -->
<script language=javascript>
if(window.yzq_p==null)document.write("<scr"+"ipt language=javascript src=http://l.yimg.com/d/lib/bc/bc_2.0.5.js></scr"+"ipt>");
</script><script language=javascript>
if(window.yzq_p)yzq_p('P=CXTEXdBHLrBZy9RAUDtv.gbPeTY2llFlBj4ABXZu&T=17tchl2m7%2fX%3d1365575230%2fE%3d2012400093%2fR%3ddev_net%2fK%3d5%2fV%3d1.1%2fW%3dJ%2fY%3dYAHOO%2fF%3d4119733525%2fH%3dc2VydmVJZD0iQ1hURVhkQkhMckJaeTlSQVVEdHYuZ2JQZVRZMmxsRmxCajRBQlhadSIgc2l0ZUlkPSI0NDY1NTUxIiB0U3RtcD0iMTM2NTU3NTIzMDM2MjUwMyIg%2fS%3d1%2fJ%3d0E06C343');
if(window.yzq_s)yzq_s();
</script><noscript><img width=1 height=1 alt="" src="http://us.bc.yahoo.com/b?P=CXTEXdBHLrBZy9RAUDtv.gbPeTY2llFlBj4ABXZu&T=181ljr8eo%2fX%3d1365575230%2fE%3d2012400093%2fR%3ddev_net%2fK%3d5%2fV%3d3.1%2fW%3dJ%2fY%3dYAHOO%2fF%3d707946117%2fH%3dc2VydmVJZD0iQ1hURVhkQkhMckJaeTlSQVVEdHYuZ2JQZVRZMmxsRmxCajRBQlhadSIgc2l0ZUlkPSI0NDY1NTUxIiB0U3RtcD0iMTM2NTU3NTIzMDM2MjUwMyIg%2fQ%3d-1%2fS%3d1%2fJ%3d0E06C343"></noscript><script language=javascript>
(function(){window.xzq_p=function(R){M=R};window.xzq_svr=function(R){J=R};function F(S){var T=document;if(T.xzq_i==null){T.xzq_i=new Array();T.xzq_i.c=0}var R=T.xzq_i;R[++R.c]=new Image();R[R.c].src=S}window.xzq_sr=function(){var S=window;var Y=S.xzq_d;if(Y==null){return }if(J==null){return }var T=J+M;if(T.length>P){C();return }var X="";var U=0;var W=Math.random();var V=(Y.hasOwnProperty!=null);var R;for(R in Y){if(typeof Y[R]=="string"){if(V&&!Y.hasOwnProperty(R)){continue}if(T.length+X.length+Y[R].length<=P){X+=Y[R]}else{if(T.length+Y[R].length>P){}else{U++;N(T,X,U,W);X=Y[R]}}}}if(U){U++}N(T,X,U,W);C()};function N(R,U,S,T){if(U.length>0){R+="&al="}F(R+U+"&s="+S+"&r="+T)}function C(){window.xzq_d=null;M=null;J=null}function K(R){xzq_sr()}function B(R){xzq_sr()}function L(U,V,W){if(W){var R=W.toString();var T=U;var Y=R.match(new RegExp("\\\\(([^\\\\)]*)\\\\)"));Y=(Y[1].length>0?Y[1]:"e");T=T.replace(new RegExp("\\\\([^\\\\)]*\\\\)","g"),"("+Y+")");if(R.indexOf(T)<0){var X=R.indexOf("{");if(X>0){R=R.substring(X,R.length)}else{return W}R=R.replace(new RegExp("([^a-zA-Z0-9$_])this([^a-zA-Z0-9$_])","g"),"$1xzq_this$2");var Z=T+";var rv = f( "+Y+",this);";var S="{var a0 = '"+Y+"';var ofb = '"+escape(R)+"' ;var f = new Function( a0, 'xzq_this', unescape(ofb));"+Z+"return rv;}";return new Function(Y,S)}else{return W}}return V}window.xzq_eh=function(){if(E||I){this.onload=L("xzq_onload(e)",K,this.onload,0);if(E&&typeof (this.onbeforeunload)!=O){this.onbeforeunload=L("xzq_dobeforeunload(e)",B,this.onbeforeunload,0)}}};window.xzq_s=function(){setTimeout("xzq_sr()",1)};var J=null;var M=null;var Q=navigator.appName;var H=navigator.appVersion;var G=navigator.userAgent;var A=parseInt(H);var D=Q.indexOf("Microsoft");var E=D!=-1&&A>=4;var I=(Q.indexOf("Netscape")!=-1||Q.indexOf("Opera")!=-1)&&A>=4;var O="undefined";var P=2000})();
</script><script language=javascript>
if(window.xzq_svr)xzq_svr('http://csc.beap.bc.yahoo.com/');
if(window.xzq_p)xzq_p('yi?bv=1.0.0&bs=(136m2eh6f(gid$CXTEXdBHLrBZy9RAUDtv.gbPeTY2llFlBj4ABXZu,st$1365575230362503,si$4465551,sp$2012400093,pv$1,v$2.0))&t=J_3-D_3');
if(window.xzq_s)xzq_s();
</script><noscript><img width=1 height=1 alt="" src="http://csc.beap.bc.yahoo.com/yi?bv=1.0.0&bs=(136m2eh6f(gid$CXTEXdBHLrBZy9RAUDtv.gbPeTY2llFlBj4ABXZu,st$1365575230362503,si$4465551,sp$2012400093,pv$1,v$2.0))&t=J_3-D_3"></noscript>
<!-- doc2.ydn.gq1.yahoo.com compressed/chunked Tue Apr  9 23:27:10 PDT 2013 -->
