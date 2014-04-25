
<?php
	require_once("../functions/database.php");
	require_once("../functions/functions.php");

	print "<div style=\"border-width: 2px; border-color: #333334; border-style: solid;\"><h1 style=\"color: #489d1e;\">Edzők</h1></div>";
	print "<div id=\"leftcontent\">";
	$query = "SELECT * FROM fitness.edzok;";
	$result = db_query_object_array($query);

	if (!is_null($result)) {
		print "Korábban létrehozott adatok:<br>";
		print "<div class=\"scrollcontent\">";
		if (count($result) == 0) {
			print "<div style=\"color: red;\">Nincs adat hozzáadva</div><br>";
		}
		else {
			for ($i = 0; $i < count($result); $i++) {
				$jsonobject = json_from_object($result[$i]);
				// a js mar egybol tudja, hogy ez egy object, szoval ott nem kell atalakitani...
				if (!is_null($jsonobject))
					print "<div class=\"edit_data_available\" onclick='change_edit_data_site(\"edzok\", ".$jsonobject.");'><b>".$result[$i]->vnev." ".$result[$i]->knev."</b><br><span style=\"font-size: smaller;\"><i>".$result[$i]->rovid_nev."</i></span>"."</div>";
			}
		}
		print "</div>";
		print "<div onclick=\"begin_new_data('edzok'); neworeditClick();\" style=\"cursor: pointer; margin: 10px; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Új adat hozzáadása</div>";
	}
	print "</div>";

	if (isset($_GET["selectedObject"])) {
		// visszaalakitjuk, hogy tudjuk hasznalni...
		$jsonobject = $_GET["selectedObject"];
		$object = object_from_array($jsonobject);

		// megjelenes kovetkezik...
		print "<div id=\"rightcontent\">";
			print "<div onclick=\"begin_new_data('info'); neworeditClick();\" style=\"cursor: pointer; margin: 10px; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Szerkesztés</div>";
			print "<p>";
				print "<table>";
					print "<tr><td><b>Vezetéknév:</b></td><td>".$object->vnev."</td></tr>";
					print "<tr><td><b>Keresztnévknév:</b></td><td>".$object->knev."</td></tr>";
					print "<tr><td><b>Rövid név:</b></td><td>".$object->rovid_nev."</td></tr>";
					print "<tr><td><b>Alcím:</b></td><td>".$object->alcim."</td></tr>";
					print "<tr><td><b>Leírás:</b></td><td>".$object->leiras."</td></tr>";
					print "<tr><td><b>Értékelés:</b></td><td>".$object->ertekeles."</td></tr>";
				print "</table>";
			print "</p>";
			print "<div onclick=\"begin_new_data('info'); neworeditClick();\" style=\"cursor: pointer; margin: 10px; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Szerkesztés</div>";
		print "</div>";
	}
	else {
		print "<div id=\"rightcontent\"><br><div style=\"color: red; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Nincs adat kiválasztva</div></div>";
	}
?>