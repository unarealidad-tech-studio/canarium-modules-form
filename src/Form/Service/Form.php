<?php

namespace Form\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Filter\File\RenameUpload;

use Form\Entity\Form as CanariumForm;
use Form\Entity\Data;
use Form\Entity\Upload;
use Form\Entity\ParentData;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;


class Form implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Form\Mapper\ParentData
     */
    protected $parentDataMapper;
    /**
     * @var \Form\Mapper\Data
     */
    protected $dataMapper;

    public function getAvailableLogsWithSubjects($form, $user)
    {
        $available_subjects = array();

        $criteria = array(
            'form_id' => $form->getId(),
            'user_id' => $user->getId()
        );

        $query = $this->getDataMapper()->getAvailableLogValuesQuery($criteria);

        $output = $query->select('data.value')->getQuery()->getArrayResult();

        foreach ($output as $subject) {
            $unserialized_subject = unserialize($subject['value']);

            if (!in_array($unserialized_subject, $available_subjects) && is_string($unserialized_subject)) {
                $available_subjects[] = $unserialized_subject;
            }
        }

        return $available_subjects;
    }

    public function createParentDataFromApiInput(array $api_input)
    {
        if (empty($api_input['user_id'])) {
            throw new \InvalidArgumentException('Please specify a user_id');
        }

        if (empty($api_input['form_name'])) {
            throw new \InvalidArgumentException('Please specify the connected form');
        }

        $user = $this->getObjectManager()
            ->getRepository('CanariumCore\Entity\User')
            ->find($api_input['user_id']);

        if (!$user) {
            throw new \InvalidArgumentException('Invalid User passed');
        }

        $formObject = $this->getFormByName($api_input['form_name']);

        if (!$formObject) {
            throw new \InvalidArgumentException('Invalid form passed');
        }

        $newParentData = $this->createParentDataFromArray(
            $formObject, $api_input['other_fields']
        );

        $newParentData->setUser($user);
        $newParentData->setIp($_SERVER['REMOTE_ADDR']);

        $this->getParentDataMapper()->save();

        return $newParentData;
    }

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

    public function getZendFormCounterpart(CanariumForm $entity, ParentData $data = null)
    {
        $currentData = array();

        if ($data) {
            foreach ($data->getData() as $rowData) {
                $currentData[$rowData->getElement()->getId()] = unserialize($rowData->getValue());
            }
        }

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
                if (isset($currentData[$elementEntity->getId()])) {
                    $element->setValue($currentData[$elementEntity->getId()]);
                }
                if ($elementEntity->getOptions()) $element->setOptions(unserialize($elementEntity->getOptions()));
                    $element->setAttributes($elementEntity->getAttributesForForm());
                $fieldset->add($element);
            }

            $form->add($fieldset);
        }

        return $form;
    }

    public function getDataObjectsFromArray($data_array, ParentData $data = null)
    {
        $collection = new ArrayCollection();

        $currentData = array();

        if ($data) {
            foreach ($data->getData() as $rowData) {
                $currentData[$rowData->getElement()->getId()] = $rowData;
            }
        }

        foreach($data_array as $fieldsetFromPost) {

            if (!is_array($fieldsetFromPost)) {
                continue;
            }

            foreach($fieldsetFromPost as $k => $v){
                $element = $this->getObjectManager()->getRepository('Form\Entity\Element')->find($k);

                $data_object = null;

                if (isset($currentData[$k])) {
                    $data_object = $currentData[$k];
                    $data_object->setValue(serialize($v));
                } else {
                    $data_object = new Data();
                    $data_object->setElement($element);
                    $data_object->setValue(serialize($v));
                    $collection->add($data_object);
                }
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
        $filter->setOverwrite(true);

        $collectionUpload = new ArrayCollection();

        foreach($upload_array as $files){
            foreach($files as $file){

                if ($file['error'] != 0) {
                    continue;
                }

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

    public function findFormElementByName($name, CanariumForm $form = null)
    {
        $results = $this->getObjectManager()->getRepository('Form\Entity\Element')->findBy(array(
            'name' => $name
        ));

        if ($results && $form) {
            foreach ($results as $result) {
                if ($result->getFieldset()->getForm()->getId() == $form->getId()) {
                    return $result;
                }
            }
        }

        return ($results ? $results[0] : null);
    }

    public function findFormElementById($id)
    {
        $result = $this->getObjectManager()->getRepository('Form\Entity\Element')->find($id);
        return $result;
    }

    public function createDataFromArray(CanariumForm $form, ParentData $parent_data, array $data, $flush_now = false)
    {
        foreach ($data as $name => $value) {
            $currentFormElement = $this->findFormElementByName($name, $form);
            if ($currentFormElement) {
                $currentData = new Data();
                $currentData->setValue(serialize($value));
                $currentData->setElement($currentFormElement);
                $currentData->setParentData($parent_data);

                $this->getObjectManager()->persist($currentData);
            }
        }

        if ($flush_now) {
            $this->getParentDataMapper()->save();
        }

        return $parent_data;
    }

    public function createParentDataFromArray(CanariumForm $form, array $data)
    {
        //This will only support one dimensional arrays for now

        $formData = new ParentData();
        $formData->setForm($form);

        $this->getObjectManager()->persist($formData);

        $this->createDataFromArray($form, $formData, $data);

        return $formData;
    }

    public function getFormByName($name)
    {
        return $this->getObjectManager()->getRepository('Form\Entity\Form')->findOneBy(array(
            'name' => $name
        ));
    }

    public function getSubmittedData($data = array())
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $qb = $objectManager->createQueryBuilder();
        $qb->add('select', 'a')
            ->add('from', 'Form\Entity\ParentData a')
            ->add('orderBy', 'a.date DESC')
            ->setMaxResults(10000);

        // apply filters
        if ($data['user'] instanceof \CanariumCore\Entity\User) {
            $qb->andWhere('a.user = :user')
               ->setParameter(':user', $data['user']);
        }

        $query = $qb->getQuery();
        return $query->getResult();
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

    public function getObjectManager()
    {
        if (!$this->objectManager) {
            $this->objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->objectManager;
    }

    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

    public function getModuleOption()
    {
        if (empty($this->moduleOption)) {
            $this->moduleOption = $this->getServiceLocator()->get('canariumform_module_options');
        }

        return $this->moduleOption;
    }

    public function getParentDataMapper()
    {
        if (!$this->parentDataMapper) {
            $this->parentDataMapper = $this->getServiceLocator()->get('canariumform_parentdata_mapper');
        }

        return $this->parentDataMapper;
    }

    public function getDataMapper()
    {
        if (!$this->dataMapper) {
            $this->dataMapper = $this->getServiceLocator()->get('canariumform_data_mapper');
        }

        return $this->dataMapper;
    }

    public function countForms()
    {
        $queryBuilder = $this->getObjectManager()->createQueryBuilder();
        $queryBuilder->select('COUNT(f)')->from('Form\Entity\Form', 'f');
        $query = $queryBuilder->getQuery();
        return $query->getSingleScalarResult();
    }
}
