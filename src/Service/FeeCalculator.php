<?php

declare(strict_types=1);

namespace RokasApp\Service;

use DateTime;
use RokasApp\Model\CashOperation;
use RokasApp\Model\User;

class FeeCalculator {
    private $userDiscountAmountLeft;
    private $userOperationsThisWeek;
    private $userLastOperationDate;

    public function __construct()
    {
        $this->userDiscountAmountLeft = [];
        $this->userOperationsThisWeek = [];
        $this->userLastOperationDate = [];
    }

    private function resetUserDiscount(User $user)
    {
        $userId = $user->getId();
        $this->userOperationsThisWeek[$userId] = 1;
        $this->userDiscountAmountLeft[$userId] = 1000;
    }

    private function checkUserLastOperationDate(CashOperation $cashOperation)
    {
        $operationDate = new DateTime($cashOperation->getDate());
        $userId = $cashOperation->getUser()->getId();

        if (isset($this->userLastOperationDate[$userId])) {
            
            $lastOperationDate = $this->userLastOperationDate[$userId];
            $yearDifference = abs(($operationDate->format("Y") - $lastOperationDate->format("Y")));

            if ($operationDate->format("W") != $lastOperationDate->format("W") || $yearDifference > 1) {
                $this->resetUserDiscount($cashOperation->getUser());
            }

        } else {
            $this->userLastOperationDate[$userId] = $operationDate;
        }
    }

    private function checkUserOperationsThisWeek(User $user)
    {
        $userId = $user->getId();

        if (isset($this->userOperationsThisWeek[$userId])) {
            $this->userOperationsThisWeek[$userId]++;

            if ($this->userOperationsThisWeek[$userId] > 3) {
                $this->userDiscountAmountLeft[$userId] = 0;
            }
        } else {
            $this->resetUserDiscount($user);
        }
    }

    public function calculate(CashOperation $cashOperation)
    {
        // TO-DO: add currency conversion
        $this->checkUserOperationsThisWeek($cashOperation->getUser());
        $this->checkUserLastOperationDate($cashOperation);

        $amount = $cashOperation->getAmount();
        $userId = $cashOperation->getUser()->getId();

        switch ($cashOperation->getType()) {
            case CashOperation::CASH_TYPE_IN:

                $fee = $amount * 0.0003;
                if ($fee > 5) {
                    $fee = 5;
                }

                break;
            case CashOperation::CASH_TYPE_OUT:
                switch ($cashOperation->getUser()->getType()) {
                    case User::USER_TYPE_LEGAL:

                        $fee = $amount * 0.003;
                        if ($fee < 0.5) {
                            $fee = 0.5;
                        }

                        break;
                    case User::USER_TYPE_NATURAL:

                        if ($this->userDiscountAmountLeft[$userId] > 0) {
                            if ($amount < $this->userDiscountAmountLeft[$userId]) {
                                $this->userDiscountAmountLeft[$userId] -= $amount;
                                $amount = 0;
                            } else {
                                $amount -= $this->userDiscountAmountLeft[$userId];
                                $this->userDiscountAmountLeft[$userId] = 0;
                            }
                        }
                        $fee = $amount * 0.003;

                        break;
                }
                break;
        }

        return number_format($fee, 2);
    }
}
