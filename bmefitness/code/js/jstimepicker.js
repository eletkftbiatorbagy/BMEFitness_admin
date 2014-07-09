
var HighlightToday  = true;    // use true or false to have the current day highlighted
var DisablePast    = true;    // use true or false to allow past dates to be selectable
// The month names in your native language can be substituted below
//var MonthNames = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
var MonthNames = new Array("Január","Február","Március","Április","Május","Június","Július","Augusztus","Szeptember","Október","November","December");

// Global Vars
var now = new Date();
var dest = null;

var h = 0;
var m = 0;
var l = 0;
var t = 0;

var isEndTimePicker = false;

/*
 Function: GetDate(control)

 Arguments:
 control = ID of destination control
 */

function GetTimePicker(adest, aIsTimePicker) {
	isEndTimePicker = aIsTimePicker;

	EnsureTimePickerExists();
	DestroyTimePicker();
	// One arguments is required, the rest are optional
	// First arguments must be the ID of the destination control
	if (arguments[0] === null || arguments[0] === "") {
		// arguments not defined, so display error and quit
		alert("ERROR: Destination control required in funciton call GetTimePicker()");
		return;
	}
	else {
		// copy argument
		dest = arguments[0];
	}

	if (/\d{1,2}.:\d{1,2}/.test(dest.value)) {
		// element contains a date, so set the shown date
		var vParts = dest.value.split(":");
		h = Number(vParts[0]);
		m = Number(vParts[1]);
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
	var calwidht = 100;
	var minuszleft = (elemwidht / 2) - (calwidht / 2);

	l = elemleft + minuszleft;
	t = elembottom;

	if (t < 0) t = 0; // >
	DrawTimePicker();
}

/*
 function DestoryCalendar()

 Purpose: Destory any already drawn calendar so a new one can be drawn
 */
function DestroyTimePicker() {
	var cal = document.getElementById("tpTimePicker");
	if (cal !== null) {
		cal.innerHTML = null;
		cal.style.display = "none";
	}
	return;
}

function DrawTimePicker() {
	DestroyTimePicker();
	cal = document.getElementById("tpTimePicker");
	cal.style.left = l + "px";
	cal.style.top = t + "px";

	var sCal = "<table>" +
	"<tr><td class=\"cellButton\"><a href=\"javascript: NextHour();\" title=\"Előző óra\"><img src=\"code/images/icon_accordion_arrow_up_black.png\"></a></td>" +
    "<td class=\"cellButton\"><a href=\"javascript: NextMinute();\" title=\"Előző perc\"><img src=\"code/images/icon_accordion_arrow_up_black.png\"></a></td></tr>" +
	"<tr><td style=\"text-align: center;\"><input id=\"timepickerhour\" class=\"cellInput\" onchange=\"InputChange(this, true)\" maxlength=\"2\" minlength=\"1\" size=\"3\" title=\"óra\" value=\"" + (h < 10 ? "0" + h : h) + "\"></input></td>" +
    "<td style=\"text-align: center;\"><input id=\"timepickerminute\" class=\"cellInput\" onchange=\"InputChange(this, false)\" maxlength=\"2\" minlength=\"1\" size=\"3\" title=\"perc\" value=\"" + (m < 10 ? "0" + m : m) + "\"></input></td></tr>" +
	"<tr><td class=\"cellButton\"><a href=\"javascript: PrevHour();\" title=\"Következő óra\"><img src=\"code/images/icon_accordion_arrow_down_black.png\"></a></td>" +
    "<td class=\"cellButton\"><a href=\"javascript: PrevMinute();\" title=\"következő perc\"><img src=\"code/images/icon_accordion_arrow_down_black.png\"></a></td></tr>" +
	"<tr><td class=\"cellOk\"><a href=\"javascript: ReturnTime();calculateMikortol();calculateMeddig();\">OK</a></td><td class=\"cellCancel\"><a href=\"javascript: DestroyTimePicker();\">mégsem</a></td></tr></table>";

	cal.innerHTML = sCal;
	cal.style.display = "inline";
}

function ReturnTime() {
	var hourtext = Number(document.getElementById("timepickerhour").value);
	var minutetext = Number(document.getElementById("timepickerminute").value);

	if (hourtext < 10)
		hourtext = "0" + hourtext;

	if (minutetext < 10)
		minutetext = "0" + minutetext;

	dest.value = hourtext + ":" + minutetext;
	DestroyTimePicker();

	if (isEndTimePicker)
		calculateTartam();
}

function EnsureTimePickerExists() {
	if (document.getElementById("tpTimePicker") === null) {
		var eCalendar = document.createElement("div");
		eCalendar.setAttribute("id", "tpTimePicker");
		document.body.appendChild(eCalendar);
	}
}

function NextHour() {
	var inputField = document.getElementById("timepickerhour");
	var aValue = Number(inputField.value);
	if (aValue < 23)
		aValue++;

	inputField.value = aValue < 10 ? "0" + aValue : aValue;
}

function PrevHour() {
	var inputField = document.getElementById("timepickerhour");
	var aValue = Number(inputField.value);
	if (aValue > 0)
		aValue--;

	inputField.value = aValue < 10 ? "0" + aValue : aValue;
}

function NextMinute() {
	var inputField = document.getElementById("timepickerminute");
	var aValue = Number(inputField.value);
	if (aValue < 59)
		aValue++;

	inputField.value = aValue < 10 ? "0" + aValue : aValue;

}

function PrevMinute() {
	var inputField = document.getElementById("timepickerminute");
	var aValue = Number(inputField.value);
	if (aValue > 0)
		aValue--;

	inputField.value = aValue < 10 ? "0" + aValue : aValue;

}

function InputChange(object, isHour) {
	var aValue = Number(object.value);
	if (!aValue)
		aValue = 0;
	else if (isHour && aValue > 23)
		aValue = 23;
	else if (!isHour && aValue > 59)
		aValue = 59;
	else if (aValue < 0)
		aValue = 0;

	object.value = aValue < 10 ? "0" + aValue : aValue;
}
