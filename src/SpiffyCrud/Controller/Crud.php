<?php

namespace SpiffyCrud\Controller;

use SpiffyCrud\CrudManager;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;

class Crud extends AbstractActionController
{
    /**
     * @var CrudManager
     */
    protected $crudManager;

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
     * @return array
     */
    public function indexAction()
    {
        return array(
            'manager' => $this->getCrudManager(),
            'models'  => $this->getCrudManager()->getModelsAsGroup(),
        );
    }

    /**
     * @return array
     */
    public function detailsAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->get($this->params('name'));

        return array(
            'model' => $model,
            'data'  => $manager->findAllEntities($model)
        );
    }

    /**
     * @return array|Response
     */
    public function createAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->get($this->params('name'));
        $form    = $manager->getFormFromModel($model);
        $prg     = $this->prg();

        if (isset($_POST)) {
            echo '<pre>';
            print_r($_POST);
            exit;
        }

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $form->setData($prg);

            if ($form->isValid()) {
                $this->getCrudManager()->create($model);

                return $this->redirect()->toRoute(
                    'spiffy_crud/details',
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

    /**
     * @return Response
     */
    public function deleteAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->get($this->params('name'));
        $entity  = $manager->findEntity($model, $this->params('id'));

        $model->setEntity($entity);
        $manager->removeEntity($model);

        return $this->redirect()->toRoute(
            'spiffy_crud/details',
            array('name' => $this->params('name'))
        );
    }

    /**
     * @return array|Response
     */
    public function updateAction()
    {
        $manager = $this->getCrudManager();
        $model   = $manager->get($this->params('name'));
        $entity  = $manager->findEntity($model, $this->params('id'));
        $form    = $manager->getFormFromModel($model, $entity);
        $prg     = $this->prg();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $form->setData($prg);

            if ($form->isValid()) {
                $model->setEntity($entity);
                $this->getCrudManager()->updateEntity($model);

                return $this->redirect()->toRoute(
                    'spiffy_crud/details',
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
