<?php
	if (isset($_POST["type"])) {
		$edit = $_POST["type"];

		$file = "";
		if ($edit == "info") { // pontosabban egy uj edzo letrehozasa
			print "
				<table class=\"edit_data_table\">
					<tr><td id=\"edit_info_debut\" class=\"td_right redcolor\">Bemutatkozás:</td><td class=\"td_left\"><textarea rows=\"10\" cols=\"69\" type=\"text\" id=\"infodebut\" onchange=\"editedField('edit_info_debut', 'infodebut', false, 0);\"></textarea></td></tr>\n
					<tr><td id=\"edit_info_policy\" class=\"td_right redcolor\">Házirend:</td><td class=\"td_left\"><textarea rows=\"10\" cols=\"69\" type=\"text\" id=\"infopolicy\" onchange=\"editedField('edit_info_policy', 'infopolicy', false, 0);\"></textarea></td></tr>\n
					<tr><td id=\"edit_info_openinghours\" class=\"td_right redcolor\">Nyitvatartás:</td><td class=\"td_left\"><textarea rows=\"10\" cols=\"69\" type=\"text\" id=\"infoopeninghours\" onchange=\"editedField('edit_info_openinghours', 'infoopeninghours', false, 0);\"></textarea></td></tr>\n
				</table>
			";
		}
		else if ($edit == "edzok") { // pontosabban egy uj edzo letrehozasa
			print "
				<table class=\"edit_data_table\">
					<tr><td id=\"edit_edzo_vname\" class=\"td_right redcolor\">Vezetéknév:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"edzovname\" onchange=\"editedField('edit_edzo_vname', 'edzovname', false, 30);\"></input></td></tr>\n
					<tr><td id=\"edit_edzo_kname\" class=\"td_right redcolor\">Keresztnév:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"edzokname\" onchange=\"editedField('edit_edzo_kname', 'edzokname', false, 30);\"></input></td></tr>\n
					<tr><td id=\"edit_edzo_rname\" class=\"td_right redcolor\">Rövid név:</td><td class=\"td_left\"><input maxlength=\"10\" size=\"31\" type=\"text\" id=\"edzorname\" onchange=\"editedField('edit_edzo_rname', 'edzorname', false, 10);\"></input></td></tr>\n
					<tr><td id=\"edit_edzo_altitle\" class=\"td_right redcolor\">Alcím:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"edzoaltitle\" onchange=\"editedField('edit_edzo_altitle', 'edzoaltitle', false, 30);\"></input></td></tr>\n
					<tr><td id=\"edit_edzo_description\" class=\"td_right redcolor\">Leírás:</td><td class=\"td_left\"><textarea rows=\"5\" cols=\"29\" type=\"text\" id=\"edzodescription\" onchange=\"editedField('edit_edzo_description', 'edzodescription', false, 0);\"></textarea></td></tr>\n
				</table>
			";
		}
		else if ($edit == "orak") { // pontosabban egy uj edzo letrehozasa
			print "
				<table class=\"edit_data_table\">
					<tr><td id=\"edit_ora_name\" class=\"td_right redcolor\">Név:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"oraname\" onchange=\"editedField('edit_ora_name', 'oraname', false, 25);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_rname\" class=\"td_right redcolor\">Rövid név:</td><td class=\"td_left\"><input maxlength=\"10\" size=\"31\" type=\"text\" id=\"orarname\" onchange=\"editedField('edit_ora_rname', 'orarname', false, 10);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_altitle\" class=\"td_right redcolor\">Alcím:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"oraaltitle\" onchange=\"editedField('edit_ora_altitle', 'oraaltitle', false, 30);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_perc\" class=\"td_right redcolor\">Perc:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"oraperc\" onchange=\"editedField('edit_ora_perc', 'oraperc', false, 0);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_maxletszam\" class=\"td_right redcolor\">Max létszám:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"oramaxletszam\" onchange=\"editedField('edit_ora_maxletszam', 'oramaxletszam', false, 0);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_description\" class=\"td_right redcolor\">Leírás:</td><td class=\"td_left\"><textarea rows=\"5\" cols=\"29\" type=\"text\" id=\"oradescription\" onchange=\"editedField('edit_ora_description', 'oradescription', false, 0);\"></textarea></td></tr>\n
					<tr><td class=\"td_right\">Belépődíj:</td><td class=\"td_left\"><input checked=\"true\" type=\"checkbox\" size=\"23\" type=\"text\" id=\"orabelepodij\"></input></td></tr>\n
				</table>
			";
		}
		else if ($edit == "termek") { // pontosabban egy uj terem letrehozasa
			print "
				<table class=\"edit_data_table\">
					<tr><td id=\"edit_terem_name\" class=\"td_right redcolor\">Név:</td><td class=\"td_left\"><input maxlength=\"20\" size=\"23\" type=\"text\" id=\"teremname\" onchange=\"editedField('edit_terem_name', 'teremname', false, 20);\"></input></td></tr>\n
					<tr><td id=\"edit_terem_altitle\" class=\"td_right redcolor\">Alcím:</td><td class=\"td_left\"><input maxlength=\"20\" size=\"23\" type=\"text\" id=\"teremaltitle\" onchange=\"editedField('edit_terem_altitle', 'teremaltitle', false, 20);\"></input></td></tr>\n
					<tr><td class=\"td_right\">Foglalható:</td><td class=\"td_left\"><input checked=\"true\" type=\"checkbox\" size=\"23\" type=\"text\" id=\"teremavailable\"></input></td></tr>\n
				</table>
			";
		}
	}
?>
