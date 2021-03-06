<?php
namespace Gt\Database\Test;

require(__DIR__ . "/../../vendor/autoload.php");

class Helper {

const COUNT_PATH_PROVIDER = 10;

public static function getTmpDir() {
	return implode("/", [
		sys_get_temp_dir(),
		"phpgt",
		"database",
		uniqid()
	]);
}

public static function deleteDir(string $dir) {
	exec("rm -rf $dir");
}

public static function queryPathExistsProvider() {
	return self::queryPathProvider(true);
}

public static function queryPathNotExistsProvider() {
	return self::queryPathProvider(false);
}

public static function queryPathExtensionNotValidProvider() {
	return self::queryPathProvider(true, null);
}

private static function queryPathProvider(bool $exists, $extension = "sql") {
	$data = [];

	foreach(self::queryCollectionPathProvider(true) as $qcName => $qcData) {
		$queryCollectionPath = $qcData[1];

		if(is_null($extension)) {
			$extension = uniqid();
		}

		$queryName = uniqid("query");
		$filename = "$queryName.$extension";
		$filePath = implode(DIRECTORY_SEPARATOR, [
			$queryCollectionPath,
			$filename,
		]);

		if($exists) {
			touch($filePath);
		}

		$data []= [
			$queryName,
			$queryCollectionPath,
			$filePath,
		];
	}

	return $data;
}

public static function queryCollectionPathExistsProvider() {
	return self::queryCollectionPathProvider(true);
}

public static function queryCollectionPathNotExistsProvider() {
	return self::queryCollectionPathProvider(false);
}

private static function queryCollectionPathProvider(bool $exists) {
	$data = [];

	for($i = 0; $i < self::COUNT_PATH_PROVIDER; ++$i) {
		$name = uniqid();
		$path = self::getTmpDir() . "/query/" . $name;

		if($exists) {
			mkdir($path, 0775, true);
		}

		$data []= [
			$name,
			$path
		];
	}

	return $data;
}

}#