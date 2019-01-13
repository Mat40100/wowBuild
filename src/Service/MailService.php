<?php
/**
 * Created by PhpStorm.
 * User: dolhen
 * Date: 04/01/19
 * Time: 15:11
 */

namespace App\Service;


use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailService
{
    private $mailer;
    private $twig;

    public function __construct(ContainerInterface $container)
    {
        $this->mailer = $container->get('mailer');
        $this->twig = $container->get('twig');
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function recoveryMail(User $user)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('Recovery@wowBuid.fr')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render('mails/recovery.html.twig', [
                    'user' => $user,
                        'site_url'=> getenv('SITE_URL')
                    ]
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);

        return true;
    }
}