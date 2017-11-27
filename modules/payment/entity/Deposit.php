<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\entity;


class Deposit extends Transaction
{
    /**
     * @var Account
     */
    private $beneficiaryAccount;

    public function __construct(Account $beneficiaryAccount, int $amount)
    {

        $this->beneficiaryAccount = $beneficiaryAccount;
        $this->amount = $amount;
    }

    /**
     * @return Account
     */
    public function getBeneficiaryAccount(): Account
    {
        return $this->beneficiaryAccount;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Deposit';
    }
}