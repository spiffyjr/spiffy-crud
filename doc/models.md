# Models

Models are the backbone of the CRUD operation and define everything the crud manager needs to know in order to handle
the entire process.

The only requirement of a model is that it implements [SpiffyCrud\Model\ModelInterface](https://github.com/spiffyjr/spiffy-crud/blob/master/src/SpiffyCrud/Model/ModelInterface.php)
but typically you should extend the provided [SpiffyCrud\Model\AbstractModel](https://github.com/spiffyjr/spiffy-crud/blob/master/src/SpiffyCrud/Model/AbstractModel.php).

The Crud Manager will inform you if you attempt to register or retrieve any services that are invalid.

## SpiffyCrud\Model\ModelInterface

```php
<?php

namespace SpiffyCrud\Model;

interface ModelInterface
{
    /**
     * @return string
     */
    public function getDisplayName();

    /**
     * @return string
     */
    public function getEntityClass();

    /**
     * @return string
     */
    public function getFormSpec();

    /**
     * @return string
     */
    public function getAdapterName();

    /**
     * @return string
     */
    public function getHydratorName();

    /**
     * @return array
     */
    public function getAdapterOptions();

    /**
     * @return array
     */
    public function getViewOptions();
}
```

## Model Properties

### $displayName (optional)
Custom name to use for display purposes.

### $adapterName (optional)
The name of the service from the adapter manager to use for persistence. Falls back to the 
[default_adapter](https://github.com/spiffyjr/spiffy-crud/blob/master/doc/options.md#default_adapter) if not set.

### $hydratorName (optional)
The name of the service from the hydrator manager to use for hydration. Falls back to the 
[default_hydrator](https://github.com/spiffyjr/spiffy-crud/blob/master/doc/options.md#default_hydrator) if not set.

### $entityClass (required)
The class name of the entity for CRUD operations.

### $formSpec (optional)
The spec to use for creating the custom form for this entity. If no form is present then forms will be built using 
the [annotation form builder](https://github.com/spiffyjr/spiffy-crud/blob/master/doc/options.md#form_builder) 
from the model [$entityClass](https://github.com/spiffyjr/spiffy-crud/blob/master/doc/models.md#entityclass-required)). 
Arrays are passed to the form factory and strings are retrieved from the 
[form element manager](https://github.com/zendframework/zf2/blob/master/library/Zend/Form/FormElementManager.php), 
`forms` [abstract service factory](https://github.com/zendframework/zf2/blob/master/library/Zend/Form/FormAbstractServiceFactory.php) (if registered), 
and finaly instantiated directly if the class exists.

### $viewOptions (optional)
These are passed directly to the view helper and vary based on which view helper you are using. By default, SpiffyCrud
uses [spiffy-datatables](http://github.com/spiffyjr/spiffy-datatables) for renderering the view.

### $adapterOptions (optional)
These are passed directly to the adapter for each crud operation and typically contain things like the table name or
additional metadata. By default, SpiffyCrud uses [DoctrineORMModule](https://github.com/doctrine/doctrineormmodule) 
for persistence so no additional metadata is required.


