<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\entity;


class Account
{
    private $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}