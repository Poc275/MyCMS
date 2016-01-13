<?php

require_once("includes/session.php");
validateUser();

//var_dump($_FILES['file']);

if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
    //only files ending with this extension would be uploaded
    //add more to the array to fit your needs
    $validExtensions = ['jpg', 'jpeg', 'png'];
    $tmpFilename = $_FILES['file']['tmp_name'];
    $filepath = 'img/' . $_FILES['file']['name'];

    // for security reasons check the file has been uploaded by PHP
    //make sure only valid files are uploaded
    if (is_uploaded_file($tmpFilename) && in_array(strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION)), $validExtensions)) {
        // TODO - report success, returns true if successful
        move_uploaded_file($tmpFilename, $filepath);
    }
}
