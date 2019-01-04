<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/user/{id}-{token}")
     */
    public function reset(User $user, $token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($user->getResetPasswordToken() !== $token) {
            $this->addFlash('warning', 'Ce token ne correspond à rien.');

            return $this->redirectToRoute('app_default_index');
        }

        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être les mêmes.',
                'required' => true,
                'first_options'  => array('label' => false),
                'second_options' => array('label' => false),
            ])
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPlainPassword($form->get('password')->getData());
            $user->setResetPasswordToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le mot de passe a bien été réinitialisé!');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/index.html.twig', [
            'id' => $user->getId(),
            'token' => $token,
            'form' => $form->createView()]);
    }

    public function sendResetMail(User $user, MailService $mailService)
    {
        $token = md5(random_bytes(15));
        $user->setResetPasswordToken($token);
        $this->getDoctrine()->getManager()->flush();

        $mailService->recoveryMail($user);

        $this->addFlash('success', 'Un email contenant les instruction de récupération de password vous a été envoyé.');

        return $this->redirectToRoute('app_default_index');
    }

    /**
     * @Route("/user/reset")
     */
    public function getUserToRecover(Request $request, MailService $mailService)
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, ['required' => true])
            ->getForm()
            ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo = $this->getDoctrine()->getManager()->getRepository(User::class);
            $user = $repo->findOneBy(['email' => $form->get('email')->getData()]);

            if ($user === null) {
                $this->addFlash('warning', 'Aucun utilisateur ayant cette adresse n\'a été retrouvé :( contactez un administraeur');

                return $this->render('reset_password/index.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $this->sendResetMail($user, $mailService);
        }

        return $this->render('reset_password/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
