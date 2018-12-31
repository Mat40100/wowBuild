<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Vote;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    public function isVoteExists(Build $post)
    {
        $repo = $this->getDoctrine()->getRepository(Vote::class);

        $vote = $repo->findOneBy(array(
            'build' => $post,
            'author' => $this->getUser(),
        ));

        if ($vote === null) {

            return false;
        }

        return $vote;
    }

    /**
     * @Route("vote/add/{id}-{value}", requirements={"id"="\d+", "value"="1|0"})
     * @Security("has_role('ROLE_USER')")
     */
    public function add(Build $post, $value)
    {
        $vote = $this->isVoteExists($post);
        $entityManager = $this->getDoctrine()->getManager();

        if ($vote === false) {
            $vote = new Vote();

            $vote->setBuild($post);
            $vote->setAuthor($this->getUser());
            $vote->setValue($value);

            $entityManager->persist($vote);
            $entityManager->flush();

            return $this->redirectToRoute('app_build_show', array('id' => $post->getId()));
        }

        $vote->setValue($value);
        $entityManager->flush();

        return $this->redirectToRoute('app_build_show', array('id' => $post->getId()));
    }
}
