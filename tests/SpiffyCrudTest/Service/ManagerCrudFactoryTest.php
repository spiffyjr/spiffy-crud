<?php

namespace SpiffyCrudTest\Model;

use SpiffyCrud\Service\ManagerCrudFactory;
use SpiffyTest\Module as SpiffyTest;

class ManagerCrudFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ManagerCrudFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ManagerCrudFactory();
    }

    public function testExpectedInstanceReturned()
    {
        $service = $this->factory->createService(SpiffyTest::getInstance()->getServiceManager());
        $this->assertInstanceOf('SpiffyCrud\CrudManager', $service);
    }
}
