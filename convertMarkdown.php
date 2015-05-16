<?php
require_once("includes/MarkdownExtra.inc.php");
require_once("includes/session.php");
validateUser();

$mdExtra = new Michelf\MarkdownExtra();
$instructionsHtml = $mdExtra->defaultTransform($_POST["instructions"]);

// trim instructions html to remove <ol> and </ol> as we are appending it to
// an existing ol
$instructionsHtml = substr($instructionsHtml, 4);
$instructionsHtml = substr($instructionsHtml, 0, -6);

$articleHtml = $mdExtra->defaultTransform($_POST["markdown"]);

$responses = array('instructions'=>$instructionsHtml, 'article'=>$articleHtml);
echo json_encode($responses);