<?php

declare(strict_types=1);

namespace RokasApp\Service;

use DateTime;
use RokasApp\Data\Currency;
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

            $daysDifference = date_diff($lastOperationDate,$operationDate);
            $daysDifference = (int)$daysDifference->format("%a");

            if ($operationDate->format("W") != $lastOperationDate->format("W") || $daysDifference > 30) {
                $this->resetUserDiscount($cashOperation->getUser());
            }
        }

        $this->userLastOperationDate[$userId] = $operationDate;
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

                            switch ($cashOperation->getCurrency()) {
                                case Currency::CURRENCY_JPY:
                                    $discount = $this->userDiscountAmountLeft[$userId] * 129.53;
                                    break;
                                case Currency::CURRENCY_USD:
                                    $discount = $this->userDiscountAmountLeft[$userId] * 1.1497;
                                    break;
                                default:
                                    $discount = $this->userDiscountAmountLeft[$userId];
                                    break;
                            }
                            
                            if ($amount < $discount) {
                                switch ($cashOperation->getCurrency()) {
                                    case Currency::CURRENCY_JPY:
                                        $this->userDiscountAmountLeft[$userId] -= ceil($amount / 129.53);
                                        break;
                                    case Currency::CURRENCY_USD:
                                        $this->userDiscountAmountLeft[$userId] -= $amount / 1.1497;
                                        break;
                                    default:
                                        $this->userDiscountAmountLeft[$userId] -= $amount;
                                        break;
                                }
                                $amount = 0;
                            } else {
                                $amount -= $discount;
                                $this->userDiscountAmountLeft[$userId] = 0;
                            }
                        }
                        $fee = $amount * 0.003;

                        break;
                }
                break;
        }

        if ($cashOperation->getCurrency() == Currency::CURRENCY_JPY) {
            return ceil($fee);
        }

        return number_format($fee, 2);
    }
}
