<?php
/**
 * @author Sergey Pantushin
 */

namespace app\modules\payment\models;


use app\models\User;
use app\modules\payment\entity\Account;
use app\modules\payment\entity\Transfer;
use app\modules\payment\service\PaymentService;
use yii\base\Model;

class TransferForm extends Model
{
    public $username;

    public $amount;

    public $confirm = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'amount'], 'required'],
            ['username', 'validateUser'],
            ['amount', 'integer'],
            ['amount', 'validateAmount'],
            ['confirm', 'safe'],
        ];
    }

    public function validateUser($attribute, $params)
    {
        if (!($this->username && $this->getUser())) {
            $this->addError($attribute, 'User Not Found');
        }
    }

    public function validateAmount($attribute, $params)
    {
        if ($this->amount) {
            try {
                $this->getPaymentService()->isSufficientFunds(new Account($this->identity()->account_id), $this->amount);
            } catch (\Exception $exception) {
                $this->addError($attribute, $exception->getMessage());
            }
        }
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirm;
    }

    public function getTransferTransaction()
    {
        return new Transfer(
            new Account($this->identity()->account_id),
            new Account($this->getUser()->account_id),
            $this->amount
        );
    }

    /**
     * @return User
     */
    public function identity()
    {
        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();

        return $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return User::findByUsername($this->username);
    }

    private function getPaymentService()
    {
        return new PaymentService();
    }
}