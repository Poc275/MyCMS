<?php

require "Article.php";

class Database
{
	private $mConnection;

	public function openConnection()
	{
		$result = false;
		$this->mConnection = new mysqli("localhost", "user", "", "cms");

		if ($this->mConnection)
        {
            $result = true;
        }

        return $result;
	}

	public function closeConnection()
	{
		mysqli_close($this->mConnection);
	}

	public function getArticles()
	{
		$articles = array();
		$query = "SELECT * FROM articles";
	    $stmt = mysqli_prepare($this->mConnection, $query);

	    if (!mysqli_stmt_execute($stmt))
	    {
	        throw new Exception("Database query failed");
	    }
	    else
	    {
	        mysqli_stmt_bind_result($stmt, $titleCol, $summaryCol, $tagsCol, $contentCol, $pubDateCol);

	        while (mysqli_stmt_fetch($stmt))
	        {
	        	$article = new Article($titleCol, $summaryCol, $tagsCol, $contentCol, $pubDateCol);
	        	array_push($articles, $article);
	        }

	        mysqli_stmt_close($stmt);
		}

		return $articles;
	}
}