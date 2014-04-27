<?php
	if (isset($_POST["table"]) && isset($_POST["id"]) && isset($_POST["ujsorszam"])) {
		$table = $_POST["table"];
		$id = $_POST["id"];
		$ujsorszam = $_POST["ujsorszam"];

		require_once("../database.php");
		$query = "SELECT fitness.update_sorszam('".$table."', ".$id.", ".$ujsorszam.")";
		$result = db_query_object_array($query);
		if (is_array($result) && count($result) > 0 && $result[0]->update_sorszam == "t")
			print "true";
		else
			print "false";
	}
?>
