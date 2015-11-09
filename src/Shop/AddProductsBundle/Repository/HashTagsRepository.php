<?php

namespace Shop\AddProductsBundle\Repository;

/**
 * CacheTags
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
use \Doctrine\ORM\EntityRepository;

class HashTagsRepository extends EntityRepository
{
    /**
     * Ищем все перечисленные теги и добавлям их к продукту
     *
     * @param array $data
     *
     * @return array
    */
    public function findByNameTags(array $data)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                    SELECT h FROM ShopAddProductsBundle:HashTags h
                    WHERE h.name IN (:tags)
                ')->setParameter('tags', $data);


        return $query->getResult();
    }
}
