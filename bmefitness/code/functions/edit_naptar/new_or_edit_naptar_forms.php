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


	// azert van itt, mert a default idot at akarom irni, ha naptarat szerkesztek
	$lehetsegesIdok = array("Egyéni perccel", "10 perc", "15 perc", "20 perc", "25 perc", "30 perc", "40 perc", "50 perc", "1 óra", "2 óra", "3 óra", "4 óra");
	$lehetsegesValues = array("-1", "10", "15", "20", "25", "30", "40", "50", "60", "120", "180", "240");
	$defaultido = 8;
	$defaultidotext = $lehetsegesValues[$defaultido];
	$defaultvisibility = "hidden";
	$pluszminutes = intval($lehetsegesValues[$defaultido]) * 60;

	$allfromdatetime = date("Y-m-d H:i");
	$fromdate = date("Y. m. d.");
	$fromtime = date("H:i");

	$alltodatetime = date("Y-m-d H:i");
	$todate = date("Y. m. d.");
	$totime = date("H:i", time() + $pluszminutes);

	if (is_null($object)) {
		$newdate = NULL;

		if (isset($_POST["clickdateparams"])) {
			$clickdateparams = $_POST["clickdateparams"];
			$dateparams = explode(",", $clickdateparams);
			$newdate = mktime($dateparams[3], 0, 0, $dateparams[1], $dateparams[2], $dateparams[0]);
		}

		if (!is_null($newdate)) {
			$allfromdatetime = date("Y-m-d H:i", $newdate);
			$fromdate = date("Y. m. d.", $newdate);
			$fromtime = date("H:i", $newdate);

			$alltodatetime = date("Y-m-d H:i", $newdate + $pluszminutes);
			$todate = date("Y. m. d.", $newdate + $pluszminutes);
			$totime = date("H:i", $newdate + $pluszminutes);
		}
	}
	else {
		$allfromdatetime = date("Y-m-d H:i", strtotime($object->tol));
		$fromdate = date("Y. m. d.", strtotime($object->tol));
		$fromtime = date("H:i", strtotime($object->tol));

		$alltodatetime = date("Y-m-d H:i", strtotime($object->ig));
		$todate = date("Y. m. d.", strtotime($object->ig));
		$totime = date("H:i", strtotime($object->ig));

		$kulonbseg = intval((strtotime($object->ig) - strtotime($object->tol)) / 60);
		$van = false;

		for ($i = 0; $i < count($lehetsegesValues); $i++) {
			if ($lehetsegesValues[$i] == $kulonbseg) {
				$defaultido = $i;
				$van = true;
				break;
			}
		}

		if (!$van) {
			$defaultido = 0;
			$defaultidotext = $kulonbseg;
			$defaultvisibility = "visible";
		}

	}

	print "
		<div style=\"display: none;\"><input type=\"text\" id=\"naptartol\" value=\"".$allfromdatetime."\"></input></div>\n
		<div style=\"display: none;\"><input type=\"text\" id=\"naptarig\" value=\"".$alltodatetime."\"></input></div>\n

		<table class=\"edit_data_table\">
		<tr><td class=\"td_right\">Mikortól:</td><td class=\"td_left\">
		<input size=\"11\" type=\"text\" value=\"".$fromdate."\" name=\"selected_from_date\" id=\"selected_from_date\" readonly onClick=\"DestroyTimePicker(); GetDate(this, false);\"></input>
		<input size=\"5\" type=\"text\" value=\"".$fromtime."\" name=\"selected_from_time\" id=\"selected_from_time\" readonly onClick=\"DestroyCalendar(); GetTimePicker(this, false);\"></input></td></tr>\n
		<tr><td class=\"td_right\">Időtartam:</td><td><select id=\"naptaroratartam\" onchange=\"changeNaptarTartam();calculateMeddig();\">\n
	";

	// korabbrol kapja az adatokat
	for ($i = 0; $i < count($lehetsegesIdok); $i++) {
		$tartamido = $lehetsegesIdok[$i];
		$value = $lehetsegesValues[$i];
		print "<option";
		if ($i == $defaultido)
			print " selected=\"selected\"";
		print " value=\"".$value."\">".$tartamido."</option>\n";
	}

	print "
			</select><input style=\"visibility: ".$defaultvisibility.";\" id=\"naptaregyeniperc\" maxlength=\"6\" minlength=\"1\" size=\"6\" value=\"".$defaultidotext."\" onchange=\"checkIsMinute(this);calculateMeddig();\"></input><span id=\"naptarperctext\" style=\"visibility: ".$defaultvisibility.";\">perc</span></td></tr>\n

			<tr><td class=\"td_right\">Meddig:</td><td class=\"td_left\">
			<input size=\"11\" type=\"text\" value=\"".$todate."\" name=\"selected_to_date\" id=\"selected_to_date\" readonly onClick=\"DestroyTimePicker(); GetDate(this, true);\"></input>
			<input size=\"5\" type=\"text\" value=\"".$totime."\" name=\"selected_to_time\" id=\"selected_to_time\" readonly onClick=\"DestroyCalendar(); GetTimePicker(this, true);\"></input></td></tr>\n
	";

	// orak
	print "<tr><td class=\"td_right\">Óra:</td><td class=\"td_left\"><select id=\"naptarora\">\n";
	print "<option>Óra kiválasztása...</option>\n";
	foreach ($orak as $ora)
	print "<option".((!is_null($object) && $object->ora == $ora->id) ? " selected=\"selected\"" : "")." value=\"".$ora->id."\">".$ora->nev." (".$ora->id.")</option>\n";
	print "</select></td></tr>\n";

	// edzok
	print "<tr><td class=\"td_right\">Edző:</td><td class=\"td_left\"><select id=\"naptaredzo\">\n";
	print "<option>Edző kiválasztása...</option>\n";
	foreach ($edzok as $edzo)
		print "<option".((!is_null($object) && $object->edzo == $edzo->id) ? " selected=\"selected\"" : "")." value=\"".$edzo->id."\">".$edzo->vnev." ".$edzo->knev." (".$edzo->id.")</option>\n";
	print "</select></td></tr>\n";

	// termek
	print "<tr><td class=\"td_right\">Terem:</td><td class=\"td_left\"><select id=\"naptarterem\">\n";
	print "<option>Terem kiválasztása...</option>\n";
	foreach ($termek as $terem)
		print "<option".((!is_null($object) && $object->terem == $terem->id) ? " selected=\"selected\"" : "")." value=\"".$terem->id."\">".$terem->nev." (".$terem->id.")</option>\n";
	print "</select></td></tr>\n";

	print "
		</table>
		<br>
	";
?>
