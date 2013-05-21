<?php

namespace SpiffyCrud\View\Helper;

use SpiffyCrud\Model\AbstractModel;
use SpiffyDatatables\Column\Collection;
use SpiffyDatatables\DataResult;
use SpiffyDatatables\Datatable as SpiffyDatatable;
use SpiffyDatatables\Options as DatatableOptions;
use Zend\View\Helper\AbstractHelper;

class Datatable extends AbstractHelper implements HelperInterface
{
    /**
     * @var SpiffyDatatable
     */
    protected $datatable;

    public function __construct()
    {
        $this->datatable = new SpiffyDatatable();
    }

    public function __invoke(AbstractModel $model, $name, array $data)
    {
        $options = $model->getViewOptions();

        if (isset($options['options'])) {
            $this->datatable->setOptions(new DatatableOptions($options['options']));
        }

        $columns = $this->detectColumns($model, $data);
        $columns[] = array(
            'sTitle'  => 'Admin',
            'mRender' => 'function(i, j, row) {
                return "<a href=\"/crud/'. $name . '/" + row.id + "/update\">edit</a> " +
                       "<a href=\"/crud/'. $name . '/" + row.id + "/delete\">delete</a>";
            }'
        );

        $this->datatable->setColumns(Collection::factory($columns));
        $this->datatable->setDataResult(new DataResult($data, count($data)));

        return $this->getView()->datatable('crudlist', $this->datatable);
    }

    protected function detectColumns(AbstractModel $model, array $data)
    {
        $rendererOptions = $model->getViewOptions();
        if (isset($rendererOptions['columns'])) {
            return $rendererOptions['columns'];
        }

        $columns = array();
        if (empty($data)) {
            $entity = $data[0];
        } else {
            $entity = $model->getEntity() ? $model->getEntity() : $model->getEntityClass();
        }

        $reflection = new \ReflectionClass($entity);
        $properties = $reflection->getProperties();
        foreach($properties as $property) {
            $columns[] = array(
                'sName'  => $property->getName(),
                'sTitle' => ucfirst($property->getName()),
                'mData'  => $property->getName()
            );
        }

        return $columns;
    }
}