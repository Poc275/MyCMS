<?php

require_once "ImagesView.php";
require_once("includes/session.php");
validateUser();

$imagesView = new ImagesView();
$imagesView->getImages();