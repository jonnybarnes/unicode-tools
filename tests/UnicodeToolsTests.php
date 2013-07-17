<?php

require __DIR__.'/../src/Jonnybarnes/UnicodeTools/UnicodeTools.php';

class UnicodeToolsTest extends PHPUnit_Framework_TestCase {
	protected $u;

	protected function setUp()
	{
		$this->u = new \Jonnybarnes\UnicodeTools\UnicodeTools();
	}

	protected function tearDown()
	{
		$this->u = null;
	}

	public function testBMP()
	{
		$actual = $this->u->convertUnicodeCodepoints('Latin Small Letter M: \\u006D\\, Pound sign: \\u00A3\\');
		$expected = 'Latin Small Letter M: m, Pound sign: £';

		$this->assertEquals($actual, $expected);

	}

	public function testAstral()
	{
		$actual = $this->u->convertUnicodeCodepoints('White Star: \\u2606\\, MUSICAL SYMBOL G CLEF: \\u1D11E\\');
		$expected = 'White Star: ☆, MUSICAL SYMBOL G CLEF: 𝄞';

		$this->assertEquals($actual, $expected);

	}

	/**
	 * @expectedException 			Exception
	 * @expectedExceptionMessage	Codepoint maps to invalid Unicode character
	 */
	public function testInvalid()
	{
		$this->u->convertUnicodeCodepoints('\\u110000\\');
	}
}