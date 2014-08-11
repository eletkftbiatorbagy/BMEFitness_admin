// beallitas
var minnaptarlength = 10;

var edit_data_content = null;
//var edit_data_object = null;
var edited_data_fields = [];
var last_selected_edit_data_ids = [];
var last_selected_edit_data = "edit_data_edzok_button";
var last_selected_relationship_values = "";
var last_selected_relationship_values2 = "";
var last_selected_relationship_2 = false;
var last_updated_edit_data = 0;
var last_updated_edit_data_type = "";
var frissitesre_varok_szama = 0;
var frissitettek_szama = 0;

// timetable, distress settings
var last_selected_het = 0;
var last_selected_terem = 0;
var last_selected_torolt_distress = false;

function change_main_site(site) {
	if (!site) // check: "", null, undefined, 0, false, NaN
		return;

	var contentSite = null;
	var settingsSite = null;
	var alsitesFolder = "code/alsites/";

	if (site == "edit_data") {
		if (!edit_data_content)
			edit_data_content = "edzok";

		if (edit_data_content == "info") {
			contentSite = alsitesFolder + "edit_data_content_info.php";
		}
		else if (edit_data_content == "edzok") {
			contentSite = alsitesFolder + "edit_data_content_edzok.php";
		}
		else if (edit_data_content == "felhasznalok") {
			contentSite = alsitesFolder + "edit_data_content_felhasznalok.php";
		}
		else if (edit_data_content == "termek") {
			contentSite = alsitesFolder + "edit_data_content_termek.php";
		}
		else if (edit_data_content == "orak") {
			contentSite = alsitesFolder + "edit_data_content_orak.php";
		}
		else if (edit_data_content == "beallitasok") {
			contentSite = alsitesFolder + "edit_data_content_beallitasok.php";
		}

		settingsSite = alsitesFolder + "edit_data_settings.php";
	}
	else if (site == "timetable") {
		contentSite = alsitesFolder + "timetable_content.php";
		settingsSite = alsitesFolder + "timetable_settings.php";
	}
	else if (site == "distress") {
		contentSite = alsitesFolder + "distress_content.php";
		settingsSite = alsitesFolder + "distress_settings.php";
	}

	if (settingsSite && contentSite) {
		if (site == "edit_data") {
			var aobject = last_selected_edit_data_ids[edit_data_content];
			if (!aobject)
				aobject = "";

			$.post(contentSite, { selectedObject: aobject, random: Math.random() }, function (result) {
				if (result) {
					$('#content').html(result);
				}

			});

			$.post(settingsSite, { random: Math.random() }, function (result) {
				if (result) {
					$('#settings').html(result);

				   if ($('#edit_data_edzok_button')) $('#edit_data_edzok_button').removeClass("selected_terem");
				   if ($('#edit_data_termek_button')) $('#edit_data_termek_button').removeClass("selected_terem");
				   if ($('#edit_data_orak_button')) $('#edit_data_orak_button').removeClass("selected_terem");
				   if ($('#edit_data_felhasznalok_button')) $('#edit_data_felhasznalok_button').removeClass("selected_terem");
				   if ($('#edit_data_info_button')) $('#edit_data_info_button').removeClass("selected_terem");
				   if ($('#edit_data_beallitasok_button')) $('#edit_data_beallitasok_button').removeClass("selected_terem");
				   if ($('#' + last_selected_edit_data)) $('#' + last_selected_edit_data).addClass("selected_terem");
				}
			});
		}
		else if (site == "timetable" || site == "distress") {
			var change = last_selected_torolt_distress ? "true" : "false";

			$.post(contentSite, { het: last_selected_het, terem: last_selected_terem, torolt: change, random: Math.random() }, function (result) {
				if (result) {
					$('#content').html(result);
				}
			});

			$.post(settingsSite, { terem: last_selected_terem, torolt: change, random: Math.random() }, function (result) {
				if (result) {
					$('#settings').html(result);
				}
			});
		}
		else {
			$.get(contentSite, { random: Math.random() }, function (result) {
				if (result) {
					$('#content').html(result);
				}
			});

			$.post(settingsSite, { random: Math.random() }, function (result) {
				if (result) {
				  $('#settings').html(result);
				}
			});
		}

		var menu1 = (site == "edit_data") ? "<div class=\"menu_button menu_button_kijelolt\">Adatok</div>" : "<div class=\"menu_button\" onclick=\"nullify_edit_data_object(); change_main_site('edit_data');\">Adatok</div>\n";
		var menu2 = (site == "timetable") ? "<div class=\"menu_button menu_button_kijelolt\">Órarend</div>" : "<div class=\"menu_button\" onclick=\"nullify_edit_data_object(); change_main_site('timetable');\">Órarend</div>\n";
		var menu3 = (site == "distress") ? "<div class=\"menu_button menu_button_kijelolt\">Foglalások</div>" : "<div class=\"menu_button\" onclick=\"nullify_edit_data_object(); change_main_site('distress');\">Foglalások</div>\n";
		var egyeb = "";// = "<div style=\"float: right; color: white; font-size: 28pt; margin-right: 30px; margin-top: 80px;\">BME Fitness Mobile Admin</div>";
		$('#menu').html(menu1 + menu2 + menu3 + egyeb);
	}
}

function nullify_edit_data_object() {
//	edit_data_object = null;

	// toroljuk teljes egeszeben a letrehozott array-t
	while (last_selected_edit_data_ids.length > 0) {
		last_selected_edit_data_ids.pop();
	}
}

function change_edit_data_site(site, editedobjectid) {
	if (!site) // check: "", null, undefined, 0, false, NaN
		return;

	edit_data_content = site;
	if (editedobjectid) {
//		edit_data_object = editedobjectid;
		last_selected_edit_data_ids[site] = editedobjectid;
	}
	change_main_site("edit_data");
}

function change_het(het, content) {
	last_selected_het = het;
	change_main_site(content);
}

function change_terem(terem_id, content) {
	last_selected_terem = terem_id;
	change_main_site(content);
}

/*!	Itt nem szarozok, egyszeruen mindegyik adat kulon id-t kap sorrendben....
 *
 */
function change_selected_edit_data_button(id) {
	last_selected_edit_data = id;
}

/*!
 * \param field				Ez a mezo lesz piros, ha nem jo az eredmeny
 * \param checkfield			Ennel a mezonel ellenorizzuk, hogy lehet-e ures illetve a karakterszamot
 * \param isAvailableEmpty		Ha ez igaz, akkor csak a karakter szamat ellenorzi
 * \param charCount			Ha nagyobb, mint 0, akkor azt is ellenorzi...
 */
function editedField(field, checkfield, isAvailableEmpty, maxCharCount) {
	if (!field || !checkfield)
		return;

	var afield = $('#' + field);
	var acheckfield = $('#' + checkfield);

	if (!afield || !acheckfield)
		return;


	if ((!isAvailableEmpty && !acheckfield.val()) || (maxCharCount > 0 && !acheckfield.val()) || (maxCharCount > 0 && acheckfield.val().length > maxCharCount))
		afield.addClass("redcolor");
	else
		afield.removeClass("redcolor");
}

/*!	yyyy-mm-dd HH:MM formatumban ellenorzi az idot, hogy ilyen formatum-e
 * \param	datetime			ezt csekkoljuk
 * \return	boolean			igaz, ha jo a formatum es valos a datum
 */
function is_yyyymmddHHMM_FormattedDateTime(datetime) {
	if (!datetime || datetime.length != 16)
		return false;

	var formatok = true;

	if (isNaN(parseInt(datetime[0])))		// y
		formatok = false;
	else if (isNaN(parseInt(datetime[1])))	// y
		formatok = false;
	else if (isNaN(parseInt(datetime[2])))	// y
		formatok = false;
	else if (isNaN(parseInt(datetime[3])))	// y
		formatok = false;
	else if (datetime[4] != "-")				// -
		formatok = false;
	else if (isNaN(parseInt(datetime[5])))	// m
		formatok = false;
	else if (isNaN(parseInt(datetime[6])))	// m
		formatok = false;
	else if (datetime[7] != "-")				// -
		formatok = false;
	else if (isNaN(parseInt(datetime[8])))	// d
		formatok = false;
	else if (isNaN(parseInt(datetime[9])))	// d
		formatok = false;
	else if (datetime[10] != " ")			// _
		formatok = false;
	else if (isNaN(parseInt(datetime[11])))	// H
		formatok = false;
	else if (isNaN(parseInt(datetime[12])))	// H
		formatok = false;
	else if (datetime[13] != ":")			// :
		formatok = false;
	else if (isNaN(parseInt(datetime[14])))	// M
		formatok = false;
	else if (isNaN(parseInt(datetime[15])))	// M
		formatok = false;

	if (!formatok)
		return false;

	// egyszeruen szet kell szednunk az ellenorzes miatt
	var ev = datetime.substring(0, 4);
	var ho = datetime.substring(5, 7);
	var nap = datetime.substring(8, 10);
	var ora = datetime.substring(11, 13);
	var perc = datetime.substring(14, 16);

	// letrehozunk
	var adate = new Date(ev, ho - 1, nap, ora, perc, 0);

	// most visszaellenorizzuk az ertekeket
	// azert kell ezt igy csinalni, mert elfogad peldaul februar 30-at is, de akkor ugyebar marcius 1 vagy 2 lesz belole, es akkor nem egyezik
	if (parseInt(ev) != adate.getFullYear()) return false;
	if ((parseInt(ho) - 1) != adate.getMonth()) return false;
	if (parseInt(nap) != adate.getDate()) return false;
	if (parseInt(ora) != adate.getHours()) return false;
	if (parseInt(perc) != adate.getMinutes()) return false;

	return true;
}

/*!
* \param field				Ez a mezo lesz piros, ha nem jo az eredmeny
 * \param checkfield			Ennel a mezonel ellenorizzuk, hogy lehet-e ures illetve a karakterszamot
 */
function editedDateTimeFormatField(field, checkfield) {
	if (!field || !checkfield)
		return;

	var afield = $('#' + field);
	var acheckfield = $('#' + checkfield);

	if (!afield || !acheckfield)
		return;

	if (is_yyyymmddHHMM_FormattedDateTime(acheckfield.val()))
		afield.removeClass("redcolor");
	else
		afield.addClass("redcolor");
}

/*!
 * \param data_type			kötelező paraméter, hogy tudjuk mit szerkesztünk.
 * \param a_edit_data_object	opcionális paraméter, csak akkor kell, ha valtoztatni akarom az adatatot es nem ujat letrehozni...
 */
function begin_new_or_edit_data(data_type, a_edit_data_object) {
	if (!data_type)
		return;

	// toroljuk teljes egeszeben a letrehozott array-t
	while (edited_data_fields.length > 0) {
		edited_data_fields.pop();
	}

	last_selected_relationship_values = "";
	last_selected_relationship_values2 = "";
	last_selected_relationship_2 = false;

	var title = null;
	if (data_type == "info") {
		title = "Infó adatok szerkesztése";
	}
	else if (data_type == "edzok") {
		if (a_edit_data_object)
			title = "Edző szerkesztése";
		else
			title = "Új edző adatai";
	}
	else if (data_type == "orak") {
		if (a_edit_data_object)
			title = "Óra szerkesztése";
		else
			title = "Új óra adatai";
	}
	else if (data_type == "termek") {
		if (a_edit_data_object)
			title = "Terem szerkesztése";
		else
			title = "Új terem adatai";
	}

	if (title)
		$('.editTitle').html(title);

	if (a_edit_data_object) {
		$('#neworeditlink').html("Módosítás");
		$.post("code/functions/edit_data/new_or_edit_data_forms.php", {type: data_type, selectedObject: a_edit_data_object, random: Math.random()}, function(result) {
			var vanfoto = false;
			var vanlogo = false;

			var ar = result.split("<!±!>");
			if (ar) {
				if (ar.length > 0) {
					$('#newOrEditArea').html(ar[0]);
				}
				if (ar.length > 1) {
					var fotos = ar[1];
					var fotosdata = fotos.split(",");
					vanfoto = (fotosdata.length > 0 && fotosdata[0] == "true");
					vanlogo = (fotosdata.length > 1 && fotosdata[1] == "true");
				}
			}

			// elore beallitjuk a linket az ujnak, mert ugyebar egybol ujat lehet hozzaadni, es nem szerkeszteni a regit...
			$('#neworeditlink').attr("onclick", "end_new_or_edit_data('" + data_type + "', " + a_edit_data_object + ", " + vanfoto + ", " + vanlogo + ");");
			if (data_type == "orak")
				jscolor.init();
			popupDiv('popupNewOrEdit');

			// megnezzuk, hogy voltak-e mar szerkesztve a mezok, mert ha igen, akkor le kell titani a szerkesztesuket...
			var field = document.getElementById('edzorname');
			if (field && field.value)
				editedFieldValue('edzorname');
			field = document.getElementById('edzoaltitle');
			if (field && field.value)
				editedFieldValue('edzoaltitle');

			field = document.getElementById('orarname');
			if (field && field.value)
				editedFieldValue('orarname');
			field = document.getElementById('oraaltitle');
			if (field && field.value)
				editedFieldValue('oraaltitle');

			field = document.getElementById('teremaltitle');
			if (field && field.value)
				editedFieldValue('teremaltitle');
		});
	}
	else {
		$('#neworeditlink').html("Létrehozás");
		$.post("code/functions/edit_data/new_or_edit_data_forms.php", {type: data_type, random: Math.random()}, function(result) {
			$('#newOrEditArea').html(result);
			// elore beallitjuk a linket az ujnak, mert ugyebar egybol ujat lehet hozzaadni, es nem szerkeszteni a regit...
			$('#neworeditlink').attr("onclick", "end_new_or_edit_data('" + data_type + "');");
			if (data_type == "orak")
				jscolor.init();

			popupDiv('popupNewOrEdit');

			// megnezzuk, hogy voltak-e mar szerkesztve a mezok, mert ha igen, akkor le kell titani a szerkesztesuket...
			var field = document.getElementById('edzorname');
			if (field && field.value)
				editedFieldValue('edzorname');
			field = document.getElementById('edzoaltitle');
			if (field && field.value)
				editedFieldValue('edzoaltitle');

			field = document.getElementById('orarname');
			if (field && field.value)
				editedFieldValue('orarname');
			field = document.getElementById('oraaltitle');
			if (field && field.value)
				editedFieldValue('oraaltitle');

			field = document.getElementById('teremaltitle');
			if (field && field.value)
				editedFieldValue('teremaltitle');
		});
	}
}

/*!
 * \param data_type			kötelező paraméter, hogy tudjuk mit szerkesztünk.
 * \param objectid			opcionális paraméter, csak akkor kell, ha valtoztatni akarom az adatatot es nem ujat letrehozni...
 * \param vanfoto				opcionális paraméter, igaz, ha szerkesztjük az adatot, és van már létrehozva fotója
 * \param vanlogo				opcionális paraméter, igaz, ha szerkesztjük az adatot, és van már létrehozva logója
 */
function end_new_or_edit_data(data_type, objectid, vanfoto, vanlogo) {
	if (!data_type)
		return;

	var error_message = "";
	var schema = "";
	var avalueIDs = "";
	var avalues = "";
	var returningValues = ""; // ide az osszes ertek kell, ami megjelenik, tehat nem csak az, amit szerkesztettunk...
	var aid = "-1";

	var elvalaszto = ",";
	var allelvalaszto = "<!±!>";

	var reltable = "";
	var reldefaultcolumnname = "";
	var relothercolumnname = "";
	var reltable2 = "";
	var reldefaultcolumnname2 = "";
	var relothercolumnname2 = "";


	/*
	 Figyelem, ha all, vagy updatelni szeretnénk, akkor az allelvalszot kell hasznalni a sima miatt.
	 Mert az UPDATE az szétbontja részekre és csak utána saját maga teszi össze vesszővel elválasztva
	*/

	if (objectid) {
		aid = objectid;
		elvalaszto = allelvalaszto;
	}

	if (data_type == "info") {
		if (!$('#infodebut').val())
			error_message += "A bemutatkozást meg kell adni!";
		if (!$('#infopolicy').val())
			error_message += (error_message ? "\nA házirendet meg kell adni!" : "A házirendet meg kell adni!");
		if (!$('#infoopeninghours').val())
			error_message += (error_message ? "\nA nyitvatartást meg kell adni!" : "A nyitvatartást meg kell adni!");

		aid = "all";
		schema = "info";
		avalueIDs = "bemutatkozas" + allelvalaszto + "hazirend" + allelvalaszto + "nyitvatartas";
		avalues = "'" + $('#infodebut').val() + "'" + allelvalaszto + "'" + $('#infopolicy').val() + "'" + allelvalaszto + "'" + $('#infoopeninghours').val() + "'";
		returningValues = avalueIDs; // itt nincs id....
	}
	else if (data_type == "edzok") {
		if (!$('#edzovname').val())
			error_message += "Az edző vezetéknevét meg kell adni!";
		if (!$('#edzokname').val())
			error_message += (error_message ? "\nAz edző keresztnevét meg kell adni!" : "Az edző keresztnevét meg kell adni!");
		if (!$('#edzorname').val())
			error_message += (error_message ? "\nAz edző rövid nevét meg kell adni!" : "Az edző rövid nevét meg kell adni!");
		if (!$('#edzoaltitle').val())
			error_message += (error_message ? "\nAz edző alcímét meg kell adni!" : "Az edző alcímét meg kell adni!");
		if (!$('#edzodescription').val())
			error_message += (error_message ? "\nAz edző leírását meg kell adni!" : "Az edző leírását meg kell adni!");

		if ($('#edzovname').val() && $('#edzovname').val().length > 30)
			error_message += (error_message ? "\nAz edző vezetékneve maximum 30 karakter lehet!" : "Az edző vezetékneve maximum 30 karakter lehet!");
		if ($('#edzokname').val() && $('#edzokname').val().length > 30)
			error_message += (error_message ? "\nAz edző keresztneve maximum 30 karakter lehet!" : "Az edző keresztneve maximum 30 karakter lehet!");
		if ($('#edzorname').val() && $('#edzorname').val().length > 10)
			error_message += (error_message ? "\nAz edző rövid neve maximum 10 karakter lehet!" : "Az edző rövid neve maximum 10 karakter lehet!");
		if ($('#edzoaltitle').val() && $('#edzoaltitle').val().length > 30)
			error_message += (error_message ? "\nAz edző alcíme maximum 30 karakter lehet!" : "Az edző alcíme maximum 30 karakter lehet!");

		if ((!objectid && !fileselected) || (!vanfoto && !fileselected))
			error_message +=  (error_message ? "\nKötelező megadni az edző fényképét!" : "Kötelező megadni az edző fényképét!");

		schema = "edzok";
		avalueIDs = "vnev" + elvalaszto + "knev" + elvalaszto + "rovid_nev" + elvalaszto + "alcim" + elvalaszto + "leiras";
		avalues = "'" + $('#edzovname').val() + "'" + elvalaszto + "'" + $('#edzokname').val() + "'" + elvalaszto + "'" + $('#edzorname').val() + "'" + elvalaszto + "'" + $('#edzoaltitle').val() + "'" + elvalaszto + "'" + $('#edzodescription').val() + "'";
		returningValues = "id" + elvalaszto + "foto";
		// hozzafuzzuk a sorszamot, ha ujat akarunk felvinni, mert amugy a sorszam nem valtozik
		if (!objectid) {
			avalueIDs += elvalaszto + "sorszam";
			avalues += elvalaszto + "fitness.zero_if_null((SELECT max(sorszam) FROM fitness.edzok)) + 1";
		}

		reltable = "foglalkozas";
		reldefaultcolumnname = "edzo";
		relothercolumnname = "ora";
	}
	else if (data_type == "orak") {
		if (!$('#oraname').val())
			error_message += "Az óra nevét meg kell adni!";
		if (!$('#orarname').val())
			error_message += (error_message ? "\nAz óra rövid nevét meg kell adni!" : "Az óra rövid nevét meg kell adni!");
		if (!$('#oraaltitle').val())
			error_message += (error_message ? "\nAz óra alcímét meg kell adni!" : "Az óra alcímét meg kell adni!");
//		if (!$('#oraperc').val())
//			error_message += (error_message ? "\nA percet meg kell adni!" : "A percet meg kell adni!");
		if (!$('#oramaxletszam').val())
			error_message += (error_message ? "\nA maximum létszámot meg kell adni!" : "A maximum létszámot meg kell adni!");
		if (!$('#oradescription').val())
			error_message += (error_message ? "\nAz óra leírását meg kell adni!" : "Az óra leírását meg kell adni!");

		if ($('#oraname').val() && $('#oraname').val().length > 25)
			error_message += (error_message ? "\nAz óra neve maximum 25 karakter lehet!" : "Az óra neve maximum 25 karakter lehet!");
		if ($('#orarname').val() && $('#orarname').val().length > 10)
			error_message += (error_message ? "\nAz óra rövid maximum 10 karakter lehet!" : "Az óra rövid neve maximum 10 karakter lehet!");
		if ($('#oraaltitle').val() && $('#oraaltitle').val().length > 30)
			error_message += (error_message ? "\nAz óra alcíme maximum 30 karakter lehet!" : "Az óra alcíme maximum 30 karakter lehet!");
		if ($('#oracolor').val() && $('#oracolor').val().length != 6)
			error_message += (error_message ? "\nAz óra színe csak 6 karakter lehet!\npéldául fekete: 000000, fehér: FFFFFF" : "Az óra színe csak 6 karakter lehet!\npéldául fekete: 000000, fehér: FFFFFF");

		if ((!objectid && !fileselected) || (!vanfoto && !fileselected))
			error_message +=  (error_message ? "\nKötelező megadni az óra fényképét!" : "Kötelező megadni az óra fényképét!");
//		if ((!objectid && !logoselected) || (vanlogo && !logoselected))
//			error_message +=  (error_message ? "\nKötelező megadni az óra logóját!" : "Kötelező megadni az óra logóját!");

		schema = "orak";
		avalueIDs = "nev" + elvalaszto + "rovid_nev" + elvalaszto + "alcim" + elvalaszto + "leiras" + elvalaszto + "max_letszam" + elvalaszto + "perc" + elvalaszto + "belepodij" + elvalaszto + "color";
		avalues = "'" + $('#oraname').val() + "'" + elvalaszto + "'" + $('#orarname').val() + "'" + elvalaszto + "'" + $('#oraaltitle').val() + "'" + elvalaszto + "'" + $('#oradescription').val() + "'" + elvalaszto + "'" + $('#oramaxletszam').val() + "'" + elvalaszto + "'" + $('#oraperc').val() + "'" + elvalaszto + "'" + ($('#orabelepodij').prop("checked") ? "t" : "f") + "'" + elvalaszto + "'" + $('#oracolor').val() + "'";
		returningValues = "id" + elvalaszto + "foto" + elvalaszto + "logo";
		// hozzafuzzuk a sorszamot, ha ujat akarunk felvinni, mert amugy a sorszam nem valtozik
		if (!objectid) {
			avalueIDs += elvalaszto + "sorszam";
			avalues += elvalaszto + "fitness.zero_if_null((SELECT max(sorszam) FROM fitness.orak)) + 1";
		}

		reltable = "foglalkozas";
		reldefaultcolumnname = "ora";
		relothercolumnname = "edzo";

		reltable2 = "oraterme";
		reldefaultcolumnname2 = "ora";
		relothercolumnname2 = "terem";

		// INFO: az oraknal, ha termet is akarunk allitani, akkor hasznalni kell ezt masodikkent: last_selected_relationship_values2
	}
	else if (data_type == "termek") {
		if (!$('#teremname').val())
			error_message += "A terem nevét meg kell adni!";
		if (!$('#teremaltitle').val())
			error_message += (error_message ? "\nA terem alcímét meg kell adni!" : "A terem alcímét meg kell adni!");
		if ($('#teremname').val() && $('#teremname').val().length > 20)
			error_message += (error_message ? "\nA terem neve maximum 20 karakter lehet!" : "A terem neve maximum 20 karakter lehet!");
		if ($('#teremaltitle').val() && $('#teremaltitle').val().length > 20)
			error_message += (error_message ? "\nA terem alcíme maximum 20 karakter lehet!" : "A terem alcíme maximum 20 karakter lehet!");

		if ((!objectid && !fileselected) || (!vanfoto && !fileselected))
			error_message +=  (error_message ? "\nKötelező megadni a terem fényképét!" : "Kötelező megadni a terem fényképét!");

		schema = "termek";
		avalueIDs = "nev" + elvalaszto + "alcim" + elvalaszto + "foglalhato";
		avalues = "'" + $('#teremname').val() + "'" + elvalaszto + "'" + $('#teremaltitle').val() + "'" + elvalaszto + "'" + ($('#teremavailable').prop("checked") ? "t" : "f") + "'";
		returningValues = "id" + elvalaszto + "foto";
		// hozzafuzzuk a sorszamot, ha ujat akarunk felvinni, mert amugy a sorszam nem valtozik
		if (!objectid) {
			avalueIDs += elvalaszto + "sorszam";
			avalues += elvalaszto + "fitness.zero_if_null((SELECT max(sorszam) FROM fitness.termek)) + 1";
		}

		reltable = "oraterme";
		reldefaultcolumnname = "terem";
		relothercolumnname = "ora";
	}

	if (error_message) {
		window.alert(error_message);
		return;
	}

	if (schema && avalueIDs && avalues) {
		frissitesre_varok_szama = 0;
		frissitettek_szama = 0;
		last_updated_edit_data_type = data_type;
		$.post("code/functions/insert_or_update_data.php", {data_id: aid, table_name: "fitness", schema: schema, value_ids: avalueIDs, values: avalues, returning: returningValues, random: Math.random()}, function(result) {
//			window.alert("elvileg kesz, eredmeny: " + (result ? "OK" : "XAR") + " result: " + result);
			if (result) {
				// at kell alakitani json objektte
				var json_decoded = JSON.parse(result);
				last_updated_edit_data = json_decoded.id;

				if (fileselected)
					frissitesre_varok_szama++;
				if (logoselected)
					frissitesre_varok_szama++;

				// itt nem kell leellenorizni, hogy fel van-e mar toltve a kep, mert a funkcioban ellenorzom le
				if (!uploadFile(json_decoded, data_type)) {
					// ide nem kell semmi
				}

				if (last_selected_relationship_values && reltable && reldefaultcolumnname && relothercolumnname) {
					frissitesre_varok_szama++;
					$.post("code/functions/edit_data/change_relationship.php", {table: reltable, defaultcolumnname: reldefaultcolumnname, id: last_updated_edit_data, othercolumnname: relothercolumnname, values: last_selected_relationship_values, random: Math.random()}, function(result) {
						frissitettek_szama++;
						if (frissitettek_szama == frissitesre_varok_szama) {
							change_edit_data_site(last_updated_edit_data_type, last_updated_edit_data);
						}
					});
				}

				if (last_selected_relationship_values2 && reltable2 && reldefaultcolumnname2 && relothercolumnname2) {
					frissitesre_varok_szama++;
					$.post("code/functions/edit_data/change_relationship.php", {table: reltable2, defaultcolumnname: reldefaultcolumnname2, id: last_updated_edit_data, othercolumnname: relothercolumnname2, values: last_selected_relationship_values2, random: Math.random()}, function(result) {
						frissitettek_szama++;
						if (frissitettek_szama == frissitesre_varok_szama) {
							change_edit_data_site(last_updated_edit_data_type, last_updated_edit_data);
						}
					});
				}
			}
		});
	}

	disablePopup();
}

function editedFieldValue(fieldname) {
	if (edited_data_fields.indexOf(fieldname) == -1) {
		edited_data_fields.push(fieldname);
	}

	// ha benne van es ures a cucc, akkor meg kivesszuk...
	var field = document.getElementById(fieldname);
	if (field && !field.value) {
		var index = edited_data_fields.indexOf(fieldname);
		if (index > -1)
			edited_data_fields.splice(index, 1);
	}
}

// jo hulye nevet adtam neki :)
function editValueFiled(adat) {
	// ezekbol a mezokbol szamitodik ki
	var data1 = null;
	var data2 = null;

	// ezekbe a mazokbe szamitodik ki
	var value1 = null;
	var value2 = null;

	// value length
	var lenght1 = 0;
	var lenght2 = 0;

	var display1 = null;
	var display2 = null;

	if (adat === "edzok") {
		data1 = document.getElementById('edzovname');
		data2 = document.getElementById('edzokname');

		if (edited_data_fields.indexOf('edzorname') == -1) // ha meg nem volt szerkesztve, akkor hozzarendeljuk
			value1 = document.getElementById('edzorname');
		if (edited_data_fields.indexOf('edzoaltitle') == -1) // ha meg nem volt szerkesztve, akkor hozzarendeljuk
			value2 = document.getElementById('edzoaltitle');

		lenght1 = 10;
		lenght2 = 30;

		display1 = document.getElementById('edit_edzo_rname');
		display2 = document.getElementById('edit_edzo_altitle');
	}
	else if (adat === "orak") {
		data1 = document.getElementById('oraname');

		if (edited_data_fields.indexOf('orarname') == -1) // ha meg nem volt szerkesztve, akkor hozzarendeljuk
			value1 = document.getElementById('orarname');
		if (edited_data_fields.indexOf('oraaltitle') == -1) // ha meg nem volt szerkesztve, akkor hozzarendeljuk
			value2 = document.getElementById('oraaltitle');

		lenght1 = 10;
		lenght2 = 30;

		display1 = document.getElementById('edit_ora_rname');
		display2 = document.getElementById('edit_ora_altitle');
	}
	else if (adat === "termek") {
		data1 = document.getElementById('teremname');

		if (edited_data_fields.indexOf('teremaltitle') == -1) // ha meg nem volt szerkesztve, akkor hozzarendeljuk
			value1 = document.getElementById('teremaltitle');

		lenght1 = 20;

		display1 = document.getElementById('edit_terem_altitle');
	}

	if (value1) {
		if (data1 && data2) {
			if (data1.value.length + data2.value.length > lenght1) {
				var alenght = parseInt(lenght1 / 2);
				value1.value = data1.value.substring(0, alenght) + data2.value.substring(0, alenght);
			}
			else {
				value1.value = data1.value + data2.value;
			}
		}
		else if (data1) {
			if (data1.value.length > lenght1) {
				value1.value = data1.value.substring(0, lenght1);
			}
			else {
				value1.value = data1.value;
			}
		}
		else if (data2) {
			if (data2.value.length > lenght1) {
				value1.value = data2.value.substring(0, lenght1);
			}
			else {
				value1.value = data2.value;
			}
		}

		if (value1.value && display1)
			display1.className = display1.className.replace(/\bredcolor\b/,'');
	}

	if (value2) {
		if (data1 && data2) {
			if (data1.value.length + data2.value.length > lenght2) {
				var alenght2 = parseInt(lenght2 / 2);
				value2.value = data1.value.substring(0, alenght2) + data2.value.substring(0, alenght2);
			}
			else {
				value2.value = data1.value + data2.value;
			}
		}
		else if (data1) {
			if (data1.value.length > lenght2) {
				value2.value = data1.value.substring(0, lenght2);
			}
			else {
				value2.value = data1.value;
			}
		}
		else if (data2) {
			if (data2.value.length > lenght2) {
				value2.value = data2.value.substring(0, lenght2);
			}
			else {
				value2.value = data2.value;
			}
		}

		if (value2.value && display2)
			display2.className = display2.className.replace(/\bredcolor\b/,'');
	}
}

function fileUploadCompleted(objectid, data_type) {
	frissitettek_szama++;
	if (frissitettek_szama == frissitesre_varok_szama) {
		change_edit_data_site(last_updated_edit_data_type, last_updated_edit_data);
	}
	//change_edit_data_site(data_type, objectid);
}

function changeSorszam(changeSelectedDataValue, table_name_with_schema, id, ujsorszam) {
//	window.alert("change sorszam table: " + table + ", id: " + id + ", ujsorszam: " + ujsorszam);
	$.post("code/functions/edit_data/change_sorszam.php", {table: table_name_with_schema, id: id, ujsorszam: ujsorszam, random: Math.random()}, function(result) {
//		window.alert("elvileg kesz, eredmeny: " + result);
		if (result && result == "true") {
		   change_main_site("edit_data");
		}
	});
}

function ShowChangeRelationshipForm(button, table, edzo_or_ora_select_id, islast2) {
	var last_values = last_selected_relationship_values;
	if (islast2) {
		last_selected_relationship_2 = true;
		last_values = last_selected_relationship_values2;
	}

	var ablak = document.getElementById('popupEditEdzokOrak');

	var bodyRect = document.body.getBoundingClientRect();
    var elemRect = button.getBoundingClientRect();
    var elemtop = elemRect.top - bodyRect.top;
	var elemleft = elemRect.left - bodyRect.left;
	var elemheight = elemRect.top - elemRect.bottom;


	$.post("code/functions/edit_data/change_relationship_form.php", {last_relship: last_values, selectedObject: edzo_or_ora_select_id, table: table, random: Math.random()}, function(result) {
		if (result) {
		   if (last_selected_relationship_2)
			   last_selected_relationship_values2 = "";
		   else
			   last_selected_relationship_values = "";

		   $('#editEzokOrakContent').html(result);
		   var absa = document.getElementById('editEzokOrakContent').getBoundingClientRect();
		   var calheight = absa.bottom - absa.top;
		   var minuszheight = (elemheight / 2) - (calheight / 2);

		   ablak.style.left = (elemRect.right + 10) + "px";
		   ablak.style.top = (elemtop + minuszheight) + "px";
		   ablak.style.display = "inline";
		}
	});
}

function EndRelationshipForm() {
	var retvalues = "";

	var ar = document.getElementById('orakedzoktermekselects').getElementsByTagName('INPUT');
	var darabszam = 0;
    for (var x = 0; x < ar.length; x++) {
		retvalues += ar[x].id + "=";
		if (ar[x].type.toUpperCase()=='CHECKBOX') {
			if (ar[x].checked) {
				darabszam++;
				retvalues += "1";
			}
			else {
				retvalues += "0";
			}
		}

		if (x < ar.length - 1)
			retvalues += ",";
    }

	if (last_selected_relationship_2)
		document.getElementById('querycount2').innerHTML = darabszam;
	else
		document.getElementById('querycount').innerHTML = darabszam;

	if (last_selected_relationship_2)
		last_selected_relationship_values2 = retvalues;
	else
		last_selected_relationship_values = retvalues;

	HideRelationshipForm();
}

function HideRelationshipForm() {
	var ablak = document.getElementById('popupEditEdzokOrak');
	ablak.style.display = "none";
}



/*!
 * \param naptar_id			opcionális paraméter, csak akkor kell, ha valtoztatni akarom az adatatot es nem ujat letrehozni...
 * \param aclickdateparams		opcionális paraméter, új naptár esetén kell megadni, hogy mennyi időnél kattintottunk
 */
function begin_new_or_edit_naptar(naptar_id, aclickdateparams) {
	// megprobaljuk atkonvertalni json-ra, ha nem sikerul, akkor ujat viszunk fel, nem a legjobb, de nem rossz...
	if (naptar_id) {
		$('#neworeditlink').html("Módosítás");
		$.post("code/functions/edit_naptar/new_or_edit_naptar_forms.php", {selectedObject: naptar_id, random: Math.random()}, function(result) {
			if (result) {
			   if (result.substring(0, 5) == "Hiba.") {
				   window.alert(result.substring(5, result.length));
				   return;
			   }
			   else {
				   $('#newOrEditArea').html(result);
				   // elore beallitjuk a linket az ujnak, mert ugyebar egybol ujat lehet hozzaadni, es nem szerkeszteni a regit...
				   $('#neworeditlink').attr("onclick", "end_new_or_edit_naptar(" + naptar_id + ");");
				   popupDiv('popupNewOrEdit');
			   }
			}
		});
	}
	else {
		var clickdateparams = -1;
		if (aclickdateparams)
			clickdateparams = aclickdateparams;

		$('#neworeditlink').html("Létrehozás");
		$.post("code/functions/edit_naptar/new_or_edit_naptar_forms.php", {clickdateparams: clickdateparams, terem: last_selected_terem, random: Math.random()}, function(result) {
			if (result) {
			   if (result.substring(0, 5) == "Hiba.") {
				   window.alert(result.substring(5, result.length));
				   return;
			   }
			   else {
				   $('#newOrEditArea').html(result);
					// elore beallitjuk a linket az ujnak, mert ugyebar egybol ujat lehet hozzaadni, es nem szerkeszteni a regit...
				   $('#neworeditlink').attr("onclick", "end_new_or_edit_naptar();");
				   popupDiv('popupNewOrEdit');
			   }
			}
		});
	}

	if (naptar_id)
		$('.editTitle').html("Naptárbejegyzés szerkesztése");
	else
		$('.editTitle').html("Új naptárbejegyzés létrehozása");
}

/*!
 * \param naptar_id			opcionális paraméter, csak akkor kell, ha valtoztatni akarom az adatatot es nem ujat letrehozni...
 */
function end_new_or_edit_naptar(naptar_id) {
	var error_message = "";
	var tol = $('#naptartol');
	var ig = $('#naptarig');
	var ora = $('#naptarora');
	var edzo = $('#naptaredzo');
	var terem = $('#naptarterem');

	if (!tol) {
		if (error_message)
			error_message += "\n";
		error_message += "A 'Mikortól' mező kitöltése kötelező!";
	}
	else if (!is_yyyymmddHHMM_FormattedDateTime(tol.val())) {
		if (error_message)
			error_message += "\n";
		error_message += "A 'Mikor' mező formátuma nem megfelelő vagy érvénytelen a dátum vagy az idő!";//\nA 'Mikor' mező formátuma kötelezően: 'eeee-hh-nn oo:pp', például: '2014-01-01 06:00'!";
	}

	if (!ig) {
		if (error_message)
			error_message += "\n";
		error_message += "A 'Meddig' mező kitöltése kötelező!";
	}
	else if (!is_yyyymmddHHMM_FormattedDateTime(ig.val())) {
		if (error_message)
			error_message += "\n";
		error_message += "A 'Meddig' mező formátuma nem megfelelő vagy érvénytelen a dátum vagy az idő!";//\nA 'Meddig' mező formátuma kötelezően: 'eeee-hh-nn oo:pp', például: '2014-01-01 06:00'!";
	}

	if (ora.prop("selectedIndex") === 0) {
		if (error_message)
			error_message += "\n";
		error_message += "Kötelező kiválasztani az óra típusát!";
	}

	if (edzo.prop("selectedIndex") === 0) {
		if (error_message)
			error_message += "\n";
		error_message += "Kötelező kiválasztani az edzőt!";
	}

	if (terem.prop("selectedIndex") === 0) {
		if (error_message)
			error_message += "\n";
		error_message += "Kötelező kiválasztani a termet!";
	}

	if (error_message) {
		window.alert(error_message);
		return;
	}

	var aid = "-1";
	var elvalaszto = ",";
	var allelvalaszto = "<!±!>";

	if (naptar_id) {
		aid = naptar_id;
		elvalaszto = allelvalaszto;
	}

//	var atableNameWithSchema = "fitness.naptar";
	var avalueIDs = "tol" + elvalaszto + "ig" + elvalaszto + "ora" + elvalaszto + "edzo" + elvalaszto + "terem";
	var avalues = "'" + tol.val() + "'" + elvalaszto + "'" + ig.val() + "'" + elvalaszto + "'" + ora.val() + "'" + elvalaszto + "'" + edzo.val() + "'" + elvalaszto + "'" + terem.val() + "'";
	var returningValues = "id";

	$.post("code/functions/insert_or_update_data.php", {data_id: aid, table_name: "fitness", schema: "naptar", value_ids: avalueIDs, values: avalues, returning: returningValues, random: Math.random()}, function(result) {
//		window.alert("elvileg kesz, eredmeny: " + (result ? "OK" : "XAR") + " result: " + result);
		if (result) {
		   change_main_site("timetable");
		}
	});

	disablePopup();
}

function changeNaptarTartam() {
	var naptarselect = document.getElementById("naptaroratartam");
//	var selectedText = naptarselect.options[naptarselect.selectedIndex].text;
	var selectedValue = naptarselect.value;
//	alert("selected text: " + selectedText + "\n" + "selected value: " + selectedValue);

	var egyenipercinput = document.getElementById("naptaregyeniperc");
	var egyeniperctext = document.getElementById("naptarperctext");

	if (selectedValue == "-1") {
		egyenipercinput.style.visibility = "visible";
		egyeniperctext.style.visibility = "visible";
	}
	else {
		egyenipercinput.style.visibility = "hidden";
		egyeniperctext.style.visibility = "hidden";
	}

}

function ChangedEdzoSelect() {
	var edzoid = document.getElementById("naptaredzo").value;
	var oraid = document.getElementById("naptarora").value;
	var teremid = document.getElementById("naptarterem").value;

	$.post("code/functions/edit_naptar/change_select_options.php", {edzo_id: edzoid , ora_id: oraid, terem_id: teremid, table: "orak", random: Math.random()}, function(result) {
//		window.alert("elvileg kesz az orak, eredmeny: " + (result ? "OK" : "XAR") + " result: " + result);
		if (result) {
			$('#oraselect').html(result);

			// ujra le kell kerdezni, mert 1: megvaltozhatott, 2: nem erhetoek el belul a valtozok...
			var edzoid = document.getElementById("naptaredzo").value;
			var oraid = document.getElementById("naptarora").value;
			var teremid = document.getElementById("naptarterem").value;

			$.post("code/functions/edit_naptar/change_select_options.php", {edzo_id: edzoid , ora_id: oraid, terem_id: teremid, table: "termek", random: Math.random()}, function(result2) {
//				window.alert("elvileg kesz a termek, eredmeny: " + (result2 ? "OK" : "XAR") + " result: " + result2);
				if (result2) {
					$('#teremselect').html(result2);
				}
			});
		}
	});
}

function ChangedOraSelect() {
	var edzoid = document.getElementById("naptaredzo").value;
	var oraid = document.getElementById("naptarora").value;
	var teremid = document.getElementById("naptarterem").value;

	$.post("code/functions/edit_naptar/change_select_options.php", {edzo_id: edzoid , ora_id: oraid, terem_id: teremid, table: "edzok", random: Math.random()}, function(result) {
//		window.alert("elvileg kesz az edzok, eredmeny: " + (result ? "OK" : "XAR") + " result: " + result);
		if (result) {
			$('#edzoselect').html(result);
		}
	});

	$.post("code/functions/edit_naptar/change_select_options.php", {edzo_id: edzoid , ora_id: oraid, terem_id: teremid, table: "termek", random: Math.random()}, function(result2) {
//		window.alert("elvileg kesz a termek, eredmeny: " + (result2 ? "OK" : "XAR") + " result: " + result2);
		if (result2) {
			$('#teremselect').html(result2);
		}
	});

	$.post("code/functions/edit_naptar/get_selected_ora_perc.php", {ora_id: oraid, random: Math.random()}, function(result3) {
		if (result3) {
			var aperc = Number(result3);
//			alert("result: " + result3 + "\naperc: " + aperc);
			if (aperc > 0) {
				var nit = document.getElementById("naptaroratartam");

				var van = false;
				for (var i = 0; i < nit.options.length; i++) {
					if (aperc == nit.options[i].value) {
						van = true;
						nit.options[i].selected = true;
					}
					else {
						nit.options[i].selected = false;
					}
				}

				var egyenipercinput = document.getElementById("naptaregyeniperc");
				var egyeniperctext = document.getElementById("naptarperctext");
				egyenipercinput.style.visibility = "hidden";
				egyeniperctext.style.visibility = "hidden";

				if (!van) {
					nit.options[0].selected = true;

					egyenipercinput.style.visibility = "visible";
					egyeniperctext.style.visibility = "visible";
					egyenipercinput.value = aperc;
				}

				calculateMeddig();
			}
		}
	});
}

function checkIsMinute(field) {
	var fval = Number(field.value);
	if (!fval || fval < minnaptarlength)
		field.value = String(minnaptarlength);
}

function calculateTartam() {
	var nit = document.getElementById("naptaroratartam");

	var std = document.getElementById("selected_to_date").value;
	var stt = document.getElementById("selected_to_time").value;
	var sfd = document.getElementById("selected_from_date").value;
	var sft = document.getElementById("selected_from_time").value;

	var vtParts = std.slice(0, -1).split(". ");
	var atParts = stt.split(":");
	var igdate = new Date(vtParts[0], vtParts[1], vtParts[2], atParts[0], atParts[1], 0, 0);

	var vfParts = sfd.slice(0, -1).split(". ");
	var afParts = sft.split(":");
	var toldate = new Date(vfParts[0], vfParts[1], vfParts[2], afParts[0], afParts[1], 0, 0);

	var percek = Math.floor((igdate - toldate) / 60000); // elvileg masodpercekkel es miliseconddal kell oszani

	if (percek < minnaptarlength)
		percek = minnaptarlength;

	var van = false;
	for (var i = 0; i < nit.options.length; i++) {
		if (percek == nit.options[i].value) {
			van = true;
			nit.options[i].selected = true;
		}
		else {
			nit.options[i].selected = false;
		}
	}

	var egyenipercinput = document.getElementById("naptaregyeniperc");
	var egyeniperctext = document.getElementById("naptarperctext");
	egyenipercinput.style.visibility = "hidden";
	egyeniperctext.style.visibility = "hidden";

	if (!van) {
		nit.options[0].selected = true;

		egyenipercinput.style.visibility = "visible";
		egyeniperctext.style.visibility = "visible";
		egyenipercinput.value = percek;
	}
}

function calculateMikortol() {
	document.getElementById("naptartol").value = stringFromDateAndTimeInput("selected_from_date", "selected_from_time");
}

function calculateMeddig() {
	// mikortol datum es time mezo
	var datefield = document.getElementById("selected_from_date").value;
	var timefield = document.getElementById("selected_from_time").value;

	var vParts = datefield.slice(0, -1).split(". ");
	m = Number(vParts[1]);
	d = Number(vParts[2]);
	y = Number(vParts[0]);

	var aParts = timefield.split(":");
	h = Number(aParts[0]);
	mp = Number(aParts[1]);

	var changeminutes = 0;

	var naptarselect = document.getElementById("naptaroratartam");
	var selectedValue = naptarselect.value;

	if (selectedValue == "-1") {
		var egyenipercinput = document.getElementById("naptaregyeniperc");
		changeminutes = Number(egyenipercinput.value);
	}
	else
		changeminutes = Number(selectedValue);

	var toldate = new Date(y, m, d, h, mp, 0, 0);
	var igdate = new Date(toldate.getTime() + (changeminutes * 60 * 1000)); // 60 masodperc, 1000 millisecond a vegen

	y = igdate.getFullYear();
	m = igdate.getMonth();
	d = igdate.getDate();

	h = igdate.getHours();
	mp = igdate.getMinutes();

	document.getElementById("naptarig").value = y + "-" + (m < 10 ? "0" + m : m) + "-" + (d < 10 ? "0" + d : d) + " " + (h < 10 ? "0" + h : h) + ":" + (mp < 10 ? "0" + mp : mp);
	document.getElementById("selected_to_date").value = y + ". " + (m < 10 ? "0" + m : m) + ". " + (d < 10 ? "0" + d : d + ".");
	document.getElementById("selected_to_time").value = (h < 10 ? "0" + h : h) + ":" + (mp < 10 ? "0" + mp : mp);
}

function stringFromDateAndTimeInput(adatefield, atimefield) {
	var datefield = document.getElementById(adatefield).value;
	var timefield = document.getElementById(atimefield).value;


	var vParts = datefield.slice(0, -1).split(". ");
	y = Number(vParts[0]);
	m = Number(vParts[1]);
	d = Number(vParts[2]);

	var aParts = timefield.split(":");
	h = Number(aParts[0]);
	mp = Number(aParts[1]);

	return y + "-" + (m < 10 ? "0" + m : m) + "-" + (d < 10 ? "0" + d : d) + " " + (h < 10 ? "0" + h : h) + ":" + (mp < 10 ? "0" + mp : mp);
}



/*!
 *	Új foglalast vezet fel a telefonos aszisztensecskécske.
 */
function begin_new_distress() {
	// TODO: be kell fejezni ezt a funkciot
	alert("befejezetlen funkcio: begin_new_distress()");
}

/*!
 *	Vége az új foglalas felvezetésének.
 */
function end_new_distress() {
	// TODO: be kell fejezni ezt a funkciot
	alert("befejezetlen funkcio: end_new_distress()");
}


/*!
 * \param naptar_id	kötelező paraméter, melyik foglalást akarjuk engedélyezni, tiltani
 * \param utkozesek	string, kötelező, ami "<!±!>" sorozattal elválasztott naptar_id-ket tartalmaz, amiket el kell utasítani, ha a sima naptar_id-t elfogadtuk
 */
function begin_allow_distress(naptar_id, utkozesek) {
	// megprobaljuk atkonvertalni json-ra, ha nem sikerul, akkor ujat viszunk fel, nem a legjobb, de nem rossz...
//	alert("utkozesek: " + utkozesek + ", naptar: " + naptar_id);

	$.post("code/functions/edit_distress/edit_distress.php", {selectedObject: naptar_id, random: Math.random()}, function(result) {
		if (result) {
			if (result.length >= 6 && result.substring(0, 6) == "error:") {
				window.alert(result.substring(6, result.length));
				return;
			}
			else {
				$('#allowDistressArea').html(result);
				// elore beallitjuk a linket az ujnak, mert ugyebar egybol ujat lehet hozzaadni, es nem szerkeszteni a regit...
				$('#allowDistressButton').attr("onclick", "end_allow_distress(true, " + naptar_id + ", '" + utkozesek + "');");
				$('#disallowDistressButton').attr("onclick", "end_allow_distress(false, " + naptar_id + ", '" + utkozesek + "');");
				popupDiv('popupAllowDistress');
			}
		}
	});
}

/*!
 * \param allow				elfogadták-e a bejegyzést vagy sem
 * \param naptar_id			kötelező paraméter
 */
function end_allow_distress(allow, naptar_id, utkozesek) {
	disablePopup();
	var aid = "-1";
	var elvalaszto = ",";
	var allelvalaszto = "<!±!>";

	if (naptar_id) {
		aid = naptar_id;
		elvalaszto = allelvalaszto;
	}

	var allowed = "true";
	if (!allow)
		allowed = "false";

	$.post("code/functions/update_distress.php", {data_id: naptar_id, allow: allowed, utkozesek: utkozesek, random: Math.random()}, function(result) {
//		window.alert("elvileg kesz, eredmeny: " + (result ? "OK" : "XAR") + " result: " + result);
		if (result) {
		   change_main_site("distress");
		}
	});
}

function change_distress_torolt(change) {
	last_selected_torolt_distress = change;// ? "true" : "false";
	change_main_site("distress");
}

function getFirstTerem(site) {
	$.post("code/functions/elsoterem.php", {random: Math.random()}, function(result) {
//		window.alert("elvileg kesz, eredmeny: " + (result ? "OK" : "XAR") + " result: " + result);
		if (result && result != "error") {
			last_selected_terem = result;
		}
		   // ez ne legyen a result kozott, hogy mindenkeppen lefusson, meg ha hibat is dobott...
		change_main_site(site);
	});

}
