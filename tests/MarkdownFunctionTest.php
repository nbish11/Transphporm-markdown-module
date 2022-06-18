<?php

declare(strict_types=1);

namespace nbish11\Transphporm;

use nbish11\Transphporm\MarkdownModule;
use Transphporm\Builder;
use PHPUnit\Framework\TestCase;

final class MarkdownFunctionTest extends TestCase
{
	/**
	 * @test
	 * @covers Markdown::run
	 */
	public function converts_markdown_string_to_html(): void
	{
		$template = '<h1>Template Heading</h1>';
		$tss = 'h1 {content: markdown("# Markdown Heading"); content-mode: replace; format: html;}';
		$template = new Builder($template, $tss);

		$template->loadModule(new MarkdownModule());

		$this->assertEquals('<h1>Markdown Heading</h1>', $template->output()->body);
	}

	/**
	 * @test
	 * @covers Markdown::run
	 */
	public function converts_markdown_from_data_function_to_html(): void
	{
		$template = '<h1>Template Heading</h1>';
		$tss = 'h1 {content: markdown(data()); content-mode: replace; format: html; }';
		$template = new Builder($template, $tss);

		$template->loadModule(new MarkdownModule());

		$this->assertEquals('<h1>Markdown Heading</h1>', $template->output('# Markdown Heading')->body);
	}

	/**
	 * @test
	 * @covers Markdown::run
	 */
	public function converts_markdown_file_to_html(): void
	{
		$file = __DIR__ . '/fixtures/heading.md';

		$template = '<h1>Template Heading</h1>';
		$tss = 'h1 {content: markdown("'.$file.'"); content-mode:replace; format:html;}';
		$template = new Builder($template, $tss);

		$template->loadModule(new MarkdownModule());

		$this->assertEquals('<h1>Markdown Heading</h1>', $template->output()->body);
	}

	/**
	 * @test
	 * @covers Markdown::run
	 */
	public function converts_markdown_string_to_text(): void
	{
		$template = '<pre></pre>';
		$tss = 'pre {content: markdown("# Markdown Heading");}';
		$template = new Builder($template, $tss);

		$template->loadModule(new MarkdownModule());

		$this->assertEquals(
			'<pre>&lt;h1&gt;Markdown Heading&lt;/h1&gt;</pre>',
			$this->stripNewlines($template->output()->body)
		);
	}

	private function stripNewlines(string $source): string
	{
		return preg_replace('/\r\n|\r|\n/', '', $source);
	}
}
