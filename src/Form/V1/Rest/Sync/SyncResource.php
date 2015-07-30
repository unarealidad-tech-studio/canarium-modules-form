<?php
namespace Form\V1\Rest\Sync;

use CanariumCore\Entity\User;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

use Form\Service\Form as FormService;

class SyncResource extends AbstractResourceListener
{
    protected $service;
    protected $loggedInUser;

    public function __construct(FormService $service, User $loggedInUser = null)
    {
        $this->service = $service;
        $this->loggedInUser = $loggedInUser;
    }

    /**
     * Handles the syncing of resources
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        if (empty($data->user_id) && $this->loggedInUser) {
            $data->user_id = $this->loggedInUser->getId();
        } elseif (empty($data->user_id)) {
            return new ApiProblem(401, 'You are not authorize to use this api');
        }

        $data->form_name = $this->getEvent()->getRouteMatch()->getParam('form_name');

        try {

            if ($data->limit) {
                $em = $this->getService()->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $query = $em->createQuery('SELECT COUNT(d) FROM Form\Entity\ParentData d WHERE d.user=:user AND d.date>:dateadded')
                            ->setParameter('user', $this->loggedInUser)
                            ->setParameter('dateadded', new \DateTime('today 12:00 am'));
                $result = $query->getSingleScalarResult();
                if (intval($result) > 0) {
                    throw new \Exception("Max limit reached");
                }
            }

            $newItem = $this->getService()->createParentDataFromApiInput(
                json_decode(json_encode($data), true)
            );

            $data->id = $newItem->getId();

            return $data;
        } catch (\Exception $e) {
            return new ApiProblem(401, 'You are not authorize to use this api');
        }
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        try {
            $this->getService()->getParentDataMapper()->delete($id);
            $this->getService()->getParentDataMapper()->save();
            return true;
        } catch (\Exception $e) {
            return new ApiProblem(400, $e->getMessage());
        }
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        $entity = $this->getService()->getParentDataMapper()->fetch($id);

        if (!$entity) {
            return new ApiProblem(410, 'The data has already been deleted');
        }

        return $this->convertEntityToArray($entity);
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        $form_name = $this->getEvent()->getRouteMatch()->getParam('form_name');

        $additional_filters = array(
            'form_name' => $form_name
        );

        if (empty($params->user_id) && $this->loggedInUser) {
            $additional_filters['user_id'] = $this->loggedInUser->getId();
        } elseif (empty($params->user_id)) {
            return array();
        }

        $entities = $this->getService()
            ->getParentDataMapper()
            ->getFilteredQuery(array_merge($params->toArray(), $additional_filters))
            ->getQuery()
            ->getResult();
        return $this->convertEntityToArray($entities);
    }

    protected function convertEntityToArray($entities)
    {
        $return_array = array();

        $is_array = true;

        if (!is_array($entities)) {
            $entities = array($entities);
            $is_array = false;
        }

        foreach ($entities as $entity) {
            $current_parent_data = array();
            $current_parent_data['id']= $entity->getId();
            $current_parent_data['user_id'] = $entity->getUser()->getId();
            $current_parent_data['date_added'] = $entity->getDate()->getTimestamp();
            $current_parent_data['date_updated'] = (
                $entity->getDateUpdated() ? $entity->getDateUpdated()->getTimestamp() : null
            );

            $current_parent_data['other_fields'] = array();

            if ($entity->getData()) {
                foreach($entity->getData() as $element_data) {
                    if ($element_data->getElement()) {
                        $current_parent_data['other_fields'][$element_data->getElement()->getName()] =
                            unserialize($element_data->getValue());
                    }
                }
            }

            $return_array[] = $current_parent_data;
        }

        return ($is_array ? $return_array : $return_array[0]);
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        $entity = $this->getService()->getParentDataMapper()->fetch($id);

        if (!$entity) {
            return new ApiProblem(410, 'The data has already been deleted');
        }

        if (
            !empty($data->force_update) ||
            (isset($data->date_updated) && $entity->getDateUpdated() && $data->date_updated >= $entity->getDateUpdated()->getTimestamp()) ||
            (!isset($data->date_updated) && !$entity->getDateUpdated())
        ) {
            $entity->setDateUpdated(new \DateTime('now'));

            foreach ($data->other_fields as $name => $value) {
                $element = $this->getService()->findFormElementByName($name, $entity->getForm());

                if ($element) {
                    foreach($entity->getData() as $data) {
                        if ($data->getElement()->getId() == $element->getId()) {
                            $data->setValue(serialize($value));
                        }
                    }
                }
            }
        } else {
            return new ApiProblem(409, 'A conflict occurred while syncing data. Local:'.@$data->date_updated.' remote:'.@$entity->getDateUpdated()->getTimestamp());
        }

        $this->getService()->getParentDataMapper()->save();

        return $this->convertEntityToArray($entity);
    }

    /**
     * @return FormService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param FormService $service
     * @return $this
     */
    public function setService(FormService $service)
    {
        $this->service = $service;
        return $this;
    }
}
