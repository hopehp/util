<?php

use \Hope\Util\Dict;

class DictTest extends PHPUnit_Framework_TestCase
{

    /** @var \Hope\Util\Dict  */
    protected $map;

    /**
     * Default values
     * @var array
     */
    protected $values = [
        'foo' => 'foo',
        'bar' => 'bar',
        'baz' => [
            'boo' => 'boo'
        ]
    ];

    protected function setUp()
    {
        $this->map = new \Hope\Util\Dict($this->values);
    }

    public function testConstruction()
    {
        $dict = new Dict($this->values);
        $this->assertTrue($dict->exists('foo'));
        $this->assertCount(count($this->values), $dict);
        $this->assertEquals($this->values, $dict->all());
    }

    public function testGetters()
    {
        $dict = $this->map;
        $dict->set('test.with.dots', true);

        $this->assertEquals('foo', $dict['foo']);
        $this->assertEquals('boo', $dict['baz.boo']);

        $this->assertEquals('foo+bar', $dict->get(function($dict) {
            return $dict['foo'] . '+' . $dict['bar'];
        }));

        $this->assertTrue($dict->exists('test.with.dots'));
        $this->assertTrue($dict->get('test.with.dots'));


        $this->assertNull($dict->get('unknown', null));
        $this->assertNull($dict->get('deep.unknown', null));
        $this->assertInternalType('array', $dict->get('deep.unknown', []));
    }

    public function testSetters()
    {
        $dict = $this->map;

        $r = $dict->set('test', null);

        $this->assertSame($dict, $r);
        $this->assertTrue($dict->exists('test'));

        $dict['test2'] = true;
        $this->assertTrue($dict->exists('test2'));
        $this->assertTrue($dict['test2']);

    }

    public function testExists()
    {
        $dict = $this->map;
        $this->assertTrue($dict->exists('baz.boo'));
        $this->assertTrue($dict->exists('bar'));
        $this->assertFalse($dict->exists('undefined'));

        $this->assertTrue(isset($dict['baz.boo']));
        $this->assertTrue(isset($dict['bar']));
        $this->assertFalse(isset($dict['undefined']));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSettersFail()
    {
        $dict = $this->map;
        $dict->set(123123, 123123);
    }

    public function testKeys()
    {
        $dict = $this->map;
        $this->assertEquals(array_keys($this->values), $dict->keys());
    }

    public function testClear()
    {
        $dict = $this->map;

        $this->assertCount(count($this->values), $dict);
        $dict->clear();
        $this->assertCount(0, $dict);
    }

    public function testFirstAndLast()
    {
        $dict = $this->map;

        $this->assertEquals($this->values['foo'], $dict->first());
        $this->assertEquals($this->values['baz'], $dict->last());
    }

    public function testCopy()
    {
        $r = $this->map->copy();

        $this->assertInstanceOf(Dict::class, $r);
        $this->assertNotSame($this->map, $r);
        $this->assertEquals($this->map->all(), $r->all());
    }

    public function testFind()
    {
        $dict = $this->map;
        $this->assertEquals('foo', $dict->find('foo'));
        $this->assertEquals(null, $dict->find('foo2'));
    }

}