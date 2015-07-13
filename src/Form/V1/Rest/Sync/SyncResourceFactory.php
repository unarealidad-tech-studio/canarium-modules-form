<?php
namespace Form\V1\Rest\Sync;

class SyncResourceFactory
{
    public function __invoke($services)
    {
        $currentUser = null;

        $auth = $services->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            $currentUser = $auth->getIdentity();
        }

        return new SyncResource($services->get('form_form_service'), $currentUser);
    }
}
