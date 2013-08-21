# Controllers

SpiffyCrud uses a special controller to setup crud operations. The controller along with the crud manager do the majority
of the work in the crud process.

Your controllers should be registered with the controller manager like normal or registered using the [`controllers`](https://github.com/spiffyjr/spiffy-crud/blob/master/doc/options.md#controllers)
module option.

## Registering controllers with the abstract factory

```php
return array(
    'spiffy_crud' => array(
        // required
        'model_name' => 'My\Application\Crud\Model',
        
        // required, the base route name (appended with /create, /delete, or /update for each operation)
        'route' => '/admin/crud',
        
        // optional, defaults to 'id
        'identifier' => 'id',
        
        // optional, defaults to 'spiffy-crud/controller/create'
        'create_template' => 'my/custom/template/create',
        
        // optional, defaults to 'spiffy-crud/controller/read'
        'read_template' => 'my/custom/template/read',
        
        // optional, defaults to 'spiffy-crud/controller/update'
        'update_template' => 'my/custom/template/update',
    )
);
```
