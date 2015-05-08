<?php
namespace Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class ElementAttributeFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('form-element-attribute');

        $this->setHydrator(new DoctrineHydrator($objectManager,'Form\Entity\ElementAttribute'))->setObject(new \Form\Entity\ElementAttribute());
		
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		
        $this->add(array(
            'name' => 'element',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

		$this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
				'placeholder' => 'Name',
            ),
        ));
		
        $this->add(array(
            'name' => 'value',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Value'
            ),
        ));
    }

	public function getInputFilterSpecification(){
		return array(
        );
	}    
}