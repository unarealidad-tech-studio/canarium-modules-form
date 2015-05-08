<?php
namespace Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class ElementAttributeForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('create-element-attribute');

        $this->setHydrator(new DoctrineHydrator($objectManager));

        $fieldset = new ElementAttributeFieldset($objectManager);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);


        $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Save',
                 'class' => 'btn btn-lg btn-success btn-block',
             ),
        ));
    }
}