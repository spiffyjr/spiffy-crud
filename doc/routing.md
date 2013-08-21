# Routing

SpiffyCrud requires a special routing configuration to handle routes for CRUD operations. The 
[CrudRoute](https://github.com/spiffyjr/spiffy-crud/blob/master/src/SpiffyCrud/CrudRoute.php) is provided for you
which handles setting up all the route endpoints.

## Example

```php
return array(
    'router' => array(
        'routes' => array(
            'myroute' => array(
                // required
                'type' => 'crud',
                
                'options' => array(
                    // required, the base route to use
                    'route' => '/guides',
                    
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
