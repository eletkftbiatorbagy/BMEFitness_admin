<?php

	require_once("functions.php");
	require_once("database.php");

	function hiba($messsage, $clean_file) {
		echo "error:".$messsage;
		if ($clean_file != NULL)
			clean_file($clean_file);
	}

	function clean_file($fileName) {
		if (file_exists(realpath($fileName)))
			unlink(realpath($fileName));
	}

	$vanhiba = false;
	if (!isset($_POST['folder'])) {
		hiba("Belső működési hiba, nem található a 'folder' változó.");
		$vanhiba = true;
	}

	if (!isset($_POST['schema'])) {
		hiba("Belső működési hiba, nem található a 'schema' változó.");
		$vanhiba = true;
	}

	if (!isset($_POST['table'])) {
		hiba("Belső működési hiba, nem található a 'table' változó.");
		$vanhiba = true;
	}

	if (!isset($_POST['column'])) {
		hiba("Belső működési hiba, nem található a 'column' változó.");
		$vanhiba = true;
	}

	if (!isset($_POST['id_name'])) {
		hiba("Belső működési hiba, nem található a 'id_name' változó.");
		$vanhiba = true;
	}

	if (!isset($_POST['id_value'])) {
		hiba("Belső működési hiba, nem található a 'id_value' változó.");
		$vanhiba = true;
	}

	if (!isset($_POST['convertToWidth'])) {
		hiba("Belső működési hiba, nem található a 'convertToWidth' változó.");
		$vanhiba = true;
	}

	if (!isset($_POST['convertToHeight'])) {
		hiba("Belső működési hiba, nem található a 'convertToHeight' változó.");
		$vanhiba = true;
	}

	if ($vanhiba)
		exit(2);

	$temp_path = "../data_tmp/";
	$target_path = "../".$_POST['folder']."/";
	$oldfilename = $_FILES['uploadedfile']['tmp_name'];
	$filename = basename($_FILES['uploadedfile']['name']);
	$move_path = $temp_path.$filename;
	$convert_path = $temp_path.$_POST['folder']."_uploaded.jpg"; // azert adom meg ezt igy, hogy ha hiba van valahol utkozben, akkor a kovetkezo feltoltes biztosan torolje az elozo probalkozast

	// eloszor eltavolitjuk a mar letezo kepet.
	clean_file($convert_path);


	if (move_uploaded_file($oldfilename, $move_path)) {
		$schema = $_POST['schema'];
		$table = $_POST['table'];
		$column = $_POST['column'];
		$id_name = $_POST['id_name'];
		$id = $_POST['id_value'];
		$convertToWidth = $_POST['convertToWidth'];
		$convertToHeight = $_POST['convertToHeight'];

		$convertCommand = "convert \"".$move_path."\" -resize ".$convertToWidth."x".$convertToHeight." ".$convert_path;
		$result = exec($convertCommand, $output = array(), $returnCode);
		if ($returnCode === 0) {
			// toroljuk az eredeti kepet
			clean_file($move_path);

			// eloszor letrehozzuk az uj adatbazis bejegyzest
			// hash-t keszitunk belole
			$file_hash = hash_file("md5", $convert_path);

			// leellenorizzuk, hogy a hash nincs-e hasznalatban...
			$query = "SELECT * FROM ".$schema.".kepek WHERE hash = '".$file_hash."';";
			$result = db_query_object_array_without_close($query, true);
			if (is_array($result)) { // nem jo a sima !$result, mert ha ures a visszateresi ertek (marpedig ures, ha nincs RETURNING)
				$kellrename = true;
				if (count($result) > 0) {
					echo "Ez a fájl már létezik, a létező fájlt hozzárendeljük a megadott adathoz.";
					clean_file($convert_path);
					$kellrename = false;
				}
				else {
					// bejegyezzuk az uj kepet az adatbazisba
					$query = "INSERT INTO ".$schema.".kepek(hash) VALUES('".$file_hash."') RETURNING id;";
					$result = db_query_object_array_without_close($query, false);
				}

				if (count($result) > 0) { // ez azert kell megegszer, mert a felette levo else szekcioban ujra hozzarendelem a result ertekhez...
					$fileid = $result[0]->id;

					// atmozgatjuk az uj nevre
					$newbasefile = sprintf("%08s", $fileid);
					$new_filename = $target_path.$newbasefile.".jpg";
					if ($kellrename && !rename($convert_path, $new_filename)) {
						hiba("Nem sikerült átnevezni a feltöltött fájlt.", $convert_path);
						if ($kellrename) { // el kell tavolitani az adatbazisbol a kepet, hogy ujra fel lehessen tolteni
							$query = "DELETE FROM ".$schema.".kepek WHERE id = ".$fileid.";";
							$result = db_query_object_array_without_close($query, false);
						}
						exit(1);
					}
					else  { // ha esetleg letezett mar hash alapjan ez a fajl, akkor nem kell torolni, hiszen ezt rendeljuk az adott kephez...
						// eloszor megkeressuk a regi kepet, ha volt, es azt toroljuk a lemezrol es a kepek tablabol
						$query = "SELECT ".$column." FROM ".$schema.".".$table." WHERE ".$id_name." = ".$id.";";
						$result = db_query_object_array_without_close($query, false);

/*
						foreach ($result[0] as $key => $value) {
							echo "key: ".$key.", value: ".$value."\n";
						}
*/

						if (is_array($result) && count($result) > 0 && property_exists($result[0], $column) && !empty($result[0]->$column)) {
							// file torles elott meg kell nezni, hogy hanyan hasznaljak az adott fajlt, mert ha 1-nel tobben, akkor nem szabad torolni!!!
							$query = "SELECT ".$column." FROM ".$schema.".".$table." WHERE ".$column." = '".$result[0]->$column."';";
							$aresult = db_query_object_array_without_close($query, false);
							if (is_array($aresult) && count($aresult) == 1) { // csak akkor torlunk, ha kizarolag es csak 1 talalt van
								$oldfilepath = $target_path.$result[0]->$column.".jpg";
								clean_file($oldfilepath);

								$oldid = $result[0]->$column;
								while (true) {
									if (strlen($oldid) == 0)
										break;

									if ($oldid[0] === "0")
										$oldid = substr($oldid, 1);
									else
										break;
								}

								if (!empty($oldid)) {
									$query = "DELETE FROM ".$schema.".kepek WHERE id = ".$oldid.";";
									$result = db_query_object_array_without_close($query, false);
									if (!is_array($result)) {
										echo "Nem sikerült törölni a régi fájlt.";
									}
								}
							}
						}

						// feltoltjuk az adatbazisba eloszor a kepet
						$query = "UPDATE ".$schema.".".$table." SET ".$column." = '".$newbasefile."' WHERE ".$id_name." = ".$id.";";
						$result = db_query_object_array_without_close($query, true);
						if (!is_array($result)) { // nem jo a sima !$result, mert ha ures a visszateresi ertek (marpedig ures, ha nincs RETURNING, akkor ures az array...)
							hiba("Nem sikerült feltölteni az adatbázisba a fájlt.", $convert_path);
							if ($kellrename) { // el kell tavolitani az adatbazisbol a kepet, hogy ujra fel lehessen tolteni
								$query = "DELETE FROM ".$schema.".kepek WHERE id = ".$fileid.";";
								$result = db_query_object_array_without_close($query, true);
							}
							exit(1);
						}
						else {
							echo "|".$newbasefile; // visszakuldjuk, hogy at tudjuk adni
							exit(0);
							// azert nincs ok, mert hulyen nez ki, ha mar letezik a fajl, hibat ir ki a vegere meg OK-t.
							// igy javascriptben akkor lesz hibamentes a feltoltes, ha az eredeny total ures
//							echo "OK";
						}
					}
				}
				else {
					hiba("Nem sikerült a fájl hash bejegyzés.", $convert_path);
					exit(1);
				}
			}
			else {
				hiba("Nem sikerült a hash lekérdezés lefuttatása.", $convert_path);
				exit(1);
			}
		}
		else {
			clean_file($move_path); // eredeti kepet is torlom...
			hiba("Nem sikerült a fájl konvertálása.(".$move_path.")", $convert_path);
			exit(1);
		}
	}
	else {
		hiba("Hiba történt a feltöltés közben, újra fel kell tölteni a fájlt!", $oldfilename);
		exit(1);
	}
?>
