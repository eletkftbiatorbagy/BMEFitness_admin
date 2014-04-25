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
?>
