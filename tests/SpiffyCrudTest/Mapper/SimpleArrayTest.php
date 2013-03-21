<?php

namespace SpiffyCrudTest\Model;

use SpiffyCrud\Mapper\SimpleArray;

class SimpleArrayTest extends \PHPUnit_Framework_TestCase
{
    protected $data = array(
        array('id' => 1, 'name' => 'foo'),
        array('id' => 2, 'name' => 'foofoo'),
        array('id' => 3, 'name' => 'bar'),
        array('id' => 4, 'name' => 'barbar'),
    );

    public function testReadSingle()
    {
        $expected = array('id' => 3, 'name' => 'bar');

        $mapper = new SimpleArray($this->data);
        $this->assertEquals($expected, $mapper->read(2));
    }

    public function testUpdate()
    {
        $expected = array(
            array('id' => 1, 'name' => 'foo'),
            array('id' => 9, 'name' => 'updated'),
            array('id' => 3, 'name' => 'bar'),
            array('id' => 4, 'name' => 'barbar'),
        );

        $mapper = new SimpleArray($this->data);
        $mapper->update(array('id' => 9, 'name' => 'updated'), 1);
        $this->assertEquals($expected, $mapper->read('all'));
    }

    public function testDelete()
    {
        $expected = array(
            0 => array('id' => 1, 'name' => 'foo'),
            2 => array('id' => 3, 'name' => 'bar'),
            3 => array('id' => 4, 'name' => 'barbar'),
        );

        $mapper = new SimpleArray($this->data);
        $mapper->delete(1);
        $this->assertEquals($expected, $mapper->read('all'));

        $this->setExpectedException('OutOfBoundsException', 'invalid index');
        $mapper->delete(10);
    }

    public function testRead()
    {
        $mapper = new SimpleArray($this->data);
        $this->assertEquals($this->data, $mapper->read('all'));
    }

    public function testCreate()
    {
        $expected = array(
            array('id' => 1, 'name' => 'foo'),
            array('id' => 2, 'name' => 'foofoo'),
            array('id' => 3, 'name' => 'bar'),
            array('id' => 4, 'name' => 'barbar'),
            array('id' => 5, 'name' => 'created')
        );

        $mapper = new SimpleArray($this->data);
        $mapper->create(array('id' => 5, 'name' => 'created'));
        $this->assertEquals($expected, $mapper->read('all'));
    }
}
