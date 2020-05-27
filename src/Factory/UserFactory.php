<?php

declare(strict_types=1);

namespace RokasApp\Factory;

use RokasApp\Model\User;
use RokasApp\Repository\Storage;

class UserFactory
{
    public static function create($id, $type, Storage $storage)
    {
        $user = new User($id, $type);

        $storage->storeUser($user);

        return $user;
    }
}
