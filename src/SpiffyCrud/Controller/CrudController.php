<?php

namespace SpiffyCrud\Controller;

use SpiffyCrud\CrudManager;
use Zend\Mvc\Controller\AbstractActionController;

class CrudController extends AbstractActionController
{
    /**
     * @var CrudManager
     */
    protected $crudManager;

    /**
     * @param \SpiffyCrud\CrudManager $crudManager
     * @return CrudController
     */
    public function setCrudManager($crudManager)
    {
        $this->crudManager = $crudManager;
        return $this;
    }

    /**
     * @return \SpiffyCrud\CrudManager
     */
    public function getCrudManager()
    {
        if (!$this->crudManager instanceof CrudManager) {
            $this->crudManager = $this->getServiceLocator()->get('SpiffyCrudManagerCrud');
        }
        return $this->crudManager;
    }

    public function indexAction()
    {
        $manager = $this->getCrudManager();
        $models  = $manager->getModelManager()->getCanonicalNames();
        ksort($models);

        return array('models' => $models);
    }

    public function detailsAction()
    {
        $manager = $this->getCrudManager();
        $names   = array_flip($manager->getModelManager()->getCanonicalNames());
        $model   = $manager->getModelManager()->get($this->params('name'));

        return array(
            'model' => $model,
            'name'  => $names[$this->params('name')]
        );
    }

    public function updateAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->getModelManager()->get($this->params('name'));
        $names   = array_flip($manager->getModelManager()->getCanonicalNames());
        $entity  = $manager->read($model, $this->params('id'));

        return array(
            'model' => $model,
            'name'  => $names[$this->params('name')]
        );
    }
}
