<?php
namespace Form\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\Common\Persistence\ObjectManager;

class GetParentData extends AbstractHelper
{
	public function __construct(ObjectManager $objectManager){
		$this->objectManager = $objectManager;
	}

    public function __invoke(array $value){
        $i = $this->objectManager->getRepository('Form\Entity\ParentData')->findOneBy($value);
		return $i;
    }
}