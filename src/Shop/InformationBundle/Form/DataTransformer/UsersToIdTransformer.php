<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Shop\InformationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use User\UserBundle\Entity\Users;

class UsersToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Users|null $users
     *
     * @return string
     */
    public function transform($users)
    {
        if (null === $users)
            return "";

        return $users->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $id
     *
     * @return Users|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) return null;

        $users = $this->om->getRepository('UserUserBundle:Users')
            ->findOneById($id);

        if (null === $users) {
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $id
            ));
        }

        return $users;
    }
}