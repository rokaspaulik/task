<?php

declare(strict_types=1);

namespace RokasApp\Model;

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

    public function storeCashOperation(CashOperation $cashOperation)
    {
        $this->cashOperations[] = $cashOperation;
    }
}
