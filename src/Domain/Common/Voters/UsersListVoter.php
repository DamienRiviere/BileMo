<?php

namespace App\Domain\Common\Voters;

use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class UsersListVoter
 * @package App\Domain\Common\Voters
 */
final class UsersListVoter extends Voter
{

    protected const LIST = 'usersList';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::LIST])) {
            return false;
        }

        if (!is_array($subject)) {
            return false;
        }

        if (!array_key_exists('users', $subject)) {
            return false;
        }

        if (!array_key_exists('customer', $subject)) {
            return false;
        }

        if (!$subject['users'][0] instanceof User) {
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
        $user = $subject['users'][0];
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
