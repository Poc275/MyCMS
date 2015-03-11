<?php
require_once("includes/MarkdownExtra.inc.php");
require_once("includes/session.php");
validateUser();

$mdExtra = new Michelf\MarkdownExtra();
echo $mdExtra->defaultTransform($_POST["markdown"]);