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
     * {@inheritDoc}
     */
    public function __invoke($name, array $data, array $options = array())
    {
        /** @var \SpiffyCrud\Model\ModelInterface $model */
        $model       = $this->manager->get($name);
        $viewOptions = $model->getViewOptions();

        if (isset($viewOptions['options'])) {
            $this->datatable->setOptions($viewOptions['options']);
        }

        $this->datatable->setColumns(Collection::factory($this->detectColumns($name)));
        $this->datatable->setDataResult(new DataResult($data, count($data)));

        if (isset($options['return_datatable'])) {
            return $this->datatable;
        } else {
            return $this->getView()->datatable($this->datatable);
        }
    }

    /**
     * @param string $name
     * @return array
     */
    protected function detectColumns($name)
    {
        $model       = $this->manager->get($name);
        $viewOptions = $model->getViewOptions();
        if (isset($viewOptions['columns'])) {
            return $viewOptions['columns'];
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