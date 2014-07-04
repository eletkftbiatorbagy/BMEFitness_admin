<?php
	// static mezok
	require_once("database.php");
	require_once(__DIR__."/../../settings/settings.php");
	

	/*! Egy 7 tagú tömb lesz a végeredmény, így könnyen fogjuk tudni az adott nap adatait (például hétfő az a 0. elem)
	 */
	function weekdays($thedate) {
		$days = array();
		$dayofweek = date("N", $thedate);
		for ($i = 1 - $dayofweek; $i < 8 - $dayofweek; $i++)
			$days[] = $thedate + ((24 * $i) * 60 * 60);

		return $days;
	}

	/*!	Hány héttel a mai dátumhoz képest szeretnénk az időt. Ha 0, akkor a mai dátum lesz az eredmény.
	 */
	function dateForNextWeek($week) {
		$thedate = time() + ((7 * $week) * 24 * 60 * 60);
		return $thedate;
	}

	/*!	Természetesen annyi, hogy 1-12 kell megadni a hónap számát, és azért csökkentem egyel a visszatérésnél...
	 */
	function shortMonthName($month) {
		$names = array("jan.", "feb.", "már.", "ápr.", "máj.", "jún.", "júl.", "aug.", "szep.", "okt.", "nov.", "dec.");
		return $names[$month - 1];
	}

	/*!	Természetesen annyi, hogy 1-7 kell megadni a het nap szamat, és azért csökkentem egyel a visszatérésnél...
	 */
	function dayName($day) {
		$names = array("hétfő", "kedd", "szerda", "csütörtök", "péntek", "szombat", "vasárnap");
		return $names[$day - 1];
	}

	/*!	Ez egy tombot fog kidobni, hogy hany kulonbozo ora adatai vannak a megadott oraban.
	 *	Pontosabban visszakuldi az ora azonositojat, es ahhoz az orahoz tartozo kezdesi es befejezesi percet fogja tartalmazni.
	 *	Mivel csak 1 hetet vizsgalunk, igy eleg az aznapi datum, marmint a nap, es az ora, amire kivancsiak vagyunk
	 *	Egy tomb eleme:
	 *		- adat->bejegyzes, azaz konkretan az adott naptar bejegyzes az adatbazisban
	 *		- adat->min, azaz az adott oraban mennyi a minimum oraja. Ez 0-59 kozott van.
	 *		- adat->max, azaz az adott oraban mennyi a maximuma oraja. Ez 0-59 kozott van.
	 */
	function bejegyzesAtHourOfDayInNaptarak($naptarak, $year, $month, $day, $hour) {
		$eredmeny = array();
		$adate = $year."-".$month."-".$day." ".$hour;

		foreach ($naptarak as $bejegyzes) {
			$tol = date("Y-m-d H:i", $bejegyzes->tol);
			$ig = date("Y-m-d H:i", $bejegyzes->ig);

			if (($tol <= date("Y-m-d H:i", strtotime($adate.":00")) || $tol <= date("Y-m-d H:i", strtotime($adate.":59"))) &&
				($ig > date("Y-m-d H:i", strtotime($adate.":59")) || $ig > date("Y-m-d H:i", strtotime($adate.":00")))) {
				// ekkor biztosan aznap is van...
				// megkeressuk a minimumot
				$min = 0;
				for ($i = 0; $i < 60; $i++) {
					if ($tol <= date("Y-m-d H:i", strtotime($adate.($i < 10 ? ":0".$i : ":".$i)))) {
						$min = $i;
						break;
					}
				}

				// megkeressuk a maximumot
				$max = 0;
				for ($i = 59; $i >= 0; $i--) {
					if ($ig >= date("Y-m-d H:i", strtotime($adate.($i < 10 ? ":0".$i : ":".$i)))) {
						$max = $i;
						break;
					}
				}

				$adat = new stdClass;
				$adat->bejegyzes = $bejegyzes;
				$adat->min = $min;
				$adat->max = $max;

				$eredmeny[] = $adat;
			}
		}

		return $eredmeny;
	}


	/*!	Órák lefoglalásának időpontjai.
	 */
	function printOrakTable($inaktiv_only, $weekplusz = 0) {
		$thedate = dateForNextWeek($weekplusz);
		$weekdays = weekdays($thedate);

		// SELECT * FROM fitness.naptar WHERE tol > cast('2014-05-09' AS date) AND ig <= cast('2014-05-10' AS date);
		// megkeressuk az elso es utols utani napot // H:i:s
		$firstday = date("Y-m-d", $weekdays[0]);
		$firstday .= " 00:00:00";
		$lastday = date("Y-m-d", $weekdays[count($weekdays) - 1]);
		$lastday .= " 23:59:59";

		// forditva kell lekerdezni, tehat az ig, a befejezodesnek nagyobbnak kell lennie, mint az elso nap reggele, es a tol, azaz el kell kezdodnie a het utolso perce elott...
		$where = "";
		if ($inaktiv_only)
			$where .= "NOT ";
		$where .= "naptar.aktiv AND NOT naptar.torolve";
		$where .= " AND naptar.ig > cast('".$firstday."' AS timestamp) AND naptar.tol < cast('".$lastday."' AS timestamp) AND naptar.ora = orak.id AND naptar.edzo = edzok.id AND naptar.terem = termek.id";

		$select = "*"; // minden legyen benne
		$select .= ", naptar.id AS naptar_id"; // naptar atalakitasok
		$select .= ", orak.id AS ora_id, orak.nev AS ora_nev"; // ora atalakitasok
		$select .= ", edzok.id AS edzo_id, edzok.rovid_nev AS edzo_rovid_nev, edzok.vnev AS edzo_vezetek_nev, edzok.knev AS edzo_kereszt_nev"; // edzo atalakitasok
		$select .= ", termek.id AS terem_id, termek.nev AS terem_nev, termek.alcim AS terem_alcim"; // terem atalakitasok

		$naptarak = db_select_data("fitness.naptar, fitness.orak, fitness.edzok, fitness.termek", $select, $where, "naptar.tol");

		printTable($weekdays, $naptarak, "begin_new_or_edit_naptar");
	}

	/*!	Órák lefoglalásának időpontjai.
	 */
	function printFoglalasokTable($inaktiv_only, $only_torolt_bejegyzes, $weekplusz = 0) {
		$thedate = dateForNextWeek($weekplusz);
		$weekdays = weekdays($thedate);

		// SELECT * FROM fitness.naptar WHERE tol > cast('2014-05-09' AS date) AND ig <= cast('2014-05-10' AS date);
		// megkeressuk az elso es utols utani napot // H:i:s
		$firstday = date("Y-m-d", $weekdays[0]);
		$firstday .= " 00:00:00";
		$lastday = date("Y-m-d", $weekdays[count($weekdays) - 1]);
		$lastday .= " 23:59:59";

		// forditva kell lekerdezni, tehat az ig, a befejezodesnek nagyobbnak kell lennie, mint az elso nap reggele, es a tol, azaz el kell kezdodnie a het utolso perce elott...
		$where = "";
		if ($inaktiv_only)
			$where .= "NOT ";
		$where .= "naptar.aktiv AND ";
		if (!$only_torolt_bejegyzes)
			$where .= "NOT ";
		$where .= "naptar.torolve";
		$where .= " AND felhasznalok.aktiv";
		$where .= " AND naptar.ig > cast('".$firstday."' AS timestamp) AND naptar.tol < cast('".$lastday."' AS timestamp) AND naptar.terem = termek.id AND naptar.berlo = felhasznalok.id";

		$select = "*"; // minden legyen benne
		$select .= ", naptar.id AS naptar_id"; // naptar atalakitasok
		$select .= ", felhasznalok.id AS berlo_id, felhasznalok.vnev AS berlo_vezetek_nev, felhasznalok.knev AS berlo_kereszt_nev"; // felhasznalo atalakitasok
		$select .= ", termek.id AS terem_id, termek.nev AS terem_nev, termek.alcim AS terem_alcim"; // terem atalakitasok

//		print "select: ".$select."<br>";
//		print "where: ".$where."<br>";
//		return;

		$naptarak = db_select_data("fitness.naptar, fitness.termek, fitness.felhasznalok", $select, $where, "naptar.tol");

		printTable($weekdays, $naptarak, "begin_allow_distress");
	}

	/*!	Tabla kirajzolasa a het napjai es naptarak alapjan
	 */
	function printTable($weekdays, $naptarak, $editfunction) {
		$percek = array();

		// szerintem az osszes datum szoveget atkonvertalom rendes datumra
		for ($i = 0; $i < count($naptarak); $i++) {
			$naptarak[$i]->tol = strtotime($naptarak[$i]->tol);
			$naptarak[$i]->ig = strtotime($naptarak[$i]->ig);

			// fixen levonok egyet, mert altalaban ha azt irjuk, hogy 17:10-ig tart, akkor 17:10-kor mar nincs, azaz 17:09:59 -ig tart...
			$naptarak[$i]->ig -= 1;

			$naptarak[$i]->torolve_mikor = strtotime($naptarak[$i]->torolve_mikor);
			$naptarak[$i]->visszaigazolva = strtotime($naptarak[$i]->visszaigazolva);
			$naptarak[$i]->letrehozva = strtotime($naptarak[$i]->letrehozva);

			$naptarak[$i]->maxatfedes = 1; // hany darab naptar atfedesunk van..., azert kezdodik 1-rol, mert az egy azt jelenti, hogy csak az az egy bejegzesunk van... kesobb nem kell hozzaadni egyet...
			$naptarak[$i]->hanyadik = -1; // ha a masik naptar tol-ja elorebb van, akkor ennek bentebb kell lenni, es hogy mennyivel bentebb...
			$naptarak[$i]->atfedesek = array();
			$naptarak[$i]->sajatatfedesek = array();
		}

		//!! Figyelem: direkt van kulon ez a resz es nem egyben az elozovel, mert az elozoben alakitom at integerre szovg helyett a datumokat.
		for ($i = 0; $i < count($naptarak); $i++) {
			// itt pedig megkeressuk, hogy hany naptarbejegyzes talalhato meg atfedesben hozza kepest
				for ($j = 0; $j < count($naptarak); $j++) {
					if ($j == $i) // nyilvan sajat magat nem ellenorizzuk...
						continue;

					// eleg csak a tol vagy ig idopontokat megnezni, hogy az adott naptar tolig kozott van-e, es ha valamelyik kozotte van, akkor atfedes van
					if ((date("Y-m-d H:i", $naptarak[$i]->tol) <= date("Y-m-d H:i", $naptarak[$j]->tol) && date("Y-m-d H:i", $naptarak[$i]->ig) >= date("Y-m-d H:i", $naptarak[$j]->tol)) ||
						(date("Y-m-d H:i", $naptarak[$i]->tol) <= date("Y-m-d H:i", $naptarak[$j]->ig) && date("Y-m-d H:i", $naptarak[$i]->ig) >= date("Y-m-d H:i", $naptarak[$j]->ig)) ||
						// vagy ha a masik tolja az kisebb, egyenlo, mint a mi tolunk, de ugyanakkor nagyobb vagy egyenlo a masik igje :)
						(date("Y-m-d H:i", $naptarak[$j]->tol) <= date("Y-m-d H:i", $naptarak[$i]->tol) && date("Y-m-d H:i", $naptarak[$j]->ig) >= date("Y-m-d H:i", $naptarak[$i]->tol))) {

						if (!in_array($j, $naptarak[$i]->atfedesek))
							$naptarak[$i]->atfedesek[] = $j;

						if (!in_array($i, $naptarak[$j]->atfedesek))
							$naptarak[$j]->atfedesek[] = $i;

						if (!in_array($j, $naptarak[$i]->sajatatfedesek))
							$naptarak[$i]->sajatatfedesek[] = $j;

						if (!in_array($i, $naptarak[$j]->sajatatfedesek))
							$naptarak[$j]->sajatatfedesek[] = $i;

						for ($id = 0; $id < count($naptarak); $id++) {
							if ($id != $i && $id != $j) {
								if (in_array($i, $naptarak[$id]->atfedesek) && !in_array($j, $naptarak[$id]->atfedesek))
									$naptarak[$id]->atfedesek[] = $j;

								if (in_array($j, $naptarak[$id]->atfedesek) && !in_array($i, $naptarak[$id]->atfedesek))
									$naptarak[$id]->atfedesek[] = $i;
							}
						}
					}
				}
		}

		// eddig megvan, hogy ki kivel van atfedve, most megkeressuk a max atfedest es hogy hanyadik...
		$vizsgalt = array(); // ez csak azert, mert felesleges tobbszor vizsgalni ugyanazt a csoportot
		for ($i = 0; $i < count($naptarak); $i++) {
			$maxatfedes = 0;
			for ($perc = $naptarak[$i]->tol; $perc <= $naptarak[$i]->ig; $perc += 60) { // 60 masodpercenkent, azaz egy percenkent nezzuk...
				$atfedes = 0;
				for ($j = 0; $j < count($naptarak[$i]->atfedesek); $j++) {
					if ($naptarak[$naptarak[$i]->atfedesek[$j]]->tol <= $perc && $naptarak[$naptarak[$i]->atfedesek[$j]]->ig >= $perc) {
						$atfedes++;
					}
				}

				if ($atfedes > $maxatfedes)
					$maxatfedes = $atfedes;
			}

			$maxatfedes++; // egyel noveljk, mert minimum 1 kell, hogy legyen.

			if ($naptarak[$i]->maxatfedes < $maxatfedes)
				$naptarak[$i]->maxatfedes = $maxatfedes;

			// az osszesnel ujra kell allitani a max atfedest
			for ($j = 0; $j < count($naptarak[$i]->atfedesek); $j++) {
				if ($naptarak[$naptarak[$i]->atfedesek[$j]]->maxatfedes < $maxatfedes)
					$naptarak[$naptarak[$i]->atfedesek[$j]]->maxatfedes = $maxatfedes;
			}

			// most megkeressuk, hogy ki hanyadik
			for ($j = 0; $j < count($naptarak[$i]->sajatatfedesek); $j++) {
				if ($naptarak[$i]->hanyadik == -1)
					$naptarak[$i]->hanyadik++;

				if ($naptarak[$naptarak[$i]->sajatatfedesek[$j]]->hanyadik == $naptarak[$i]->hanyadik)
					$naptarak[$i]->hanyadik++;
			}

			// ez itt azert van, mert -1 az alap beallitas, es ha nincs "rivalis", akkor 0-nak kell lennie...
			if (count($naptarak[$i]->sajatatfedesek) == 0)
				$naptarak[$i]->hanyadik = 0;

			if ($naptarak[$i]->hanyadik >= $naptarak[$i]->maxatfedes)
				$naptarak[$i]->hanyadik = $naptarak[$i]->maxatfedes - 1;
		}

		// globalis adatok beolvasasa, hogy ne kesobb kelljen...
		$minhour = $GLOBALS['MINHOUR'];
		$maxhour = $GLOBALS['MAXHOUR'];
		$tdwidth = $GLOBALS['TDWIDTH'];
		$tdheight = $GLOBALS['TDHEIGHT'];
		$minheightforedzo = $GLOBALS['MINHEIGHTFOREDZO'];
		$shownaptarinfo = $GLOBALS['SHOWNAPTARINFO'];
		$fejlecszin = $GLOBALS['FEJLECSZIN'];
		$mainapfejlecszin = $GLOBALS['MAINAPFEJLECSZIN'];
		$cellaszin = $GLOBALS['CELLASZIN'];
		$mainapcellaszin = $GLOBALS['MAINAPCELLASZIN'];

		print "<table class=\"calendartable\">\n";
		// azert minusz egy, mert az elso oszlop az orak kiiratasa
		for ($hours = $minhour - 1; $hours <= $maxhour; $hours++) {
			print "<tr style=\"height: ".$tdheight."px;\">\n";
			// azert minusz egytol kezdodik, mert a -1 sor a datumot tartalmazza
			for ($day = -1; $day < count($weekdays); $day++) {
				$tdstyle = "<td style=\"";
				$tdcontent = "";
				$fejlecstyle = "border-top: 1px solid gray; border-bottom: 1px solid gray;";
				// ekkor a fejlecet iratjuk ki (datumokat)
				if ($day == -1 && $hours == $minhour - 1) {
					$tdstyle .= $fejlecstyle." padding: 5px; background-color: ".$fejlecszin.";";
					$tdcontent = "óra";
				}
				else if ($day == -1) {
					$tdstyle .= $fejlecstyle." padding: 5px; background-color: ".$fejlecszin.";";
					$tdcontent = $hours;
				}
				else if ($hours == $minhour - 1) {
					$tdstyle .= $fejlecstyle." width: ".$tdwidth."px; padding: 5px 0px 5px 0px; background-color: ";
					if (date("j", $weekdays[$day]) == date("j"))
						$tdstyle .= $mainapfejlecszin.";";
					else
						$tdstyle .= $fejlecszin.";";

					$tdcontent = date("Y", $weekdays[$day])." ".shortMonthName(date("n", $weekdays[$day]))." ".date("j", $weekdays[$day]).".<br>".dayName($day + 1)."\n";
				}
				// ekkor mar az orakat
				else {
					// kulso div kezdete
					$tdcontent = "<div style=\"position: relative; width: ".$tdwidth."px; height: ".$tdheight."px;\">";

					// felso vonal es also vonal
					if ($hours > $minhour) // a 0-s oranak a tetejen ne jelenjen meg...
						$tdcontent .= "<div style=\"position: absolute; width: 100%; height: 1px; top: 0px; background-color: gray;\"></div>";
					$tdcontent .= "<div style=\"position: absolute; width: 100%; height: 1px; top: ".$tdheight."px; background-color: gray;\"></div>";

					// cella hatterszinenk beallitasa...
					if (date("Y-m-d", $weekdays[$day]) == date("Y-m-d"))
						$tdstyle .= " background-color: ".$mainapcellaszin.";";
					else
						$tdstyle .= " background-color: ".$cellaszin.";";

					// naptar kirajzolasa
					foreach (bejegyzesAtHourOfDayInNaptarak($naptarak, date("Y", $weekdays[$day]), date("m", $weekdays[$day]), date("d", $weekdays[$day]), $hours) as $adat) {
//						print "adat ".date("Y. m. d. H:i", $adat->bejegyzes->tol)." - ".date("Y. m. d. H:i", $adat->bejegyzes->ig + 1)."<br>";
						$isOra = property_exists($adat->bejegyzes, "ora_nev");

						// szin kiszedese
						$bcolor = "#FFFFFF"; // feher lesz, ha nincs szine...

						// tehat orakat akarjuk kiiratni...
						if ($isOra) {
							if ($adat->bejegyzes->color != "")
								$bcolor = "#".$adat->bejegyzes->color;
						}
						else {
							// zold, ha el lett fogadva
							// sarga, ha el lett fogadva, de utkozes van
							// piros, ha nincs elfogadva es utkozes van
							// ha nincs visszaigazolva es nincs utkozes sem, akkor feher, igy nem valtozik

							if ($adat->bejegyzes->visszaigazolta && $adat->bejegyzes->maxatfedes > 1)
								$bcolor = "yellow";
							else if ($adat->bejegyzes->visszaigazolta)
								$bcolor = "#99FF75"; // zold
							else if ($adat->bejegyzes->maxatfedes > 1)
								$bcolor = "red";
						}

						// atalakitjuk, hogy osszefuggo legyen az egesz...
						$amax = $adat->max == 59 ? 60 : $adat->max; // hogy teljesen ki legyen toltve...
						$minm = round($tdheight / 60 * $adat->min); // top...
						$maxm = round($tdheight / 60 * ($amax - $adat->min)); // height

						// ha min 0, akkor el kell felul tuntetni a bordert
						if ($adat->min == 0)
							$tdstyle .= " border-top-color: ".$bcolor.";";
						// ha max 60, akkor meg alulrol tuntetjuk el a bordert
						if ($amax == 60)
							$tdstyle .= "border-bottom-color: ".$bcolor.";";

						$width = $tdwidth / $adat->bejegyzes->maxatfedes;
						$leftsz = " left: ".($adat->bejegyzes->hanyadik * $width)."px;";

						$widthsz = " width: ".$width."px;";

						// kiszamitjuj a magassagot, mert ez kell a naptar info kiiratasahoz is
						$topsz = " top: ".$minm."px;";

						// hozzaadjuk a tooltipet, hogy ne kelljen kattingatni az informacio miatt...
						// $adat->bejegyzes->ig, kijelzesnel hozza kell adni egyet...
						$tooltip = "";
						// tehat orat akarunk kiiratni
						if ($isOra) {
							$tooltip .= "Óra:\n\t".$adat->bejegyzes->ora_nev."\n\t".date("Y. m. d. H:i", $adat->bejegyzes->tol)." - ".(date("Y. m. d. H:i", $adat->bejegyzes->ig + 1))."\n\tMax létszám: ".$adat->bejegyzes->max_letszam." fő";
							$tooltip .= "\nEdző:\n\t".$adat->bejegyzes->edzo_vezetek_nev." ".$adat->bejegyzes->edzo_kereszt_nev." (".$adat->bejegyzes->edzo_rovid_nev.")";
						}
						else {
							$tooltip .= "Bejegyzés:\n\t".date("Y. m. d. H:i", $adat->bejegyzes->tol)." - ".(date("Y. m. d. H:i", $adat->bejegyzes->ig + 1));
							$tooltip .= "\n\tMeghívottak száma: ".count(pg_array_parse($adat->bejegyzes->meghivottak))." fő";
							$tooltip .= "\n\tRésztvevők száma: ".count(pg_array_parse($adat->bejegyzes->resztvevok))." fő";
							$tooltip .= "\nBérlő:\n\t".$adat->bejegyzes->berlo_vezetek_nev." ".$adat->bejegyzes->berlo_kereszt_nev;
							$tooltip .= "\n\tEmail: ".$adat->bejegyzes->tel;
							$tooltip .= "\n\tTelefonszám: ".$adat->bejegyzes->email;
							$tooltip .= "\n\tFoglalásai: ".$adat->bejegyzes->foglalas." alkalom";
							$tooltip .= "\n\tVisszamondta: ".$adat->bejegyzes->visszamondas." alkalom";
							$tooltip .= "\n\tNem jött el: ".$adat->bejegyzes->nemjott." alkalom";
							// TODO: talan meg a Visszaigazolo ember adatai is kellenenek, ha kell a visszaigazolo ember adatai, es o is felhasznalo, akkor kulon sql lekerdezes kell!!!
						}
						$tooltip .= "\nTerem:\n\t".$adat->bejegyzes->terem_nev."\n\t".$adat->bejegyzes->terem_alcim;

						$onclickfunction = "";
						if (!is_null($editfunction) && $editfunction != "") {
							$onclickfunction =  " onclick=\"".$editfunction."(".$adat->bejegyzes->naptar_id;
							// hozzaasdjuk a fogalast atfedo azonositokat, hogy el tudjuk kuldeni
							if (!$isOra) {
								$elvalaszto = "<!±!>";
								$onclickfunction .= ",'";
								for ($uid = 0; $uid < count($adat->bejegyzes->sajatatfedesek); $uid++) {
									$onclickfunction .= $naptarak[$adat->bejegyzes->sajatatfedesek[$uid]]->naptar_id;
									if ($uid + 1 < count($adat->bejegyzes->sajatatfedesek))
										$onclickfunction .= $elvalaszto;
								}
								$onclickfunction .= "'";
							}
							$onclickfunction .= ")\"";
						}

						// ez a szines div
						$tdcontent .= "<div title=\"".$tooltip."\"".$onclickfunction." style=\"cursor: pointer; position: absolute;".$widthsz.$leftsz.$topsz.($amax >= 60 && $adat->min == 0 ? " height: 100%;" : " height: ".$maxm."px;")." background-color: ".$bcolor.";\"></div>";

						// kikapcsoljuk a naptarinfot, ha nem egyeduli bejegyzes
						if ($adat->bejegyzes->maxatfedes > 1)
							$shownaptarinfo = false;

						if ($shownaptarinfo) {
							// kiiratjuk a naptar nevet es egyeb infojat, ha ez a kezdodatum.
							$mindate = date("Y", $weekdays[$day])."-".date("m", $weekdays[$day])."-".date("d", $weekdays[$day])." ".$hours.":".($adat->min < 10 ? "0".$adat->min : $adat->min);
							$diveloke = "<div title=\"".$tooltip."\"".$onclickfunction." style=\"position: relative; z-index: 1; cursor: pointer;".$topsz."\">";

							// ha az elso napon az elso oraban vagyunk es az esemenynek korabbi a kezdo idopontja, akkor megjelenitjuk a neve elott egy '<-' szoveget is.
							if ($hours == $minhour && $day == 0 && date("Y-m-d H:i", $adat->bejegyzes->tol) < date("Y-m-d H:i", strtotime($mindate))) {
								if ($isOra) {
									$tdcontent .= $diveloke."<- ".$adat->bejegyzes->ora_nev;
								}
								else {
									$tdcontent .= $diveloke."<- ".$adat->bejegyzes->berlo_vezetek_nev." ".$adat->bejegyzes->berlo_kereszt_nev;
								}
								// ha nagyobb vagy egyenlo, mint 70 perc, akkor megjelenitjuk az edzo rovid nevet is, mert egyebkent nem fer ki...
								if (($adat->bejegyzes->ig - strtotime(date("Y", $weekdays[$day])."-".date("m", $weekdays[$day])."-".date("d", $weekdays[$day])." ".$hours.":00") / 60) >= $minheightforedzo)
									$tdcontent .= "<br>".$adat->bejegyzes->terem_nev;
								$tdcontent .= "</div>";
							}
							// ha az utolso napon az utolso oraban vagyunk es az esemenynek kesobbi a zaro idopontja, akkor megjelenitjuk '->' szoveget. // nevre itt nincs szukseg, mert elotte szerepel
							else if ($hours == $maxhour && $day == count($weekdays) - 1 && date("Y-m-d H:i", $adat->bejegyzes->ig) > date("Y-m-d H:i", strtotime(date("Y", $weekdays[$day])."-".date("m", $weekdays[$day])."-".date("d", $weekdays[$day])." ".$hours.":59"))) {
								$tdcontent .= $diveloke."-></div>";
							}
							// egyebkent csak akkor iratjuk ki az ora adatait, ha ez a kezdodatum
							else if (date("Y-m-d H:i", $adat->bejegyzes->tol) == date("Y-m-d H:i", strtotime($mindate))) {
								if ($isOra) {
									$tdcontent .= $diveloke.$adat->bejegyzes->ora_nev;
								}
								else {
									$tdcontent .= $diveloke.$adat->bejegyzes->berlo_vezetek_nev." ".$adat->bejegyzes->berlo_kereszt_nev;
								}
								// ha nagyobb vagy egyenlo, mint 70 perc, akkor megjelenitjuk az edzo rovid nevet is, mert egyebkent nem fer ki...
								if ((($adat->bejegyzes->ig - $adat->bejegyzes->tol) / 60) >= $minheightforedzo)
									$tdcontent .= "<br>".$adat->bejegyzes->terem_nev;
								$tdcontent .= "</div>";
							}
						}
					}
					$tdcontent .= "</div>";
				}

				$tdstyle .= "\">";
				print $tdstyle.$tdcontent."</td>\n";
			}
			print "</tr>\n";
		}
		print "</table>\n";
	}

?>
