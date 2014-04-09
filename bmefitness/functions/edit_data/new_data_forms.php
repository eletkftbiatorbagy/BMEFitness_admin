<?php
	if (isset($_POST["type"])) {
		$edit = $_POST["type"];

		$file = "";
		if ($edit == "termek") { // pontosabban egy uj terem letrehozasa
			print "
			<table class=\"edit_data_table\">
				<tr><td id=\"edit_terem_name\" class=\"td_right redcolor\">terem neve:</td><td class=\"td_left\"><input maxlength=\"20\" size=\"23\" type=\"text\" id=\"teremname\" onchange=\"editedField('edit_terem_name', 'teremname', false, 20);\"></input></td></tr>\n
				<tr><td id=\"edit_terem_altitle\" class=\"td_right\">terem alcíme:</td><td class=\"td_left\"><input maxlength=\"20\" size=\"23\" type=\"text\" id=\"teremaltitle\" onchange=\"editedField('edit_terem_altitle', 'teremaltitle', true, 20);\"></input></td></tr>\n
				<tr><td class=\"td_right\">foglalható:</td><td class=\"td_left\"><input checked=\"true\" type=\"checkbox\" size=\"23\" type=\"text\" id=\"teremavailable\"></input></td></tr>\n
			</table>
			";
		}
	}
?>
