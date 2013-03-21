<?php

namespace SpiffyCrudTest\Model;

use SpiffyCrud\Service\CrudManagerFactory;
use SpiffyTest\Module as SpiffyTest;

class CrudManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CrudManagerFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new CrudManagerFactory();
    }

    public function testExpectedInstanceReturned()
    {
        $service = $this->factory->createService(SpiffyTest::getInstance()->getServiceManager());
        $this->assertInstanceOf('SpiffyCrud\CrudManager', $service);
    }
}
