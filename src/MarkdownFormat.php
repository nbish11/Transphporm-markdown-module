<?php

declare(strict_types=1);

namespace nbish11\Transphporm;

use Transphporm\Config;
use League\CommonMark\MarkdownConverterInterface as MarkdownConverter;

final class MarkdownFormat
{
	private $converter;
	private $config;

	public function __construct(MarkdownConverter $converter, Config $config)
	{
		$this->converter = $converter;
		$this->config = $config;
	}

	public function markdown($markdownText)
	{
		$reflection = new \ReflectionProperty($this->config->getFunctionSet(), 'functions');
		$html = $this->converter->convertToHtml($markdownText);

		$reflection->setAccessible(true);
		$template = $reflection->getValue($this->config->getFunctionSet())['template'];

		return $template->run(['<template>' . $html . '</template>']);
	}
}