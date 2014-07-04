<?php
	if (isset($_POST["type"])) {
		$edit = $_POST["type"];
		$object = NULL;

		$oid = "-1";

		require_once("../functions.php");

		if (isset($_POST["selectedObject"])) {
			// visszaalakitjuk, hogy tudjuk hasznalni...
			$jsonobject = $_POST["selectedObject"];
			$object = json_decode($jsonobject);
			$oid = $object->id;
		}
		
		if ($edit == "info") { // pontosabban egy uj edzo letrehozasa
			print "
				<table class=\"edit_data_table\">
					<tr><td id=\"edit_info_debut\" class=\"td_right ".(is_null($object) || strlen($object->bemutatkozas) == 0 ? "redcolor" : "")."\">Bemutatkozás:</td><td class=\"td_left\"><textarea rows=\"10\" cols=\"69\" type=\"text\" id=\"infodebut\" onchange=\"editedField('edit_info_debut', 'infodebut', false, 0);\">".(is_null($object) ? "" : $object->bemutatkozas)."</textarea></td></tr>\n
					<tr><td id=\"edit_info_policy\" class=\"td_right ".(is_null($object) || strlen($object->hazirend) == 0 ? "redcolor" : "")."\">Házirend:</td><td class=\"td_left\"><textarea rows=\"10\" cols=\"69\" type=\"text\" id=\"infopolicy\" onchange=\"editedField('edit_info_policy', 'infopolicy', false, 0);\">".(is_null($object) ? "" : $object->hazirend)."</textarea></td></tr>\n
					<tr><td id=\"edit_info_openinghours\" class=\"td_right ".(is_null($object) || strlen($object->nyitvatartas) == 0 ? "redcolor" : "")."\">Nyitvatartás:</td><td class=\"td_left\"><textarea rows=\"10\" cols=\"69\" type=\"text\" id=\"infoopeninghours\" onchange=\"editedField('edit_info_openinghours', 'infoopeninghours', false, 0);\">".(is_null($object) ? "" : $object->nyitvatartas)."</textarea></td></tr>\n
				</table>
			";
		}
		else if ($edit == "edzok") { // pontosabban egy uj edzo letrehozasa
			$imageForm = uploadImageForm("Fotó kiválasztása", "fileToUpload", "data_edzok", "fitness", "edzok", "foto", "id", $oid, 400, 300);
			print "
				<table class=\"edit_data_table\">
					<tr><td id=\"edit_edzo_vname\" class=\"td_right ".(is_null($object) || strlen($object->vnev) == 0 ? "redcolor" : "")."\">Vezetéknév:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"edzovname\" ".(is_null($object) ? "" : "value=\"".$object->vnev."\" ")."onchange=\"editedField('edit_edzo_vname', 'edzovname', false, 30);\"></input></td></tr>\n
					<tr><td id=\"edit_edzo_kname\" class=\"td_right ".(is_null($object) || strlen($object->knev) == 0 ? "redcolor" : "")."\">Keresztnév:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"edzokname\" ".(is_null($object) ? "" : "value=\"".$object->knev."\" ")."onchange=\"editedField('edit_edzo_kname', 'edzokname', false, 30);\"></input></td></tr>\n
					<tr><td id=\"edit_edzo_rname\" class=\"td_right ".(is_null($object) || strlen($object->rovid_nev) == 0 ? "redcolor" : "")."\">Rövid név:</td><td class=\"td_left\"><input maxlength=\"10\" size=\"31\" type=\"text\" id=\"edzorname\" ".(is_null($object) ? "" : "value=\"".$object->rovid_nev."\" ")."onchange=\"editedField('edit_edzo_rname', 'edzorname', false, 10);\"></input></td></tr>\n
					<tr><td id=\"edit_edzo_altitle\" class=\"td_right ".(is_null($object) || strlen($object->alcim) == 0 ? "redcolor" : "")."\">Alcím:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"edzoaltitle\" ".(is_null($object) ? "" : "value=\"".$object->alcim."\" ")."onchange=\"editedField('edit_edzo_altitle', 'edzoaltitle', false, 30);\"></input></td></tr>\n
					<tr><td id=\"edit_edzo_description\" class=\"td_right ".(is_null($object) || strlen($object->leiras) == 0 ? "redcolor" : "")."\">Leírás:</td><td class=\"td_left\"><textarea rows=\"5\" cols=\"29\" type=\"text\" id=\"edzodescription\" onchange=\"editedField('edit_edzo_description', 'edzodescription', false, 0);\">".(is_null($object) ? "" : $object->leiras)."</textarea></td></tr>\n
					<tr>
						<td id=\"edit_edzo_foto\" class=\"td_right ".(is_null($object) || strlen($object->foto) == 0 ? "redcolor" : "")."\">Fotó:
						</td>
						<td class=\"td_left\">"
							.$imageForm."\n"
							.((is_null($object) || $object->foto == "") ? "" : "<br><img src=\"data/data_edzok/".$object->foto.".jpg\"></img>")."
						</td>
					</tr>\n
				</table>
			";
		}
		else if ($edit == "orak") { // pontosabban egy uj edzo letrehozasa
			$logoForm = uploadImageForm("Logó kiválasztása", "logoToUpload", "data_orak", "fitness", "orak", "logo", "id", $oid, 400, 300);
			$imageForm = uploadImageForm("Fotó kiválasztása", "fileToUpload", "data_orak", "fitness", "orak", "foto", "id", $oid, 400, 300);
			print "
				<table class=\"edit_data_table\">
					<tr><td id=\"edit_ora_name\" class=\"td_right ".(is_null($object) || strlen($object->nev) == 0 ? "redcolor" : "")."\">Név:</td><td class=\"td_left\"><input maxlength=\"25\" size=\"31\" type=\"text\" id=\"oraname\" ".(is_null($object) ? "" : "value=\"".$object->nev."\" ")."onchange=\"editedField('edit_ora_name', 'oraname', false, 25);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_rname\" class=\"td_right ".(is_null($object) || strlen($object->rovid_nev) == 0 ? "redcolor" : "")."\">Rövid név:</td><td class=\"td_left\"><input maxlength=\"10\" size=\"31\" type=\"text\" id=\"orarname\" ".(is_null($object) ? "" : "value=\"".$object->rovid_nev."\" ")."onchange=\"editedField('edit_ora_rname', 'orarname', false, 10);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_altitle\" class=\"td_right ".(is_null($object) || strlen($object->alcim) == 0 ? "redcolor" : "")."\">Alcím:</td><td class=\"td_left\"><input maxlength=\"30\" size=\"31\" type=\"text\" id=\"oraaltitle\" ".(is_null($object) ? "" : "value=\"".$object->alcim."\" ")."onchange=\"editedField('edit_ora_altitle', 'oraaltitle', false, 30);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_perc\" class=\"td_right ".(is_null($object) || strlen($object->perc) == 0 ? "redcolor" : "")."\">Perc:</td><td class=\"td_left\"><input maxlength=\"3\" size=\"31\" type=\"text\" id=\"oraperc\" ".(is_null($object) ? "" : "value=\"".$object->perc."\" ")."onchange=\"editedField('edit_ora_perc', 'oraperc', false, 3);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_maxletszam\" class=\"td_right ".(is_null($object) || strlen($object->max_letszam) == 0 ? "redcolor" : "")."\">Max létszám:</td><td class=\"td_left\"><input maxlength=\"3\" size=\"31\" type=\"text\" id=\"oramaxletszam\" ".(is_null($object) ? "" : "value=\"".$object->max_letszam."\" ")."onchange=\"editedField('edit_ora_maxletszam', 'oramaxletszam', false, 3);\"></input></td></tr>\n
					<tr><td id=\"edit_ora_description\" class=\"td_right ".(is_null($object) || strlen($object->leiras) == 0 ? "redcolor" : "")."\">Leírás:</td><td class=\"td_left\"><textarea rows=\"5\" cols=\"29\" type=\"text\" id=\"oradescription\" onchange=\"editedField('edit_ora_description', 'oradescription', false, 0);\">".(is_null($object) ? "" : $object->leiras)."</textarea></td></tr>\n
					<tr><td class=\"td_right\">Belépődíj:</td><td class=\"td_left\"><input ".(is_null($object) || $object->belepodij == "t" ? "checked=\"true\" " : "")."type=\"checkbox\" size=\"23\" type=\"text\" id=\"orabelepodij\"></input></td></tr>\n
					<tr><td id=\"edit_ora_color\" class=\"td_right\">Szín:</td><td class=\"td_left\"><input class=\"color\" maxlength=\"6\" size=\"31\" type=\"text\" id=\"oracolor\" ".(is_null($object) ? "" : "value=\"".(strval($object->color))."\" ")."></input></td></tr>\n
					<tr>
						<td id=\"edit_ora_foto\" class=\"td_right ".(is_null($object) || strlen($object->foto) == 0 ? "redcolor" : "")."\">Fotó:
						</td>
						<td class=\"td_left\">"
							.$imageForm."\n"
							.((is_null($object) || $object->foto == "") ? "" : "<br><img src=\"data/data_orak/".$object->foto.".jpg\"></img>")."
						</td>
					</tr>\n
					<tr>
					<td id=\"edit_ora_logo\" class=\"td_right ".(is_null($object) || strlen($object->foto) == 0 ? "redcolor" : "")."\">Logó:
						</td>
						<td class=\"td_left\">"
							.$logoForm."\n"
							.((is_null($object) || $object->foto == "") ? "" : "<br><img src=\"data/data_orak/".$object->logo.".jpg\"></img>")."
						</td>
					</tr>\n
				</table>
			";
		}
		else if ($edit == "termek") { // pontosabban egy uj terem letrehozasa
			$imageForm = uploadImageForm("Fotó kiválasztása", "fileToUpload", "data_termek", "fitness", "termek", "foto", "id", $oid, 400, 300);
			print "
				<table class=\"edit_data_table\">
					<tr><td id=\"edit_terem_name\" class=\"td_right ".(is_null($object) || strlen($object->nev) == 0 ? "redcolor" : "")."\">Név:</td><td class=\"td_left\"><input maxlength=\"20\" size=\"23\" type=\"text\" id=\"teremname\" ".(is_null($object) ? "" : "value=\"".$object->nev."\" ")."onchange=\"editedField('edit_terem_name', 'teremname', false, 20);\"></input></td></tr>\n
					<tr><td id=\"edit_terem_altitle\" class=\"td_right ".(is_null($object) || strlen($object->alcim) == 0 ? "redcolor" : "")."\">Alcím:</td><td class=\"td_left\"><input maxlength=\"20\" size=\"23\" type=\"text\" id=\"teremaltitle\" ".(is_null($object) ? "" : "value=\"".$object->alcim."\" ")."onchange=\"editedField('edit_terem_altitle', 'teremaltitle', false, 20);\"></input></td></tr>\n
					<tr><td class=\"td_right\">Foglalható:</td><td class=\"td_left\"><input ".(is_null($object) || $object->foglalhato == "t" ? "checked=\"true\" " : "")."type=\"checkbox\" size=\"23\" type=\"text\" id=\"teremavailable\"></input></td></tr>\n
					<tr>
						<td id=\"edit_terem_foto\" class=\"td_right ".(is_null($object) || strlen($object->foto) == 0 ? "redcolor" : "")."\">Fotó:
						</td>
						<td class=\"td_left\">"
							.$imageForm."\n"
							.((is_null($object) || $object->foto == "") ? "" : "<br><img src=\"data/data_termek/".$object->foto.".jpg\"></img>")."
						</td>
					</tr>\n
				</table>
			";
		}
	}
?>
