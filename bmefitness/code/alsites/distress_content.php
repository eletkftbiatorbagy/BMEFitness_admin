<?php
	require_once("../functions/weekcalendar.php");

	$het = 0;
	if (isset($_POST['het']))
		$het = $_POST['het'];

	$only_torolt = false;
	if (isset($_POST['torolt']) && $_POST['torolt'] === "true")
		$only_torolt = true;

	// TODO: itt meg nem jo az onclick het valtoztatasa!
	print "<br><span onclick=\"change_timetable_het(".($het - 1).");\" class=\"action_button\"><</span><span style=\"font-size: 2em;\"> ".(date("W") + $het).". hét </span><span onclick=\"change_timetable_het(".($het + 1).");\" class=\"action_button\">></span><br><br>";
	printFoglalasokTable(false, $only_torolt, $het);
	// TODO: csak egy termet kell megjeleniteni egyszerre
?>