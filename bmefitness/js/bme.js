
function change_main_site(site) {
	if (!site) // check: "", null, undefined, 0, false, NaN
		return;

	var contentSite = null;
	var settingsSite = null;

	if (site == "edit_data") {
		contentSite = "alsites/edit_data_content.php";
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
	if (data_type == "termek") {
		title = "Új terem adatai";
	}
	else if (data_type == "") {
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

	if (data_type == "termek") {
		if (!$('#teremname').val())
			error_message += "A terem nevét meg kell adni!";
		if ($('#teremname').val() && $('#teremname').val().length > 20)
			error_message += (error_message ? "\nA terem neve maximum 20 karakter lehet!" : "A terem neve maximum 20 karakter lehet!");
		if ($('#teremaltitle').val() && $('#teremaltitle').val().length > 20)
			error_message += (error_message ? "\nA terem alcíme maximum 20 karakter lehet!" : "A terem alcíme maximum 20 karakter lehet!");

		atableNameWithSchema = "fitness.termek"
		avalueIDs = "nev,alcim,foglalhato,sorszam";
		avalues = "'" + $('#teremname').val() + "','" + $('#teremaltitle').val() + "','" + ($('#teremavailable').prop("checked") ? "t" : "f") + "',fitness.zero_if_null((SELECT max(sorszam) FROM fitness.termek)) + 1";
	}
	else if (data_type == "") {
	}

	if (error_message) {
		window.alert(error_message);
		return;
	}

	if (atableNameWithSchema && avalueIDs && avalues) {
		$.post("functions/edit_data/insert_new_data.php", {table_name_with_schema: atableNameWithSchema, value_ids: avalueIDs, values: avalues}, function(result) {
			//window.alert("elvileg kesz, eredmeny: " + (result ? "OK" : "XAR") + " (result: " + result + ")");
			if (result) {
			   // TODO: itt nem ez kell, azaz nem a main siteot kell ujratolteni, hanem azon belul a termek alsiteot
			   change_main_site("edit_data");
			}
	   });
	}

	disablePopup();
}
