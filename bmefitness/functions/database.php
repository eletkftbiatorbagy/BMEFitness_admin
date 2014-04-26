<?php
	$DatabaseIP = "localhost";
	$DatabasePort = "5432";
	$DatabaseName = "bmefitness";
	$DatabaseUser = "bme";
	$DatabasePassword = "fitness";

	function db_query_object_array($query) {
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

	/*!
	 *	$returnID a tablazat egyik oszlopanak neve lehet, ha nem ures string, akkor az kerul visszakuldesre
	 */
	function db_insert_into_table($tableNameWithSchema, $vauleIDs, $values, $returnID = '') {
		if (is_null($tableNameWithSchema) || is_null($vauleIDs) || is_null($values) ||
			$tableNameWithSchema == "" || $vauleIDs == "" || $values == "")
			return NULL;

		// INSERT INTO $tableNameWithSchema($vauleIDs) VALUES($values);
		$query = "INSERT INTO ".$tableNameWithSchema."(".$vauleIDs.") VALUES(".$values.")";
		if ($returnID != '')
			$query .= " RETURNING ".$returnID;
		$query .= ";";

//		return $query;

		return db_query_object_array($query);
	}

	/*!
	 *	Az összes sor adatait felülírjuk így
	 */
	function db_update_rows_in_table($ids, $tableNameWithSchema, $vauleIDs, $values, $returnID = '') {
		if (is_null($ids) || is_null($tableNameWithSchema) || is_null($vauleIDs) || is_null($values) ||
			$ids == "" || $tableNameWithSchema == "" || $vauleIDs == "" || $values == "")
			return NULL;

		$elvalaszto = "<!±!>";
		$avalueids = explode($elvalaszto, $vauleIDs);
		$avalues = explode($elvalaszto, $values);
		$areturnValues = $returnID; // init a biztonsag kedveert
		if (!is_null($returnID) && !empty($returnID))
			$areturnValues = explode($elvalaszto, $returnID);

		if (count($avalueids) == 0 || (count($avalueids) != count($avalues)))
			return NULL;

		$vauleIDsAndValues = "";
		$returningIDs = "";

		for ($i = 0; $i < count($avalueids); $i++) {
			$vauleIDsAndValues .= ($vauleIDsAndValues == "" ? ($avalueids[$i]." = ".$avalues[$i]) : (",".$avalueids[$i]." = ".$avalues[$i]));
		}

		for ($i = 0; $i < count($areturnValues); $i++) {
			$returningIDs .= ($returningIDs == "" ? $areturnValues[$i] : ",".$areturnValues[$i]);
		}

		if ($ids == "all")
			$query = "UPDATE ".$tableNameWithSchema." SET ".$vauleIDsAndValues;
		else
			$query = "UPDATE ".$tableNameWithSchema." SET ".$vauleIDsAndValues." WHERE id IN (".$ids.")";

		if (!is_null($returningIDs) && !empty($returningIDs))
			$query .= " RETURNING ".$returningIDs;
		else if ($ids == "all")
			$query .= " RETURNING ".$avalueids[0]; // azert csinalom igy, mert igy lesz visszztero ertek, tehat nem viszi fel uj sort, ha nem kell
		$query .= ";";

//		return $query;

		$result = db_query_object_array($query);
		if (!$result && $ids == "all") {
			// ha mindenkeppen updatelni akarunk es meg nincs a tablankban semmi sor, akkor hozzafuzi...
			// at kell alakitani ,-re az elvalaszto karaktereket
			$vauleIDs = str_replace($elvalaszto, ",", $vauleIDs);
			$values = str_replace($elvalaszto, ",", $values);
			return db_insert_into_table($tableNameWithSchema, $vauleIDs, $values, $returningIDs);
		}
		else {
			return $result;
		}
	}
?>
