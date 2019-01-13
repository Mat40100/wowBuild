<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 23/12/18
 * Time: 15:14
 */

namespace App\EventListener;


use App\Entity\Build;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\UserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Doctrine
{
    private $passwordEncoder;
    private $entityManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            if (!null === $entity->getPlainPassword()) {
                $password = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
                $entity->setPassword($password);
            }
        }

        if ($entity instanceof Build || $entity instanceof Comment || $entity instanceof UserMessage) {
            $entity->setLastModificationDate(new \DateTime());
        }

        return;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            $password = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
            $entity->setPassword($password);
        }

        if ($entity instanceof Build || $entity instanceof Comment || $entity instanceof UserMessage) {
            $entity->setCreationDate(new \DateTime());
        }

        return;
    }
}