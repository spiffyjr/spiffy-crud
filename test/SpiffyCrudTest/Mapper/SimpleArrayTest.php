<?php

namespace SpiffyCrudTest\Model;

use SpiffyCrud\Mapper\SimpleArray;
use SpiffyCrudTest\Asset\SimpleEntity;
use Zend\Stdlib\Hydrator\ClassMethods;

class SimpleArrayTest extends \PHPUnit_Framework_TestCase
{
    protected $data = array(
        array('foo' =>  1, 'bar' => 'foo'),
        array('foo' =>  2, 'bar' => 'foofoo'),
        array('foo' =>  3, 'bar' => 'bar'),
        array('foo' =>  4, 'bar' => 'barbar'),
    );

    public function testRead()
    {
        $expected = new SimpleEntity();
        $expected->setFoo(3)
                 ->setBar('bar');

        $mapper   = new SimpleArray($this->data);
        $hydrator = new ClassMethods();

        $this->assertEquals($expected, $mapper->read(new SimpleEntity(), 2, $hydrator));
    }

    public function testReadAll()
    {
        $mapper   = new SimpleArray($this->data);
        $hydrator = new ClassMethods();

        $this->assertEquals($this->data, $mapper->getData());
    }

    public function testUpdate()
    {
        $expected = array(
            array('foo' =>  1, 'bar' => 'foo'),
            array('foo' =>  9, 'bar' => 'updated'),
            array('foo' =>  3, 'bar' => 'bar'),
            array('foo' =>  4, 'bar' => 'barbar'),
        );

        $entity = new SimpleEntity();
        $entity->setFoo(9)
               ->setBar('updated');

        $mapper   = new SimpleArray($this->data);
        $mapper->update($entity, 1, new ClassMethods());
        $this->assertEquals($expected, $mapper->getData());
    }

    public function testDelete()
    {
        $expected = array(
            0 => array('foo' =>  1, 'bar' => 'foo'),
            2 => array('foo' =>  3, 'bar' => 'bar'),
            3 => array('foo' =>  4, 'bar' => 'barbar'),
        );

        $mapper = new SimpleArray($this->data);
        $mapper->delete(1);
        $this->assertEquals($expected, $mapper->getData());

        $this->setExpectedException('OutOfBoundsException', 'invalid index');
        $mapper->delete(10);
    }

    public function testCreate()
    {
        $expected = array(
            array('foo' =>  1, 'bar' => 'foo'),
            array('foo' =>  2, 'bar' => 'foofoo'),
            array('foo' =>  3, 'bar' => 'bar'),
            array('foo' =>  4, 'bar' => 'barbar'),
            array('foo' =>  5, 'bar' => 'created')
        );

        $entity = new SimpleEntity();
        $entity->setFoo(5)
               ->setBar('created');

        $mapper = new SimpleArray($this->data);
        $mapper->create($entity, new ClassMethods());
        $this->assertEquals($expected, $mapper->getData());
    }
}
