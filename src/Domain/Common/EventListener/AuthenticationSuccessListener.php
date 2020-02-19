<?php

namespace App\Domain\Common\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthenticationSuccessListener
 * @package App\Domain\EventListener
 */
final class AuthenticationSuccessListener
{

    /** @var EntityManagerInterface */
    protected $em;

    /**
     * AuthenticationSuccessListener constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        $customerRepo = $this->em->getRepository("App:Customer");
        $customer = $customerRepo->findOneBy(['email' => $user->getUsername()]);

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['data'] = array(
            'organization' => $customer->getOrganization()
        );

        $event->setData($data);
    }
}
