<?php

namespace App\Domain\Helpers;

final class AuthorizationHelper
{

    public const STATUS = '403 Non autorisé';
    public const ACCESS_MESSAGE = 'Vous n\'êtes pas autorisé à accéder à cette ressource !';
    public const CREATE_MESSAGE = 'Vous n\'êtes pas autorisé à créer cette ressource !';
    public const DELETE_MESSAGE = 'Vous n\'êtes pas autorisé à supprimer cette ressource !';

    public function checkAccess(bool $authorization)
    {
        if (!$authorization) {
            return [
                'status' => self::STATUS,
                'message' => self::ACCESS_MESSAGE
            ];
        }

        return null;
    }

    public function checkCreate(bool $authorization)
    {
        if (!$authorization) {
            return [
                'status' => self::STATUS,
                'message' => self::CREATE_MESSAGE
            ];
        }

        return null;
    }

    public function checkDelete(bool $authorization)
    {
        if (!$authorization) {
            return [
                'status' => self::STATUS,
                'message' => self::DELETE_MESSAGE
            ];
        }

        return null;
    }
}
