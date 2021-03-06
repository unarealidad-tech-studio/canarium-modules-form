<?php
namespace Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class FieldsetForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('create-fieldset');

        // The form will hydrate an object of type "BlogPost"
        $this->setHydrator(new DoctrineHydrator($objectManager));

        // Add the user fieldset, and set it as the base fieldset
        $fieldset = new FieldsetFieldset($objectManager);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);

        $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Save',
                 'class' => 'btn btn-lg btn-success btn-block save-fieldset',
             ),
        ));
    }
}