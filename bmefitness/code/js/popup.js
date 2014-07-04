//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;
var actualPopup = "";
var doNotDisable = false;

//loading popup with jQuery magic!
function loadPopup() {
	//loads popup only if it is disabled
	if (popupStatus === 0) {
		$("#backgroundPopup").css( {
			"opacity": "0.4"
		});
		$("#backgroundPopup").fadeIn("normal");
		$(actualPopup).fadeIn("normal");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup() {
	if (doNotDisable)
		return;

	//disables popup only if it is enabled
	if (popupStatus == 1) {
		$("#backgroundPopup").fadeOut("normal");
		$(actualPopup).fadeOut("normal");
		popupStatus = 0;
	}
}

function setDoNotDisable(value) {
	doNotDisable = value;
}

//centering popup
function centerPopup() {
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $(actualPopup).height();
	var popupWidth = $(actualPopup).width();
	//centering  
	$(actualPopup).css( {
		"position": "absolute",
		"top": windowHeight / 2 - popupHeight / 2,
		"left": windowWidth / 2 - popupWidth / 2
	});
	
	//only need force for IE6
	$("#backgroundPopup").css( {
		"height": windowHeight
	});
}

function popupDiv (div) {
	actualPopup = "#" + div;
	//centering with css
	centerPopup();
	//load popup
	loadPopup();
}

//CONTROLLING EVENTS IN jQuery
$(document).ready(function() {
	//CLOSING POPUP
	//Click the x event!
	$(".popupContactClose").click(function() {
		disablePopup();
	});
	
	//Click out event!
	$("#backgroundPopup").click(function() {
		disablePopup();
	});
	
	//Press Escape event!
	$(document).keypress(function(e) {
		if (e.keyCode == 27 && popupStatus == 1) {
			disablePopup();
		}
	});
}); 