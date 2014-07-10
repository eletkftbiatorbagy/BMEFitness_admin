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

	print "<div onclick=\"change_het(0, 'distress');\" class=\"action_button\" style=\"width: 80px; float: left;\">ma</div><br>";
	print "<span onclick=\"change_het(".($het - 1).", 'distress');\" class=\"action_button\"><</span><span style=\"font-size: 2em;\"> ".(date("W") + $het).". hét </span><span onclick=\"change_het(".($het + 1).", 'distress');\" class=\"action_button\">></span><br><br>";
	printFoglalasokTable(false, $only_torolt, $terem, $het);

	print "<br><table style=\"margin-left: auto; margin-right: auto;\">";
	print "<tr><td style=\"text-align: center;\"><div style=\"padding: 5px; margin: 5px; width: 400px; background-color: #99FF75;\">El van fogadva a foglalás, és nem ütközik másik eseménnyel.</div></td>";
	print "<td style=\"text-align: center;\"><div style=\"padding: 5px; margin: 5px; width: 400px; background-color: yellow;\">El van fogadva a foglalás, de ütközik egy másik eseménnyel.</div></td></tr>";
	print "<tr><td style=\"text-align: center;\"><div style=\"padding: 5px; margin: 5px; width: 400px; background-color: red; color: white;\">Nincs elfogadva a foglalás, és ütközik egy másik eseménnyel.</div>";
	print "<td style=\"text-align: center;\"><div style=\"padding: 5px; margin: 5px; width: 400px; background-color: white;\">Nincs elfogadva a foglalás, és nem ütközik másik eseménnyel.</div></tr>";
	print "</table>";
?>