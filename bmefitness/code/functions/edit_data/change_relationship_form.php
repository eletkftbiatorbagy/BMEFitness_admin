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

	print "<div id=\"orakedzoktermekselects\" class=\"orakedzoktermekselectcontent\">";
	if (isset($_POST["selectedObject"]) && isset($_POST["table"])) {
		require_once("../database.php");
		$selectedValue = $_POST["selectedObject"];
		$table = $_POST["table"];

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
		else if ($table === "orak") { // ebben az esetben az edzok adatai kellenek...
			$tablename = "fitness.edzok";
			$select = "id,rovid_nev";
			$where = "edzok.aktiv";
			$order = "edzok.sorszam";
			$otherdatabase = "fitness.foglalkozas";
			$owncolumn = "ora";
			$othercolumn = "edzo";
			$displayname = "rovid_nev";
		}

		if (!empty($otherdatabase) && !empty($selectedValue) && !empty($tablename) && !empty($select) && !empty($where) && !empty($order) && !empty($othercolumn)) {
			$otherdata = db_select_data($tablename, $select, $where, $order);
			$otherarray = array();

			if ($selectedValue > 0) {
				$otherarray = db_select_data($otherdatabase, "*", $owncolumn."=".$selectedValue."", "");
			}

			foreach ($otherdata as $data) {
//				for ($i = 0; $i < 15; $i++)
				print "<input id=\"".($data->id)."\" ".(bennevane($data->id, $otherarray, $othercolumn) ? " checked=\"true\"" : "")." type=\"checkbox\"><span>".$data->$displayname."</span></input><br>";
			}
		}
	}
	print "</div><br>";
	print "<span onclick=\"EndRelationshipForm();\" class=\"relationship_ok\">OK</span><span onclick=\"HideRelationshipForm();\" class=\"relationship_cancel\">mégsem</span>";
?>
