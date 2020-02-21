<?php

namespace App\Domain\Services;

use App\Domain\Common\Exception\AuthorizationException;

/**
 * Class CheckAuthorization
 * @package App\Domain\Services
 */
final class CheckAuthorization
{
    
    public const ACCESS_MESSAGE = 'Vous n\'êtes pas autorisé à accéder à cette ressource !';
    public const CREATE_MESSAGE = 'Vous n\'êtes pas autorisé à créer cette ressource !';
    public const DELETE_MESSAGE = 'Vous n\'êtes pas autorisé à supprimer cette ressource !';

    /**
     * @param bool $authorization
     * @return null
     * @throws AuthorizationException
     */
    public function checkAccess(bool $authorization)
    {
        if (!$authorization) {
            throw new AuthorizationException(self::ACCESS_MESSAGE);
        }

        return null;
    }

    /**
     * @param bool $authorization
     * @return null
     * @throws AuthorizationException
     */
    public function checkCreate(bool $authorization)
    {
        if (!$authorization) {
            throw new AuthorizationException(self::CREATE_MESSAGE);
        }

        return null;
    }

    /**
     * @param bool $authorization
     * @return null
     * @throws AuthorizationException
     */
    public function checkDelete(bool $authorization)
    {
        if (!$authorization) {
            throw new AuthorizationException(self::DELETE_MESSAGE);
        }

        return null;
    }
}
