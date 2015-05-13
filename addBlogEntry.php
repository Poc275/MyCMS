<?php
require "Database.php";
require_once("includes/session.php");
require_once("includes/MarkdownExtra.inc.php");
validateUser();

$db = new Database;

if ($db->openPrivilegedConnection())
{
	$mdExtra = new Michelf\MarkdownExtra();
	$directionsHtml = $mdExtra->defaultTransform($_POST["directionsMd"]);
	$contentHtml = $mdExtra->defaultTransform($_POST["contentMd"]);

	$date = new DateTime();

	// TODO - does Article need 2 constructors? 1 with an id field for when the DB has generated it, 
	// and 1 without when it is first created and before it is submitted to the DB.
	// PHP doesn't allow multiple constructors so must use static functions
	$article = new Article(0, $_POST["title"], $_POST["summary"], $_POST["tags"], $_POST["contentMd"], 
		$contentHtml, $date, $_POST["banner-image-path"], $_POST["directionsMd"], $directionsHtml);

	if ($db->addArticle($article))
	{
		updateFeed($db->getArticles());
	}

	$db->closeConnection();
	header("Location: content.php");
}


function updateFeed($articles)
{
	$xml = '<?xml version="1.0" encoding="UTF-8" ?><rss version="2.0" 
		xmlns:atom="http://www.w3.org/2005/Atom">
		<channel><title>The Ponderer\'s Cookbook</title>
		<link>http://localhost:8080/MyCMS</link>
		<description>A thoughtful approach to cookery from a pair of keen amateur cooks</description>
		<category>Food</category>
		<image><url>http://localhost:8080/MyCMS/assets/pensive.png</url>
		<title>The Ponderer\'s Cookbook</title>
		<link>http://localhost:8080/MyCMS</link></image>
		<language>en-uk</language>
		<atom:link href="http://localhost:8080/MyCMS/rss.xml" rel="self" type="application/rss+xml" />';

	foreach ($articles as $article)
	{
		$xml = $xml . '<item><title>' . $article->getTitle() . '</title><link>http://localhost:8080/MyCMS/articles/' . 
			$article->getId() . '</link><description>' . $article->getSummary() . '</description><pubDate>' . 
			$article->getPubDateRssFormat() . '</pubDate><guid>http://localhost:8080/MyCMS/articles/' . 
			$article->getId() . '</guid></item>';
	}

	$xml .= "</channel></rss>";

	$handle = fopen("rss.xml", "w+");
	fwrite($handle, $xml);
	fclose($handle);
}