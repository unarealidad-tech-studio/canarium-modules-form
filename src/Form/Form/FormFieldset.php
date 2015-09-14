<?php
namespace Form\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class FormFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('form');
        $this->objectManager = $objectManager;

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

        $this->add(
            array(
                'name' => 'permalink',
                'attributes' => array(
                    'type'          => 'text',
                    'placeholder'   => '',
                    'class'         => 'form-control',
                    'id'            => 'fieldset-permalink'
                )
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'publish',
                'attributes' => array(
                    'placeholder'   => '',
                    'class'         => 'form-control',
                    'id'            => 'fieldset-publish'
                )
            )
        );

        $this->add(
            array(
                'name' => 'featured_image',
                'attributes' => array(
                    'type'          => 'file',
                    'placeholder'   => '',
                    'class'         => 'form-control',
                    'id'            => 'fieldset-featured_image'
                )
            )
        );

    }

	public function getInputFilterSpecification(){
		return array(
			'redirect' => array('required' => false),

            'permalink' => array(
                'required' => false,
                'filters' => array(
                    new \Page\Filter\Url()
                ),
                'validators' => array(
                    array(
                        'name' => 'DoctrineModule\Validator\UniqueObject',
                        'options' => array(
                            'use_context' => true,
                            'object_manager' => $this->objectManager,
                            'object_repository' => $this->objectManager->getRepository('Form\Entity\Form'),
                            'fields' => 'permalink',
                            'context' => array('id'),
                            'messages' => array(
                                'objectNotUnique' => 'This permalink is already taken.'
                            ),
                        ),
                    )
                ),
            ),

            'featured_image' => array(
                'required' => false,
                'validators' => array(
                //     array(
                //         'name' => 'Zend\Validator\File\Size',
                //         'options' => array(
                //             'min' => 120,
                //             'max' => 2000000,
                //         ),
                //         'break_chain_on_failure' => true,
                //     ),
                //     array(
                //         'name' => 'Zend\Validator\File\Extension',
                //         'options' => array(
                //             'extension' => array('png', 'jpg', 'gif', 'jpeg'),
                //         ),
                //         'break_chain_on_failure' => true,
                //     ),
                )
            ),
        );
	}
}
