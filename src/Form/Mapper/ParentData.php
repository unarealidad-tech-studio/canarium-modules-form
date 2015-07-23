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

class ParentData extends EntityRepository implements MapperInterface
{
    public function create(array $data)
    {
        $newItem = new ParentDataEntity();

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

    public function getFilteredQuery(array $criteria = null)
    {
        $query = $this->createQueryBuilder('parent_data')
            ->leftJoin('parent_data.form', 'parent_data_form')
            ->leftJoin('parent_data.user', 'parent_data_user');

        if (isset($criteria['form_name'])) {
            $query->andWhere($query->expr()->eq(
                'parent_data_form.name', $query->expr()->literal($criteria['form_name'])
            ));
        }

        if (isset($criteria['user_id'])) {
            $query->andWhere($query->expr()->eq(
                'parent_data_user.id', $query->expr()->literal($criteria['user_id'])
            ));
        }

        if (!empty($criteria['ids_to_exclude'])) {

            if (!array($criteria['ids_to_exclude'])) {
                $criteria['ids_to_exclude'] = explode(',', $criteria['ids_to_exclude']);
            }

            $query->andWhere($query->expr()->notIn('parent_data.id', $criteria['ids_to_exclude']));
        }

        if (!empty($criteria['order_by']['field'])) {
            $query->orderBy(
                'parent_data.'.$criteria['order_by']['field'],
                !empty($criteria['order_by']['direction']) && strtolower($criteria['order_by']['direction']) == 'desc' ? Criteria::DESC : Criteria::ASC
            );
        } else {
            $query->orderBy('parent_data.date', Criteria::ASC);
        }

        return $query;
    }

    public function getPaginatedAdapter(array $criteria = null)
    {
        $query = $this->getFilteredQuery($criteria);

        $adapter = new DoctrinePaginator(new Paginator($query, $fetchJoinCollection = true));

        return $adapter;
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