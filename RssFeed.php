<?php

require_once "Database.php";
require_once "Article.php";

class RssFeed
{
	private $mXmlHeader = '<?xml version="1.0" encoding="UTF-8" ?>
				<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
				<channel>
				<title>A Food Odyssey</title>
				<link>http://localhost:8080/MyCMS</link>
				<description>Amateurs trawl through the world of food</description>
				<category>Food</category>
				<image>
				<url>img/placeholder.jpg</url>
				<title>A Food Odyssey</title>
				<link>http://localhost:8080/MyCMS</link>
				</image>
				<language>en-uk</language>
				<atom:link href="http://localhost:8080/MyCMS/rss.xml" rel="self" type="application/rss+xml" />';


	public function updateFeed()
	{
		$db = new Database;
		$xml = "";

		if ($db->openRestrictedConnection())
		{
			$articles = $db->getArticles();
			$db->closeConnection();

			foreach ($articles as $article)
			{
				$xml .= '<item><title>' . $article->getTitle() . 
					'</title><link>http://localhost:8080/MyCMS/articles/' . $article->getUrl() . 
					'</link><description>' . $article->getSummary() . '</description><pubDate>' . 
					$article->getPubDateRssFormat() . '</pubDate><guid>http://localhost:8080/MyCMS/articles/' . 
					$article->getUrl() . '</guid></item>';
			}

			$xml .= "</channel></rss>";

			$finalXml = $this->mXmlHeader . $xml;

			$handle = fopen("rss.xml", "w+");
			fwrite($handle, $finalXml);
			fclose($handle);
		}
	}

}