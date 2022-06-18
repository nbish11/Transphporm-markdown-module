<?php
declare(strict_types=1);

namespace nbish11\Transphporm;

use Transphporm\TSSFunction;
use Transphporm\Parser\Tokenizer;
use League\CommonMark\Environment\EnvironmentInterface;
use League\CommonMark\Renderer\HtmlDecorator;
use League\CommonMark\Parser\MarkdownParser;
use League\CommonMark\Renderer\HtmlRenderer;
use League\CommonMark\Renderer\Block\DocumentRenderer;
use League\CommonMark\Node\Block\Document;
use InvalidArgumentException;
use stdClass;

final class MarkdownFunction implements TSSFunction
{
	private $env;

	public function __construct(EnvironmentInterface $env)
	{
		$this->env = $env;
	}

	public function run(array $args, \DomElement $element = null)
	{
		$documentAST = $this->parse($args[0]);

		// wrap markdown text in another HTML element
		if (isset($args[1]) && is_string($args[1])) {
			$element = $this->createElementFromSelector($args[1]);
			$decorator = new HtmlDecorator(new DocumentRenderer(), $element->tagName, $element->attributes);

			return (string)$decorator->render($documentAST, new HtmlRenderer($this->env));

		} else {
			$renderer = new HtmlRenderer($this->env);

			return (string)$renderer->renderDocument($documentAST);
		}
	}

	private function parse(string $markdown): Document
	{
		if (is_readable($markdown)) {
			$markdown = file_get_contents($markdown);
		}

		return (new MarkdownParser($this->env))->parse($markdown);
	}

	/**
	 * Creates an HTML element representation from a compound CSS selector.
	 *
	 * @param string $selector A compound CSS selector.
	 * @return stdClass An object representing a HTML element.
	 */
	private function createElementFromSelector(string $selector): stdClass
	{
		$tokenizer = new Tokenizer($selector);
		$tokens = $tokenizer->getTokens();

		if ($tokens->count() === 0) {
			throw new InvalidArgumentException('selector not provided');
		}

		$element = new stdClass();
		$element->tagName = '';
		$element->attributes = [];

		// no tag name provided
		if ($tokens->current()['type'] !== $tokenizer::NAME) {
			throw new InvalidArgumentException('no tag name provided');
		}

		$element->tagName = $tokens->current()['value'];
		$tokens->next();

		// process attributes
		while ($tokens->valid()) {
			switch ($tokens->current()['type']) {
				case $tokenizer::DOT:
					if (($value = $tokens->read($tokens->key() + 1)) !== false) {
						if ($tokens->type($tokens->key() + 1) === $tokenizer::NAME) {
							$element->attributes['class'] = array_key_exists('class', $element->attributes) ? array_merge($element->attributes['class'], [$value]) : [$value];
							$tokens->skip(2);
							continue 2;
						}
					}
					throw new InvalidArgumentException('cannot pass class in selector');

				case $tokenizer::NUM_SIGN:
					if (($value = $tokens->read($tokens->key() + 1)) !== false) {
						if ($tokens->type($tokens->key() + 1) === $tokenizer::NAME) {
							$element->attributes['id'] = $value;
							$tokens->skip(2);
							continue 2;
						}
					}
					throw new InvalidArgumentException('cannot pass class in selector');

				default:
					throw new InvalidArgumentException('invalid selector');
			}

			$tokens->next();
		}

		return $element;
	}
}
