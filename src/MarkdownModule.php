<?php

declare(strict_types=1);

namespace nbish11\Transphporm;

use nbish11\Transphporm\MarkdownFormat;
use Transphporm\Config;
use Transphporm\Module;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

final class MarkdownModule implements Module
{
	private $converter;

	public function __construct(MarkdownConverter $converter = null)
	{
		if ($converter === null) {
			$converter = $this->createDefaultConverter();
		}

		$this->converter = $converter;
	}

	public function load(Config $config)
	{
		$markdownFormat = new MarkdownFormat($this->converter, $config);

		$config->registerFormatter($markdownFormat);
	}

	private function createDefaultConverter(): MarkdownConverter
	{
		$environment = new Environment([
			'html_input' => 'strip',
			'allow_unsafe_links' => false
		]);

		$environment->addExtension(new CommonMarkCoreExtension());
		$environment->addExtension(new GithubFlavoredMarkdownExtension());

		return new MarkdownConverter($environment);
	}
}