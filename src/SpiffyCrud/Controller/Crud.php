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
        );
    }

    /**
     * @return array
     */
    public function detailsAction()
    {
        $manager = $this->getCrudManager();
        $name    = $this->params('name');
        $model   = $manager->get($name);

        return array(
            'model' => $model,
            'name'  => $name,
            'data'  => $manager->findAll($name)
        );
    }

    /**
     * @return array|Response
     */
    public function createAction()
    {
        $name    = $this->params('name');
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

                return $this->redirect()->toRoute(
                    'spiffy_crud/details',
                    array('name' => $name)
                );
            }
        }

        return array(
            'model' => $model,
            'form'  => $form,
            'name'  => $name
        );
    }

    /**
     * @return Response
     */
    public function deleteAction()
    {
        $name    = $this->params('name');
        $manager = $this->getCrudManager();
        $entity  = $manager->find($name, $this->params('id'));

        $manager->remove($name, $entity);

        return $this->redirect()->toRoute(
            'spiffy_crud/details',
            array('name' => $name)
        );
    }

    /**
     * @return array|Response
     */
    public function updateAction()
    {
        $name    = $this->params('name');
        $manager = $this->getCrudManager();
        $entity  = $manager->find($name, $this->params('id'));
        $form    = $manager->getForm($name, $entity);
        $prg     = $this->prg();

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false !== $prg) {
            $form->setData($prg);

            if ($form->isValid()) {
                $this->getCrudManager()->persist($name, $entity);

                return $this->redirect()->toRoute(
                    'spiffy_crud/details',
                    array('name' => $name)
                );
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form,
            'name'   => $name
        );
    }
}
