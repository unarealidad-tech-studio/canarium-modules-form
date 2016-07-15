<?php

namespace Form\Mapper;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Common\Collections\ArrayCollection;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use CanariumCore\Mapper\MapperInterface;

use Form\Entity\ParentData as ParentDataEntity;
use Form\Entity\Data as DataEntity;

class Data extends EntityRepository implements MapperInterface
{
    public function create(array $data)
    {
        $newItem = new DataEntity();

        $hydrator = new DoctrineHydrator($this->getEntityManager(), $byValue = false);

        $hydrator->hydrate($data, $newItem);

        $this->getEntityManager()->persist($newItem);

        return $newItem;
    }

    public function delete($id)
    {
        $toDelete = $this->fetch($id);

        if (!$toDelete) {
            throw new \InvalidArgumentException('Item does not exist');
        }

        $this->getEntityManager()->remove($toDelete);
    }

    public function update($id, array $data)
    {
        $toUpdate = $this->fetch($id);

        if (!$toUpdate) {
            throw new \InvalidArgumentException('Item does not exist');
        }

        $toUpdate->populate($data);
    }

    public function fetch($id)
    {
        return $this->find($id);
    }

    public function getAvailableLogValuesQuery(array $criteria = null)
    {

        $query = $this->createQueryBuilder('data')
            ->innerJoin('data.parentdata', 'parent_data')
            ->leftJoin('parent_data.user', 'parent_data_user')
            ->leftJoin('parent_data.form', 'parent_data_form');

        if (isset($criteria['form_id'])) {
            $query->andWhere($query->expr()->eq(
                'parent_data_form.id', $query->expr()->literal($criteria['form_id'])
            ));
        }

        if (isset($criteria['user_id'])) {
            $query->andWhere($query->expr()->eq(
                'parent_data_user.id', $query->expr()->literal($criteria['user_id'])
            ));
        }

        $query->groupBy('parent_data.id');
        return $query;
    }

    public function fetchAll(
        array $criteria = null,
        array $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $output = null;

        if (!$criteria) {
            $output = $this->findAll();
        } else {
            if (!$orderBy) {
                $orderBy = array('name' => 'ASC');
            }

            $output = $this->findBy($criteria, $orderBy, $limit, $offset);
        }

        return $output;
    }

    public function save($newItem = null)
    {
        if ($newItem) {
            $this->getEntityManager()->persist($newItem);
        }

        $this->getEntityManager()->flush();

        return true;
    }
}