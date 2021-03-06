<?php
require_once "Database.php";
require_once "RssFeed.php";
require_once("includes/session.php");
require_once("includes/MarkdownExtra.inc.php");
validateUser();

$db = new Database;

if ($db->openPrivilegedConnection())
{
	$mdExtra = new Michelf\MarkdownExtra();
	$rssFeed = new RssFeed;
	$date = new DateTime();

	$directionsHtml = $mdExtra->defaultTransform($_POST["directionsMd"]);
	$contentHtml = $mdExtra->defaultTransform($_POST["contentMd"]);

	// filter article html to remove p tags from around img tags for better caption appearance
	$contentHtml = filterHtmlForImageParagraphs($contentHtml);

	// form url from title
	$url = strtolower(str_replace(' ', '-', $_POST["title"]));

	// check if we are editing or creating new
	if (isset($_POST["submit"]))
	{
		// TODO - does Article need 2 constructors? 1 with an id field for when the DB has generated it, 
		// and 1 without when it is first created and before it is submitted to the DB.
		// PHP doesn't allow multiple constructors so must use static functions
		$article = new Article(0, $_POST["title"], $_POST["summary"], $_POST["tags"], $_POST["contentMd"], 
			$contentHtml, $date, $_POST["banner-image-path"], $_POST["directionsMd"], $directionsHtml, $url);

		if ($db->addArticle($article))
		{
			$rssFeed->updateFeed();
		}
		else
		{
			throw new Exception("Database insertion failed");
		}
	}

	if (isset($_POST["edit"]))
	{
		$article = new Article($_POST["article-id"], $_POST["title"], $_POST["summary"], $_POST["tags"], 
			$_POST["contentMd"], $contentHtml, $date, $_POST["banner-image-path"], $_POST["directionsMd"], 
			$directionsHtml, $url);

		if ($db->updateArticle($article))
		{
			$rssFeed->updateFeed();
		}
		else
		{
			throw new Exception("Database update failed");
		}
	}

	$db->closeConnection();
	header("Location: content.php");
}


function filterHtmlForImageParagraphs($input)
{
	// regex courtesy of https://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $input);
}