<?php

namespace App\Domain\Common\Voters;

use App\Entity\Customer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CreateUserVoter extends Voter
{

    protected const CREATE = 'create';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::CREATE])) {
            return false;
        }

        if (!$subject instanceof Customer) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $customerAuth = $token->getUser();
        $customerSubject = $subject;

        if ($customerAuth === $customerSubject) {
            return true;
        }

        return false;
    }
}
