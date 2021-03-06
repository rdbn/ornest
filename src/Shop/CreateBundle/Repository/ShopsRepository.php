<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Shop\CreateBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ShopsRepository extends EntityRepository
{
    /**
     * Список всех магазинов
     *
     * @param int $count
     *
     * @return array
    */
    public function findByShops($count)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT s.id, s.shopname, s.uniqueName, s.rating, s.path, count(DISTINCT sub) as users, count(DISTINCT like_shop) as likes
                FROM ShopCreateBundle:Shops s
                LEFT JOIN s.users sub
                LEFT JOIN s.likeShop like_shop
                GROUP BY s
                ORDER BY s.createdAt desc
            ')
            ->setFirstResult($count)
            ->setMaxResults(20);

        try {
            return $query->getResult();
        } catch(\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * Список всех магазинов у менеджера
     *
     * @param int $id
     *
     * @return array
     */
    public function findByShopsManager($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT s.id, s.shopname, s.uniqueName, s.rating, s.path, count(DISTINCT sub) as users, count(DISTINCT like_shop) as likes
                FROM ShopCreateBundle:Shops s
                LEFT JOIN s.manager u
                LEFT JOIN s.users sub
                LEFT JOIN s.likeShop like_shop
                WHERE u.id = :id
                GROUP BY s
            ')
            ->setParameter('id', $id);

        try {
            return $query->getResult();
        } catch(\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * Информация по магазину с количеством подписчиков
     *
     * @param string $name
     *
     * @return array
    */
    public function findOneByShop($name) 
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT 
                  s.id, 
                  s.uniqueName,
                  s.shopname, 
                  s.path, 
                  count(DISTINCT u) as subscribers, 
                  m.id as manager
                FROM ShopCreateBundle:Shops s
                  LEFT JOIN s.manager m
                  LEFT JOIN s.users u
                WHERE s.uniqueName = :name
                GROUP BY s.id, m.id
            ')
            ->setParameter('name', $name);

        try {
            $result = $query->getResult();

            return $result["0"];
        } catch(\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * Проверяем наличие лайка от пользователя
     *
     * @param int $shop
     * @param int $user
     *
     * @return array
     */
    public function findOneByIsLike($shop, $user)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT s FROM ShopCreateBundle:Shops s
                  LEFT JOIN s.likeShop u
                WHERE s.id = :shop AND u.id = :users
            ')
            ->setParameters(['shop' => $shop, 'users' => $user]);

        try {
            return $query->getSingleResult();
        } catch(\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * Кол-во лайков у магазина
     *
     * @param int $id
     *
     * @return array
     */
    public function findOneByCountLike($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT count(u.id) as likes FROM ShopCreateBundle:Shops s
                  LEFT JOIN s.likeShop u
                WHERE s.id = :shop
            ')
            ->setParameter('shop', $id);

        try {
            $result = $query->getResult();

            return $result[0]["likes"];
        } catch(\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}