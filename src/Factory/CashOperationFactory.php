<?php

declare(strict_types=1);

namespace RokasApp\Factory;

use RokasApp\Model\CashOperation;
use RokasApp\Model\User;

class CashOperationFactory
{
    public static function create(string $date, User $user, string $type, string $currency, float $amount)
    {
        return new CashOperation($date, $user, $type, $currency, $amount);
    }
}
