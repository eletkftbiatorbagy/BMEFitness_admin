<?php
	require_once("../functions/weekcalendar.php");

	$het = 0;
	if (isset($_POST['het']))
	$het = $_POST['het'];

	print "<br><span onclick=\"change_timetable_het(".($het - 1).");\" class=\"action_button\"><</span><span style=\"font-size: 2em;\"> ".(date("W") + $het).". hét </span><span onclick=\"change_timetable_het(".($het + 1).");\" class=\"action_button\">></span><br><br>";
	printFoglalasokTable(false, $het);
	// TODO: csak egy termet kell megjeleniteni egyszerre
?>