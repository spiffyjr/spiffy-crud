<?php

namespace SpiffyCrud\Controller;

use SpiffyCrud\CrudManager;
use SpiffyCrud\Model;
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
     * @var string
     */
    protected $identifier = 'id';

    /**
     * @var string
     */
    protected $modelName;

    /**
     * @var string
     */
    protected $createTemplate = 'spiffy-crud/controller/create';

    /**
     * @var string
     */
    protected $readTemplate = 'spiffy-crud/controller/read';

    /**
     * @var string
     */
    protected $updateTemplate = 'spiffy-crud/controller/update';

    /**
     * @var string
     */
    protected $route;

    /**
     * @return array
     */
    public function readAction()
    {
        $manager = $this->getCrudManager();
        $model   = $this->getModel();

        $viewModel = new ViewModel(array(
            'model'       => $model,
            'name'        => $this->modelName,
            'data'        => $manager->findAll($this->modelName),
            'createRoute' => $this->getCreateRoute(),
        ));
        $viewModel->setTemplate($this->readTemplate);
        return $viewModel;
    }

    /**
     * @return array|Response
     */
    public function createAction()
    {
        $name    = $this->modelName;
        $manager = $this->getCrudManager();
        $model   = $this->getModel();
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
            'readRoute'   => $this->getReadRoute(),
        ));
        $viewModel->setTemplate($this->createTemplate);
        return $viewModel;
    }

    /**
     * @return array|Response
     */
    public function updateAction()
    {
        $name    = $this->modelName;
        $id      = $this->params($this->identifier);
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
            'readRoute'   => $this->getReadRoute(),
            'updateRoute' => $this->getUpdateRoute(),
        ));
        $viewModel->setTemplate($this->updateTemplate);
        return $viewModel;
    }

    /**
     * @return Response
     */
    public function deleteAction()
    {
        $name    = $this->modelName;
        $manager = $this->getCrudManager();
        $entity  = $manager->find($name, $this->params($this->identifier));

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

    /**
     * @param string $modelName
     * @return $this
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
        return $this;
    }

    /**
     * @param string $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @throws \RuntimeException
     * @return Model\ModelInterface
     */
    protected function getModel()
    {
        if (!$this->modelName) {
            throw new \RuntimeException('Missing model name');
        }
        return $this->getCrudManager()->get($this->modelName);
    }

    /**
     * @return string
     */
    protected function getCreateRoute()
    {
        return $this->route . '/create';
    }

    /**
     * @return string
     */
    protected function getDeleteRoute()
    {
        return $this->route . '/delete';
    }

    /**
     * @return string
     */
    protected function getReadRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    protected function getUpdateRoute()
    {
        return $this->route . '/update';
    }
}
