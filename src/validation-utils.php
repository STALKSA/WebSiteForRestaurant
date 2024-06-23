<?php

function get_val($inputName) {
	return isset($_POST[$inputName]) ? $_POST[$inputName] : "";
}

function has_error($inputName, $regex = null, $errorType = "invalid") {
	if (!isset($_POST[$inputName])) {
		return "unset";
	} else if (trim($_POST[$inputName]) == "") {
		return "error required";
	} else if (!is_null($regex) && !preg_match($regex, $_POST[$inputName])) {
		return "error " . $errorType;
	}

	return "";
}
