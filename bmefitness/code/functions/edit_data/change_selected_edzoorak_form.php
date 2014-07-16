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
	if (isset($_POST["selectedObject"])) {
		require_once("../database.php");
		$selectedValue = $_POST["selectedObject"];

		$orak = db_select_data("fitness.orak", "id,rovid_nev", "orak.aktiv", "orak.sorszam");
		$edzoorai = array();

		if ($selectedValue > 0) {
			$edzoorai = db_select_data("fitness.foglalkozas", "*", "edzo=".$selectedValue."", "");
		}

		foreach ($orak as $ora) {
//			for ($i = 0; $i < 15; $i++)
			print "<input id=\"".($ora->id)."\" ".(bennevane($ora->id, $edzoorai, "ora") ? " checked=\"true\"" : "")." type=\"checkbox\"><span>".$ora->rovid_nev."</span></input><br>";
		}
	}
	print "</div><br>";
	print "<span onclick=\"endEditEdzoOrak();\" class=\"relationship_ok\">OK</span><span onclick=\"hideEditEdzoOrak();\" class=\"relationship_cancel\">mégsem</span>";
?>
