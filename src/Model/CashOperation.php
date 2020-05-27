<?php

declare(strict_types=1);

namespace RokasApp\Model;

use RokasApp\Model\User;

class CashOperation
{
    const CASH_TYPE_OUT = 'cash_out';
    const CASH_TYPE_IN = 'cash_in';

    private $date;
    private $user;
    private $type;
    private $currency;
    private $amount;

    public function __construct(string $date, User $user, string $type, string $currency, float $amount)
    {
        $this->date = $date;
        $this->user = $user;
        $this->type = $type;
        $this->currency = $currency;
        $this->amount = $amount;
    }

    public function __toString()
    {
        return "\noperationDate: ".$this->date."\noperationUserId: ".$this->user->getId()."\noperationUserType: ".$this->user->getType()."\noperationType: ".$this->type."\noperationCurrency: ".$this->currency."\noperationAmount: ".$this->amount;
    }
}
