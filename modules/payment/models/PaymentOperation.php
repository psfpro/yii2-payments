<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "payment_operation".
 *
 * @property string $id
 * @property string $transaction_id
 * @property string $account_id
 * @property integer $amount
 * @property string $description
 * @property string $created_at
 *
 * @property PaymentAccount $account
 * @property PaymentTransaction $transaction
 */
class PaymentOperation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_operation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'account_id', 'amount'], 'integer'],
            [['created_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentAccount::className(), 'targetAttribute' => ['account_id' => 'id']],
            [['transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentTransaction::className(), 'targetAttribute' => ['transaction_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transaction_id' => 'Transaction ID',
            'account_id' => 'Account ID',
            'amount' => 'Amount',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(PaymentAccount::className(), ['id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(PaymentTransaction::className(), ['id' => 'transaction_id']);
    }
}
