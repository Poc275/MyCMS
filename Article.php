<?php

class Article
{
	private $mTitle;
	private $mSummary;
	private $mTags;
	private $mContentMd;
	private $mContentHtml;
	private $mPubDate;

	public function __construct($title, $summary, $tags, $contentMd, $contentHtml, $pubDate)
	{
		$this->mTitle = $title;
		$this->mSummary = $summary;
		$this->mTags = $tags;
		$this->mContentMd = $contentMd;
		$this->mContentHtml = $contentHtml;

		if ($pubDate instanceof DateTime)
		{
			$this->mPubDate = $pubDate;
		}
		else
		{
			throw new InvalidArgumentException("$pubDate must be of type DateTime");
		}
	}

	public function toString()
	{
		return $this->mTitle . "<br />" . $this->mSummary . "<br />" . $this->mTags . "<br />" . 
			$this->mContentMd . "<br />" . $this->mContentHtml . "<br />" . $this->mPubDate->format('Y-m-d H:i:s');
	}
}