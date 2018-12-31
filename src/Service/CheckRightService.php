<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 23/12/18
 * Time: 18:10
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class CheckRightService
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Return if user is the build's owner, or ADMIN
     * @param User|null $user
     * @param  User|Comment $object
     * @return bool
     */
    public function isRightUser(?User $user, $object)
    {
        if ($object->getAuthor() === $user || $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
}