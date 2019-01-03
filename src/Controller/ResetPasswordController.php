<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/user/{id}-{token}")
     */
    public function reset(User $user, $token)
    {
        if ($user->getResetPasswordToken() === $token) {
            // ENvoi vers le formulaire de reset de password
        }

        return $this->redirectToRoute('app_default_index');
    }

    /**
     * @Route("/user/{id}/reset")
     */
    public function sendResetMail(User $user)
    {
        $token = md5(random_bytes(15));
        $user->setResetPasswordToken($token);
        $this->getDoctrine()->getManager()->flush();

        // GERER LE MAILER POUR ENVOIE DU MAIL //

        $this->addFlash('success', 'Un email contenant les instruction de récupération de password vous a été envoyé.
        DEBUG : '.$user->getId().'-'.$token);

        return $this->redirectToRoute('app_default_index');
    }
}
