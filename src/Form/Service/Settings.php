<?php

namespace Form\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Settings implements ServiceLocatorAwareInterface
{
    public function writeConfiguration($data)
    {
        $resource = $this->getServiceLocator()->get('ZF\Configuration\ConfigResource');
        $override = array();

        $option = $this->getServiceLocator()->get('canariumform_module_options');
        foreach ($data as $key => $value) {
            if (property_exists($option, $key)) {
                $override[$key] = $value;
            }
        }
        $resource->patch(array('canariumform' => $override));
        return true;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return User
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

}
