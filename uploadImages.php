<?php

require_once("includes/session.php");
validateUser();

//var_dump($_FILES['file']);

if ($_FILES['file']['error'] == UPLOAD_ERR_OK)
{
	$tmpFilename = $_FILES['file']['tmp_name'];
	$filepath = 'img/' . $_FILES['file']['name'];

	// for security reasons check the file has been uploaded by PHP
	if (is_uploaded_file($tmpFilename))
	{
		// TODO - report success, returns true if successful
		move_uploaded_file($tmpFilename, $filepath);
	}
}