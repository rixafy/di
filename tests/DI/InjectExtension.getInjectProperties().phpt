<?php

/**
 * Test: Nette\DI\Extensions\InjectExtension::getInjectProperties()
 */

declare(strict_types=1);

namespace A
{
	class AClass
	{
		/** @var AInjected @inject */
		public $varA;

		/** @var B\BInjected @inject */
		public $varB;

		/** @var AInjected @inject */
		public $varC;

		/** @var AInjected */
		public $varD;
	}

	class AInjected
	{
	}
}

namespace A\B
{
	use A;

	class BClass extends A\AClass
	{
		/** @var BInjected @inject */
		public $varF;
	}

	class BInjected
	{
	}
}

namespace C
{
	use A\AInjected;
	use A\B;
	use C\CInjected as CAlias;

	class CClass
	{
		/** @var AInjected @inject */
		public $var1;

		/** @var B\BInjected @inject */
		public $var2;

		/** @var CAlias @inject */
		public $var3;

		/** @var CInjected @inject */
		public $var4;
	}

	class CInjected
	{
	}
}

namespace {
	use Nette\DI\Extensions\InjectExtension;
	use Tester\Assert;

	require __DIR__ . '/../bootstrap.php';


	Assert::same([
		'varA' => [
			'type' => A\AInjected::class,
			'tag' => null,
		],
		'varB' => [
			'type' => A\B\BInjected::class,
			'tag' => null,
		],
		'varC' => [
			'type' => A\AInjected::class,
			'tag' => null,
		],
	], InjectExtension::getInjectProperties(A\AClass::class));

	Assert::same([
		'varA' => [
			'type' => A\AInjected::class,
			'tag' => null,
		],
		'varB' => [
			'type' => A\B\BInjected::class,
			'tag' => null,
		],
		'varC' => [
			'type' => A\AInjected::class,
			'tag' => null,
		],
		'varF' => [
			'type' => A\B\BInjected::class,
			'tag' => null,
		],
	], InjectExtension::getInjectProperties(A\B\BClass::class));

	Assert::same([
		'var1' => [
			'type' => A\AInjected::class,
			'tag' => null,
		],
		'var2' => [
			'type' => A\B\BInjected::class,
			'tag' => null,
		],
		'var3' => [
			'type' => C\CInjected::class,
			'tag' => null,
		],
		'var4' => [
			'type' => C\CInjected::class,
			'tag' => null,
		],
	], InjectExtension::getInjectProperties(C\CClass::class));
}
