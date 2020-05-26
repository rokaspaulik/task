<?php

namespace RokasApp\Factory;

use RokasApp\Model\User;

class UserFactory
{
    public static function create($id, $type) {
        return new User($id, $type);
    }
}
