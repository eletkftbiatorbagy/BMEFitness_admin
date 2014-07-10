<?php
	require_once("../functions/weekcalendar.php");

	$het = 0;
	if (isset($_POST['het']))
		$het = $_POST['het'];

	$terem = 0;
	if (isset($_POST['terem']))
		$terem = $_POST['terem'];

	print "<div onclick=\"change_het(0, 'timetable');\" class=\"action_button\" style=\"width: 80px; float: left;\">ma</div><br>";
	print "<span onclick=\"change_het(".($het - 1).", 'timetable');\" class=\"action_button\"><</span><span style=\"font-size: 2em;\"> ".(date("W") + $het).". hét </span><span onclick=\"change_het(".($het + 1).", 'timetable');\" class=\"action_button\">></span><br><br>";
	printOrakTable(false, $terem, $het);
?>