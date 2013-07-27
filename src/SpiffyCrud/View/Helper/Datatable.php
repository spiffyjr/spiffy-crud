<?php

namespace SpiffyCrud\View\Helper;

use ReflectionClass;
use SpiffyCrud\CrudManager;
use SpiffyCrud\Model;
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

    /**
     * @var \SpiffyCrud\CrudManager
     */
    protected $manager;

    /**
     * @param CrudManager $manager
     */
    public function __construct(CrudManager $manager)
    {
        $this->datatable = new SpiffyDatatable();
        $this->manager   = $manager;
    }

    /**
     * @param $name
     * @param array $data
     * @return mixed
     */
    public function __invoke($name, array $data)
    {
        /** @var \SpiffyCrud\Model\ModelInterface $model */
        $model   = $this->manager->get($name);
        $options = $model->getViewOptions();

        if (isset($options['options'])) {
            $this->datatable->setOptions(new DatatableOptions($options['options']));
        }

        $columns = $this->detectColumns($model, $name);
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

    /**
     * @param Model\ModelInterface $model
     * @param string $name
     * @return array
     */
    protected function detectColumns(Model\ModelInterface $model, $name)
    {
        $rendererOptions = $model->getViewOptions();
        if (isset($rendererOptions['columns'])) {
            return $rendererOptions['columns'];
        }

        $columns    = array();
        $entity     = $this->manager->getPrototype($name);
        $reflection = new ReflectionClass($entity);
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