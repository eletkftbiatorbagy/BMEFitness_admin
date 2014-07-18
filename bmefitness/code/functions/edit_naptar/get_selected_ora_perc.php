<?php
	// eloszor megnezzuk, hogy a keresendo id 0-e, mert ha igen, akkor az osszeset meg kell jeleniteni
	if (isset($_POST["ora_id"])) {
		require_once("../database.php");

		$ora_id = $_POST["ora_id"];
		$ora = db_select_data("fitness.orak", "perc", "orak.id=".$ora_id, "");
		if (!is_null($ora) && is_array($ora) && count($ora) > 0)
			print $ora[0]->perc;
	}
?>
