<?php

namespace Form\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Form\Entity\Form;
use Form\Entity\Fieldset;
use Form\Entity\Element;
use Form\Entity\ElementAttribute;

use Form\Service\Form as FormService;

use Doctrine\ORM\Tools\Pagination\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\Paginator\Paginator as ZendPaginator;

class AdminController extends AbstractActionController
{
    /**
     * @var FormService
     */
    protected $formService;

    public function indexAction()
    {
        $view = new ViewModel();
        return $view;
    }


    public function createFormAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form = new \Form\Form\FormForm($objectManager);
        $entity = new Form();
        $form->bind($entity);

        $formsCount = $this->getFormService()->countForms();
        $formsLimit = $this->getServiceLocator()->get('canariumform_module_options')->getFormCreationLimit();

        if ($this->request->isPost()) {

            if ($formsLimit > 0 && $formsLimit <= $formsCount) {
                $this->flashMessenger()->addErrorMessage('The maximum forms allowed has been reached.');
                return $this->redirect()->toRoute('admin/form', array('action' => 'create-form'));
            }

            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                $objectManager->persist($entity);
                $objectManager->flush();
                return $this->redirect()->toRoute('admin/form', array('action'=>'manage'));
            } else {
                foreach ($form->getMessages() as $messageId => $message) {
                    echo "Validation failure '$messageId': $message\n";
                }
            }
        }

        $view = new ViewModel();
        $view->form = $form;
        return $view;
    }


    public function manageAction()  {
        $page = $this->params()->fromQuery('page', 1);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $query = $objectManager->createQuery('SELECT a FROM Form\Entity\Form a ORDER BY a.id DESC');
        $doctrinePaginator = new Paginator($query, $fetchJoinCollection = true);
        $paginator = new ZendPaginator(new DoctrinePaginator($doctrinePaginator));
        $paginator->setCurrentPageNumber( $page );
        $paginator->setItemCountPerPage(10);

        $view = new ViewModel();
        $view->paginator = $paginator;
        $view->routeParams = array('route' => 'admin/form','urlParams' => array('action' => 'manage'));
        return $view;
    }


    public function editFormAction()  {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form = new \Form\Form\FormForm($objectManager);

        $entity = $objectManager->getRepository('Form\Entity\Form')->find($id);
        $form->bind($entity);

        $query = $objectManager->createQuery("SELECT a FROM Form\Entity\Fieldset a WHERE a.form = :id ORDER BY a.sort, a.id ASC")
                               ->setParameter('id',$id);
        $filedset = $query->getResult();

        $error = false;
        if ($this->request->isPost()) {

            $post = array_merge_recursive(
                $this->request->getPost()->toArray(),
                $this->request->getFiles()->toArray()
            );

            $form->setData($post);

            if (
                !empty($post['form']['featured_image']) &&
                !$post['form']['featured_image']['error']
            ) {
                $valid = new \Zend\Validator\File\Extension(
                    array('png', 'jpg', 'gif', 'jpeg'),
                    true
                );

                if (!$valid->isValid($post['form']['featured_image'])) {
                    $form->setMessages(array(
                        'form' => array(
                            'featured_image' => array(
                                'Please enter a valid image.'
                            )
                        )
                    ));
                    $error = true;
                }
            }

            if ($form->isValid() && !$error) {

                $objectManager->persist($entity);
                $objectManager->flush();

                if (
                    !empty($post['form']['featured_image']) &&
                    !$post['form']['featured_image']['error']
                ) {
                    $filename = $this->getFormService()->uploadImage($post['form']['featured_image']);
                    if ($filename) {
                        $entity->setFeaturedImage($filename);
                        $objectManager->flush();
                    }
                }

                return $this->redirect()->toRoute('admin/form', array('action'=>'manage'));
             } else {
                foreach ($form->getMessages() as $messageId => $message) {
                    echo "Validation failure '$messageId': $message\n";
                }
            }
        }

        $view = new ViewModel();
        $view->id = $id;
        $view->entity = $entity;
        $view->form = $form;
        $view->fieldset = $filedset;
        return $view;
    }

    public function createSectionAction()  {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $formEntity = $objectManager->getRepository('Form\Entity\Form')->find($id);
        $form = new \Form\Form\FieldsetForm($objectManager);
        $entity = new Fieldset();
        $entity->setForm($formEntity);
        $form->bind($entity);
        $fId = $entity->getForm()->getId();
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                $objectManager->persist($entity);
                $objectManager->flush();
                return $this->redirect()->toRoute('admin/form', array('action'=>'edit-form', 'id'=>$id));
             } else {
                foreach ($form->getMessages() as $messageId => $message) {
                    echo "Validation failure '$messageId': $message\n";
                }
            }
        }

        $view = new ViewModel();
        $view->id = $id;
        $view->form = $form;
        return $view;
    }


    public function editSectionAction()  {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form = new \Form\Form\FieldsetForm($objectManager);

        $entity = $objectManager->getRepository('Form\Entity\Fieldset')->find($id);
        $form->bind($entity);
        $fId = $entity->getForm()->getId();
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                $objectManager->persist($entity);
                $objectManager->flush();
                return $this->redirect()->toRoute('admin/form', array('action'=>'edit-form', 'id'=>$fId));
             } else {
                foreach ($form->getMessages() as $messageId => $message) {
                    echo "Validation failure '$messageId': $message\n";
                }
            }
        }

        $view = new ViewModel();
        $view->id = $id;
        $view->form = $form;
        return $view;
    }

    public function createFieldsetAsChildAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $partentEntity = $objectManager->getRepository('Form\Entity\Fieldset')->find($id);
        $formId = $partentEntity->getForm()->getId();
        $formEntity = $objectManager->getRepository('Form\Entity\Form')->find($formId);

        $form = new \Form\Form\FieldsetForm($objectManager);
        $entity = new Fieldset();
        $entity->setForm($formEntity);
        $entity->setParent($partentEntity);
        $form->bind($entity);

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->setData($post);

            if ($form->isValid()) {
                $objectManager->persist($entity);
                $objectManager->flush();

                $lastId = $entity->getId();
                $query = $objectManager->createQuery("SELECT a FROM Form\Entity\Element a WHERE a.fieldset = :id ORDER BY a.id DESC")
                                       ->setParameter('id',$lastId);
                $element = $query->getResult();

                $this->getFormService()->updateFieldSetElementOptions($element, $post['value-options']);

                $objectManager->flush();

                return $this->redirect()->toRoute('admin/form', array('action'=>'edit-form', 'id'=>$formId));
            } else {
                foreach ($form->getMessages() as $messageId => $message) {
                    echo "Validation failure '$messageId': $message\n";
                }
            }
        }

        $view = new ViewModel();
        $view->id = $id;
        $view->form = $form;
        return $view;

        return $this->getResponse();
    }

    public function editFieldsetAsChildAction()  {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form = new \Form\Form\FieldsetForm($objectManager);

        $entity = $objectManager->getRepository('Form\Entity\Fieldset')->find($id);
        $form->bind($entity);
        $fId = $entity->getForm()->getId();
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                $query = $objectManager->createQuery("SELECT a FROM Form\Entity\Element a WHERE a.fieldset = :id ORDER BY a.sort DESC")
                                       ->setParameter('id', $entity->getId());
                $element = $query->getResult();

                $this->getFormService()->updateFieldSetElementOptions($element, $post['value-options']);

                $objectManager->flush();

                return $this->redirect()->toRoute('admin/form', array('action'=>'edit-fieldset-as-child', 'id'=>$id));
             } else {
                foreach ($form->getMessages() as $messageId => $message) {
                    error_log("Validation failure '$messageId': $message\n",3,'/tmp/trans.logs');
                }
            }
        }


        $view = new ViewModel();
        $view->id = $id;
        $view->form = $form;
        return $view;
    }

    public function createElementAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $fieldsetEntity = $objectManager->getRepository('Form\Entity\Fieldset')->find($id);

        $form = new \Form\Form\ElementForm($objectManager);
        $entity = new Element();
        $entity->setFieldset($fieldsetEntity);
        $form->bind($entity);

        $closeWindow = 0;
        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                if (isset($post['value-options'])) {

                    $objectManager->persist($entity);

                    $this->getFormService()->updateFieldSetElementOptions($entity, $post['value-options']);

                    $objectManager->flush();
                }

                $closeWindow = 1;
            } else {
                foreach ($form->getMessages() as $messageId => $message) {
                    echo "Validation failure '$messageId': $message\n";
                }
            }
        }

        $view = new ViewModel();
        $view->setTerminal(true);
        $view->id = $id;
        $view->closeWindow = $closeWindow;
        $view->form = $form;
        return $view;
    }

    public function deleteFieldsetAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $entity = $objectManager->getRepository('Form\Entity\Fieldset')->find($id);
        $msg = array();
        if (!empty($id)) {
            $objectManager->remove($entity);
            $objectManager->flush();
            $msg['status'] = 'success';
        }

        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($msg));
        return $response;
    }

    public function deleteElementAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $entity = $objectManager->getRepository('Form\Entity\Element')->find($id);

        $msg = array();
        if (!empty($id)) {
            //$objectManager->remove($element);
            $objectManager->remove($entity);
            $objectManager->flush();
            $msg['status'] = 'success';
        }

        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($msg));
        return $response;
    }

    public function resultsAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = $objectManager->getRepository('Form\Entity\Form')->find($id);
        $query = $objectManager->createQuery("SELECT a FROM Form\Entity\ParentData a WHERE a.form = :id ORDER BY a.id DESC")
                               ->setParameter('id',$id);
        $results = $query->getResult();

        $view = new ViewModel();
        $view->form = $form;
        $view->results = $results;
        return $view;
    }

    public function submittedFormsAction() {
        //$page = $this->params()->fromQuery('page', 1);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $query = $objectManager->createQuery("SELECT a FROM Form\Entity\ParentData a ORDER BY a.id DESC")
                               ->setMaxResults(1000);
        $results = $query->getResult();
        /*$doctrinePaginator = new Paginator($query, $fetchJoinCollection = true);
        $paginator = new ZendPaginator(new DoctrinePaginator($doctrinePaginator));
        $paginator->setCurrentPageNumber( $page );
        $paginator->setItemCountPerPage(10);*/

        $query = $objectManager->createQuery('SELECT a FROM Form\Entity\Form a ORDER BY a.id DESC');
        $forms = $query->getResult();

        $view = new ViewModel();
        //$view->paginator = $paginator;
        $view->results = $results;
        $view->forms = $forms;
        return $view;
    }

    public function formResultsAction() {
        $page = $this->params()->fromQuery('page', 1);
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = $objectManager->getRepository('Form\Entity\Form')->find($id);
        $query = $objectManager->createQuery("SELECT a FROM Form\Entity\ParentData a WHERE a.form = :id ORDER BY a.id DESC")
                               ->setParameter('id',$id);
        //$results = $query->getResult();

        $doctrinePaginator = new Paginator($query, $fetchJoinCollection = true);
        $paginator = new ZendPaginator(new DoctrinePaginator($doctrinePaginator));
        $paginator->setCurrentPageNumber( $page );
        $paginator->setItemCountPerPage(10);

        $view = new ViewModel();
        $view->form = $form;
        //$view->results = $results;
        $view->paginator = $paginator;
        $view->routeParams = array('route' => 'admin/form','urlParams' => array('action'=>'form-results','id'=>$id));
        return $view;
    }

    public function userFormDataAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $data = $objectManager->getRepository('Form\Entity\ParentData')->find($id);

        $view = new ViewModel();
        $view->data = $data;
        return $view;
    }

    public function reportsAction() {
        $view = new ViewModel();
        return $view;
    }

    public function downloadCsvAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $query = $objectManager->createQuery("SELECT a FROM Form\Entity\ParentData a WHERE a.form = :id ORDER BY a.id DESC")
                                ->setParameter('id',$id);

        $results = $query->getResult();

        foreach ($results as $row) {
            $filename = str_replace(" ","-",$row->getForm()->getName()) . '.' . date('mdY') . '.' . date('His') . '.csv';
            break;
        }

        if (empty($filename)) $filename = 'form.' . date('mdY') . '.' . date('His') . '.csv';

        $view = new ViewModel();
        $view->setTemplate('download/csv')
             ->setVariable('results', $results)
             ->setTerminal(true);


        $output = $this->getServiceLocator()
                       ->get('viewrenderer')
                       ->render($view);

        $response = $this->getResponse();

        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/csv')
                ->addHeaderLine(
                    'Content-Disposition',
                    sprintf("attachment; filename=\"%s\"", $filename)
                )
                ->addHeaderLine('Accept-Ranges', 'bytes')
                ->addHeaderLine('Content-Length', strlen($output));

        $response->setContent($output);

        return $response;
    }

    public function settingsAction()
    {
        $form = new \Form\Form\SettingsForm();
        $data = $this->getServiceLocator()->get('canariumform_module_options')->toArray();
        $form->setData($data);

        $service = $this->getServiceLocator()->get('form_settings_service');

        if ($this->getRequest()->isPost()) {
            $this->flashMessenger()->addSuccessMessage('Configurations has been updated');
            $service->writeConfiguration($this->getRequest()->getPost());
            return $this->redirect()->toRoute('admin/form', array('action'=>'settings'));
        }

        return array(
            'form' => $form,
        );
    }

    public function getFormService()
    {
        if (!$this->formService) {
            $this->formService = $this->getServiceLocator()->get('form_form_service');
        }
        return $this->formService;
    }

    public function setFormService(FormService $formService)
    {
        $this->formService = $formService;
        return $this;
    }
}
