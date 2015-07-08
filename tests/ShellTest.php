<?php
/**
 * phpAsyncShell - Run asynchronous shell commands
 * (simplifies using stream-process by christiaan)
 *
 * @copyright 2015
 * @license GPL-3
 * @author lorenzo at poixson.com
 * @link http://poixson.com/
 */
namespace pxn\phpAsyncShell;

//use pxn\phpAsyncShell\Shell;
use pxn\phpAsyncShell\BufferedShell;

class ShellTest extends \PHPUnit_Framework_TestCase {



	public function testShell_pid() {
		$shell = new BufferedShell(
				'pgrep php'
		);
		$shell->run();
		$this->assertGreaterThan(0, (int) $shell->getLine(0));
		unset($shell);
	}



	public function testStoredShell() {
		$shell = BufferedShell::get(
				'echo -e "Line 1\nLine 2\nLine 3"'
		);
		$shell->run();
		$this->assertEquals(
				\print_r(['Line 1', 'Line 2', 'Line 3'], TRUE),
				\print_r($shell->getLines(), TRUE)
		);
		$shell->reset();
		$this->assertEquals('Line 1', $shell->next());
		$this->assertEquals('Line 2', $shell->next());
		$this->assertEquals('Line 3', $shell->next());
		unset($shell);
	}



	public function testShellCallback() {
		$a = \str_repeat('a', 10);
		$b = \str_repeat('b', 10);
		$c = \str_repeat('c', 10);
		$expected = [ $a, $b, $c ];
		$index = 0;
		$shell = Shell::get(\sprintf(
				'echo -n "%s";sleep 1;echo -n "%s";sleep 1;echo -n "%s"',
				$a,
				$b,
				$c
		));
		$shell->setCallback(function($line) use ($expected, &$index) {
			$expect = $expected[$index++];
			$this->assertEquals($expect, $line);
		});
		$shell->run();
	}



}
