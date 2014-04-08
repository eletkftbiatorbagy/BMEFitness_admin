
<?php
	require_once("../functions/database.php");

	print "<div class=\"leftcontent\">";
		$terem_query = "SELECT * FROM fitness.termek WHERE foglalhato;";
		$terem_result = db_select_object_array($terem_query);

		if (!is_null($terem_result)) {
			print "Korábban létrehozott adatok:<br>";
			print "<div class=\"scrollcontent\">";
				if (count($terem_result) == 0) {
					print "<div style=\"border-color: black; border-width: 1px; border-style: solid;\">Nincs adat hozzáadva</div><br>";
				}
				else {
					for ($j = 0; $j < 100; $j++) {
					for ($i = 0; $i < count($terem_result); $i++) {
						print "<div class=\"edit_data_available\">terem id: ".$terem_result[$i]->id.", nev: ".$terem_result[$i]->foglalhato."</div>";
					}
					}
				}
			print "</div>";
			print "<div style=\"margin: 10px; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Új adat hozzáadása";
		}
	print "</div>";


	print "<div class=\"rightcontent\"></div>";

?>