<?php


class DictTest extends PHPUnit_Framework_TestCase
{

    /** @var \Hope\Util\Dict  */
    protected $map;

    protected $values = [
        'foo' => 'foo',
        'bar' => 'bar',
        'baz' => 'baz',
        'boo' => 'boo'
    ];

    protected function setUp()
    {
        $this->map = new \Hope\Util\Dict($this->values);
    }

    /**
     * @covers \Hope\Util\Map::__construct
     */
    public function testConstruct()
    {
        $this->assertEquals($this->values, $this->map->all());
    }

    /**
     * @covers \Hope\Util\Map::count
     */
    public function testCount()
    {
        $this->assertCount(count($this->values), $this->map);
        $this->assertEquals(count($this->values), $this->map->count());
    }

    /**
     * @covers \Hope\Util\Map::all
     */
    public function testAll()
    {
        $this->assertEquals($this->values, $this->map->all());
    }

    /**
     * @covers \Hope\Util\Map::keys
     */
    public function testKeys()
    {
        $this->assertEquals(array_keys($this->values), $this->map->keys());
    }

    /**
     * @covers \Hope\Util\Map::get
     * @covers \Hope\Util\Map::offsetGet
     */
    public function testGet()
    {
        $this->assertEquals($this->values['foo'], $this->map->get('foo'));
        $this->assertEquals($this->values['bar'], $this->map->get('bar'));

        // Test ArrayAccess
        $this->assertEquals($this->values['bar'], $this->map['bar']);

        $this->assertFalse($this->map->get('NotExistingKey'));
    }

    /**
     * Test map setter
     *
     * @covers \Hope\Util\Map::set
     * @covers \Hope\Util\Map::count
     * @covers \Hope\Util\Map::exists
     * @covers \Hope\Util\Map::offsetSet
     * @covers \Hope\Util\Map::offsetExists
     */
    public function testSet()
    {
        $map = $this->map->copy();
        $name = 'Alex';
        $users = ['Mike', 'Bob'];

        $map['users'] = $users;
        $map->set('name', $name);

        $this->assertTrue($map->exists('name'));
        $this->assertTrue($map->exists('users'));

        $this->assertEquals($map->get('name'), $name);
        $this->assertEquals($map->get('users'), $users);

        $this->assertCount(6, $map);
        $this->assertCount(4, $this->map);
    }

    /**
     * @covers \Hope\Util\Map::each
     */
    public function testEach()
    {
        $this->assertTrue($this->map->each() instanceof Generator);
    }

    public function testMerge()
    {
        $arr1 = [
            'one' => 1,
            'ten' => 2,
            'three' => [
                'four' => 5
            ]
        ];
        $arr2 = [
            'ten' => 1,
            'three' => [
                'four' => 4
            ]
        ];
        $map1 = new \Hope\Util\Dict($arr1);
        $map2 = new \Hope\Util\Dict($arr2);

        $map1->merge($map2);

        $this->assertEquals(array_merge_recursive($arr1, $arr2), $map1->all());
        $this->assertEquals($arr2, $map2->all());
    }

    public function testConcatWithString()
    {
        $expected = 'foo+bar+baz+boo';

        $this->assertEquals($expected, $this->map->concat('+'));
    }

    /**
     * @covers \Hope\Util\Map::clear
     */
    public function testClear()
    {
        $map = $this->map->copy()->clear();

        $this->assertCount(0, $map);
        $this->assertCount(count($this->values), $this->map);
    }

    /**
     * @covers \Hope\Util\Map::find
     */
    public function testFind()
    {
        $this->assertEquals('foo', $this->map->find('foo'));
    }

    /**
     * @covers \Hope\Util\Map::sort
     */
    public function testSort()
    {
        $values = $this->values;
        $sorter = function ($one, $two) {
            return $one > $two ? -1 : 1;
        };

        uksort($values, $sorter);

        $this->assertEquals($values, $this->map->sort($sorter)->all());
    }

    /**
     * @covers \Hope\Util\Map::copy
     */
    public function testCopy()
    {
        $copy = $this->map->copy();

        $this->assertTrue($copy instanceof \Hope\Util\Dict);
        $this->assertTrue($copy !== $this->map);

        $this->assertEquals($this->map->all(), $copy->all());
    }

    /**
     * @covers \Hope\Util\Map::exists
     * @covers \Hope\Util\Map::offsetExists
     */
    public function testExist()
    {
        $this->assertTrue($this->map->exists('foo'));
        $this->assertTrue(isset($this->map['foo']));

        $this->assertFalse($this->map->exists('FOO'));
    }

    /**
     * @covers \Hope\Util\Map::set
     */
    public function testReplace()
    {
        $map = $this->map->copy();
        $map->set('foo', 'hello');

        $this->assertEquals('hello', $map->get('foo'));
    }

    /**
     * @covers \Hope\Util\Map::first
     */
    public function testFirst()
    {
        $map = $this->map->copy();
        $this->assertEquals(reset($this->values), $map->first());
    }

    /**
     * @covers \Hope\Util\Map::last
     */
    public function testLast()
    {
        $map = $this->map->copy();
        $this->assertEquals(end($this->values), $map->last());
    }

    /**
     * @covers \Hope\Util\Map::filter
     */
    public function testFilter()
    {
        $map = $this->map->filter(function ($item) {
            return $item === 'foo';
        });

        $this->assertCount(1, $map);
        $this->assertEquals(['foo' => 'foo'], $map->all());

        $this->assertEquals($this->values, $this->map->all());
    }

    /**
     * @covers \Hope\Util\Map::remove
     * @covers \Hope\Util\Map::offsetUnset
     */
    public function testRemove()
    {
        $map = $this->map->copy();
        $map->remove('foo');

        $this->assertFalse($map->exists('foo'));

        unset($map['bar']);
        $this->assertFalse($map->exists('bar'));
    }

    /**
     * Remove non existing key
     *
     * @covers \Hope\Util\Map::remove
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The map has no key named "NonExistingKey"
     */
    public function testRemoveNonExistingKey()
    {
        $this->map->remove('NonExistingKey');
    }

}