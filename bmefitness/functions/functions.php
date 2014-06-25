<?php

	function object_from_array($array) { // ez az alatta levo json visszaforditasa, csak valamiert nem json, hanem arrayt ad at a javascript...
		$object = new stdClass();

		foreach ($array as $key => $value) {
			$object->$key = $value;
		}

		return $object;
	}

	function json_from_object($object) {
		$data = object_to_array($object);

		if (is_null($data))
			return NULL;

		return json_encode($data);
	}

	function object_to_array($data) {
		if (is_array($data) || is_object($data)) {
			$result = array();

			foreach ($data as $key => $value) {
				$result[$key] = object_to_array($value);
			}

			return $result;
		}

		return $data;
	}

	function uploadImageForm($functionlabel, $functionname, $folder, $schema, $table, $column, $id_name, $id, $convertToWidth, $convertToHeight) {
//		return "
//			<form id=\"form1\" enctype=\"multipart/form-data\" method=\"post\" action=\"functions/uploadfile.php\">\n
		return "	
			<div style=\"padding: 5px;\" class=\"row\">\n
				<div style=\"text-align: center;\">____________________</div>
				<label for=\"".$functionname."\">".$functionlabel."</label><br />\n
				<input type=\"file\" accept=\".jpeg,.jpg\" name=\"".$functionname."\" id=\"".$functionname."\" onchange=\"fileSelected('".$folder."', '".$schema."', '".$table."', '".$column."', '".$id_name."', '".$id."', '".$convertToWidth."', '".$convertToHeight."');\"/>\n
			</div>\n
		";
//			</form>
//		";
/*
				<div id=\"fileName\"></div>\n
				<div id=\"fileSize\"></div>\n
				<div id=\"fileType\"></div>\n
				<div class=\"row\">\n
						<input type=\"button\" onclick=\"uploadFile('".$schema."', '".$table."', '".$column."', '".$id_name."', '".$id."', '".$convertToWidth."', '".$convertToHeight."')\" value=\"Feltöltés\" />\n
				</div>\n
				<div id=\"progressNumber\"></div>\n
			</form>
		";
 */
	}

	// Csak akkor jo ez, ha a value nem stdclass, azaz nem object!
	// Tehat cask array-ra jo!
	function printArray($aArray, $sorveg, $keyName = "key", $valueName = "value") {
		foreach ($aArray as $key => $value) {
			echo $aKeyName.": ".$key.", ".$aValueName.": ".$value.$sorveg;
		}
	}
?>
