# Adapters

Adapters sit between the [controllers](https://github.com/spiffyjr/spiffy-crud/blob/master/doc/controllers.md) and your
persistence layer. The crud manager has a [default adapter](https://github.com/spiffyjr/spiffy-crud/blob/master/doc/options.md#default_adapter)
and [models](https://github.com/spiffyjr/spiffy-crud/blob/master/doc/models.md) can specify their own to use instead
of the default.

The only requirement of an adapter is that it implements the 
[AdapterInterface](https://github.com/spiffyjr/spiffy-crud/blob/master/src/SpiffyCrud/Adapter/AdapterInterface.php). 
The adapter manager will throw an exception if you attempt to register or retrieve an invalid adapter.

## Shipped Adapters

### [DoctrineObject](https://github.com/spiffyjr/spiffy-crud/blob/master/src/SpiffyCrud/Adapter/DoctrineObject.php)

DoctrineObject uses Doctrine's ObjectRepository and ObjectManager to handle persistence. This is the default provider 
after installation.
