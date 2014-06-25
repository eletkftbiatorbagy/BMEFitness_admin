<?php
	if (isset($_POST["data_id"]) && isset($_POST["table_name"]) && isset($_POST["schema"]) && isset($_POST["value_ids"]) && isset($_POST["values"]) && isset($_POST["returning"])) {
		require_once("database.php");
		require_once("delegate.php");
		require_once("functions.php");

		$shema = $_POST["schema"];
		$data_id = $_POST["data_id"];
		$tableNameWithSchema = $_POST["table_name"].".".$shema;
		$vauleIDs = $_POST["value_ids"];
		$values = $_POST["values"];
		$returning = $_POST["returning"];

		$uj = true;
		if ($data_id != "-1")
			$uj = false;


		// felvitel elotti delegate...
		if ($shema == "edzok") {
			if ($uj)
				UjEdzoHozzaadasaElott($objectum);
			else
				EdzoModositasaElott($objectum);
		}
		else if ($shema == "orak") {
			if ($uj)
				UjOraHozzaadasaElott($objectum);
			else
				OraModositasaElott($objectum);
		}
		else if ($shema == "termek") {
			if ($uj)
				UjTeremHozzaadasaElott($objectum);
			else
				TeremModositasaElott($objectum);
		}
		else if ($shema == "naptar") {
			if ($uj)
				UjNaptarHozzaadasaElott($objectum);
			else
				NaptarModositasaElott($objectum);
		}



		// adatbazis bejegyzes...
		if ($data_id == "-1")
			$result_object = db_insert_into_table($tableNameWithSchema, $vauleIDs, $values, $returning);
		else if ($data_id == "all") // $data_id lehet 'all' is!
			$result_object = db_update_rows_in_table($data_id, $tableNameWithSchema, $vauleIDs, $values, $returning);
		else
			$result_object = db_update_rows_in_table($data_id, $tableNameWithSchema, $vauleIDs, $values, $returning);


		// kiertekeles...
		$sikeres = false;
		$objectum = NULL;
		// megint at kell alakitani, hogy jo legyen a new_or_edit_data_forms.php-ban..
		if (is_array($result_object) && count($result_object) > 0) {
			$ojson = json_from_object($result_object[0]);
			$sikeres = true;
			$objectum = $result_object[0];
		}
		else if ($result_object == NULL) {
			$ojson = "error"; // eleve false a sikeres
		}
		else {
			$ojson = json_from_object($result_object);
			$sikeres = true; // bar ez inkabb hibas, mint jo lenne...
			$objectum = $result_object;
		}



		// visszajelzesek felvitel utan...
		if ($shema == "edzok") {
			if ($uj)
				UjEdzoHozzaadasaUtan($sikeres, $objectum);
			else
				EdzoModositasaUtan($sikeres, $objectum);
		}
		else if ($shema == "orak") {
			if ($uj)
				UjOraHozzaadasaUtan($sikeres, $objectum);
			else
				OraModositasaUtan($sikeres, $objectum);
		}
		else if ($shema == "termek") {
			if ($uj)
				UjTeremHozzaadasaUtan($sikeres, $objectum);
			else
				TeremModositasaUtan($sikeres, $objectum);
		}
		else if ($shema == "naptar") {
			if ($uj)
				UjNaptarHozzaadasaUtan($sikeres, $objectum);
			else
				NaptarModositasaUtan($sikeres, $objectum);
		}

		print $ojson;
	}
?>
