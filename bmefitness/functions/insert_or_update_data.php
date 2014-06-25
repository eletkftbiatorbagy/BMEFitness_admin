<?php
	if (isset($_POST["data_id"]) && isset($_POST["table_name"]) && isset($_POST["schema"]) && isset($_POST["value_ids"]) && isset($_POST["values"]) && isset($_POST["returning"])) {
		require_once("database.php");
		require_once("functions.php");

		$data_id = $_POST["data_id"];
		$tableNameWithSchema = $_POST["table_name"].".".$_POST["schema"];
		$vauleIDs = $_POST["value_ids"];
		$values = $_POST["values"];
		$returning = $_POST["returning"];

		$uj = true;
		if ($data_id != "-1")
			$uj = false;

		if ($data_id == "-1")
			$result_object = db_insert_into_table($tableNameWithSchema, $vauleIDs, $values, $returning);
		else if ($data_id == "all") // $data_id lehet 'all' is!
			$result_object = db_update_rows_in_table($data_id, $tableNameWithSchema, $vauleIDs, $values, $returning);
		else
			$result_object = db_update_rows_in_table($data_id, $tableNameWithSchema, $vauleIDs, $values, $returning);


		// megint at kell alakitani, hogy jo legyen a new_or_edit_data_forms.php-ban..
		if (is_array($result_object) && count($result_object) > 0) {
			$ojson = json_from_object($result_object[0]);
		}
		else if ($result_object == NULL) {
			$ojson = "error";
		}
		else {
			$ojson = json_from_object($result_object);
		}

		print $ojson;
	}
?>
