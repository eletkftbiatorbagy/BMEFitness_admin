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

	// $returnID a tablazat egyik oszlopanak neve lehet, ha nem ures string, akkor az kerul visszakuldesre
	function db_insert_into_table($tableNameWithSchema, $vauleIDs, $values, $returnID = '') {
		if (is_null($tableNameWithSchema) || is_null($vauleIDs) || is_null($values) ||
			$tableNameWithSchema == "" || $vauleIDs == "" || $values == "")
			return NULL;

		$handle =	'host='.$GLOBALS['DatabaseIP'].
					' port='.$GLOBALS['DatabasePort'].
					' dbname='.$GLOBALS['DatabaseName'].
					' user='.$GLOBALS['DatabaseUser'].
					' password='.$GLOBALS['DatabasePassword'];

		$db_handle = pg_connect($handle);

		// INSERT INTO $tableNameWithSchema($vauleIDs) VALUES($values);
		$query = "INSERT INTO ".$tableNameWithSchema."(".$vauleIDs.") VALUES(".$values.")";
		if ($returnID != '')
			$query .= " RETURNING ".$returnID;
		$query .= ";";

//		return $query;

		$result = pg_query($db_handle, $query);
		if (!$result)
			return NULL;

		$result_ar = array();

		if ($returnID != '') {
			for ($i = 0; $i < pg_num_rows($result); $i++) {
				$result_ar[] = pg_fetch_object($result, $i);
			}
		}

		pg_freeResult($result);
		pg_close($db_handle);

		if ($returnID != '')
			return $result_ar;
	}
?>
