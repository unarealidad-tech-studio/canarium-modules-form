<?php

namespace Form\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Filter\File\RenameUpload;

use Form\Entity\Form as CanariumForm;
use Form\Entity\Data;
use Form\Entity\Upload;
use Form\Entity\ParentData;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;

use Form\Entity\Element;

class Form implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    protected $objectRepository;

    public function updateFieldSetElementOptions($elements, $data)
    {
        if (!is_array($elements)) {
            $elements = array($elements);
        }

        foreach ($elements as $row) {

            if (is_array($data) && !isset($data[$row->getId()])) {
                continue;
            }

            $value_options = (is_array($data) ? $data[$row->getId()] : $data);

            if (!empty($value_options)) {
                $multi = array(
                    '\Zend\Form\Element\Checkbox',
                    '\Zend\Form\Element\MultiCheckbox',
                    '\Zend\Form\Element\Select',
                    '\Zend\Form\Element\Radio'
                );

                $class = $row->getClass();

                $ops = explode("\r\n", $value_options);
                $ops = array_filter($ops, 'trim');

                if (in_array($class, $multi)) {
                    $kvOps = array();
                    foreach ($ops as $k=>$v) {
                        $kvOps[$v] = $v;
                    }

                    $ops = array('value_options' => $kvOps);
                    $row->setOptions(serialize($ops));
                } else {
                    $ops = array('value_options' => $ops);
                    $row->setOptions(serialize($ops));
                }
            }
        }
    }

    public function getZendFormCounterpart(CanariumForm $entity)
    {
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

        return $form;
    }

    public function getDataObjectsFromArray($data_array)
    {
        $collection = new ArrayCollection();

        foreach($data_array as $fieldsetFromPost) {
            foreach($fieldsetFromPost as $k => $v){
                $element = $this->getObjectRepository()->find($k);

                $data_object = new Data();
                $data_object->setElement($element);
                $data_object->setValue(serialize($v));
                $collection->add($data_object);
            }
        }

        return $collection;
    }

    public function getUploadObjectsFromArray($upload_array, ParentData $parent = null)
    {
        $dir = "./data/uploads/tmp/";

        if (!file_exists($dir) && !mkdir($dir, 0755, true)) {
            throw new \Exception("Failed to create upload folders");
        }

        $filter = new RenameUpload($dir);
        $filter->setUseUploadName(true);

        $collectionUpload = new ArrayCollection();

        foreach($upload_array as $files){
            foreach($files as $file){
                $upload = new Upload($this->getServiceLocator());

                if ($parent) {
                    $upload->setParentData($parent);
                }

                $upload->setName($file['name']);
                $upload->setType($file['type']);
                $upload->setTmpName($file['tmp_name']);
                $upload->setError($file['error']);
                $upload->setSize($file['size']);

                $collectionUpload->add($upload);

                $filter->filter($file);
            }
        }

        return $collectionUpload;
    }

    public function getObjectRepository()
    {
        if (empty($this->objectRepository)) {
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $this->objectRepository = $entityManager->getRepository('Form\Entity\Element');
        }

        return $this->objectRepository;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return User
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

}
