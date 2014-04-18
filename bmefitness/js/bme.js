var edit_data_content = null;

function change_main_site(site) {
	if (!site) // check: "", null, undefined, 0, false, NaN
		return;

	var contentSite = null;
	var settingsSite = null;

	if (site == "edit_data") {
		if (!edit_data_content)
			edit_data_content = "info";

		if (edit_data_content == "info") {
			contentSite = "alsites/edit_data_content_info.php";
		}
		else if (edit_data_content == "edzok") {
			contentSite = "alsites/edit_data_content_edzok.php";
		}
		if (edit_data_content == "felhasznalok") {
			contentSite = "alsites/edit_data_content_felhasznalok.php";
		}
		if (edit_data_content == "termek") {
			contentSite = "alsites/edit_data_content_termek.php";
		}
		if (edit_data_content == "orak") {
			contentSite = "alsites/edit_data_content_orak.php";
		}
		if (edit_data_content == "beallitasok") {
			contentSite = "alsites/edit_data_content_beallitasok.php";
		}

		settingsSite = "alsites/edit_data_settings.php";
	}
	else if (site == "timetable") {
		contentSite = "alsites/timetable_content.php";
		settingsSite = "alsites/timetable_settings.php";
	}
	else if (site == "distress") {
		contentSite = "alsites/distress_content.php";
		settingsSite = "alsites/distress_settings.php";
	}

	if (settingsSite && contentSite) {
		$.get(contentSite, { }, function (result) {
			if (result) {
				$('#content').html(result);
			}
		});

		$.get(settingsSite, { }, function (result) {
			if (result) {
				$('#settings').html(result);
			}
		});

		var menu1 = (site == "edit_data") ? "<div class=\"menu_button menu_button_kijelolt\">Adatok</div>" : "<div class=\"menu_button\" onclick=\"change_main_site('edit_data');\">Adatok</div>\n";
		var menu2 = (site == "timetable") ? "<div class=\"menu_button menu_button_kijelolt\">Órarend</div>" : "<div class=\"menu_button\" onclick=\"change_main_site('timetable');\">Órarend</div>\n";
		var menu3 = (site == "distress") ? "<div class=\"menu_button menu_button_kijelolt\">Foglalások</div>" : "<div class=\"menu_button\" onclick=\"change_main_site('distress');\">Foglalások</div>\n";
		var egyeb = "";// = "<div style=\"float: right; color: white; font-size: 28pt; margin-right: 30px; margin-top: 80px;\">BME Fitness Mobile Admin</div>";
		$('#menu').html(menu1 + menu2 + menu3 + egyeb);
	}
}

function change_edit_data_site(site) {
	if (!site) // check: "", null, undefined, 0, false, NaN
		return;

	edit_data_content = site;
	change_main_site("edit_data");
}

/*!
 \param field				Ez a mezo lesz piros, ha nem jo az eredmeny
 \param checkfield		Ennel a mezonel ellenorizzuk, hogy lehet-e ures illetve a karakterszamot
 \param isAvailableEmpty	Ha ez igaz, akkor csak a karakter szamat ellenorzi
 \param charCount			Ha nagyobb, mint 0, akkor azt is ellenorzi...
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

function begin_new_data(data_type) {
	if (!data_type)
		return;

	$.post("functions/edit_data/new_data_forms.php", {type: data_type}, function(result) {
		   $('#newOrEditArea').html(result);
		   // elore beallitjuk a linket az ujnak, mert ugyebar egybol ujat lehet hozzaadni, es nem szerkeszteni a regit...
		   $('#neworeditlink').attr("onclick", "end_new_data('" + data_type + "');");
	});

	var title = null;
	if (data_type == "info") {
		title = "Infó adatok szerkesztése";
	}
	else if (data_type == "edzok") {
		title = "Új edző adatai";
	}
	else if (data_type == "orak") {
		title = "Új óra adatai";
	}
	else if (data_type == "termek") {
		title = "Új terem adatai";
	}

	if (title)
		$('.editTitle').html(title);
}

function end_new_data(data_type) {
	if (!data_type)
		return;

	var error_message = "";
	var atableNameWithSchema = "";
	var avalueIDs = "";
	var avalues = "";
	var aid = "-1";

	if (data_type == "info") {
		if (!$('#infodebut').val())
			error_message += "A bemutatkozást meg kell adni!";
		if (!$('#infopolicy').val())
			error_message += (error_message ? "\nA házirendet meg kell adni!" : "A házirendet meg kell adni!");
		if (!$('#infoopeninghours').val())
			error_message += (error_message ? "\nA nyitvatartást meg kell adni!" : "A nyitvatartást meg kell adni!");

		aid = "all";
		atableNameWithSchema = "fitness.info"
		avalueIDs = "bemutatkozas,hazirend,nyitvatartas";
		avalues = "'" + $('#infodebut').val() + "','" + $('#infopolicy').val() + "','" + $('#infoopeninghours').val() + "'";
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

		atableNameWithSchema = "fitness.edzok"
		avalueIDs = "vnev,knev,rovid_nev,alcim,leiras,sorszam";
		avalues = "'" + $('#edzovname').val() + "','" + $('#edzokname').val() + "','" + $('#edzorname').val() + "','" + $('#edzoaltitle').val() + "','" + $('#edzodescription').val() + "',fitness.zero_if_null((SELECT max(sorszam) FROM fitness.edzok)) + 1";
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

		atableNameWithSchema = "fitness.orak"
		avalueIDs = "nev,rovid_nev,alcim,leiras,max_letszam,perc,belepodij,sorszam";
		avalues = "'" + $('#oraname').val() + "','" + $('#orarname').val() + "','" + $('#oraaltitle').val() + "','" + $('#oradescription').val() + "'," + $('#oramaxletszam').val() + "," + $('#oraperc').val() + ",'" + ($('#orabelepodij').prop("checked") ? "t" : "f") + "',fitness.zero_if_null((SELECT max(sorszam) FROM fitness.orak)) + 1";
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

		atableNameWithSchema = "fitness.termek"
		avalueIDs = "nev,alcim,foglalhato,sorszam";
		avalues = "'" + $('#teremname').val() + "','" + $('#teremaltitle').val() + "','" + ($('#teremavailable').prop("checked") ? "t" : "f") + "',fitness.zero_if_null((SELECT max(sorszam) FROM fitness.termek)) + 1";
	}

	if (error_message) {
		window.alert(error_message);
		return;
	}

	if (atableNameWithSchema && avalueIDs && avalues) {
		$.post("functions/edit_data/insert_or_update_data.php", {data_id: aid, table_name_with_schema: atableNameWithSchema, value_ids: avalueIDs, values: avalues}, function(result) {
//			window.alert("elvileg kesz, eredmeny: " + (result ? "OK" : "XAR") + " (result: " + result + ")");
			if (result) {
			   change_main_site("edit_data");
			}
	   });
	}

	disablePopup();
}
