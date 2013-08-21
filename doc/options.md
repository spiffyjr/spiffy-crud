# Module Options

Options are available for configuration via the `spiffy_datatables` key. A sample configuration is provided for you 
in `config/spiffydatatables.global.php.dist`. If you want to use this file move it to `config/autoload` in your base 
application and rename it to `spiffydatatables.global.php`.

`default_hydrator` - A string with a service name for locating the default hydrator from the HydratorManager for 
                     persistence. This will only be used if the model does not have an adapter set.
                     
`default_adapter` - A string with a service name for locating the default dapter from the AdapterManager for persistence. 
                    This will only be used if the model does not have an adapter set.
                     
`form_builder` - A string with a service name for locating the annotation form builder.

`manager` - Passed directly to the crud manager to register model services.

`adapters` - Passed directly to the adapter manager to register additional adapters.

`controllers` - Used by [SpiffyCrud\Controller\AbstractFactory](https://github.com/spiffyjr/spiffy-crud/blob/master/src/SpiffyCrud/Controller/AbstractFactory.php) to register controllers with the crud manager.

`models` - Used by [SpiffyCrud\Model\AbstractFactory](https://github.com/spiffyjr/spiffy-crud/blob/master/src/SpiffyCrud/Model/AbstractFactory.php) to register models with the crud manager.
