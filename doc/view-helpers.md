# View Helpers

View helpers are responsible for displaying the list of entities assigned to a model. The 
[`viewOptions`](https://github.com/spiffyjr/spiffy-crud/blob/master/doc/models.md#viewoptions-optional) property of each
model is used to pass runtime options to each view helper.

## SpiffyDatatables

The default view helper is 
[SpiffyDatatables](https://github.com/spiffyjr/spiffy-crud/blob/master/src/SpiffyCrud/View/Helper/Datatable.php) which 
hooks into the [SpiffyDatatables](https://github.com/spiffyjr/spiffy-datatables) module.

Available view options:

* options, (optional) passed directly to the datatable. An exhaustive list of options can be found 
            [on the datatables.net website](http://datatables.net/usage/options).
* columns, (optional) passed to the 
            [SpiffyDatatables collection factory](https://github.com/spiffyjr/spiffy-datatables/blob/master/src/SpiffyDatatables/Column/Collection.php#L27) 
            to generate the column definitions. An exhaustive list of column options can be found 
            [on the datatables.net website](http://datatables.net/usage/columns).
