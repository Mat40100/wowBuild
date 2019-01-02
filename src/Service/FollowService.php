<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 02/01/19
 * Time: 17:10
 */

namespace App\Service;


use App\Entity\Build;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class FollowService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function follow(Build $build, User $user)
    {
        if ($user->getFavorites()->contains($build)) {
            $user->removeFavorite($build);
            $this->entityManager->flush();

            return true;
        }
        else {
            $user->addFavorite($build);
            $this->entityManager->flush();

            return false;
        }

    }

    public function checkFollow(Build $build, User $user)
    {
        if ($user->getFavorites()->contains($build)) {

            return true;
        }
        else {

            return false;
        }
    }
}