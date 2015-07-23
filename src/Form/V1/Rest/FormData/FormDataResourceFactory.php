<?php
namespace Form\V1\Rest\FormData;

class FormDataResourceFactory
{
    public function __invoke($services)
    {
        $currentUser = null;

        $auth = $services->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            $currentUser = $auth->getIdentity();
        }

        return new FormDataResource($services->get('form_form_service'), $currentUser);
    }
}
