var fileselected = false;
var folder = null;
var schema = null;
var table = null;
var column = null;
var id_name = null;
var id = null;
var convertToWidth = null;
var convertToHeight = null;
var json_decoded;
var data_type;

function clearAll() {
	folder = null;
	schema = null;
	table = null;
	column = null;
	id_name = null;
	id = null;
	convertToWidth = null;
	convertToHeight = null;
	json_decoded = null;
	data_type = null;
}

function fileSelected(aFolder, aSchema, aTable, aColumn, aId_name, aId, aConvertToWidth, aConvertToHeight) {
	var file = document.getElementById('fileToUpload').files[0];
	if (file) {
		fileselected = true;
		folder = aFolder;
		schema = aSchema;
		table = aTable;
		column = aColumn;
		id_name = aId_name;
		id = aId;
		convertToWidth = aConvertToWidth;
		convertToHeight = aConvertToHeight;

		/*
		var fileSize = 0;
		if (file.size > 1024 * 1024)
			fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
		else
			fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';

		document.getElementById('fileName').innerHTML = 'Fájlnév: ' + file.name;
		document.getElementById('fileSize').innerHTML = 'Méret: ' + fileSize;
		document.getElementById('fileType').innerHTML = 'Típus: ' + file.type;
		 */
	}
}

function uploadFile(aJson_decoded, aData_type) {
	if (!fileselected)
		return;

	if (aJson_decoded) { // kaphatja az ID-t a feltolto rutinbol is, de nem kotelezo
		id = aJson_decoded.id;
		json_decoded = aJson_decoded;
	}

	if (aData_type)
		data_type = aData_type;

	document.getElementById('uploadArea').innerHTML = "Feldolgozás...";
	document.getElementById('uploadlink').style.display = "none";
	document.getElementById('uploadImage').style.display = "none";

	popupDiv('popupUploadFile'); // ujra eloterbehozzuk az ablakot
	setDoNotDisable(true);

	fileselected = false;

	var fd = new FormData();
	fd.append("uploadedfile", document.getElementById('fileToUpload').files[0]);
	fd.append("folder", folder);
	fd.append("schema", schema);
	fd.append("table", table);
	fd.append("column", column);
	fd.append("id_name", id_name);
	fd.append("id_value", id);
	fd.append("convertToWidth", convertToWidth);
	fd.append("convertToHeight", convertToHeight);
	fd.append("random", Math.random());

	var xhr = new XMLHttpRequest();
//	xhr.upload.addEventListener("progress", uploadProgress, false);
	xhr.addEventListener("load", uploadComplete, false);
	xhr.addEventListener("error", uploadFailed, false);
	xhr.addEventListener("abort", uploadCanceled, false);
	xhr.open("POST", "functions/uploadfile.php");
	xhr.send(fd);
}

function uploadProgress(evt) {
	if (evt.lengthComputable) {
		var percentComplete = Math.round(evt.loaded * 100 / evt.total);
		document.getElementById('progressNumber').innerHTML = percentComplete.toString() + '%';
	}
	else {
		document.getElementById('progressNumber').innerHTML = 'unable to compute';
	}
}

function uploadComplete(evt) {
	var log_level = 0;
	var hiba = false;
	var szoveg = null;
	var fileid = null;

	var response = evt.target.responseText;
	if (response) {
		var comps = response.split('|');
		if (comps.length > 1) {
			fileid = comps[1];
			response = comps[0];
		}

		if (response) {
			if (response.length >= 6) {
				var errors = response.split('error:');
				if (errors.length > 1) { // ha talal benne error: szoveget, akkor mar legalabb ketto lesz a length, meg akkor is, ha egy van es az is a legelejen van
					szoveg = "Hibák:<br>";
					for (i = 0; i < errors.length; i++) {
						if (errors[i])
							szoveg += errors[i] + "<br>";
					}
					log_level = 2;
				}
				else {
					szoveg = "Figyelem:<br>" + response;
					log_level = 1;
				}
			}
			else {
				szoveg = "Figyelem:<br>" + response;
				log_level = 1;
			}
		}
		else {
			szoveg = "Kész!";
		}
	}
	// most mar mindig van response, nem kell else

	if (!szoveg) // ha valamiert megiscsak baj lenne
		szoveg = "hiba";

	endUploadFile(szoveg, log_level, fileid);
}

function uploadFailed(evt) {
	endUploadFile("Hiba történt a fájl feltöltése közben.", 2);
}

function uploadCanceled(evt) {
	endUploadFile("A fájl feltöltését megszakították vagy a böngésző szakította meg.", 1);
}

function endUploadFile(message, log_level, fileid) {
	setDoNotDisable(false);

	document.getElementById('uploadArea').innerHTML = message;
	document.getElementById('uploadlink').style.removeProperty("display");
	document.getElementById('uploadImage').style.removeProperty("display");
	var imgFile = null;
	switch (log_level) {
		case 0:
			imgFile = "images/Alarm-Tick-icon.png";
			break;
		case 1:
			imgFile = "images/Alarm-Warning-icon.png";
			break;
		case 2:
			imgFile = "images/Alarm-Error-icon.png";
			break;
		default:
			imgFile = "images/Alarm-Tick-icon.png";
			break;
	}

	document.getElementById('uploadImage').src = imgFile;

	centerPopup();
	fileUploadCompleted(json_decoded, data_type, fileid);
	clearAll();
}
