
<?php
	require_once("../functions/database.php");

	print "<div class=\"leftcontent\">";
	$query = "SELECT * FROM fitness.info;";
	$result = db_query_object_array($query);

	if (!is_null($result) == count($result) > 0) {
		print "<div onclick=\"begin_new_data('info'); neworeditClick();\" style=\"cursor: pointer; margin: 10px; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Szerkesztés</div>";

		print "<p>Bemutatkozás:<br>";
		print $result[0]->bemutatkozas."</p>";

		print "<p>Házirend:<br>";
		print $result[0]->hazirend."</p>";

		print "<p>Nyitvatartás:<br>";
		print $result[0]->nyitvatartas."</p>";

		print "<div onclick=\"begin_new_data('info'); neworeditClick();\" style=\"cursor: pointer; margin: 10px; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Szerkesztés</div>";
	}
	else {
		print "<div onclick=\"begin_new_data('info'); neworeditClick();\" style=\"cursor: pointer; margin: 10px; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Szerkesztés</div>";

		print "<p>Bemutatkozás:<br><div style=\"color: red;\">Nincs adat hozzáadva</div></p>";

		print "<p>Házirend:<br><div style=\"color: red;\">Nincs adat hozzáadva</div></p>";

		print "<p>Nyitvatartás:<br><div style=\"color: red;\">Nincs adat hozzáadva</div></p>";

		print "<div onclick=\"begin_new_data('info'); neworeditClick();\" style=\"cursor: pointer; margin: 10px; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Szerkesztés</div>";
	}
	print "</div>";


	print "<div class=\"rightcontent\"></div>";

?>