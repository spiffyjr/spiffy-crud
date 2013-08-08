<?php

namespace SpiffyCrud\Adapter;

use Zend\ServiceManager\AbstractPluginManager;

class AdapterManager extends AbstractPluginManager
{
    /**
     * @var array
     */
    protected $factories = array(
        'doctrineobject' => 'SpiffyCrud\Adapter\DoctrineObjectFactory',
    );

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof AdapterInterface) {
            throw new Exception\InvalidAdapterException(sprintf(
                'Adapter of type %s is invalid; must implement %s\AdapterInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
            ));
        }
    }
}