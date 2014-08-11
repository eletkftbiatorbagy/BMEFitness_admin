var fileselected = false;
var filefolder = null;
var fileschema = null;
var filetable = null;
var filecolumn = null;
var fileid_name = null;
var fileid = null;
var fileconvertToWidth = null;
var fileconvertToHeight = null;

var logoselected = false;
var logofolder = null;
var logoschema = null;
var logotable = null;
var logocolumn = null;
var logoid_name = null;
var logoid = null;
var logoconvertToWidth = null;
var logoconvertToHeight = null;

var json_decoded = null;
var data_type = null;
var isLogo = false;
var completeUploads = 0;
var errorsmessages = "";
var warningsmessages = "";

function clearAll() {
	fileselected = false;
	filefolder = null;
	fileschema = null;
	filetable = null;
	filecolumn = null;
	fileid_name = null;
	fileid = null;
	fileconvertToWidth = null;
	fileconvertToHeight = null;
	json_decoded = null;
	data_type = null;
	isLogo = false;

	logoselected = false;
	logofolder = null;
	logoschema = null;
	logotable = null;
	logocolumn = null;
	logoid_name = null;
	logoid = null;
	logoconvertToWidth = null;
	logoconvertToHeight = null;
	completeUploads = 0;
	errorsmessages = "";
	warningsmessages = "";
}

function fileSelected(aFolder, aSchema, aTable, aColumn, aId_name, aId, aConvertToWidth, aConvertToHeight) {
	var file = null;
	var aIsLogo = aColumn == "logo" ? true : false;

	if (aIsLogo)
		file = document.getElementById('logoToUpload').files[0];
	else
		file = document.getElementById('fileToUpload').files[0];

	if (file) {
		if (aIsLogo) {
			document.getElementById("edit_ora_logo").className = document.getElementById("edit_ora_logo").className.replace(/\bredcolor\b/,'');

			logoselected = true;
			logofolder = aFolder;
			logoschema = aSchema;
			logotable = aTable;
			logocolumn = aColumn;
			logoid_name = aId_name;
			logoid = aId;
			logoconvertToWidth = aConvertToWidth;
			logoconvertToHeight = aConvertToHeight;
			isLogo = aIsLogo;
		}
		else {
			if (aTable == "edzok")
				document.getElementById("edit_edzo_foto").className = document.getElementById("edit_edzo_foto").className.replace(/\bredcolor\b/,'');
			else if (aTable == "orak")
				document.getElementById("edit_ora_foto").className = document.getElementById("edit_ora_foto").className.replace(/\bredcolor\b/,'');
			else if (aTable == "termek")
				document.getElementById("edit_terem_foto").className = document.getElementById("edit_terem_foto").className.replace(/\bredcolor\b/,'');

			fileselected = true;
			filefolder = aFolder;
			fileschema = aSchema;
			filetable = aTable;
			filecolumn = aColumn;
			fileid_name = aId_name;
			fileid = aId;
			fileconvertToWidth = aConvertToWidth;
			fileconvertToHeight = aConvertToHeight;
		}

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
	if (!fileselected && !logoselected)
		return;

	if (aJson_decoded) { // kaphatja az ID-t a feltolto rutinbol is, de nem kotelezo
		fileid = aJson_decoded.id;
		logoid = aJson_decoded.id;
		json_decoded = aJson_decoded;
	}

	if (aData_type)
		data_type = aData_type;

	document.getElementById('uploadArea').innerHTML = "Feldolgozás...";
	document.getElementById('uploadlink').style.display = "none";
	document.getElementById('uploadImage').style.display = "none";

	popupDiv('popupUploadFile'); // ujra eloterbehozzuk az ablakot
	setDoNotDisable(true);


	if (fileselected) {
		completeUploads++;
		var fd1 = new FormData();
		fd1.append("uploadedfile", document.getElementById('fileToUpload').files[0]);
		fd1.append("type", "file");
		fd1.append("folder", filefolder);
		fd1.append("schema", fileschema);
		fd1.append("table", filetable);
		fd1.append("column", filecolumn);
		fd1.append("id_name", fileid_name);
		fd1.append("id_value", fileid);
		fd1.append("convertToWidth", fileconvertToWidth);
		fd1.append("convertToHeight", fileconvertToHeight);
		fd1.append("random", Math.random());

		var xhr1 = new XMLHttpRequest();
//		xhr.upload.addEventListener("progress", uploadProgress, false);
		xhr1.addEventListener("load", uploadComplete, false);
		xhr1.addEventListener("error", uploadFailed, false);
		xhr1.addEventListener("abort", uploadCanceled, false);
		xhr1.open("POST", "code/functions/uploadfile.php");
		xhr1.send(fd1);
	}

//	if (!fileselected && logoselected) {
	if (logoselected) {
		completeUploads++;
		var fd2 = new FormData();
		fd2.append("uploadedfile", document.getElementById('logoToUpload').files[0]);
		fd2.append("type", "logo");
		fd2.append("folder", logofolder);
		fd2.append("schema", logoschema);
		fd2.append("table", logotable);
		fd2.append("column", logocolumn);
		fd2.append("id_name", logoid_name);
		fd2.append("id_value", logoid);
		fd2.append("convertToWidth", logoconvertToWidth);
		fd2.append("convertToHeight", logoconvertToHeight);
		fd2.append("random", Math.random());

		var xhr2 = new XMLHttpRequest();
//		xhr.upload.addEventListener("progress", uploadProgress, false);
		xhr2.addEventListener("load", uploadComplete, false);
		xhr2.addEventListener("error", uploadFailed, false);
		xhr2.addEventListener("abort", uploadCanceled, false);
		xhr2.open("POST", "code/functions/uploadfile.php");
		xhr2.send(fd2);
	}
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
	var szoveg = "";
	var afileid = null;
	var typeIsLogo = false;

	var response = evt.target.responseText;
	if (response) {
		if (response.length >= 4) {
			if (response.substring(0, 4) == "logo") {
				typeIsLogo = true;
				response = response.substring(4);
			}
			else if (response.substring(0, 4) == "file") {
				response = response.substring(4);
			}
		}

//		alert("response: " + response);

		var comps = response.split('|');
		if (comps.length > 1) {
			afileid = comps[1];
			response = comps[0];
		}

		if (response) {
			if (response.length >= 6) {
				var errors = response.split('error:');
				if (errors.length > 1) { // ha talal benne error: szoveget, akkor mar legalabb ketto lesz a length, meg akkor is, ha egy van es az is a legelejen van
					for (i = 0; i < errors.length; i++) {
						if (errors[i])
							errorsmessages += errors[i] + "<br>";
					}
					log_level = 2;
				}
				else {
					warningsmessages += response + "<br>";
					log_level = 1;
				}
			}
			else {
				warningsmessages += response + "<br>";
				log_level = 1;
			}
		}
		else {
//			szoveg = "Kész!";
		}
	}
	// most mar mindig van response, nem kell else
/*
	if (typeIsLogo)
		json_decoded.logo = afileid;
	else
		json_decoded.foto = afileid;
 */

	completeUploads--;
	/* ezzel talan sorrendben lehetne oket feltolteni
	if (completeUploads > 0) {
		fileselected = false;
		// ha nagyobb, nint 0, akkor azt jelenti, hogy most feltoltjuk a logot
		uploadFile(null, null); // nem kell ujra beallitani oket
	}
	else {
	 */
	
	if (completeUploads === 0) {
		if (errorsmessages) {
			szoveg += "Hibák:<br>";
			szoveg += errorsmessages;
		}
		if (warningsmessages) {
			szoveg += "Figyelmeztetések:<br>";
			szoveg += warningsmessages;
		}

		if (!szoveg)
			szoveg = "Kész!";

		endUploadFile(szoveg, log_level);
	}
}

function uploadFailed(evt) {
	endUploadFile("Hiba történt a fájl feltöltése közben.", 2);
}

function uploadCanceled(evt) {
	endUploadFile("A fájl feltöltését megszakították vagy a böngésző szakította meg.", 1);
}

function endUploadFile(message, log_level) {
	setDoNotDisable(false);

	document.getElementById('uploadArea').innerHTML = message;
	document.getElementById('uploadlink').style.removeProperty("display");
	document.getElementById('uploadImage').style.removeProperty("display");
	var imgFile = null;
	switch (log_level) {
		case 0:
			imgFile = "code/images/Alarm-Tick-icon.png";
			break;
		case 1:
			imgFile = "code/images/Alarm-Warning-icon.png";
			break;
		case 2:
			imgFile = "code/images/Alarm-Error-icon.png";
			break;
		default:
			imgFile = "code/images/Alarm-Tick-icon.png";
			break;
	}

	document.getElementById('uploadImage').src = imgFile;

	centerPopup(); // ujra kozepre kell helyeztetni, mert mar eleve latszik, es valtozik a tartalma...
	fileUploadCompleted(json_decoded.id, data_type);
	clearAll();
}
