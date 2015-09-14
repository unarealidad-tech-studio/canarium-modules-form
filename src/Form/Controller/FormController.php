<?php

namespace Form\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Headers;
use Zend\Http\Response\Stream;


class FormController extends AbstractActionController
{
	public $redirectUrl = '/form/thank-you';
    protected $formService;

    public function indexAction(){
		$id = $this->params()->fromRoute('id',0);
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        if (is_numeric($id)) {
            $entity = $objectManager->getRepository('Form\Entity\Form')->find($id);
        } else {
            $entity = $objectManager->getRepository('Form\Entity\Form')
                                    ->findOneBy(array(
                                        'permalink' => $id,
                                        'publish'   => true,
                                    ));
        }

        if (!$entity) {
            throw new \ErrorHandler\Exception\NotFoundException(
                "Form not found",
                1
            );
        }

		$form = $this->getFormService()->getZendFormCounterpart($entity);

		if ($this->request->isPost()) {
			$post = array_merge_recursive(
				$this->request->getPost()->toArray(),
				$this->request->getFiles()->toArray()
			);

			$form->setData($post);

			$parentData = new \Form\Entity\ParentData();
			$parentData->setForm($entity);
			$parentData->setUser($this->zfcUserAuthentication()->getIdentity());

			$form->setData($this->params()->fromPost());

			$data_objects = $this->getFormService()->getDataObjectsFromArray(
				$this->params()->fromPost()
			);

			$parentData->addData($data_objects);

			$upload_objects = $this->getFormService()->getUploadObjectsFromArray(
				$this->getRequest()->getFiles(),
				$parentData
			);

			$parentData->addUploads($upload_objects);

			$objectManager->persist($parentData);
			$objectManager->flush();

			return $this->redirect()->toUrl($this->redirectUrl);
		}

		$view = new ViewModel();
		$view->form = $form;
        $view->entity = $entity;
		return $view;
	}

	public function thankYouAction(){

	}

	public function downloadAction() {
		$id = (int) $this->params()->fromRoute('id', 0);
		$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$upload = $objectManager->getRepository('Form\Entity\Upload')->find($id);
		if(!$upload){
			throw new \ErrorHandler\Exception\NotFoundException('File Not Found');
		}

		$file = $upload->getDownloadPath();
		ob_end_clean();
		$response = new Stream();
        $response->setStream(fopen($file, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($file));
		$response->setCleanup(true);

        $headers = new Headers();
        $headers->addHeaders(array(
            'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
            'Content-Type' => $upload->getType()
        ));
		#echo $upload->getType(); exit;
        $response->setHeaders($headers);
        return $response;
	}

    public function submittedFormsAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $userId = (int) $this->params()->fromRoute('user_id', 0);
        $user = $this->zfcUserAuthentication()->getIdentity();

        if ($user) {
            $userRole = $user->getRoles()[0]->getRoleId();

            $filters = array();
            if ($userRole == 'admin') {
                if ($userId) {
                    $selectedUser = $objectManager->getRepository('\CanariumCore\Entity\User')->find($userId);
                    $filters['user'] = $selectedUser;
                }
                $users = $objectManager->getRepository('\CanariumCore\Entity\User')->findAll();
            } else {
                $filters['user'] = $user;
                $users[] = $user;
            }

            $results = $this->getFormService()->getSubmittedData( $filters );
        } else {
            return $this->redirect()->toUrl('user/login');
        }

        $query = $objectManager->createQuery('SELECT a FROM Form\Entity\Form a ORDER BY a.id DESC');
        $forms = $query->getResult();

        return array(
            'results'   => $results,
            'forms'     => $forms,
            'users'     => $users,
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
