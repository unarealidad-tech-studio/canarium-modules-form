<?php
namespace Form\V1\Rest\FormData;

use CanariumCore\Entity\User;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

use Form\Service\Form as FormService;

class FormDataResource extends AbstractResourceListener
{
    protected $service;
    protected $loggedInUser;

    public function __construct(FormService $service, User $loggedInUser = null)
    {
        $this->service = $service;
        $this->loggedInUser = $loggedInUser;
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return new ApiProblem(405, 'The POST method has not been defined');
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
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
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
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

        if ($params->order_by) {
            $additional_filters['order_by'] = $params->order_by;
        }

        $query = $this->getService()
            ->getParentDataMapper()
            ->getFilteredQuery(array_merge($params->toArray(), $additional_filters));
        if (!empty($params->unique)) {
            $entities = $query->getQuery()->getResult();

            $count = $this->getNonDuplicateCount($entities);
            return array(array('count' => $count));
        } elseif (!empty($params->total_only)) {
            $count = $query->select($query->expr()->count('parent_data.id'))
                ->getQuery()->getSingleScalarResult();
            return array(array('count' => $count));
        } else {
            $entities = $query->getQuery()->getResult();
            return $this->convertEntityToArray($entities);
        }
    }

    protected function getNonDuplicateCount($entities)
    {
        $current_count = 0;
        $index_check = array();
        foreach ($entities as $entity) {
            if ($entity->getData()) {
                $current_parent_data = array();

                foreach($entity->getData() as $element_data) {
                    if ($element_data->getElement()) {
                        $current_parent_data[$element_data->getElement()->getName()] =
                            unserialize($element_data->getValue());
                    }
                }

                if (!empty($current_parent_data['subject']) && !empty($current_parent_data['sender'])) {
                    $current_index = $current_parent_data['subject'].$current_parent_data['sender'];
                    if (empty($index_check[$current_index])) {
                        $index_check[$current_index] = 1;
                        $current_count++;
                    }
                }

            }
        }

        return $current_count;
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
            $current_parent_data['user_info'] = array(
                'username' => $entity->getUser()->getUsername(),
                'displayName' => $entity->getUser()->getDisplayName(),
                'email' => $entity->getUser()->getEmail()
            );
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
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
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
