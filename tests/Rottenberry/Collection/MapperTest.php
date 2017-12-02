<?php
use \Rottenberry\Collection\Mapper;
use \PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
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
    $this->assertEquals(true, false, 'There must be the exception'); 
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
    $this->assertEquals(true, false, 'There must be the exception'); 
  }
}
