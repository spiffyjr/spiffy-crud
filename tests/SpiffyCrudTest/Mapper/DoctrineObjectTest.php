<?php

namespace SpiffyCrudTest\Model;

use Mockery as m;
use SpiffyCrud\Mapper\DoctrineObject;
use SpiffyCrudTest\Asset\SimpleModel;

class DoctrineObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrineObject
     */
    protected $mapper;

    public function setUp()
    {
        $or = m::mock('Doctrine\ORM\EntityRepository');
        $or->shouldReceive('find')->once()->with(1)->andReturn(new SimpleModel);
        $or->shouldReceive('findAll')->once()->withAnyArgs()->andReturn(array(new SimpleModel, new SimpleModel));

        $om = m::mock('Doctrine\ORM\EntityManager');
        $om->shouldReceive('getRepository')->zeroOrMoreTimes()->andReturn($or);
        $om->shouldReceive('persist')->zeroOrMoreTimes();
        $om->shouldReceive('remove')->zeroOrMoreTimes();
        $om->shouldReceive('flush')->zeroOrMoreTimes();

        $this->mapper = new DoctrineObject($om);
    }

    public function testReadAll()
    {
        $model  = new SimpleModel();
        $result = $this->mapper->read($model);

        $this->assertCount(2, $result);

        foreach($result as $obj) {
            $this->assertInstanceOf(get_class($model), $obj);
        }
    }

    public function testRead()
    {
        $model = new SimpleModel();
        $this->assertInstanceOf(get_class($model), $this->mapper->read($model, 1));
    }

    public function testUpdate()
    {
        $model = new SimpleModel();
        $this->assertInstanceOf(get_class($model), $this->mapper->update($model, 1));
    }

    public function testDelete()
    {
        $model = new SimpleModel();
        $this->assertInstanceOf(get_class($model), $this->mapper->delete($model));
    }

    public function testFindAllThrowsExceptionOnInvalidArgument()
    {
        $this->setExpectedException('InvalidArgumentException', 'object expected');
        $this->mapper->findAll(false);
    }

    public function testCreate()
    {
        $model = new SimpleModel();
        $this->assertInstanceOf(get_class($model), $this->mapper->create($model, array()));
    }
}
