<?php
	if (isset($_POST["data_id"]) && isset($_POST["allow"]) && isset($_POST["utkozesek"])) {
		require_once("database.php");
		require_once("functions.php");

		$data_id = $_POST["data_id"];

		$aallow = $_POST["allow"];
		$allow = true;
		if ($aallow == "false")
			$allow = false;

		$utkozesek = $_POST["utkozesek"];

		$elvalaszto = "<!±!>";

		// TODO: visszaigazolta-nal meg kell adni, hogy ki volt az aktiv felhasznalo
		$columns = "visszaigazolta".$elvalaszto."torolve".$elvalaszto."torolve_id";
		$values = "1".$elvalaszto."'f'".$elvalaszto."0";
		$disvalues = "0".$elvalaszto."'t'".$elvalaszto."1";
		if (!$allow)
			$values = $disvalues;
		$result_object = db_update_rows_in_table($data_id, "fitness.naptar", $columns, $values, "");

		// frissitjuk
		if ($allow && !empty($utkozesek)) {
			$elvalaszto = "<!±!>";
			$disids = explode($elvalaszto, $utkozesek);
			foreach ($disids as $disid) {
				$result_object = db_update_rows_in_table($disid, "fitness.naptar", $columns, $disvalues, "");
			}
		}
/*
		// megint at kell alakitani, hogy jo legyen a new_or_edit_data_forms.php-ban..
		if (is_array($result_object) && count($result_object) > 0)
			$ojson = json_from_object($result_object[0]);
		else
			$ojson = json_from_object($result_object);

		print $ojson;
 */

		print "ok"; // kell visszajelzes, mert ha nincs, akkor assziszi a js, hogy xar ugy van
	}
?>
