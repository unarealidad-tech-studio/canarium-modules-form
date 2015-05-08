<?php
namespace Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class FormFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('form');

        $this->setHydrator(new DoctrineHydrator($objectManager,'Form\Entity\Form'))->setObject(new \Form\Entity\Form());
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		
		$this->add(array(
            'name' => 'name',
            'attributes' => array(
				'required' => 'required',
                'type'  => 'text',
				'placeholder' => '',
                'class' => 'form-control',
                'id' => 'fieldset-name'
            ),
        ));
		
		$this->add(array(
            'name' => 'label',
            'attributes' => array(
				'required' => 'required',
                'type'  => 'text',
				'placeholder' => '',
                'class' => 'form-control',
                'id' => 'fieldset-name'
            ),
        ));
		
		$this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'redirect',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class'   => 'Page\Entity\Page',
                    'property'       => 'name',
					'display_empty_item' => true,
					'empty_item_label'   => '---',
                ),
				'attributes' => array(
					'class' => 'form-control',
				),
            )
        );
		
    }

	public function getInputFilterSpecification(){
		return array(
			'redirect' => array('required' => true)
        );
	}    
}