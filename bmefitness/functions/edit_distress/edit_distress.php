<?php
	$object = NULL;

	require_once("../functions.php");
	require_once("../database.php");

	if (!isset($_POST["selectedObject"])) {
		print "error:Nincs kiválasztva foglalás bejegyzés!";
		exit;
	}

	// visszaalakitjuk, hogy tudjuk hasznalni...
	$edited_naptar_id = $_POST["selectedObject"];
	$where = "naptar.id = ".$edited_naptar_id;
	$where += " AND naptar.berlo = felhasznalok.id";
	$where += " AND naptar.terem = termek.id";
	$select = "*";
	$select .= ", naptar.id AS naptar_id"; // naptar atalakitasok
	$select .= ", felhasznalok.id AS berlo_id, felhasznalok.vnev AS berlo_vezetek_nev, felhasznalok.knev AS berlo_kereszt_nev"; // felhasznalo atalakitasok
	$select .= ", termek.id AS terem_id, termek.nev AS terem_nev, termek.alcim AS terem_alcim"; // terem atalakitasok
	$selected_naptar_data = db_select_data("fitness.naptar, fitness.termek, fitness.felhasznalok", $select, $where, "");
	
	if (count($selected_naptar_data) > 0)
		$object = $selected_naptar_data[0];
	else {
		print "error:Nem található az adatbázisban a kiválasztott foglalás!";
		exit;
	}


	// foglalas
	print "<table class=\"edit_data_table\">\n";
	print "<tr><td class=\"td_right\">Mikortól:</td><td class=\"td_left\">".date("Y-m-d H:i", strtotime($object->tol))."</td></tr>\n";
	print "<tr><td class=\"td_right\">Meddig:</td><td class=\"td_left\">".date("Y-m-d H:i", strtotime($object->ig))."</td></tr>\n";
	print "<tr><td class=\"td_right\">Résztvevők:</td><td class=\"td_left\">".(count(pg_array_parse($object->resztvevok)))." fő</td></tr>\n";
	print "<tr><td class=\"td_right\">Meghívottak:</td><td class=\"td_left\">".(count(pg_array_parse($object->meghivottak)))." fő</td></tr>\n";

	// berlo
	print "<tr><td class=\"td_right\">Bérlő:</td><td class=\"td_left\">".$object->berlo_vezetek_nev." ".$object->berlo_kereszt_nev."</td></tr>\n";
	print "<tr><td></td><td class=\"td_left\">".$object->tel."</td></tr>\n";
	print "<tr><td></td><td class=\"td_left\">".$object->email."</td></tr>\n";
	print "<tr><td></td><td class=\"td_left\">foglalásai: ".$object->foglalas." alkalom</td></tr>\n";
	print "<tr><td></td><td class=\"td_left\">visszamondta: ".$object->visszamondas." alkalom</td></tr>\n";
	print "<tr><td></td><td class=\"td_left\">nem jött el: ".$object->nemjott." alkalom</td></tr>\n";

	// terem
	print "<tr><td class=\"td_right\">Terem:</td><td class=\"td_left\">".$object->terem_nev."</td></tr>\n";

	print "</table>\n";
?>
