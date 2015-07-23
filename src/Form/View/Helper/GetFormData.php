<?php
namespace Form\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\Common\Persistence\ObjectManager;

class GetFormData extends AbstractHelper
{
	public function __construct(ObjectManager $objectManager){
		$this->objectManager = $objectManager;
	}

    public function __invoke(array $value){
        $i = $this->objectManager->getRepository('Form\Entity\Data')->findOneBy($value);
		return $i;
    }
}