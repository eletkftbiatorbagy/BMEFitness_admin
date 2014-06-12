
<?php
	require_once("../functions/database.php");
	require_once("../functions/functions.php");

	print "<div style=\"border-width: 2px; border-color: #333334; border-style: solid;\"><h1 style=\"color: #489d1e;\">Infó</h1></div>";
	print "<div id=\"rightcontent\" style=\"margin-left: 0px;\">";

	$result = db_select_data("fitness.info", "*", "", "");

	if (!is_null($result) == count($result) > 0) {
		$object = object_from_array($result[0]);
		$ojson = json_from_object($object);

		print "<div onclick='begin_new_or_edit_data(\"info\", ".$ojson."); popupDiv(\"popupNewOrEdit\");\" class='action_button'>Szerkesztés</div>";
		print "<b>Bemutatkozás</b><br><p>";
		print $result[0]->bemutatkozas."</p>";

		print "<b>Házirend</b><br><p>";
		print $result[0]->hazirend."</p>";

		print "<b>Nyitvatartás</b><br><p>";
		print $result[0]->nyitvatartas."</p>";
		print "<div onclick='begin_new_or_edit_data(\"info\", ".$ojson."); popupDiv(\"popupNewOrEdit\");' class='action_button'>Szerkesztés</div>";
	}
	else {
		print "<div onclick='begin_new_or_edit_data(\"info\"); popupDiv(\"popupNewOrEdit\");' class='action_button'>Szerkesztés</div>";
		print "<b>Bemutatkozás</b><br><p><div style=\"color: red;\">Nincs adat hozzáadva</div></p>";
		print "<b>Házirend</b><br><p><div style=\"color: red;\">Nincs adat hozzáadva</div></p>";
		print "<b>Nyitvatartás</b><br><p><div style=\"color: red;\">Nincs adat hozzáadva</div></p>";
		print "<div onclick='begin_new_or_edit_data(\"info\"); popupDiv(\"popupNewOrEdit\");' class='action_button'>Szerkesztés</div>";
	}


	print "</div>";
?>