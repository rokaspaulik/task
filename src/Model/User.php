<?php

declare(strict_types=1);

namespace RokasApp\Model;

class User
{
    const USER_TYPE_NATURAL = 'natural';
    const USER_TYPE_LEGAL = 'legal';

    private $id;
    private $type;
    private $cashOperations;

    public function __construct(int $id, string $type)
    {
        $this->id = $id;
        $this->type = $type;
        $this->cashOperations = [];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function addCashOperation(CashOperation $cashOperation)
    {
        $this->cashOperations[] = $cashOperation;
    }

    public function __toString()
    {
        return "\nuserID: " . $this->id . "\nuserType: " . $this->type;
    }
}
