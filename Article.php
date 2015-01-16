<?php

class Article
{
	private $mTitle;
	private $mSummary;
	private $mTags;
	private $mContent;
	private $mPubDate;

	public function __construct($title, $summary, $tags, $content, $pubDate)
	{
		$this->mTitle = $title;
		$this->mSummary = $summary;
		$this->mTags = $tags;
		$this->mContent = $content;

		if ($pubDate instanceof DateTime)
		{
			$this->mPubDate = $pubDate;
		}
		else
		{
			throw new InvalidArgumentException("$pubDate must be of type DateTime");
		}
	}
}