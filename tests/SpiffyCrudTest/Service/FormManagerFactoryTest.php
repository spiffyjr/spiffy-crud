<?php

namespace SpiffyCrudTest\Model;

use SpiffyCrud\Service\FormManagerFactory;
use SpiffyTest\Module as SpiffyTest;

class FormManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormManagerFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new FormManagerFactory();
    }

    public function testExpectedInstanceReturned()
    {
        $service = $this->factory->createService(SpiffyTest::getInstance()->getServiceManager());
        $this->assertInstanceOf('SpiffyCrud\FormManager', $service);
    }

    public function testExceptionThrownOnMissingConfiguration()
    {
        $sm     = clone SpiffyTest::getInstance()->getServiceManager();
        $config = $sm->get('Configuration');
        unset($config['spiffycrud']['forms']);

        $sm->setAllowOverride(true);
        $sm->setService('Configuration', $config);

        $this->setExpectedException('RuntimeException', 'No form configuration given');
        $this->factory->createService($sm);
    }

    public function testManagerSetFromConfiguration()
    {
        /** @var $service \SpiffyCrud\FormManager */
        $service = $this->factory->createService(SpiffyTest::getInstance()->getServiceManager());

        $this->assertInstanceOf('SpiffyCrudTest\Asset\SimpleForm', $service->get('simpleInvokable'));
        $this->assertInstanceOf('SpiffyCrudTest\Asset\SimpleForm', $service->get('simpleFactory'));
    }
}
