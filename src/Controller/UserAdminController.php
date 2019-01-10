<?php

namespace App\Controller;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController as BaseAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAdminController extends BaseAdminController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserAdminController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $entity
     */
    protected function persistEntity($entity)
    {
        $entity->setPassword($this->encodePassword($entity));
        parent::persistEntity($entity);
    }

    /**
     * @param User $entity
     */
    protected function updateEntity($entity)
    {
        $entity->setPassword($this->encodePassword($entity));
        parent::updateEntity($entity);
    }

    /**
     * @param User $entity
     * @return string
     */
    protected function encodePassword(User $entity): string
    {
        return $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
    }
}