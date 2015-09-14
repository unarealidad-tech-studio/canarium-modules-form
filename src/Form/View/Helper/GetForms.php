<?php
namespace Form\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\Common\Persistence\ObjectManager;

class GetForms extends AbstractHelper
{
    public function __construct(ObjectManager $objectManager){
        $this->objectManager = $objectManager;
    }

    public function __invoke(array $params)
    {
        $validParams = array('id', 'publish', 'permalink');
        $queryParams = array();

        foreach ($params as $key => $val) {
            if (in_array($key, $validParams)) {
                $queryParams[$key] = $val;
            }
        }

        return $this->objectManager
                    ->getRepository('Form\Entity\Form')
                    ->findBy($queryParams);
    }
}
