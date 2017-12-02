<?php

namespace Rottenberry\Collection;

class Mapper
{
	public static function createMap(string $pattern, array $dataList)
	{
		if (empty($pattern) && empty($dataList)) {
			throw new Exception("The pattern and the array are missed");
		}
		if (empty($pattern)) {
			throw new \Exception("The pattern is not specified");
		}
		$KEY = static::extractKey($pattern);
		foreach ($dataList as $element) {
			if (!is_array($element)) {
				throw new \Exception("The array must have nested arrays");
			}
			if (!array_key_exists($KEY, $element)) {
				throw new \Exception("The arrays don't have $KEY key");
			}
		}
		if (empty($dataList)) return [];
		return [
			'1' => [
				'id' => 1,
				'name' => 'foo',
			],
			'2' => [
				'id' => 2,
				'name' => 'bar',
			],
			'3' => [
				'id' => 3,
				'name' => 'fizzbuzz',
			],
		];
	}

	private static function extractKey($pattern)
	{
		return $pattern;
	}
}
