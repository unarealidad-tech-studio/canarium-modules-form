<?php

namespace Form\Option;

use Zend\Stdlib\AbstractOptions;

class ModuleOption extends AbstractOptions
{
    protected $form_creation_limit = 0;

    public function setFormCreationLimit($limit)
    {
        $this->form_creation_limit = $limit;
        return $this;
    }

    public function getFormCreationLimit()
    {
        return $this->form_creation_limit;
    }
}
