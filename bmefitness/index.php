<?php
	session_start();
	require_once("code/functions/database.php");
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Content-Language" content="hun">
		<meta lang="hu">
		<title>BME Fitness admin</title>

		<script src="code/js/jquery.js" type="text/javascript"></script>
		<link rel="stylesheet" href="code/css/popup.css" />
		<script src="code/js/popup.js" type="text/javascript"></script>
		<link rel="stylesheet" href="code/css/reset.css" />
		<link rel="stylesheet" href="code/css/base.css" />
		<link rel="stylesheet" href="code/css/bme.css" />
		<script type="text/javascript" src="code/js/bme.js"></script>
		<script type="text/javascript" src="code/js/imageupload.js"></script>
		<script type="text/javascript" src="code/jscolor/jscolor.js"></script>

<?php

// ddddddd
//	fsdfa
//	fadsf
	/*


<script src="js/log.js" type="text/javascript"></script>
<script src="js/jquery.js" type="text/javascript"></script>


<link rel="stylesheet" href="css/slider.css" />
<script src="js/slider.js" type="text/javascript"></script>

*/

?>

	</head>
	<body onload="hideddrivetip(); change_main_site('distress');">

		<div id="dhtmltooltip" class="tooltipandinfodivstyle"></div>

		<script type="text/javascript">

		/***********************************************
		 * Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
		 * This notice MUST stay intact for legal use
		 * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
		 ***********************************************/

		var offsetxpoint = -10; //Customize x offset of tooltip
		var offsetypoint = 20; //Customize y offset of tooltip
		var ie = document.all;
		var ns6 = document.getElementById && !document.all;
		var enabletip = false;
		if (ie || ns6) var tipobj = document.all ? document.all["dhtmltooltip"] : document.getElementById ? document.getElementById("dhtmltooltip") : "";

		function ietruebody() {
			return (document.compatMode && document.compatMode != "BackCompat") ? document.documentElement : document.body;
		}

		function ddrivetip(thetext, thecolor, thewidth) {
			if (ns6 || ie) {
				if (typeof thewidth != "undefined")
					tipobj.style.width=thewidth + "px";
				if (typeof thecolor != "undefined" && thecolor != "")
					tipobj.style.backgroundColor = thecolor;

				tipobj.innerHTML = thetext;

				return false;
			}
		}

		function positiontip(e) {
			if (enabletip) {
				var curX = (ns6) ? e.pageX : event.clientX+ietruebody().scrollLeft;
				var curY = (ns6) ? e.pageY : event.clientY+ietruebody().scrollTop;
				//Find out how close the mouse is to the corner of the window
				var rightedge = ie && !window.opera ? ietruebody().clientWidth - event.clientX - offsetxpoint : window.innerWidth - e.clientX - offsetxpoint - 20;
				var bottomedge = ie && !window.opera ? ietruebody().clientHeight - event.clientY - offsetypoint : window.innerHeight - e.clientY - offsetypoint - 20;

				var leftedge = (offsetxpoint < 0) ? offsetxpoint * (-1) : -1000;

				//if the horizontal distance isn't enough to accomodate the width of the context menu
				if (rightedge < tipobj.offsetWidth)
					//move the horizontal position of the menu to the left by it's width
					tipobj.style.left = ie ? ietruebody().scrollLeft + event.clientX - tipobj.offsetWidth + "px" : window.pageXOffset + e.clientX - tipobj.offsetWidth + "px";
				else if (curX<leftedge)
					tipobj.style.left = "5px";
				else
					//position the horizontal position of the menu where the mouse is positioned
					tipobj.style.left = curX + offsetxpoint + "px";

				//same concept with the vertical position
				if (bottomedge < tipobj.offsetHeight)
					tipobj.style.top=ie ? ietruebody().scrollTop + event.clientY - tipobj.offsetHeight - offsetypoint + "px" : window.pageYOffset + e.clientY - tipobj.offsetHeight - offsetypoint + "px";
				else
					tipobj.style.top = curY + offsetypoint + "px";

				tipobj.style.visibility = "visible";
			}
		}

		function hideddrivetip() {
			if (ns6 || ie) {
				enabletip = false;
				tipobj.style.visibility = "hidden";
				tipobj.style.left = "-1000px";
				tipobj.style.backgroundColor = '';
				tipobj.style.width = '';
			}
		}

		document.onmousemove = positiontip;

		</script>


	<!-- felugro szerkeszto ablakok -->
		<!-- hatter miatt kell -->
		<div id="backgroundPopup"></div>
		<div id="popupNewOrEdit" class="popupContact popupSajat">
			<a class="popupContactClose">X</a>
			<h1 class="editTitle"></h1>
			<p class="contactArea" id="newOrEditArea"></p>
			<a id="neworeditlink">létrehozás</a>
		</div>
		<div id="popupUploadFile" class="popupContact popupSajat popupSajatCenter">
			<p class="contactArea" id="uploadArea"></p>
			<img class="imageArea" id="uploadImage">
			<br><br>
			<a id="uploadlink" onclick="disablePopup();">OK</a>
		</div>
		<div id="popupAllowDistress" class="popupContact popupSajat popupSajatCenter">
			<a class="popupContactClose">X</a>
			<h1 class="editTitle">Foglalás engedélyezése</h1>
			<p class="contactArea" id="allowDistressArea"></p>
			<br><br>
			<a id="allowDistressButton">Elfogad</a>&nbsp;&nbsp;&nbsp;&nbsp;<a id="disallowDistressButton">Elutasít</a>
		</div>


	<!-- oldal teruletei -->
		<div class="menu" id="menu"></div>
		<div id="acontent">
			<div id="settings"></div>
			<div id="content"></div>
		</div>

	</body>
</html>
