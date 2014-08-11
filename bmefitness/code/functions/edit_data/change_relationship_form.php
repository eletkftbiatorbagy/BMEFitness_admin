<?php
	function bennevane($id, $table, $tablename) {
		$bennevan = false;
		foreach ($table as $value) {
			if ($value->$tablename === $id) {
				$bennevan = true;
				break;
			}
		}

		return $bennevan;
	}

	// ha nincs ilyen bejegyzes, akkor -1, ha van, akkor pedig vagy 0 vagy 1, aszerint, hogy mit tartalmaz...
	function last_enabled($id, $array) {
		$returnvalue = -1;
		if (count($array) == 0)
			return $returnvalue;

		foreach ($array as $key => $value) {
			if ("key".$id == $key) {
				$returnvalue = $value;
				break;
			}
		}

		return $returnvalue;
	}

	print "<div id=\"orakedzoktermekselects\" class=\"orakedzoktermekselectcontent\">";
	if (isset($_POST["selectedObject"]) && isset($_POST["table"])) {
		require_once("../database.php");
		$selectedValue = $_POST["selectedObject"];
		$table = $_POST["table"];

		$last_relship = "";
		if (isset($_POST["last_relship"]))
			$last_relship = $_POST["last_relship"];

		$last_relarray = array();
		if (!empty($last_relship)) {
			$sar = explode(",", $last_relship);
			foreach ($sar as $arelship) {
				$xar = explode("=", $arelship);
				$last_relarray["key".$xar[0]] = $xar[1];
			}
		}

		$tablename = "";
		$select = "";
		$where = "";
		$order = "";
		$otherdatabase = "";
		$owncolumn = ""; // a masik adatbazisban a sajt azonositonk neve
		$othercolumn = "";  // a masik adatbazisban levo oszlop neve
		$displayname = "";

		if ($table === "edzok") { // ebben az eseten az orak adatai kellenek...
			$tablename = "fitness.orak";
			$select = "id,rovid_nev";
			$where = "orak.aktiv";
			$order = "orak.sorszam";
			$otherdatabase = "fitness.foglalkozas";
			$owncolumn = "edzo";
			$othercolumn = "ora";
			$displayname = "rovid_nev";
		}
		else if ($table === "orakedzok") { // ebben az esetben az edzok adatai kellenek...
			$tablename = "fitness.edzok";
			$select = "id,rovid_nev";
			$where = "edzok.aktiv";
			$order = "edzok.sorszam";
			$otherdatabase = "fitness.foglalkozas";
			$owncolumn = "ora";
			$othercolumn = "edzo";
			$displayname = "rovid_nev";
		}
		else if ($table === "oraktermek") { // ebben az esetben az edzok adatai kellenek...
			$tablename = "fitness.termek";
			$select = "id,nev";
			$where = "";
			$order = "termek.sorszam";
			$otherdatabase = "fitness.oraterme";
			$owncolumn = "ora";
			$othercolumn = "terem";
			$displayname = "nev";
		}
		else if ($table === "termek") { // ebben az esetben az edzok adatai kellenek...
			$tablename = "fitness.orak";
			$select = "id,rovid_nev";
			$where = "orak.aktiv";
			$order = "orak.sorszam";
			$otherdatabase = "fitness.oraterme";
			$owncolumn = "terem";
			$othercolumn = "ora";
			$displayname = "rovid_nev";
		}

		if (!empty($otherdatabase) && !empty($tablename) && !empty($select) && !empty($othercolumn)) {
			$otherdata = db_select_data($tablename, $select, $where, $order);
			$otherarray = array();

			if (!is_null($selectedValue) && $selectedValue > 0) {
				$otherarray = db_select_data($otherdatabase, "*", $owncolumn."=".$selectedValue."", "");
			}

			foreach ($otherdata as $data) {
				$kell = false;
				if (count($last_relarray) > 0) {
					$le = last_enabled($data->id, $last_relarray);
					if ($le == - 1) {
						$kell = bennevane($data->id, $otherarray, $othercolumn);
					}
					else {
						$kell = ($le === "1");
					}
				}
				else if (bennevane($data->id, $otherarray, $othercolumn)) {
					$kell = true;
				}
				print "<input id=\"".($data->id)."\" ".($kell ? " checked=\"true\"" : "")." type=\"checkbox\"><span>".$data->$displayname."</span></input><br>";
			}
		}
	}
	print "</div><br>";
	print "<span onclick=\"EndRelationshipForm();\" class=\"relationship_ok\">OK</span><span onclick=\"HideRelationshipForm();\" class=\"relationship_cancel\">mégsem</span>";
?>
