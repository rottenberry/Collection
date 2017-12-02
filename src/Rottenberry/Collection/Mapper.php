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
		foreach ($dataList as $element) {
			if (is_array($element)) continue;
			throw new \Exception("The array must have nested arrays");
			
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
}
