
<?php
	require_once("../functions/database.php");

	print "<div class=\"leftcontent\">";
		$terem_query = "SELECT * FROM fitness.termek;";
		$terem_result = db_query_object_array($terem_query);

		if (!is_null($terem_result)) {
			print "Korábban létrehozott adatok:<br>";
			print "<div class=\"scrollcontent\">";
				if (count($terem_result) == 0) {
					print "<div style=\"color: red;\">Nincs adat hozzáadva</div><br>";
				}
				else {
					for ($i = 0; $i < count($terem_result); $i++) {
						print "<div class=\"edit_data_available\">terem id: ".$terem_result[$i]->id.", nev: ".$terem_result[$i]->nev."</div>";
					}
				}
			print "</div>";
			print "<div onclick=\"begin_new_data('termek'); neworeditClick();\" style=\"cursor: pointer; margin: 10px; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Új adat hozzáadása</div>";
		}
	print "</div>";


	print "<div class=\"rightcontent\"></div>";

?>