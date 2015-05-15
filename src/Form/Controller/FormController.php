<?php

namespace Form\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Headers;
use Zend\Http\Response\Stream;


class FormController extends AbstractActionController
{
	public $redirectUrl = '/form/thank-you';

    public function indexAction(){
		$id = $this->params()->fromRoute('id',0);
		$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$entity = $objectManager->getRepository('Form\Entity\Form')->find($id);

		$form = new \Zend\Form\Form($entity->getName());
		$form->setLabel($entity->getLabel());
		foreach($entity->getFieldset() as $fieldsetEntity){
			$class = $fieldsetEntity->getClass();
			$fieldset = new $class($fieldsetEntity->getName());
			$fieldset->setLabel($fieldsetEntity->getLabel());
			foreach($fieldsetEntity->getElement() as $elementEntity){
				$elementClass = $elementEntity->getClass();
				$element = new $elementClass($elementEntity->getId());
				$element->setLabel($elementEntity->getLabel());
				if ($elementEntity->getOptions()) $element->setOptions(unserialize($elementEntity->getOptions()));
					$element->setAttributes($elementEntity->getAttributesForForm());
				$fieldset->add($element);
			}

			$form->add($fieldset);
		}

		if ($this->request->isPost()) {
			$post = array_merge_recursive(
				$this->request->getPost()->toArray(),
				$this->request->getFiles()->toArray()
			);
			$form->setData($post);
			#if ($form->isValid()) {
				$parentData = new \Form\Entity\ParentData();
				$parentData->setForm($entity);
				$parentData->setUser($this->zfcUserAuthentication()->getIdentity());
				$collection = new \Doctrine\Common\Collections\ArrayCollection();
				$collectionUpload = new \Doctrine\Common\Collections\ArrayCollection();
				$form->setData($this->params()->fromPost());
				foreach($this->params()->fromPost() as $fieldsetFromPost) {
					foreach($fieldsetFromPost as $k => $v){
						$element = $objectManager->getRepository('Form\Entity\Element')->find($k);
						$data = new \Form\Entity\Data();
						$data->setElement($element);
						$data->setValue(serialize($v));
						$collection->add($data);
					}
				}


				$files = $this->getRequest()->getFiles();
				$dir = "./data/uploads/tmp/";
				if(!file_exists($dir)){
					if(!mkdir($dir, 0755, true))
						throw new \Exception("Failed to create upload folders");
				}
				$filter = new \Zend\Filter\File\RenameUpload($dir);
				$filter->setUseUploadName(true);
				foreach($this->getRequest()->getFiles() as $files){
					foreach($files as $file){
						$upload = new \Form\Entity\Upload($this->getServiceLocator());
						$upload->setParentData($parentData);
						$upload->setName($file['name']);
						$upload->setType($file['type']);
						$upload->setTmpName($file['tmp_name']);
						$upload->setError($file['error']);
						$upload->setSize($file['size']);
						$collectionUpload->add($upload);

						$filter->filter($file);
					}
				}

				$parentData->addData($collection);
				$parentData->addUploads($collectionUpload);

				$objectManager->persist($parentData);
				$objectManager->flush();
				return $this->redirect()->toUrl($this->redirectUrl);
			}
		#}

		$view = new ViewModel();
		$view->form = $form;
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
}
