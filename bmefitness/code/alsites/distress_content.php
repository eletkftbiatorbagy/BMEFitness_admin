<?php
	require_once("../functions/weekcalendar.php");

	$het = 0;
	if (isset($_POST['het']))
		$het = $_POST['het'];

	$only_torolt = false;
	if (isset($_POST['torolt']) && $_POST['torolt'] === "true")
		$only_torolt = true;

	$terem = 0;
	if (isset($_POST['terem']))
		$terem = $_POST['terem'];

	printFoglalasokTable(false, $only_torolt, $terem, $het);
?>