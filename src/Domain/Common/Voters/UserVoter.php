<?php

namespace App\Domain\Common\Voters;

use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserVoter extends Voter
{

    protected const DETAILS = 'userDetails';
    protected const DELETE = 'userDelete';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::DETAILS, self::DELETE])) {
            return false;
        }

        if (!is_array($subject)) {
            return false;
        }

        if (!array_key_exists('user', $subject)) {
            return false;
        }

        if (!array_key_exists('customer', $subject)) {
            return false;
        }

        if (!$subject['user'] instanceof User) {
            return false;
        }

        if (!$subject['customer'] instanceof Customer) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $subject['user'];
        $customer = $subject['customer'];

        if ($customer != $token->getUser()) {
            return false;
        }

        if ($user->getCustomer() === $token->getUser()) {
            return true;
        }

        return false;
    }
}
