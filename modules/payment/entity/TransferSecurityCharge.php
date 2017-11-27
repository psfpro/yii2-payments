<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\entity;


class TransferSecurityCharge extends Charge
{
    /**
     * @param int $amount
     * @return int
     */
    public function getChargeAmount(int $amount): int
    {
        $charge = (int)ceil($amount * 0.01);
        if ($charge < 44) {
            $charge = 44;
        }

        return $charge;
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
        return 'Transfer security charge';
    }
}