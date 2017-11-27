<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\entity;


class Withdraw extends Transaction
{
    /**
     * @var Account
     */
    private $sourceAccount;

    public function __construct(Account $sourceAccount, int $amount)
    {

        $this->sourceAccount = $sourceAccount;
        $this->amount = $amount;
    }

    /**
     * @return Account
     */
    public function getSourceAccount(): Account
    {
        return $this->sourceAccount;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Withdraw';
    }
}