<?php
	if (isset($_POST["table"]) && isset($_POST["defaultcolumnname"]) && isset($_POST["id"]) && isset($_POST["othercolumnname"]) && isset($_POST["values"])) {
		require_once("../database.php");
		// SELECT fitness.update_relationship('foglalkozas', 'edzo', 1, 'ora', '2=1,3=0');

		$table = $_POST["table"];
		$defaultcolumnname = $_POST["defaultcolumnname"];
		$id = $_POST["id"];
		$othercolumnname = $_POST["othercolumnname"];
		$values = $_POST["values"];

		$query = "SELECT fitness.update_relationship('".$table."', '".$defaultcolumnname."', ".$id.", '".$othercolumnname."', '".$values."')";
//		echo $query;
		$result = db_query_object_array($query);
//		echo $result;
		if (is_array($result) && count($result) > 0 && $result[0]->update_relationship == "t")
			print "true";
		else
			print "false";
	}
?>
