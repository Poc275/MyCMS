<?php

require "ImagesView.php";
require_once("includes/session.php");
validateUser();

$imagesView = new ImagesView();
$imagesView->getImages();