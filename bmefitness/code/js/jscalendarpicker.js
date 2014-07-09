
/**************************************************************************************
 htmlDatePicker v0.1

 Copyright (c) 2005, Jason Powell
 All Rights Reserved

 Redistribution and use in source and binary forms, with or without modification, are
 permitted provided that the following conditions are met:

 * Redistributions of source code must retain the above copyright notice, this list of
 conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 of conditions and the following disclaimer in the documentation and/or other materials
 provided with the distribution.
 * Neither the name of the product nor the names of its contributors may be used to
 endorse or promote products derived from this software without specific prior
 written permission.

 THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS
 OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
 THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 OF THE POSSIBILITY OF SUCH DAMAGE.

 -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-


 ***************************************************************************************/
// User Changeable Vars
var HighlightToday  = true;    // use true or false to have the current day highlighted
var DisablePast    = true;    // use true or false to allow past dates to be selectable
// The month names in your native language can be substituted below
//var MonthNames = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
var MonthNames = new Array("Január","Február","Március","Április","Május","Június","Július","Augusztus","Szeptember","Október","November","December");

// Global Vars
var now = new Date();
var dest = null;
var ny = now.getFullYear(); // Today's Date
var nm = now.getMonth();
var nd = now.getDate();
var sy = 0; // currently Selected date
var sm = 0;
var sd = 0;
var y = now.getFullYear(); // Working Date
var m = now.getMonth();
var d = now.getDate();
var l = 0;
var t = 0;
var MonthLengths = new Array(31,28,31,30,31,30,31,31,30,31,30,31);

var isEndTimePicker = false;

/*
 Function: GetDate(control)

 Arguments:
 control = ID of destination control
 */
function GetDate(adest, aIsTimePicker) {
	isEndTimePicker = aIsTimePicker;

	EnsureCalendarExists();
	DestroyCalendar();
	// One arguments is required, the rest are optional
	// First arguments must be the ID of the destination control
	if(arguments[0] === null || arguments[0] === "") {
		// arguments not defined, so display error and quit
		alert("ERROR: Destination control required in funciton call GetDate()");
		return;
	} else {
		// copy argument
		dest = arguments[0];
	}
	y = now.getFullYear();
	m = now.getMonth();
	d = now.getDate();
	sy = 0;
	sm = 0;
	sd = 0;

	if (/\d{4}..\d{1,2}..\d{1,2}./.test(dest.value)) {
		// element contains a date, so set the shown date
		var vParts = dest.value.slice(0, -1).split(". "); // assume mm/dd/yyyy
		sy = vParts[0];
		sm = vParts[1] - 1;
		sd = vParts[2];
		y=sy;
		m=sm;
		d=sd;
	}

	/* Calendar is displayed 125 pixels above the destination element
	 or (somewhat) over top of it. ;)*/

	var bodyRect = document.body.getBoundingClientRect();
    var elemRect = dest.getBoundingClientRect();
    var elemtop = elemRect.top - bodyRect.top;
	var elemleft = elemRect.left - bodyRect.left;
	var elembottom = elemRect.bottom - bodyRect.top;
	var elemwidht = elemRect.right - elemRect.left;

	// calendar height 125px, width 160px;
	var calwidht = 160;
	var minuszleft = (elemwidht / 2) - (calwidht / 2);

	l = elemleft + minuszleft;
	t = elembottom;

	if(t < 0) t = 0; // >
	DrawCalendar();
}

/*
 function DestoryCalendar()

 Purpose: Destory any already drawn calendar so a new one can be drawn
 */
function DestroyCalendar() {
	var cal = document.getElementById("dpCalendar");
	if (cal !== null) {
		cal.innerHTML = null;
		cal.style.display = "none";
	}
	return;
}

function DrawCalendar() {
	DestroyCalendar();
	cal = document.getElementById("dpCalendar");
	cal.style.left = l + "px";
	cal.style.top = t + "px";

	var sCal = "<table><tr><td class=\"cellButton\"><a href=\"javascript: PrevMonth();\" title=\"Előző hónap\">&lt;&lt;</a></td>"+
    "<td class=\"cellMonth\" width=\"80%\" colspan=\"5\">" + y + " " + MonthNames[m] + "</td>"+
    "<td class=\"cellButton\"><a href=\"javascript: NextMonth();\" title=\"Következő hónap\">&gt;&gt;</a></td></tr>"+
    "<tr><td style=\"text-align: center;\">H</td><td style=\"text-align: center;\">K</td><td style=\"text-align: center;\">Sz</td><td style=\"text-align: center;\">Cs</td><td style=\"text-align: center;\">P</td><td style=\"text-align: center;\">Sz</td><td style=\"text-align: center;\">V</td></tr>";
	var wDay = 1;
	var waDate = new Date(y,m,wDay);
	if(isLeapYear(waDate)) {
		MonthLengths[1] = 29;
	} else {
		MonthLengths[1] = 28;
	}
	var dayclass = "";
	var isToday = false;

	for(var r=1; r<7; r++) {
		sCal = sCal + "<tr>";
		for(var c=0; c<7; c++) {
			var wDate = new Date(y,m,wDay);
			var aweekday = c + 1;
			if (aweekday >= 7)
				aweekday = 0;

			if (wDate.getDay() == aweekday && wDay <= MonthLengths[m]) {
				if (wDate.getDate()==sd && wDate.getMonth()==sm && wDate.getFullYear()==sy) {
					dayclass = "cellSelected";
					isToday = true;  // only matters if the selected day IS today, otherwise ignored.
				}
				else if(wDate.getDate()==nd && wDate.getMonth()==nm && wDate.getFullYear()==ny && HighlightToday) {
					dayclass = "cellToday";
					isToday = true;
				}
				else {
					dayclass = "cellDay";
					isToday = false;
				}

				if (((now > wDate) && !DisablePast) || (now <= wDate) || isToday) { // >
					// user wants past dates selectable
					sCal = sCal + "<td class=\""+dayclass+"\"><a href=\"javascript: ReturnDay("+wDay+");\">"+wDay+"</a></td>";
				}
				else {
					// user wants past dates to be read only
					sCal = sCal + "<td class=\""+dayclass+"\">"+wDay+"</td>";
				}
				wDay++;
			} else {
				sCal = sCal + "<td class=\"unused\"></td>";
			}
		}
		sCal = sCal + "</tr>";
	}
	sCal = sCal + "<tr><td colspan=\"2\" class=\"unused\"></td><td colspan=\"3\" class=\"cellCancel\"><a href=\"javascript: DestroyCalendar();\">mégsem</a></td></tr></table>";
	cal.innerHTML = sCal; // works in FireFox, opera
	cal.style.display = "inline";
}

function PrevMonth() {
	m--;
	if(m==-1) {
		m = 11;
		y--;
	}
	DrawCalendar();
}

function NextMonth() {
	m++;
	if(m==12) {
		m = 0;
		y++;
	}
	DrawCalendar();
}

function ReturnDay(day) {
	var monthtext = m + 1;
	if (monthtext < 10)
		monthtext = "0" + monthtext;

	var daytext = day;
	if (daytext < 10)
		daytext = "0" + daytext;

	dest.value = y + ". " + monthtext + ". " + daytext + ".";
	DestroyCalendar();

	if (isEndTimePicker)
		calculateTartam();
	
	calculateMikortol();
	calculateMeddig();
}

function EnsureCalendarExists() {
	if(document.getElementById("dpCalendar") === null) {
		var eCalendar = document.createElement("div");
		eCalendar.setAttribute("id", "dpCalendar");
		document.body.appendChild(eCalendar);
	}
}

function isLeapYear(dTest) {
	var y = dTest.getYear();
	var bReturn = false;

	if(y % 4 === 0) {
		if(y % 100 !== 0) {
			bReturn = true;
		} else {
			if (y % 400 === 0) {
				bReturn = true;
			}
		}
	}

	return bReturn;
}