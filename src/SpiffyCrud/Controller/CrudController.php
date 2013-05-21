<?php

namespace SpiffyCrud\Controller;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use SpiffyCrud\CrudManager;
use SpiffyCrud\Renderer\Datatable;
use Zend\Http\Response;
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
            $this->crudManager = $this->getServiceLocator()->get('SpiffyCrudManager');
        }
        return $this->crudManager;
    }

    public function indexAction()
    {
        return array(
            'manager' => $this->getCrudManager(),
            'models'  => $this->getCrudManager()->getModels()
        );
    }

    public function detailsAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->getModelFromCanonicalName($this->params('name'));

        return array(
            'model'         => $model,
            'canonicalName' => $manager->getModelCanonicalName($model),
            'name'          => $manager->getModelName($model),
            'data'          => $manager->readAll($model)
        );
    }

    public function createAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->getModelFromCanonicalName($this->params('name'));
        $form    = $manager->getFormFromModel($model);
        $prg     = $this->prg();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $form->setData($prg);

            if ($form->isValid()) {
                $this->getCrudManager()->create($model);

                return $this->redirect()->toRoute(
                    'spiffy-crud/details',
                    array('name' => $this->params('name'))
                );
            }
        }

        return array(
            'model'  => $model,
            'form'   => $form,
            'name'   => $this->params('name')
        );
    }

    public function deleteAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->getModelFromCanonicalName($this->params('name'));
        $entity  = $manager->read($model, $this->params('id'));

        $model->setEntity($entity);
        $manager->delete($model);

        return $this->redirect()->toRoute(
            'spiffy-crud/details',
            array('name' => $this->params('name'))
        );
    }

    public function updateAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->getModelFromCanonicalName($this->params('name'));
        $entity  = $manager->read($model, $this->params('id'));
        $form    = $manager->getFormFromModel($model, $entity);
        $prg     = $this->prg();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $form->setData($prg);

            if ($form->isValid()) {
                $this->getCrudManager()->update($model);

                return $this->redirect()->toRoute(
                    'spiffy-crud/details',
                    array('name' => $this->params('name'))
                );
            }
        }

        return array(
            'model'  => $model,
            'entity' => $entity,
            'form'   => $form,
            'name'   => $this->params('name')
        );
    }
}
