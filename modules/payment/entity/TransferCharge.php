<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\entity;


class TransferCharge extends Charge
{
    /**
     * @param int $amount
     * @return int
     */
    public function getChargeAmount(int $amount): int
    {
        return (int)ceil($amount * 0.01);
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return new Account(1);
    }

    public function getDescription(): string
    {
        return 'Transfer charge';
    }
}