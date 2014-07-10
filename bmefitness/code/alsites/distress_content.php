<?php
	require_once("../functions/weekcalendar.php");

	$het = 0;
	if (isset($_POST['het']))
		$het = $_POST['het'];

	$only_torolt = false;
	if (isset($_POST['torolt']) && $_POST['torolt'] === "true")
		$only_torolt = true;

	print "<div onclick=\"change_het(0, 'distress');\" class=\"action_button\" style=\"width: 80px; float: left;\">ma</div><br>";
	print "<span onclick=\"change_het(".($het - 1).", 'distress');\" class=\"action_button\"><</span><span style=\"font-size: 2em;\"> ".(date("W") + $het).". hét </span><span onclick=\"change_het(".($het + 1).", 'distress');\" class=\"action_button\">></span><br><br>";
	printFoglalasokTable(false, $only_torolt, $het);
	// TODO: csak egy termet kell megjeleniteni egyszerre
?>