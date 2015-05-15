<?php

namespace Form\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

use Form\Entity\Element;

class Form implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function updateFieldSetElementOptions($elements, $data)
    {
        if (!is_array($elements)) {
            $elements = array($elements);
        }

        foreach ($elements as $row) {

            if (is_array($data) && !isset($data[$row->getId()])) {
                continue;
            }

            $value_options = (is_array($data) ? $data[$row->getId()] : $data);

            if (!empty($value_options)) {
                $multi = array(
                    '\Zend\Form\Element\Checkbox',
                    '\Zend\Form\Element\MultiCheckbox',
                    '\Zend\Form\Element\Select',
                    '\Zend\Form\Element\Radio'
                );

                $class = $row->getClass();

                $ops = explode("\r\n", $value_options);
                $ops = array_filter($ops, 'trim');

                if (in_array($class, $multi)) {
                    $kvOps = array();
                    foreach ($ops as $k=>$v) {
                        $kvOps[$v] = $v;
                    }

                    $ops = array('value_options' => $kvOps);
                    $row->setOptions(serialize($ops));
                } else {
                    $ops = array('value_options' => $ops);
                    $row->setOptions(serialize($ops));
                }
            }
        }
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

}
