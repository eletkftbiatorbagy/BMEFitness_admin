<?php
	$DatabaseIP = "localhost";
	$DatabasePort = "5432";
	$DatabaseName = "bmefitness";
	$DatabaseUser = "bme";
	$DatabasePassword = "fitness";

	function db_select_object_array($query) {
		$handle =	'host='.$GLOBALS['DatabaseIP'].
					' port='.$GLOBALS['DatabasePort'].
					' dbname='.$GLOBALS['DatabaseName'].
					' user='.$GLOBALS['DatabaseUser'].
					' password='.$GLOBALS['DatabasePassword'];

		$db_handle = pg_connect($handle);
		$result = pg_query($db_handle, $query);
		if (!$result)
			return NULL;

		$result_ar = array();
		for ($i = 0; $i < pg_num_rows($result); $i++) {
			$result_ar[] = pg_fetch_object($result, $i);
		}
		pg_freeResult($result);
		pg_close($db_handle);

		return $result_ar;
	}
?>
