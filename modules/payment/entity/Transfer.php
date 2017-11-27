<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\entity;


class Transfer extends Transaction
{
    /**
     * @var Account
     */
    private $sourceAccount;
    /**
     * @var Account
     */
    private $beneficiaryAccount;

    public function __construct(Account $sourceAccount, Account $beneficiaryAccount, int $amount)
    {

        $this->sourceAccount = $sourceAccount;
        $this->beneficiaryAccount = $beneficiaryAccount;
        $this->amount = $amount;

        $this->addCharge(new TransferCharge());
        $this->addCharge(new TransferSecurityCharge());
    }

    /**
     * @return Account
     */
    public function getSourceAccount(): Account
    {
        return $this->sourceAccount;
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
        return 'Transfer';
    }
}