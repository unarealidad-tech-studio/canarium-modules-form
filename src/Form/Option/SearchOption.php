<?php

namespace Form\Option;

use Zend\Stdlib\AbstractOptions;

class SearchOption extends AbstractOptions
{
    protected $limit = 10;
    protected $offset = 0;
    protected $parentId = null;
    protected $orderBy = array('name' => 'ASC');
    protected $filters = array();

    protected $acceptedFilters = array(
        'name',
        'parent_id',
        'user_id',
        'parent_data_id'
    );

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (empty($options['filters'])) {
            foreach ($options as $key => $value) {
                if (in_array($key, $this->acceptedFilters)) {
                    $this->addFilter($key, $value);
                }
            }

        }
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy(array $orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public function addOrderBy($name, $orderType)
    {
        $this->orderBy[$name] = $orderType;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }

    public function addFilter($name, $value)
    {
        $this->filters[$name] = $value;
    }

}
