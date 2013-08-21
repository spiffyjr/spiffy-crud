# Routing

SpiffyCrud requires a special routing configuration to handle routes for CRUD operations. The 
[CrudRoute](https://github.com/spiffyjr/spiffy-crud/blob/master/src/SpiffyCrud/CrudRoute.php) is provided for you
which handles setting up all the route endpoints.

## Example

Given a sample configuration of:

```php
return array(
    'router' => array(
        'routes' => array(
            'myroute' => array(
                // required
                'type' => 'crud',
                
                'options' => array(
                    // required, the base route to use
                    'route' => '/crud',
                    
                    // required, the name of the crud controller
                    'controller' => 'My\Crud\Controller',
                    
                    // optional, defaults to 'id'
                    'identifier' => 'id',
                )
            )
        )
    )
);
```

the following endpoints would be available:

* `/crud` the list of available entities
* `/crud/:id/update` update the entity identified by `id`
* `/crud/:id/remove` remove the entity identified by `id`
* `/crud/create` create a new entity
