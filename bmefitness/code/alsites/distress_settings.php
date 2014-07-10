<?php
	require_once("../functions/database.php");

	$terem_id = 0;
	if (isset($_POST['terem']))
		$terem_id = $_POST['terem'];

	$only_torolt = false;
	$torolt_text = "true";
	if (isset($_POST['torolt']) && $_POST['torolt'] === "true") {
		$only_torolt = true;
		$torolt_text = "false"; // pont forditva kell leellenorizni, hogy jol mukodjon..., szoval direkt vna ez igy
	}

	print "<div id=\"change_distress_torolt_button\" onclick=\"change_distress_torolt(".$torolt_text.");\" class=\"action_button".($only_torolt ? " selected_button" : "")."\">".($only_torolt ? "Foglalások engedélyezése" : "Törölt foglalások")."</div>";
	print "<div class=\"separateline\"></div>";

	$termek = db_select_data("fitness.termek", "id, nev", "", "termek.sorszam");
	print "<div class=\"termekscrollcontent\">";
	foreach ($termek as $terem) {
		print "<div onclick=\"change_terem(".$terem->id.", 'distress');\" class=\"action_button";
		if ($terem->id == intval($terem_id))
			print " selected_terem";
		print "\">".$terem->nev."</div>";
	}
	print "</div>";
?>