<?php
	$object = NULL;

	require_once("../functions.php");
	require_once("../database.php");

	$errormessage = "";
	$orak = db_select_data("fitness.orak", "id, nev", "orak.aktiv", "orak.nev");
	if (count($orak) == 0)
		$errormessage = "Legalább egy órát fel kell vinni.";

	$edzok = db_select_data("fitness.edzok", "id, knev, vnev", "edzok.aktiv", "edzok.vnev,edzok.knev");
	if (count($edzok) == 0) {
		if (!is_null($errormessage))
			$errormessage .= "\n";

		$errormessage .= "Legalább egy edzőt fel kell vinni.";
	}

	$termek = db_select_data("fitness.termek", "id, nev", "", "termek.nev");
	if (count($termek) == 0) {
		if (!is_null($errormessage))
			$errormessage .= "\n";

		$errormessage .= "Legalább egy termet fel kell vinni.";
	}

	if ($errormessage != "") {
		exit("Hiba.".$errormessage);
	}

	if (isset($_POST["selectedObject"])) {
		// visszaalakitjuk, hogy tudjuk hasznalni...
		$edited_naptar_id = $_POST["selectedObject"];
		$selected_naptar_data = db_select_data("fitness.naptar", "*", "naptar.id = ".$edited_naptar_id, "");
		
		if (count($selected_naptar_data) > 0)
			$object = $selected_naptar_data[0];
	}

	print "
		<table class=\"edit_data_table\">
			<tr><td id=\"edit_naptar_tol\" class=\"td_right ".(is_null($object) ? "redcolor" : "")."\">Mikortól:</td><td class=\"td_left\"><input maxlength=\"16\" minlength=\"16\" size=\"31\" type=\"text\" id=\"naptartol\" ".(is_null($object) ? "" : "value=\"".date("Y-m-d H:i", strtotime($object->tol))."\" ")."onchange=\"editedDateTimeFormatField('edit_naptar_tol', 'naptartol');\"></input></td></tr>\n
			<tr><td id=\"edit_naptar_ig\" class=\"td_right ".(is_null($object) ? "redcolor" : "")."\">Meddig:</td><td class=\"td_left\"><input maxlength=\"16\" minlength=\"16\" size=\"31\" type=\"text\" id=\"naptarig\" ".(is_null($object) ? "" : "value=\"".date("Y-m-d H:i", strtotime($object->ig))."\" ")."onchange=\"editedDateTimeFormatField('edit_naptar_ig', 'naptarig');\"></input></td></tr>\n
	";

	// orak
	print "<tr><td class=\"td_right\">Óra:</td><td class=\"td_left\"><select id=\"naptarora\">\n";
	print "<option>Óra kiválasztása...</option>";
	foreach ($orak as $ora)
	print "<option".((!is_null($object) && $object->ora == $ora->id) ? " selected=\"selected\"" : "")." value=\"".$ora->id."\">".$ora->nev." (".$ora->id.")</option>";
	print "</select>";

	// edzok
	print "<tr><td class=\"td_right\">Edző:</td><td class=\"td_left\"><select id=\"naptaredzo\">\n";
	print "<option>Edző kiválasztása...</option>";
	foreach ($edzok as $edzo)
		print "<option".((!is_null($object) && $object->edzo == $edzo->id) ? " selected=\"selected\"" : "")." value=\"".$edzo->id."\">".$edzo->vnev." ".$edzo->knev." (".$edzo->id.")</option>";
	print "</select>";

	// termek
	print "<tr><td class=\"td_right\">Terem:</td><td class=\"td_left\"><select id=\"naptarterem\">\n";
	print "<option>Terem kiválasztása...</option>";
	foreach ($termek as $terem)
		print "<option".((!is_null($object) && $object->terem == $terem->id) ? " selected=\"selected\"" : "")." value=\"".$terem->id."\">".$terem->nev." (".$terem->id.")</option>";
	print "</select>";


//			<tr><td id=\"edit_info_openinghours\" class=\"td_right ".(is_null($object) || strlen($object->nyitvatartas) == 0 ? "redcolor" : "")."\">Nyitvatartás:</td><td class=\"td_left\"><textarea rows=\"10\" cols=\"69\" type=\"text\" id=\"infoopeninghours\" onchange=\"editedField('edit_info_openinghours', 'infoopeninghours', false, 0);\">".(is_null($object) ? "" : $object->nyitvatartas)."</textarea></td></tr>\n
//			<tr><td id=\"edit_ora_description\" class=\"td_right ".(is_null($object) || strlen($object->leiras) == 0 ? "redcolor" : "")."\">Leírás:</td><td class=\"td_left\"><textarea rows=\"5\" cols=\"29\" type=\"text\" id=\"oradescription\" onchange=\"editedField('edit_ora_description', 'oradescription', false, 0);\">".(is_null($object) ? "" : $object->leiras)."</textarea></td></tr>\n
//			<tr><td class=\"td_right\">Belépődíj:</td><td class=\"td_left\"><input ".(is_null($object) || $object->belepodij == "t" ? "checked=\"true\" " : "")."type=\"checkbox\" size=\"23\" type=\"text\" id=\"orabelepodij\"></input></td></tr>\n
	print "
		</table>
		<h6><br>A mikortól és meddig mezők formátuma kötelezően:<br>'eeee-hh-nn oo:pp', például: '2014-01-01 06:00'!<br><br></h6>
	";
?>
