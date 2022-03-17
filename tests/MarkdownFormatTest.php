<?php

declare(strict_types=1);

namespace nbish11\Transphporm;

use nbish11\Transphporm\MarkdownModule;
use Transphporm\Builder;
use PHPUnit\Framework\TestCase;

final class MarkdownFormatTest extends TestCase
{
	/**
	 * @test
	 * @covers nbish11\Transphporm\MarkdownFormat::markdown
	 */
	public function converts_markdown_to_html(): void
	{
		$template = '<h1>Template Heading</h1>';
		$tss = 'h1 {content: data(); format: markdown; content-mode: replace}';
		$markdown = '# Markdown Heading';
		$template = new Builder($template, $tss);

		$template->loadModule(new MarkdownModule());

		$this->assertEquals('<h1>Markdown Heading</h1>', $template->output($markdown)->body);
	}
}