<?php
	if (isset($_POST["data_id"]) && isset($_POST["allow"])) {
		require_once("database.php");
		require_once("functions.php");

		$data_id = $_POST["data_id"];
		$allow = $_POST["allow"];

		$elvalaszto = "<!±!>";

		// TODO: visszaigazolta-nal meg kell adni, hogy ki volt az aktiv felhasznalo
		$columns = "visszaigazolta".$elvalaszto."torolve".$elvalaszto."torolve_id";
		$values = "1".$elvalaszto."'f'".$elvalaszto."0";
		if ($allow == "false")
			$values = "0".$elvalaszto."'t'".$elvalaszto."1";
		$result_object = db_update_rows_in_table($data_id, "fitness.naptar", $columns, $values, "");

		// megint at kell alakitani, hogy jo legyen a new_or_edit_data_forms.php-ban..
		if (is_array($result_object) && count($result_object) > 0)
			$ojson = json_from_object($result_object[0]);
		else
			$ojson = json_from_object($result_object);

		print $ojson;
	}
?>
