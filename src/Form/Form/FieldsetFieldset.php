<?php
namespace Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class FieldsetFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('form-fieldset');

        $this->setHydrator(new DoctrineHydrator($objectManager,'Form\Entity\Fieldset'))->setObject(new \Form\Entity\Fieldset());
		
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		
		$this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
				'placeholder' => '',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'class',
            'attributes' => array(
                'type'  => 'hidden',
                'placeholder' => '',
                'value' => '\Zend\Form\Fieldset'
            ),
        ));
		
        $this->add(array(
            'name' => 'label',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => '',
                'class' => 'form-control'
            ),
        ));

        $ElementFieldsets = new ElementFieldset($objectManager);
        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'element',
            'options' => array(
                'target_element' => $ElementFieldsets,
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'sort',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => '',
                'size' => 2
            ),
        ));
    }

	public function getInputFilterSpecification(){
		return array(
            'element' => array('required' => false)
        );
	}    
}