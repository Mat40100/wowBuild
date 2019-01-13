<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserMessage;
use App\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserMessageController extends AbstractController
{

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/messages/send")
     */
    public function send(Request $request)
    {
        $message = new UserMessage();
        $message->setMessageFrom($this->getUser());

        $form = $this->createForm(MessageType::class, $message);

        if ($request->isXmlHttpRequest()) {

            return $this->render('user_message/send.html.twig', [
                'form' => $form->createView(),
                'message' => $message
            ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash('success', 'Votre message est bien parti!');

            return $this->redirectToRoute('app_usermessage_index');
        }

        return $this->render('user_message/index.html.twig', [
            'target' => true,
            'form' => $form->createView()]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/messages/sendTo/{id}")
     */
    public function sendTo(Request $request, User $user)
    {
        $message = new UserMessage();
        $message->setMessageFrom($this->getUser());
        $message->setMessageTo($user);

        $form = $this->createFormBuilder($message)
            ->setAction($this->generateUrl('app_usermessage_sendto', ['id' => $user->getId()]))
            ->add('content', TextareaType::class, [
                'label' => false
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {

            return $this->render('user/sendTo.html.twig', [
                'form' => $form->createView(),
                'message' => $message
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash('success', 'Votre message est bien parti!');

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
        }
        $this->addFlash('warning', 'Le message n\'est pas remplie correctement');

        return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
    }


    /**
     * @Route("/messages")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        $messages = $this->getDoctrine()->getRepository(UserMessage::class)->findBy([
            'messageTo' => $this->getUser(),
            'receiverVisible' => true //Reflechir aux options de visibiltiés
        ]);

        return $this->render('user_message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/messages/received")
     * @IsGranted("ROLE_USER")
     */
    public function reception(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $messages = $this->getDoctrine()->getRepository(UserMessage::class)->findBy([
                'messageTo' => $this->getUser(),
                'receiverVisible' => true //Reflechir aux options de visibiltiés
            ]);

            return $this->render('user_message/messageList.twig', [
                'messages' => $messages,
            ]);
        }

        throw new NotFoundHttpException();
    }

    /**
     * @Route("/messages/sent")
     * @IsGranted("ROLE_USER")
     */
    public function sent(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $messages = $this->getDoctrine()->getRepository(UserMessage::class)->findBy([
                'messageFrom' => $this->getUser(),
                'writerVisible' => true //Reflechir aux options de visibiltiés
            ]);

            return $this->render('user_message/messageList.twig', [
                'messages' => $messages,
            ]);
        }

        throw new NotFoundHttpException();
    }

    /**
     * @Route("/message/{id}")
     * @IsGranted("ROLE_USER")
     */
    public function show(UserMessage $message){
        if ($message->getMessageFrom() !== $this->getUser() && $message->getMessageTo() !== $this->getUser()) {
            $this->addFlash('warning', 'Ce message ne vous appartient pas.');

            return $this->redirectToRoute('app_usermessage_index');
        }

        if (($message->getMessageTo()=== $this->getUser() && $message->getReceiverVisible() === false) || ($message->getMessageFrom() === $this->getUser() && $message->getWriterVisible() === false)) {
            $this->addFlash('warning', 'Ce message n\'est plus disponible');

            return $this->redirectToRoute('app_usermessage_index');
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

            return $this->redirectToRoute('app_usermessage_index');
        }

        if ($message->getMessageFrom() === $this->getUser()) {
            $message->setWriterVisible(false);
        }

        if ($message->getMessageTo() === $this->getUser()) {
            $message->setReceiverVisible(false);
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_usermessage_index');
    }
}
