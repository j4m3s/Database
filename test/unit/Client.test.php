<?php
namespace Gt\Database;

use Gt\Database\Connection\DefaultSettings;
use Gt\Database\Connection\Settings;
use Gt\Database\Query\QueryCollection;
use Gt\Database\Query\QueryCollectionFactory;

class ClientTest extends \PHPUnit_Framework_TestCase {

public function testInterface() {
	$db = new Client();
	$this->assertInstanceOf("\Gt\Database\Client", $db);
}

/**
 * @dataProvider \Gt\Database\Test\Helper::queryCollectionPathExistsProvider
 */
public function testQueryCollectionPathExists(string $name, string $path) {
	$basePath = dirname($path);
	$settings = new Settings(
		$basePath, Settings::DRIVER_SQLITE, Settings::DATABASE_IN_MEMORY);
	$db = new Client($settings);

	$this->assertTrue(isset($db[$name]));
	$queryCollection = $db->queryCollection($name);

	$this->assertInstanceOf("\\Gt\\Database\\Query\\QueryCollection",
		$queryCollection
	);
}

/**
 * @dataProvider \Gt\Database\Test\Helper::queryPathNotExistsProvider
 * @expectedException \Gt\Database\Query\QueryCollectionNotFoundException
 */
public function testQueryCollectionPathNotExists(string $name, string $path) {
	$basePath = dirname($path);

	$settings = new Settings(
		$basePath, Settings::DRIVER_SQLITE, Settings::DATABASE_IN_MEMORY);
	$db = new Client($settings);
	$this->assertFalse(isset($db[$name]));
	$queryCollection = $db->queryCollection($name);
}

/**
 * @dataProvider \Gt\Database\Test\Helper::queryCollectionPathExistsProvider
 */
public function testOffsetGet(string $name, string $path) {
	$settings = new Settings(dirname($path),
		Settings::DRIVER_SQLITE, Settings::DATABASE_IN_MEMORY);
	$db = new Client($settings);

	$offsetGot = $db->offsetGet($name);
	$arrayAccessed = $db[$name];

	$this->assertEquals(
		$offsetGot->getDirectoryPath(),
		$arrayAccessed->getDirectoryPath()
	);
}

/**
 * @expectedException \Gt\Database\ReadOnlyArrayAccessException
 */
public function testOffsetSet() {
	$db = new Client();
	$db["test"] = "qwerty";
}

/**
 * @expectedException \Gt\Database\ReadOnlyArrayAccessException
 */
public function testOffsetUnset() {
	$db = new Client();
	unset($db["test"]);
}

}#