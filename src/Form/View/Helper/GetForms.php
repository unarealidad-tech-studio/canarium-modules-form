<?php
namespace Form\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\Common\Persistence\ObjectManager;

class GetForms extends AbstractHelper
{
    public function __construct(ObjectManager $objectManager){
        $this->objectManager = $objectManager;
    }

    public function __invoke(array $params = array())
    {
        $validParams = array('id', 'publish', 'permalink');
        $queryParams = array();
        $queryParams['publish'] = true;

        foreach ($params as $key => $val) {
            if (in_array($key, $validParams)) {
                $queryParams[$key] = $val;
            }
        }

        return $this->objectManager
                    ->getRepository('Form\Entity\Form')
                    ->findBy($queryParams, array('id' => 'ASC'));
    }
}
