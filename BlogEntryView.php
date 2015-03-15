<?php

class BlogEntryView
{
	private $templateDir = "templates/";
	private $article;

	public function __construct()
	{
	}

	public function render($templateFile)
	{
		if (file_exists($this->templateDir . $templateFile))
		{
			include $this->templateDir . $templateFile;
		}
		else
		{
			throw new Exception("Template " . $templateFile . "does not exist");
		}
	}

	public function __set($name, $value)
	{
		$this->article = $value;
	}

	public function __get($name)
	{
		return $this->article;
	}
}