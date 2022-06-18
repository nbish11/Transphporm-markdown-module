<?php

declare(strict_types=1);

namespace nbish11\Transphporm;

use nbish11\Transphporm\MarkdownFunction;
use Transphporm\Config;
use Transphporm\Module;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

final class MarkdownModule implements Module
{
	private $env;

	public function __construct(Environment $env = null)
	{
		$this->env = $env ?: $this->createDefaultEnvironment();
	}

	public function load(Config $config)
	{
		$config->getFunctionSet()->addFunction('markdown', new MarkdownFunction($this->env));
	}

	private function createDefaultEnvironment()
	{
		$environment = new Environment([
			'html_input' => 'strip',
			'allow_unsafe_links' => false
		]);

		$environment->addExtension(new CommonMarkCoreExtension());
		$environment->addExtension(new GithubFlavoredMarkdownExtension());

		return $environment;
	}
}
