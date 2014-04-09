<?php
	if (isset($_POST["table_name_with_schema"]) && isset($_POST["value_ids"]) && isset($_POST["values"])) {
		require_once("../database.php");

		$tableNameWithSchema = $_POST["table_name_with_schema"];
		$vauleIDs = $_POST["value_ids"];
		$values = $_POST["values"];

		$result_object = db_insert_into_table($tableNameWithSchema, $vauleIDs, $values, "id");

//		print "OK: ".$result_object->id;
		print "OK: ".$result_object;
//		print "table: ".$tableNameWithSchema.", ids: ".$vauleIDs.", vals: ".$values;
	}
?>
