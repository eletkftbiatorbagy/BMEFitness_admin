<?php
	if (isset($_POST["data_id"]) && isset($_POST["table_name_with_schema"]) && isset($_POST["value_ids"]) && isset($_POST["values"])) {
		require_once("../database.php");

		$data_id = $_POST["data_id"];
		$tableNameWithSchema = $_POST["table_name_with_schema"];
		$vauleIDs = $_POST["value_ids"];
		$values = $_POST["values"];

		if ($data_id == "-1")
			$result_object = db_insert_into_table($tableNameWithSchema, $vauleIDs, $values, "id");
		else if ($data_id == "all") // $data_id lehet 'all' is!
			$result_object = db_update_rows_in_table($data_id, $tableNameWithSchema, $vauleIDs, $values);
		else
			$result_object = db_update_rows_in_table($data_id, $tableNameWithSchema, $vauleIDs, $values, "id");

//		print "OK: ".$result_object->id;
		print "OK: ".$result_object;
//		print "table: ".$tableNameWithSchema.", ids: ".$vauleIDs.", vals: ".$values;
	}
?>
