<?php
namespace Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class ElementFieldset extends Fieldset implements InputFilterProviderInterface
{
     protected $invokableClasses = array(
            '\Zend\Form\Element\Button'        => 'Button',
            '\Zend\Form\Element\Captcha'       => 'Captcha',
            '\Zend\Form\Element\Checkbox'      => 'Checkbox',
            '\Zend\Form\Element\Collection'    => 'Collection',
            '\Zend\Form\Element\Color'         => 'Color',
            '\Zend\Form\Element\Csrf'          => 'Csrf',
            '\Zend\Form\Element\Date'          => 'Date',
            '\Zend\Form\Element\DateSelect'    => 'Dateselect',
            '\Zend\Form\Element\DateTime'      => 'Datetime',
            '\Zend\Form\Element\DateTimeLocal' => 'Datetimelocal',
            '\Zend\Form\Element'               => 'Element',
            '\Zend\Form\Element\Email'         => 'Email',
            '\Zend\Form\Fieldset'              => 'Fieldset',
            '\Zend\Form\Element\File'          => 'File',
            '\Zend\Form\Form'                  => 'Form',
            '\Zend\Form\Element\Hidden'        => 'Hidden',
            '\Zend\Form\Element\Image'         => 'Image',
            '\Zend\Form\Element\Month'         => 'Month',
            '\Zend\Form\Element\MonthSelect'   => 'Monthselect',
            '\Zend\Form\Element\MultiCheckbox' => 'Multicheckbox',
            '\Zend\Form\Element\Number'        => 'Number',
            '\Zend\Form\Element\Password'      => 'Password',
            '\Zend\Form\Element\Radio'         => 'Radio',
            '\Zend\Form\Element\Range'         => 'Range',
            '\Zend\Form\Element\Select'        => 'Select',
            '\Zend\Form\Element\Submit'        => 'Submit',
            '\Zend\Form\Element\Text'          => 'Text',
            '\Zend\Form\Element\Textarea'      => 'Textarea',
            '\Zend\Form\Element\Time'          => 'Time',
            '\Zend\Form\Element\Url'           => 'Url',
            '\Zend\Form\Element\Week'          => 'Week',
        );

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('form-element');

        $this->setHydrator(new DoctrineHydrator($objectManager,'Form\Entity\Element'))->setObject(new \Form\Entity\Element());
		
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		
		$this->add(array(
            'name' => 'name',
            'attributes' => array(
				'class' => 'form-control',
                'type'  => 'text',
				'placeholder' => 'Name',
            ),
        ));
		
        $this->add(array(
            'name' => 'fieldset_id',
            'attributes' => array(
				'class' => 'form-control',
                'type'  => 'hidden',
                'placeholder' => 'Name',
                'value' => '1'
            ),
        ));

        $this->add(array(
            'name' => 'label',
            'attributes' => array(
				'class' => 'form-control',
                'type'  => 'text',
                'placeholder' => 'Label',
            ),
        ));

        $select = new \Zend\Form\Element\Select('class');
        $select->setValueOptions($this->invokableClasses);
        $select->setAttribute('class','form-control input-class');
        $select->setAttribute('required','required');
        $this->add($select);

        $this->add(array(
            'name' => 'options',
            'attributes' => array(
                'type'  => 'hidden',
                'placeholder' => 'Options',
                'class'=> 'input-options input-class'
            ),
        ));
		
		$this->add(array(
            'name' => 'attributes',
            'attributes' => array(
                'type'  => 'textarea',
                'placeholder' => 'Attributes',
                'class'=> 'form-control'
            ),
        ));

        /*$ElementAttributeFieldsets = new ElementAttributeFieldset($objectManager);
        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'element-attribute',
            'options' => array(
                'allow_add'      => true,
                'allow_remove'   => true,                
                'target_element' => $ElementAttributeFieldsets,
                'class' => 'form-control'
            )
        ));*/

        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'element-attribute',
            'options' => array(
                'target_element' => new ElementAttributeFieldset($objectManager)
            )
        ));
        $this->get('element-attribute')->setHydrator(new DoctrineHydrator($objectManager));

        $this->add(array(
            'name' => 'sort',
            'attributes' => array(
				'class' => 'form-control',
                'type'  => 'text',
                'placeholder' => '',
                'size' => 2
            ),
        ));   
    }

	public function getInputFilterSpecification(){
		return array(
        );
	}    
}