<?php
	require_once("database.php");
	$firstterem = db_select_data("fitness.termek", "id", "", "termek.sorszam LIMIT 1");
	if (is_array($firstterem) && count($firstterem) > 0)
		print $firstterem[0]->id;
	else
		print "error";
?>
