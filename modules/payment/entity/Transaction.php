<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\entity;


abstract class Transaction
{
    /**
     * @var int
     */
    protected $amount;

    /**
     * @var array
     */
    protected $charges = [];

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getTotalCharge(): int
    {
        $totalCharge = 0;
        $charges = $this->getCharges();
        foreach ($charges as $charge) {
            $totalCharge += $charge->getChargeAmount($this->getAmount());
        }

        return $totalCharge;
    }

    /**
     * @return int
     */
    public function getAmountWithTotalCharge(): int
    {
        return $this->getAmount() + $this->getTotalCharge();
    }

    /**
     * @return Charge[]
     */
    public function getCharges(): array
    {
        return $this->charges;
    }

    /**
     * @param Charge $charge
     */
    public function addCharge(Charge $charge)
    {
        $this->charges[] = $charge;
    }

    /**
     * @return string
     */
    abstract public function getDescription(): string;
}