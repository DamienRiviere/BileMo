<?php

namespace App\Domain\Common\Voters;

use App\Entity\Customer;
use App\Entity\User;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserVoter extends Voter
{

    protected const VIEW = 'view';
    protected const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::VIEW, self::DELETE])) {
            return false;
        }

        if (is_array($subject) and $subject[0] instanceof User) {
            return true;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $customer = $token->getUser();
        $user = '';

        if (!$customer instanceof Customer) {
            return false;
        }

        if (is_array($subject) and $subject[0] instanceof User) {
            $user = $subject[0];
        }

        if ($subject instanceof User) {
            $user = $subject;
        }

        if ($user->getCustomer() === $customer) {
            return true;
        }

        return false;
    }
}
