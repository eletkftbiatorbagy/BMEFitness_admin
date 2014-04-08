
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
				$("#content").html(result);
			}
		});

		$.get(settingsSite, { }, function (result) {
			if (result) {
				$("#settings").html(result);
			}
		});

		var menu1 = (site == "edit_data") ? "<div class=\"menu_button menu_button_kijelolt\">Adatok</div>" : "<div class=\"menu_button\" onclick=\"change_main_site('edit_data');\">Adatok</div>\n";
		var menu2 = (site == "timetable") ? "<div class=\"menu_button menu_button_kijelolt\">Órarend</div>" : "<div class=\"menu_button\" onclick=\"change_main_site('timetable');\">Órarend</div>\n";
		var menu3 = (site == "distress") ? "<div class=\"menu_button menu_button_kijelolt\">Foglalások</div>" : "<div class=\"menu_button\" onclick=\"change_main_site('distress');\">Foglalások</div>\n";
		var egyeb = "<div style=\"float: right; color: white; font-size: 28pt; margin-right: 30px; margin-top: 80px;\">BME Fitness Mobile Admin</div>";
		$("#menu").html(menu1 + menu2 + menu3 + egyeb);
	}
}
