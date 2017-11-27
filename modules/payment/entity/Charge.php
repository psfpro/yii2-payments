<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\entity;


abstract class Charge
{
    /**
     * @param int $amount
     * @return int
     */
    abstract public function getChargeAmount(int $amount): int;

    /**
     * @return Account
     */
    abstract public function getAccount(): Account;

    abstract public function getDescription(): string;
}