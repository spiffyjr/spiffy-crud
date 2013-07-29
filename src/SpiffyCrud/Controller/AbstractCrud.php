<?php

namespace SpiffyCrud\Controller;

use SpiffyCrud\CrudManager;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

abstract class AbstractCrud extends AbstractActionController
{
    /**
     * @var CrudManager
     */
    protected $crudManager;

    /**
     * @return string
     */
    abstract public function getModelName();

    /**
     * @return string
     */
    abstract public function getReadRoute();

    /**
     * @return string
     */
    abstract public function getCreateRoute();

    /**
     * @return string
     */
    abstract public function getDeleteRoute();

    /**
     * @return string
     */
    abstract public function getUpdateRoute();

    /**
     * @return array
     */
    public function readAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->get($this->getModelName());

        $viewModel = new ViewModel(array(
            'model'       => $model,
            'name'        => $this->getModelName(),
            'data'        => $manager->findAll($this->getModelName()),
            'createRoute' => $this->getCreateRoute(),
            'updateRoute' => $this->getUpdateRoute(),
            'deleteRoute' => $this->getDeleteRoute(),
        ));
        $viewModel->setTemplate('spiffy-crud/controller/read');
        return $viewModel;
    }

    /**
     * @return array|Response
     */
    public function createAction()
    {
        $name    = $this->getModelName();
        $manager = $this->getCrudManager();
        $model   = $manager->get($name);
        $form    = $manager->getForm($name);
        $prg     = $this->prg();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $form->setData($prg);

            if ($form->isValid()) {
                $this->getCrudManager()->persist($name, $form->getData());
                return $this->redirect()->toRoute($this->getReadRoute());
            }
        }

        $viewModel = new ViewModel(array(
            'model'       => $model,
            'form'        => $form,
            'name'        => $name,
            'createRoute' => $this->getCreateRoute(),
        ));
        $viewModel->setTemplate('spiffy-crud/controller/create');
        return $viewModel;
    }

    /**
     * @return array|Response
     */
    public function updateAction()
    {
        $name    = $this->getModelName();
        $id      = $this->params('id');
        $manager = $this->getCrudManager();
        $entity  = $manager->find($name, $id);
        $form    = $manager->getForm($name, $entity);
        $prg     = $this->prg();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $form->setData($prg);

            if ($form->isValid()) {
                $this->getCrudManager()->persist($name, $entity);
                return $this->redirect()->toRoute($this->getReadRoute());
            }
        }

        $viewModel = new ViewModel(array(
            'entity'      => $entity,
            'form'        => $form,
            'name'        => $name,
            'id'          => $id,
            'updateRoute' => $this->getUpdateRoute(),
        ));
        $viewModel->setTemplate('spiffy-crud/controller/update');
        return $viewModel;
    }

    /**
     * @return Response
     */
    public function deleteAction()
    {
        $name    = $this->getModelName();
        $manager = $this->getCrudManager();
        $entity  = $manager->find($name, $this->params('id'));

        $manager->remove($name, $entity);
        return $this->redirect()->toRoute($this->getReadRoute());
    }

    /**
     * @param \SpiffyCrud\CrudManager $crudManager
     * @return $this
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
            $this->crudManager = $this->getServiceLocator()->get('SpiffyCrud\CrudManager');
        }
        return $this->crudManager;
    }
}