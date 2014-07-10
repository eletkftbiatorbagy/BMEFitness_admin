<?php
	require_once("../functions/database.php");

	$terem_id = 0;
	if (isset($_POST['terem']))
		$terem_id = $_POST['terem'];

	$termek = db_select_data("fitness.termek", "id, nev", "", "termek.sorszam");
	print "<div class=\"termekscrollcontent\">";
	foreach ($termek as $terem) {
		print "<div onclick=\"change_terem(".$terem->id.", 'timetable');\" class=\"action_button";
		if ($terem->id == intval($terem_id))
			print " selected_terem";
		print "\">".$terem->nev."</div>";
	}
	print "</div>";
?>