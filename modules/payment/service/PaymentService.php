<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\service;


use app\modules\payment\entity\Account;
use app\modules\payment\entity\Deposit;
use app\modules\payment\entity\Transfer;
use app\modules\payment\entity\Withdraw;
use app\modules\payment\models\PaymentAccount;
use app\modules\payment\models\PaymentOperation;
use app\modules\payment\models\PaymentTransaction;
use Yii;
use yii\db\Exception;

class PaymentService
{
    /**
     * @param Transfer $transfer
     * @throws \Exception
     */
    public function transfer(Transfer $transfer)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->isSufficientFunds($transfer->getSourceAccount(), $transfer->getAmountWithTotalCharge());
            $model = new PaymentTransaction();
            $model->type = PaymentTransaction::TYPE_TRANSFER;
            $model->source_account_id = $transfer->getSourceAccount()->getNumber();
            $model->beneficiary_account_id = $transfer->getBeneficiaryAccount()->getNumber();
            $model->amount = $transfer->getAmount();
            $model->description = $transfer->getDescription();

            $model->save();
            $this->persistOperation($model, $model->sourceAccount, -$transfer->getAmountWithTotalCharge(), $transfer->getDescription());
            $this->persistOperation($model, $model->beneficiaryAccount, $transfer->getAmount(), $transfer->getDescription());
            $charges = $transfer->getCharges();
            foreach ($charges as $charge) {
                $account = PaymentAccount::findOne($charge->getAccount()->getNumber());
                $this->persistOperation($model, $account, $charge->getChargeAmount($transfer->getAmount()), $charge->getDescription());
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param Deposit $deposit
     * @throws \Exception
     */
    public function deposit(Deposit $deposit)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model = new PaymentTransaction();
            $model->type = PaymentTransaction::TYPE_DEPOSIT;
            $model->beneficiary_account_id = $deposit->getBeneficiaryAccount()->getNumber();
            $model->amount = $deposit->getAmount();
            $model->description = $deposit->getDescription();

            $model->save();
            $this->persistOperation($model, $model->beneficiaryAccount, $deposit->getAmount(), $deposit->getDescription());

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param Withdraw $withdraw
     * @throws \Exception
     */
    public function withdraw(Withdraw $withdraw)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->isSufficientFunds($withdraw->getSourceAccount(), $withdraw->getAmount());
            $model = new PaymentTransaction();
            $model->type = PaymentTransaction::TYPE_WITHDRAW;
            $model->source_account_id = $withdraw->getSourceAccount()->getNumber();
            $model->amount = $withdraw->getAmount();
            $model->description = $withdraw->getDescription();

            $model->save();
            $this->persistOperation($model, $model->sourceAccount, -$withdraw->getAmount(), $withdraw->getDescription());

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param Account $account
     * @param int $amount
     * @throws \RuntimeException
     */
    public function isSufficientFunds(Account $account, int $amount)
    {
        $paymentAccount = PaymentAccount::findOne($account->getNumber());

        if ($paymentAccount->balance < $amount) {
            throw new \RuntimeException('Insufficient funds for translation');
        }
    }

    /**
     * @param PaymentTransaction $transaction
     * @param PaymentAccount $account
     * @param $amount
     */
    protected function persistOperation(PaymentTransaction $transaction, PaymentAccount $account, $amount, $description)
    {
        $model = new PaymentOperation();
        $model->transaction_id = $transaction->id;
        $model->account_id = $account->id;
        $model->amount = $amount;
        $model->description = $description;
        $account->balance = (int)$account->balance + $amount;

        $account->save();
        $model->save();
    }
}