<?php
	require_once("../functions/weekcalendar.php");

	$het = 0;
	if (isset($_POST['het']))
		$het = $_POST['het'];

	$terem = 0;
	if (isset($_POST['terem']))
		$terem = $_POST['terem'];

	printOrakTable(false, $terem, $het);
?>