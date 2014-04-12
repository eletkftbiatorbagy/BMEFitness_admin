<?php
	if (isset($_POST["type"])) {
		$edit = $_POST["type"];

		$file = "";
		if ($edit == "edzok") { // pontosabban egy uj edzo letrehozasa
			print "
			<table class=\"edit_data_table\">
			<tr><td id=\"edit_edzo_vname\" class=\"td_right redcolor\">vezetéknév:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"edzovname\" onchange=\"editedField('edit_edzo_vname', 'edzovname', false, 30);\"></input></td></tr>\n
			<tr><td id=\"edit_edzo_kname\" class=\"td_right redcolor\">keresztnév:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"edzokname\" onchange=\"editedField('edit_edzo_kname', 'edzokname', false, 30);\"></input></td></tr>\n
			<tr><td id=\"edit_edzo_rname\" class=\"td_right redcolor\">rövid név:</td><td class=\"td_left\"><input maxlength=\"10\" size=\"31\" type=\"text\" id=\"edzorname\" onchange=\"editedField('edit_edzo_rname', 'edzorname', false, 10);\"></input></td></tr>\n
			<tr><td id=\"edit_edzo_altitle\" class=\"td_right redcolor\">alcím:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"edzoaltitle\" onchange=\"editedField('edit_edzo_altitle', 'edzoaltitle', false, 30);\"></input></td></tr>\n
			<tr><td id=\"edit_edzo_description\" class=\"td_right redcolor\">leírás:</td><td class=\"td_left\"><input size=\"31\" type=\"text\" id=\"edzodescription\" onchange=\"editedField('edit_edzo_description', 'edzodescription', false, 0);\"></input></td></tr>\n
			</table>
			";
		}
		else if ($edit == "termek") { // pontosabban egy uj terem letrehozasa
			print "
			<table class=\"edit_data_table\">
				<tr><td id=\"edit_terem_name\" class=\"td_right redcolor\">név:</td><td class=\"td_left\"><input maxlength=\"20\" size=\"23\" type=\"text\" id=\"teremname\" onchange=\"editedField('edit_terem_name', 'teremname', false, 20);\"></input></td></tr>\n
				<tr><td id=\"edit_terem_altitle\" class=\"td_right\">alcím:</td><td class=\"td_left\"><input maxlength=\"20\" size=\"23\" type=\"text\" id=\"teremaltitle\" onchange=\"editedField('edit_terem_altitle', 'teremaltitle', false, 20);\"></input></td></tr>\n
				<tr><td class=\"td_right\">foglalható:</td><td class=\"td_left\"><input checked=\"true\" type=\"checkbox\" size=\"23\" type=\"text\" id=\"teremavailable\"></input></td></tr>\n
			</table>
			";
		}
	}
?>
