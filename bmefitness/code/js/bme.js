// beallitas
var minnaptarlength = 10;

var edit_data_content = null;
var edit_data_object = null;

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
			var aobject = edit_data_object;
			if (!aobject)
				aobject = "";

			$.get(contentSite, { selectedObject: aobject, random: Math.random() }, function (result) {
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
	edit_data_object = null;
}

function change_edit_data_site(site, object) {
	if (!site) // check: "", null, undefined, 0, false, NaN
		return;

	edit_data_content = site;
	edit_data_object = object;
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
 * \param edit_data_object		opcionális paraméter, csak akkor kell, ha valtoztatni akarom az adatatot es nem ujat letrehozni...
 */
function begin_new_or_edit_data(data_type, a_edit_data_object) {
	if (!data_type)
		return;

	// megprobaljuk atkonvertalni json-ra, ha nem sikerul, akkor ujat viszunk fel, nem a legjobb, de nem rossz...
	var jsondata = null;
	if (edit_data_object)
		jsondata = JSON.stringify(a_edit_data_object);

	var title = null;
	if (data_type == "info") {
		title = "Infó adatok szerkesztése";
	}
	else if (data_type == "edzok") {
		if (jsondata)
			title = "Edző szerkesztése";
		else
			title = "Új edző adatai";
	}
	else if (data_type == "orak") {
		if (jsondata)
			title = "Óra szerkesztése";
		else
			title = "Új óra adatai";
	}
	else if (data_type == "termek") {
		if (jsondata)
			title = "Terem szerkesztése";
		else
			title = "Új terem adatai";
	}

	if (title)
		$('.editTitle').html(title);

	if (jsondata) {
		$('#neworeditlink').html("Módosítás");
		$.post("code/functions/edit_data/new_or_edit_data_forms.php", {type: data_type, selectedObject: jsondata, random: Math.random()}, function(result) {
		   $('#newOrEditArea').html(result);
		   // elore beallitjuk a linket az ujnak, mert ugyebar egybol ujat lehet hozzaadni, es nem szerkeszteni a regit...
		   $('#neworeditlink').attr("onclick", "end_new_or_edit_data('" + data_type + "', " + jsondata + ");");
			if (data_type == "orak")
			   jscolor.init();
		   popupDiv('popupNewOrEdit');
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
		});
	}
}

/*!
 * \param data_type			kötelező paraméter, hogy tudjuk mit szerkesztünk.
 * \param jsondata			opcionális paraméter, csak akkor kell, ha valtoztatni akarom az adatatot es nem ujat letrehozni...
 */
function end_new_or_edit_data(data_type, jsondata) {
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


	/*
	 Figyelem, ha all, vagy updatelni szeretnénk, akkor az allelvalszot kell hasznalni a sima miatt.
	 Mert az UPDATE az szétbontja részekre és csak utána saját maga teszi össze vesszővel elválasztva
	*/

	if (jsondata) {
		aid = jsondata.id;
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

		if ((!jsondata && !fileselected) || (jsondata && jsondata.foto === "" && !fileselected))
			error_message +=  (error_message ? "\nKötelező megadni az edző fényképét!" : "Kötelező megadni az edző fényképét!");

		schema = "edzok";
		avalueIDs = "vnev" + elvalaszto + "knev" + elvalaszto + "rovid_nev" + elvalaszto + "alcim" + elvalaszto + "leiras";
		avalues = "'" + $('#edzovname').val() + "'" + elvalaszto + "'" + $('#edzokname').val() + "'" + elvalaszto + "'" + $('#edzorname').val() + "'" + elvalaszto + "'" + $('#edzoaltitle').val() + "'" + elvalaszto + "'" + $('#edzodescription').val() + "'";
		returningValues = "id" + elvalaszto + "foto" + elvalaszto + avalueIDs + elvalaszto + "ertekeles"; // az ertekeles nem modosithato, de meg kell jeleniteni, azert van itt....
		// hozzafuzzuk a sorszamot, ha ujat akarunk felvinni, mert amugy a sorszam nem valtozik
		if (!jsondata) {
			avalueIDs += elvalaszto + "sorszam";
			avalues += elvalaszto + "fitness.zero_if_null((SELECT max(sorszam) FROM fitness.edzok)) + 1";
		}
	}
	else if (data_type == "orak") {
		if (!$('#oraname').val())
			error_message += "Az óra nevét meg kell adni!";
		if (!$('#orarname').val())
			error_message += (error_message ? "\nAz óra rövid nevét meg kell adni!" : "Az óra rövid nevét meg kell adni!");
		if (!$('#oraaltitle').val())
			error_message += (error_message ? "\nAz óra alcímét meg kell adni!" : "Az óra alcímét meg kell adni!");
		if (!$('#oraperc').val())
			error_message += (error_message ? "\nA percet meg kell adni!" : "A percet meg kell adni!");
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

		if ((!jsondata && !fileselected) || (jsondata && jsondata.foto === "" && !fileselected))
			error_message +=  (error_message ? "\nKötelező megadni az óra fényképét!" : "Kötelező megadni az óra fényképét!");
		if ((!jsondata && !logoselected) || (jsondata && jsondata.foto === "" && !logoselected))
			error_message +=  (error_message ? "\nKötelező megadni az óra logóját!" : "Kötelező megadni az óra logóját!");

		schema = "orak";
		avalueIDs = "nev" + elvalaszto + "rovid_nev" + elvalaszto + "alcim" + elvalaszto + "leiras" + elvalaszto + "max_letszam" + elvalaszto + "perc" + elvalaszto + "belepodij" + elvalaszto + "color";
		avalues = "'" + $('#oraname').val() + "'" + elvalaszto + "'" + $('#orarname').val() + "'" + elvalaszto + "'" + $('#oraaltitle').val() + "'" + elvalaszto + "'" + $('#oradescription').val() + "'" + elvalaszto + "'" + $('#oramaxletszam').val() + "'" + elvalaszto + "'" + $('#oraperc').val() + "'" + elvalaszto + "'" + ($('#orabelepodij').prop("checked") ? "t" : "f") + "'" + elvalaszto + "'" + $('#oracolor').val() + "'";
		returningValues = "id" + elvalaszto + "foto" + elvalaszto + "logo" + elvalaszto + avalueIDs;
		// hozzafuzzuk a sorszamot, ha ujat akarunk felvinni, mert amugy a sorszam nem valtozik
		if (!jsondata) {
			avalueIDs += elvalaszto + "sorszam";
			avalues += elvalaszto + "fitness.zero_if_null((SELECT max(sorszam) FROM fitness.orak)) + 1";
		}
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

		if ((!jsondata && !fileselected) || (jsondata && jsondata.foto === "" && !fileselected))
			error_message +=  (error_message ? "\nKötelező megadni a terem fényképét!" : "Kötelező megadni a terem fényképét!");

		schema = "termek";
		avalueIDs = "nev" + elvalaszto + "alcim" + elvalaszto + "foglalhato";
		avalues = "'" + $('#teremname').val() + "'" + elvalaszto + "'" + $('#teremaltitle').val() + "'" + elvalaszto + "'" + ($('#teremavailable').prop("checked") ? "t" : "f") + "'";
		returningValues = "id" + elvalaszto + "foto" + elvalaszto + avalueIDs;
		// hozzafuzzuk a sorszamot, ha ujat akarunk felvinni, mert amugy a sorszam nem valtozik
		if (!jsondata) {
			avalueIDs += elvalaszto + "sorszam";
			avalues += elvalaszto + "fitness.zero_if_null((SELECT max(sorszam) FROM fitness.termek)) + 1";
		}
	}

	if (error_message) {
		window.alert(error_message);
		return;
	}

	if (schema && avalueIDs && avalues) {
		$.post("code/functions/insert_or_update_data.php", {data_id: aid, table_name: "fitness", schema: schema, value_ids: avalueIDs, values: avalues, returning: returningValues, random: Math.random()}, function(result) {
//			window.alert("elvileg kesz, eredmeny: " + (result ? "OK" : "XAR") + " result: " + result);
				if (result) {
				// at kell alakitani json objektte
				var json_decoded = JSON.parse(result);

				// talan ha ide teszem, akkor megvarja a feltoltest mielott frissit
				if (!uploadFile(json_decoded, data_type))
					change_edit_data_site(data_type, json_decoded);
			}
	   });
	}

	disablePopup();
}

function fileUploadCompleted(json_decoded, data_type) {
	change_edit_data_site(data_type, json_decoded);
}

function changeSorszam(table_name_with_schema, id, ujsorszam) {
//	window.alert("change sorszam table: " + table + ", id: " + id + ", ujsorszam: " + ujsorszam);
	$.post("code/functions/edit_data/change_sorszam.php", {table: table_name_with_schema, id: id, ujsorszam: ujsorszam, random: Math.random()}, function(result) {
//		window.alert("elvileg kesz, eredmeny: " + result);
		if (result && result == "true") {
		   change_main_site("edit_data");
		}
	});
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
