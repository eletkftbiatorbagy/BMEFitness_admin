<?php
	// eloszor megnezzuk, hogy a keresendo id 0-e, mert ha igen, akkor az osszeset meg kell jeleniteni
	if (isset($_POST["edzo_id"]) && isset($_POST["ora_id"]) && isset($_POST["terem_id"]) && isset($_POST["table"])) {
		require_once("../database.php");

		$edzo_id = $_POST["edzo_id"];
		$ora_id = $_POST["ora_id"];
		$terem_id = $_POST["terem_id"];
		$table = $_POST["table"];			// ennek a tablanak az adatainak van szuksegunk


		if ($table === "edzok") {
			$edzok = "";
			if ($ora_id == 0) {
				// mivel az ora kivalasztasa gombra kattintottunk, ezert az osszes edzo kell
				$edzok = db_select_data("fitness.edzok", "id, knev, vnev", "edzok.aktiv", "edzok.vnev, edzok.knev");
			}
			else {
				$query = "SELECT count(*) FROM fitness.foglalkozas WHERE ora=".$ora_id.";";
				$acount = db_query_object_array($query);
				$count = 0;
				if (is_array($acount) && count($acount) > 0)
					$count = $acount[0]->count;

				if ($count == 0) {
					$edzok = db_select_data("fitness.edzok", "id, knev, vnev", "edzok.aktiv", "edzok.vnev, edzok.knev");
				}
				else {
					$oraidk = db_select_data("fitness.foglalkozas", "edzo", "ora=".$ora_id, "");
					if (is_array($oraidk) && count($oraidk) > 0) {
						$idvalues = "";
						for ($i = 0; $i < count($oraidk); $i++) {
							$idvalues .= $oraidk[$i]->edzo;
							if ((count($oraidk) - 1) > $i)
								$idvalues .= ",";
						}
						$where = "edzok.aktiv AND id IN(".$idvalues.")";
						$edzok = db_select_data("fitness.edzok", "id, knev, vnev", $where, "edzok.vnev, edzok.knev");
					}
				}
			}

			// modositani kell a printeket new_or_edit_naptar_forms.php fajban is
			print "<select id=\"naptaredzo\" onchange=\"ChangedEdzoSelect();\">\n";
			print "<option value=\"0\">Edző kiválasztása...</option>\n";
			if (!empty($edzok)) {
				foreach ($edzok as $edzo)
					print "<option".(($edzo_id == $edzo->id) ? " selected=\"selected\"" : "")." value=\"".$edzo->id."\">".$edzo->vnev." ".$edzo->knev." (".$edzo->id.")</option>\n";
			}
			print "</select>\n";
		}
		else if ($table === "orak") {
			$orak = "";

			if ($edzo_id == 0) {
				$orak = db_select_data("fitness.orak", "id, nev", "orak.aktiv", "orak.nev");
			}
			else {
				$query = "SELECT count(*) FROM fitness.foglalkozas WHERE edzo=".$edzo_id.";";
				$acount = db_query_object_array($query);
				$count = 0;
				if (is_array($acount) && count($acount) > 0)
					$count = $acount[0]->count;

				if ($count == 0) {
					$orak = db_select_data("fitness.orak", "id, nev", "orak.aktiv", "orak.nev");
				}
				else {
					$edzoidk = db_select_data("fitness.foglalkozas", "ora", "edzo=".$edzo_id, "");
					if (is_array($edzoidk) && count($edzoidk) > 0) {
						$idvalues = "";
						for ($i = 0; $i < count($edzoidk); $i++) {
							$idvalues .= $edzoidk[$i]->ora;
							if ((count($edzoidk) - 1) > $i)
								$idvalues .= ",";
						}
						$where = "orak.aktiv AND id IN(".$idvalues.")";
						$orak = db_select_data("fitness.orak", "id, nev", $where, "orak.nev");
					}
				}
			}

			// modositani kell a printeket new_or_edit_naptar_forms.php fajban is
			print "<select id=\"naptarora\" onchange=\"ChangedOraSelect();\">\n";
			print "<option value=\"0\">Óra kiválasztása...</option>\n";
			if (!empty($orak)) {
				foreach ($orak as $ora)
					print "<option".(($ora_id == $ora->id) ? " selected=\"selected\"" : "")." value=\"".$ora->id."\">".$ora->nev." (".$ora->id.")</option>\n";
			}
			print "</select>\n";
		}
		else if ($table === "termek") {
			$termek = "";
			if ($ora_id == 0) {
				$termek = db_select_data("fitness.termek", "id, nev", "", "termek.nev");
			}
			else {
				$query = "SELECT count(*) FROM fitness.oraterme WHERE ora=".$ora_id.";";
				$acount = db_query_object_array($query);
				$count = 0;
				if (is_array($acount) && count($acount) > 0)
					$count = $acount[0]->count;

				if ($count == 0) {
					$termek = db_select_data("fitness.termek", "id, nev", "", "termek.nev");
				}
				else {
					$oraidk = db_select_data("fitness.oraterme", "terem", "ora=".$ora_id, "");
					if (is_array($oraidk) && count($oraidk) > 0) {
						$idvalues = "";
						for ($i = 0; $i < count($oraidk); $i++) {
							$idvalues .= $oraidk[$i]->terem;
							if ((count($oraidk) - 1) > $i)
								$idvalues .= ",";
						}
						$where = "id IN(".$idvalues.")";
						$termek = db_select_data("fitness.termek", "id, nev", $where, "termek.nev");
					}
				}
			}

			// modositani kell a printeket new_or_edit_naptar_forms.php fajban is
			print "<select id=\"naptarterem\">\n";
			print "<option>Terem kiválasztása...</option>\n";
			if (!empty($termek)) {
				foreach ($termek as $terem)
					print "<option".(($terem_id == $terem->id) ? " selected=\"selected\"" : "")." value=\"".$terem->id."\">".$terem->nev." (".$terem->id.")</option>\n";
			}
			print "</select>\n";
		}
	}
	
?>
