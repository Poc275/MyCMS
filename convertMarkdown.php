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
// filter article html to remove p tags from around img tags for better caption appearance
$articleHtml = filterHtmlForImageParagraphs($articleHtml);

$responses = array('instructions'=>$instructionsHtml, 'article'=>$articleHtml);
echo json_encode($responses);


function filterHtmlForImageParagraphs($input)
{
	// regex courtesy of https://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $input);
}