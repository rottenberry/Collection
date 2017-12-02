<?php

namespace Rottenberry\Collection;

class Mapper
{
    const SIMPLE_PATTERN = 'simple';
    const EXTENED_PATTERN  = 'extended';

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

        $map = [];
        foreach ($dataList as $subArray) {
            $newKey = static::makeNewKey($KEY, $subArray[$KEY], $pattern);
            $map[$newKey] = $subArray;
        }
        return $map;
    }

    public static function extractKey($pattern)
    {
        $key = [
            static::SIMPLE_PATTERN => function () use ($pattern) {
                return $pattern;
            },
            static::EXTENED_PATTERN => function () use ($pattern) {
                preg_match("/\[(.*?)\]/", $pattern, $match);
                return $match[1];
            }
        ];
        return $key[static::getPatternType($pattern)]();
    }

    public static function makeNewKey($key, $value, $pattern)
    {
        $newKey = [
            static::SIMPLE_PATTERN => function () use ($key, $value, $pattern) {
                return $value;
            },
            static::EXTENED_PATTERN => function () use (
                $key,
                $value,
                $pattern
            ) {
                return str_replace("[$key]", (string) $value, $pattern);
            }
        ];
        return $newKey[static::getPatternType($pattern)]();
    }

    public static function getPatternType($pattern)
    {
        if (empty($pattern) || ! is_string($pattern)) {
            throw new Exception('The pattern is incorrect');
        }
        $hasLeftBracket = preg_match("/\[/", $pattern);
        $hasRightBracket = preg_match("/\]/", $pattern);
        if (!$hasLeftBracket && !$hasRightBracket) {
            return static::SIMPLE_PATTERN;
        }
        if (!$hasLeftBracket || !$hasRightBracket) {
            throw new \Exception("The pattern is incorrect: a bracket missed");
        }
        return static::EXTENED_PATTERN;
    }
}
