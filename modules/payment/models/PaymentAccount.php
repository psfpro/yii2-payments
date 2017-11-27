<?php

namespace app\modules\payment\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "payment_account".
 *
 * @property string $id
 * @property string $balance
 * @property string $description
 * @property string $created_at
 *
 * @property PaymentOperation[] $paymentOperations
 * @property PaymentTransaction[] $paymentTransactions
 * @property PaymentTransaction[] $paymentTransactions0
 * @property User[] $users
 */
class PaymentAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance'], 'integer'],
            [['created_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'balance' => 'Balance',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentOperations()
    {
        return $this->hasMany(PaymentOperation::className(), ['account_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::className(), ['beneficiary_account_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentTransactions0()
    {
        return $this->hasMany(PaymentTransaction::className(), ['source_account_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['account_id' => 'id']);
    }
}
