
<?php
	require_once("../functions/database.php");
	require_once("../functions/functions.php");

	print "<div style=\"border-width: 2px; border-color: #333334; border-style: solid;\"><h1 style=\"color: #489d1e;\">Termek</h1></div>";
	print "<div id=\"leftcontent\">";
	$query = "SELECT * FROM fitness.termek;";
	$result = db_query_object_array($query);

	if (!is_null($result)) {
		print "Korábban létrehozott adatok:<br>";
		print "<div class=\"scrollcontent\">";
			if (count($result) == 0) {
				print "<div style=\"color: red;\">Nincs adat hozzáadva</div><br>";
			}
			else {
				for ($i = 0; $i < count($result); $i++) {
					for ($i = 0; $i < count($result); $i++) {
						$jsonobject = json_from_object($result[$i]);
						// a js mar egybol tudja, hogy ez egy object, szoval ott nem kell atalakitani...
						if (!is_null($jsonobject))
							print "<div class=\"edit_data_available\" onclick='change_edit_data_site(\"termek\", ".$jsonobject.");'><b>".$result[$i]->nev."</b><br><span style=\"font-size: smaller;\"><i>".$result[$i]->alcim."</i></span></div>";
					}
				}
			}
		print "</div>";
		print "<div onclick=\"begin_new_or_edit_data('termek'); neworeditClick();\"class=\"action_button\">Új adat hozzáadása</div>";
	}
	print "</div>";


	if (isset($_GET["selectedObject"])) {
		// visszaalakitjuk, hogy tudjuk hasznalni...
		$jsonobject = $_GET["selectedObject"];
		$object = object_from_array($jsonobject);

		// megint at kell alakitani, hogy jo legyen a new_or_edit_data_forms.php-ban...
		$ojson = json_from_object($object);

		// megjelenes kovetkezik...
		print "<div id=\"rightcontent\">";
			print "<div onclick='begin_new_or_edit_data(\"termek\", ".$ojson."); neworeditClick();'class=\"action_button\">Szerkesztés</div>";
			print "<p>";
				print "<table>";
					print "<tr><td><b>Név:</b></td><td>".$object->nev."</td></tr>";
					print "<tr><td><b>Alcím:</b></td><td>".$object->alcim."</td></tr>";
					print "<tr><td><b>Foglalható:</b></td><td>".($object->foglalhato == "t" ? "Igen" : "Nem")."</td></tr>";
				print "</table>";
			print "</p>";
			print "<div onclick='begin_new_or_edit_data(\"termek\", ".$ojson."); neworeditClick();'class=\"action_button\">Szerkesztés</div>";
		print "</div>";
	}
	else {
		print "<div id=\"rightcontent\"><br><div style=\"color: red; padding: 5px; border-color: black; border-width: 1px; border-style: solid;\">Nincs adat kiválasztva</div></div>";
	}

?>