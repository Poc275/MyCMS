<?php
require "Database.php";
require_once("includes/session.php");
require_once("includes/MarkdownExtra.inc.php");
validateUser();

$db = new Database;

if ($db->openPrivilegedConnection())
{
	$mdExtra = new Michelf\MarkdownExtra();
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
			updateFeed($db->getArticles());
		}
		else
		{
			throw new Exception("Database insertion failed");
		}
	}

	if (isset($_POST["edit"]))
	{
		// get article id to enable SQL update
		$id = $db->getArticleIdFromTitle($_POST["title"]);

		$article = new Article($id, $_POST["title"], $_POST["summary"], $_POST["tags"], $_POST["contentMd"], 
			$contentHtml, $date, $_POST["banner-image-path"], $_POST["directionsMd"], $directionsHtml, $url);

		if (!$db->updateArticle($article))
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


function updateFeed($articles)
{
	$xml = '<?xml version="1.0" encoding="UTF-8" ?><rss version="2.0" 
		xmlns:atom="http://www.w3.org/2005/Atom">
		<channel><title>A Food Odyssey</title>
		<link>http://localhost:8080/MyCMS</link>
		<description>Amateurs trawl through the world of food</description>
		<category>Food</category>
		<image><url>/assets/placeholder.jpg</url>
		<title>A Food Odyssey</title>
		<link>http://localhost:8080/MyCMS</link></image>
		<language>en-uk</language>
		<atom:link href="http://localhost:8080/MyCMS/rss.xml" rel="self" type="application/rss+xml" />';

	foreach ($articles as $article)
	{
		$xml = $xml . '<item><title>' . $article->getTitle() . '</title><link>http://localhost:8080/MyCMS/articles/' . 
			$article->getUrl() . '</link><description>' . $article->getSummary() . 
			'</description><pubDate>' . $article->getPubDateRssFormat() . '</pubDate><guid>http://localhost:8080/MyCMS/articles/' . 
			$article->getUrl() . '</guid></item>';
	}

	$xml .= "</channel></rss>";

	$handle = fopen("rss.xml", "w+");
	fwrite($handle, $xml);
	fclose($handle);
}