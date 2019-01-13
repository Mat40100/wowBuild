<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserMessage;
use App\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserMessageController extends AbstractController
{

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/messageTo/{id}")
     */
    public function send(Request $request, User $user)
    {
        $message = new UserMessage();
        $message->setMessageFrom($this->getUser());
        $message->setMessageTo($user);

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash('success', 'Votre message est bien parti!');

            return $this->redirectToRoute('app_usermessage_reception');
        }

        return $this->render('user_message/sendMessage.html.twig' , [
            'form' => $form->createView(),
            'message' => $message
        ]);
    }

    /**
     * @Route("/messages/received")
     * @IsGranted("ROLE_USER")
     */
    public function reception()
    {
        $messages = $this->getDoctrine()->getRepository(UserMessage::class)->findBy([
            'messageTo' => $this->getUser(),
            'receiverVisible' => true //Reflechir aux options de visibiltiés
        ]);

        return $this->render('user_message/Index.html.twig', [
            'messages' => $messages,
            ]);
    }

    /**
     * @Route("/messages/sent")
     * @IsGranted("ROLE_USER")
     */
    public function sent()
    {
        $messages = $this->getDoctrine()->getRepository(UserMessage::class)->findBy([
            'messageFrom' => $this->getUser(),
            'writerVisible' => true //Reflechir aux options de visibiltiés
        ]);

        return $this->render('user_message/Index.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/message/{id}")
     * @IsGranted("ROLE_USER")
     */
    public function show(UserMessage $message){
        if ($message->getMessageFrom() !== $this->getUser() && $message->getMessageTo() !== $this->getUser()) {
            $this->addFlash('warning', 'Ce message ne vous appartient pas.');

            return $this->redirectToRoute('app_usermessage_reception');
        }

        if (($message->getMessageTo()=== $this->getUser() && $message->getReceiverVisible() === false) || ($message->getMessageFrom() === $this->getUser() && $message->getWriterVisible() === false)) {
            $this->addFlash('warning', 'Ce message n\'est plus disponible');

            return $this->redirectToRoute('app_usermessage_reception');
        }

        if ($this->getUser() === $message->getMessageTo()) {
            $message->setIsRead(true);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('user_message/show.html.twig', [
            'message' => $message
        ]);
    }

    /**
     * @Route("/message/{id}/hide")
     * @IsGranted("ROLE_USER")
     */
    public function hide(UserMessage $message)
    {
        if ($message->getMessageFrom() !== $this->getUser() && $message->getMessageTo() !== $this->getUser()) {
            $this->addFlash('warning', 'Ce message ne vous appartient pas.');

            return $this->redirectToRoute('app_usermessage_reception');
        }

        if ($message->getMessageFrom() === $this->getUser()) {
            $message->setWriterVisible(false);
        }

        if ($message->getMessageTo() === $this->getUser()) {
            $message->setReceiverVisible(false);
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_usermessage_reception');
    }
}
