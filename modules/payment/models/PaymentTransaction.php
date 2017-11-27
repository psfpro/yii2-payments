<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "payment_transaction".
 *
 * @property string $id
 * @property string $type
 * @property string $source_account_id
 * @property string $beneficiary_account_id
 * @property integer $amount
 * @property string $description
 * @property string $created_at
 *
 * @property PaymentOperation[] $paymentOperations
 * @property PaymentAccount $beneficiaryAccount
 * @property PaymentAccount $sourceAccount
 */
class PaymentTransaction extends \yii\db\ActiveRecord
{
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';
    const TYPE_TRANSFER = 'transfer';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string'],
            [['source_account_id', 'beneficiary_account_id', 'amount'], 'integer'],
            [['created_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['beneficiary_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentAccount::className(), 'targetAttribute' => ['beneficiary_account_id' => 'id']],
            [['source_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentAccount::className(), 'targetAttribute' => ['source_account_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'source_account_id' => 'Source Account ID',
            'beneficiary_account_id' => 'Beneficiary Account ID',
            'amount' => 'Amount',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentOperations()
    {
        return $this->hasMany(PaymentOperation::className(), ['transaction_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBeneficiaryAccount()
    {
        return $this->hasOne(PaymentAccount::className(), ['id' => 'beneficiary_account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceAccount()
    {
        return $this->hasOne(PaymentAccount::className(), ['id' => 'source_account_id']);
    }
}
