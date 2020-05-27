<?php

declare(strict_types=1);

namespace RokasApp\Repository;

use RokasApp\Factory\UserFactory;
use RokasApp\Model\CashOperation;
use RokasApp\Model\User;

class Storage
{
    private $users;
    private $cashOperations;

    public function __construct()
    {
        $this->users = [];
        $this->cashOperations = [];
    }

    public function storeUser(User $user)
    {
        $this->users[] = $user;
    }

    public function getAllUsers()
    {
        return $this->users;
    }

    public function findOrCreateUser($id, $type)
    {
        foreach($this->users as $user) {
            if($id == $user->getId()) {
                return $user;
            }
        }

        UserFactory::create($id, $type, $this);
    }

    public function storeCashOperation(CashOperation $cashOperation)
    {
        $this->cashOperations[] = $cashOperation;
    }
}
