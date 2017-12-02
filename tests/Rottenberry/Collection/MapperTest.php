<?php
use \Rottenberry\Collection\Mapper;
use \PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
  const MISSED_EXCEPTION = "There must be the exception";

  public function testCheckMethod()
  {
    $this->assertEquals(
      method_exists(Mapper::class, 'createMap'),
      true,
      "The class does not have 'createMap' method."
    );
  }

  public function testReturnDictionaryByPattern()
  {
    $dataList = [
      [
        'id' => 1,
        'name' => 'foo'
      ],
      [
        'id' => 2,
        'name' => 'bar'
      ],
      [
        'id' => 3,
        'name' => 'fizzbuzz'
      ]
    ];

    $result = Mapper::createMap('id', $dataList);
    $checks = [
      '1' => 'foo',
      '2' => 'bar',
      '3' => 'fizzbuzz',
    ];
    foreach ($checks as $checkId => $checkName) {
      $this->assertEquals(
        $result[$checkId]['name'],
        $checkName,
        'The values does not match'
      );
    }
  }

  public function testErrorInNoPattern()
  {
    $dataList = [
      [
        'id' => 1,
        'name' => 'foo'
      ],
      [
        'id' => 2,
        'name' => 'bar'
      ],
      [
        'id' => 3,
        'name' => 'fizzbuzz'
      ]
    ];
    $this->expectException(Exception::class);
    $result = Mapper::createMap('', $dataList);
    $this->assertEquals(
      md5(json_encode($dataList)),
      md5(json_encode($result)),
      'The original array and the resulting array are not the same'
    );
  }

  public function testReturnEmptyArrayIfGivenEmpty()
  {
    $dataList = [];
    $result = Mapper::createMap('somePattern', $dataList);
    $this->assertEquals(
      empty($result),
      true,
      'The resulting array is not empty'
    );
  }

  public function testErrorIfArgumentsHaveWrongTypeOrMissed()
  {
    $this->expectException(TypeError::class);
    $result = Mapper::createMap(1, (object) [1,2,3]);
    $result = Mapper::createMap([1,2,3], '');
    $result = Mapper::createMap('');
    $result = Mapper::createMap([]);
    $this->assertEquals(true, false, static::MISSED_EXCEPTION); 
  }

  public function testErrorIfArrayDoesNotConsistOfArrays()
  {
    $this->expectException(Exception::class);
    $dataList = [
      'foo',
      'bar',
      'fizzbuzz',
      'hello',
      'world',
    ];
    $result = Mapper::createMap('somePattern', $dataList);
    $this->assertEquals(true, false, static::MISSED_EXCEPTION); 
  }

  public function testErrorIfSubArraysDontHaveKeySpecifiedInPattern()
  {
    $this->expectException(Exception::class);
    $dataList = [
      [1, 2, 3],
      [1, 2, 3],
      [1, 2, 3],
      [1, 2, 3],
      [
        'id' => 1,
        'name' => 'some name'
      ]
    ];
    $result = Mapper::createMap('nonExistingKey', $dataList);
    $this->assertEquals(true, false, static::MISSED_EXCEPTION);
  }

  public function testMustReturnMapWithCorrectKeys()
  {
    $dataList = [
      [
        'uniqueID' => 123,
        'name' => 'lorem ipsum',
        'anotherUniqueID' => 456,
      ],
      [
        'uniqueID' => 1230,
        'name' => 'lorem ipsum',
        'anotherUniqueID' => 4560,
      ],
      [
        'uniqueID' => 12300,
        'name' => 'lorem ipsum',
        'anotherUniqueID' => 45600,
      ],
      [
        'uniqueID' => 123000,
        'name' => 'lorem ipsum',
        'anotherUniqueID' => 456000,
      ],
    ];
    $result = Mapper::createMap('uniqueID', $dataList);
    $checks = [
      '123' => 456,
      '1230' => 4560,
      '12300' => 45600,
      '123000' => 456000,
    ];
    foreach ($checks as $uniqueID => $anotherUniqueID) {
      $this->assertEquals(
        $result[$uniqueID]['anotherUniqueID'],
        $anotherUniqueID,
        'The keys are not correct'
      );
    }
  }

  public function testMustCreateKeysWithAdditionalText()
  {
    $dataList = [
      [
        'uniqueID' => 123,
        'name' => 'lorem ipsum',
        'anotherUniqueID' => 456,
      ],
      [
        'uniqueID' => 1230,
        'name' => 'lorem ipsum',
        'anotherUniqueID' => 4560,
      ],
      [
        'uniqueID' => 12300,
        'name' => 'lorem ipsum',
        'anotherUniqueID' => 45600,
      ],
      [
        'uniqueID' => 123000,
        'name' => 'lorem ipsum',
        'anotherUniqueID' => 456000,
      ],
    ];
    $result = Mapper::createMap('foo__[uniqueID]__bar', $dataList);
    $checks = [
      'foo__123__bar' => 456,
      'foo__1230__bar' => 4560,
      'foo__12300__bar' => 45600,
      'foo__123000__bar' => 456000,
    ];
    foreach ($checks as $key => $expected) {
      $actual = $result[$key]['anotherUniqueID'];
      $this->assertEquals(
        $expected,
        $actual,
        "$expected vs $actual"
      );
    }
  }
}
