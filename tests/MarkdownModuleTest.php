<?php

declare(strict_types=1);

namespace nbish11\Transphporm;

use nbish11\Transphporm\MarkdownModule;
use Transphporm\Module;
use PHPUnit\Framework\TestCase;

final class MarkdownModuleTest extends TestCase
{
	/**
	 * @test
	 * @covers nbish11\Transphporm\MarkdownModule::
	 */
	public function it_is_a_transphporm_module(): void
	{
		$module = new MarkdownModule();

		$this->assertInstanceOf(Module::class, $module);
	}
}