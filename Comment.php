<?php

class Comment
{
	private $mId;
	private $mArticleFk;
	private $mName;
	private $mComment;
	private $mDate;

	public function __construct($id, $articleFk, $name, $comment, $date)
	{
		$this->mId = $id;
		$this->mArticleFk = $articleFk;
		$this->mName = $name;
		$this->mComment = $comment;

		if ($date instanceof DateTime)
		{
			$this->mDate = $date;
		}
		else
		{
			throw new InvalidArgumentException("$date must be of type DateTime");
		}
	}

	public function getId()
	{
		return $this->mId;
	}

	public function getArticle()
	{
		return $this->mArticleFk;
	}

	public function getName()
	{
		return $this->mName;
	}

	public function getComment()
	{
		return $this->mComment;
	}

	public function getDate()
	{
		return $this->mDate->format('Y-m-d H:i:s');
	}

	public function getNiceDate()
	{
		return $this->mDate->format('F jS, Y - g:i a');
	}
}